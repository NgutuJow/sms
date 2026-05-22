<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamPaper; // Ongeza hii line
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Mark;
use App\Models\Student;
use App\Models\Stream;
use App\Helpers\GradeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class TeacherExamController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (! $user || ! $user->teacher) {
            return redirect()->route('login')->with('error', 'Tafadhali ingia kwa kutumia akaunti ya mwalimu kwanza.');
        }

        $teacherId = $user->teacher->id;

        $exams = Exam::with(['academicSession', 'semester'])
            ->latest()
            ->get();

        // Get classes and subjects assigned to this teacher
        $myClasses = SchoolClass::whereHas('subjects', function($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })->with(['subjects' => function($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }])->get();

        return view('pages.teacher.exams.index', compact('exams', 'myClasses'));
    }

    public function manage($id)
    {
        $exam = Exam::with(['academicSession', 'semester'])->findOrFail($id);
        $user = auth()->user();

        if (! $user || ! $user->teacher) {
            return redirect()->route('login')->with('error', 'Tafadhali ingia kabla ya kusimamia mtihani.');
        }

        $teacherId = $user->teacher->id;

        $mySubjects = Subject::where('teacher_id', $teacherId)
            ->with(['schoolClass', 'examPapers' => function($query) use ($id) {
                $query->where('exam_id', $id);
            }])
            ->get();

        return view('pages.teacher.exams.manage', compact('exam', 'mySubjects'));
    }
    public function storePaper(Request $request)
{
    $request->validate([
        'exam_id' => 'required',
        'subject_id' => 'required',
        'class_id' => 'required',
        'file_path' => 'required|mimes:pdf,doc,docx|max:5120', // Max 5MB
        'start_date' => 'required',
        'end_date' => 'required',
    ]);

    $teacherId = auth()->user()->teacher->id;

    // Handle File Upload
    $filePath = null;
    if ($request->hasFile('file_path')) {
        $file = $request->file('file_path');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('exam_papers', $fileName, 'public');
    }

    ExamPaper::create([
        'exam_id' => $request->exam_id,
        'teacher_id' => $teacherId,
        'class_id' => $request->class_id,
        'subject_id' => $request->subject_id,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'file_path' => $filePath,
    ]);

    return back()->with('success', 'Mtihani umepakiwa kikamilifu!');
}

public function destroyPaper($id)
{
    $paper = ExamPaper::findOrFail($id);
    
    // Futa file kule kwenye storage
    if ($paper->file_path) {
        Storage::disk('public')->delete($paper->file_path);
    }
    
    $paper->delete();
    return back()->with('success', 'Mtihani umefutwa!');
}

    // Result upload methods
    public function results($examId, $subjectId)
    {
        $exam = Exam::findOrFail($examId);
        $subject = Subject::with('schoolClass')->findOrFail($subjectId);
        $user = auth()->user();

        if (! $user || ! $user->teacher) {
            return redirect()->route('login')->with('error', 'Tafadhali ingia kabla ya kupakia matokeo.');
        }

        $teacherId = $user->teacher->id;

        // Verify teacher owns this subject
        if ($subject->teacher_id != $teacherId) {
            return back()->with('error', 'Huna ruhusa ya kupakia matokeo ya somo hili.');
        }

        $streamId = request('stream_id');

        // Get students in this class/stream combination
        $students = Student::where('classes', $subject->school_class_id)
            ->when($streamId, function ($query) use ($streamId) {
                $query->where('stream', $streamId);
            })
            ->with(['user', 'classData', 'streamData'])
            ->get();

        // Get existing marks
        $existingMarks = Mark::where('exam_id', $examId)
            ->where('subject_id', $subjectId)
            ->where('marked_by', $teacherId)
            ->pluck('marks', 'student_id')
            ->toArray();

        $streams = Stream::whereHas('students', function($query) use ($subject) {
            $query->where('classes', $subject->school_class_id);
        })->get();

        return view('pages.teacher.exams.results', compact('exam', 'subject', 'students', 'existingMarks', 'streams'));
    }

    public function storeSingleResult(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'subject_id' => 'required|exists:subjects,id',
            'student_id' => 'required|exists:students,id',
            'marks_obtained' => 'required|numeric|min:0|max:100',
        ]);

        $user = auth()->user();
        if (! $user || ! $user->teacher) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $teacherId = $user->teacher->id;

        // Verify teacher owns this subject
        $subject = Subject::find($request->subject_id);
        if ($subject->teacher_id != $teacherId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $subject = Subject::findOrFail($request->subject_id);

        Mark::updateOrCreate(
            [
                'exam_id' => $request->exam_id,
                'subject_id' => $request->subject_id,
                'student_id' => $request->student_id,
                'marked_by' => $teacherId,
            ],
            [
                'class_id' => $subject->school_class_id,
                'marks' => $request->marks_obtained,
                'grade' => $this->calculateGrade($request->marks_obtained),
                'marked_date' => now(),
                'remarks' => $request->remarks ?? null,
            ]
        );

        return response()->json(['success' => 'Alama zimehifadhiwa!']);
    }

 public function storeBulkResults(Request $request)
{
    $request->validate([
        'exam_id' => 'required',
        'subject_id' => 'required',
        'file' => 'required|mimes:csv,txt|max:5120' // Max 5MB
    ]);

    $teacherId = auth()->user()->teacher->id;
        $subject = Subject::findOrFail($request->subject_id);
        $classId = $subject->school_class_id;
        $file = $request->file('file');
    $successCount = 0;
    $errors = [];

    // Fungua file
    $path = $file->getRealPath();
    $content = file_get_contents($path);

    // 1. Safisha BOM herufi za siri ambazo Excel huziweka mwanzo wa file
    $content = preg_replace('/^[\x00-\x1F\x80-\xFF]+/', '', $content);

    // 2. Tambua separator (mkato au semicolon)
    $separator = (strpos(strtok($content, "\n"), ';') !== false) ? ';' : ',';

    // Rudisha content kwenye array ya mistari
    $lines = explode("\n", str_replace("\r", "", $content));

    foreach ($lines as $index => $line) {
        if (empty(trim($line))) continue;

        $row = str_getcsv($line, $separator);

        // 3. Ruka Header kama ipo
        if ($index === 0) {
            $firstCol = strtolower(trim($row[0] ?? ''));
            // Kama neno la kwanza ni "admission" au "student" au "name", ruka hii row
            if (in_array($firstCol, ['admission_no', 'admission', 'student', 'id', 'name', 's/n'])) {
                continue;
            }
        }

        $admissionNo = trim($row[0] ?? '');
        $marks = trim($row[1] ?? '');
        $remarks = trim($row[2] ?? 'Good');

        // Kama safu ya kwanza haina kitu, ruka
        if ($admissionNo === '') continue;

        // 4. Tafuta mwanafunzi
        $student = \App\Models\Student::where('admission_no', $admissionNo)->first();

        if ($student) {
            // Allow empty marks for now (template might not be filled)
            if ($marks !== '') {
                \App\Models\Mark::updateOrCreate(
                    [
                        'exam_id' => $request->exam_id,
                        'subject_id' => $request->subject_id,
                        'student_id' => $student->id,
                    ],
                    [
                        'class_id' => $classId,
                        'marks' => $marks,
                        'grade' => $this->calculateGrade($marks),
                        'marked_by' => $teacherId,
                        'marked_date' => now(),
                        'remarks' => $remarks
                    ]
                );
                $successCount++;
            } else {
                $errors[] = "Row " . ($index + 1) . ": Student {$admissionNo} found but marks is empty";
            }
        } else {
            $errors[] = "Row " . ($index + 1) . ": Student with admission number '{$admissionNo}' not found";
        }
    }

    if ($successCount > 0) {
        $message = "Hongera! Matokeo ya wanafunzi $successCount yamepakiwa kikamilifu.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(', ', array_slice($errors, 0, 3));
        }
        return redirect()->back()->with('success', $message);
    }

    $errorMessage = "Hakuna data iliyopakiwa.";
    if (!empty($errors)) {
        $errorMessage .= " Errors: " . implode(', ', array_slice($errors, 0, 5));
    }
    return redirect()->back()->with('error', $errorMessage);
}

    public function downloadResultsTemplate($examId, $subjectId)
    {
        $user = auth()->user();
        if (! $user || ! $user->teacher) {
            abort(403);
        }

        $teacherId = $user->teacher->id;
        $subject = Subject::findOrFail($subjectId);

        // Verify teacher owns this subject
        if ($subject->teacher_id != $teacherId) {
            abort(403);
        }

        $streamId = request('stream_id');

        $students = Student::where('classes', $subject->school_class_id)
            ->when($streamId, function ($query) use ($streamId) {
                $query->where('stream', $streamId);
            })
            ->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=results_template_{$subject->subject_name}.csv",
        ];

        $callback = function () use ($students) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['admission_no', 'marks_obtained', 'remarks']);

            foreach ($students as $student) {
                fputcsv($file, [$student->admission_no, '', '']);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadResultsReport($examId, $subjectId)
    {
        $user = auth()->user();
        if (!$user || !$user->teacher) {
            abort(403);
        }

        $teacher = $user->teacher;
        $exam = Exam::findOrFail($examId);
        $subject = Subject::with('schoolClass')->findOrFail($subjectId);

        // Verify teacher owns this subject
        if ($subject->teacher_id != $teacher->id) {
            abort(403);
        }

        // Get school info
        $school = \App\Models\School::first();

        // Get all marks for this exam/subject
        $marks = Mark::with('student', 'student.classData', 'student.streamData')
            ->where('exam_id', $examId)
            ->where('subject_id', $subjectId)
            ->orderBy('student_id')
            ->get();

        // Get all students for this class (for comparison)
        $allStudents = Student::where('classes', $subject->school_class_id)
            ->orderBy('admission_no')
            ->get();

        // Create marks map
        $marksMap = $marks->keyBy('student_id')->toArray();

        // Prepare data for PDF
        $data = [
            'school' => $school,
            'exam' => $exam,
            'subject' => $subject,
            'teacher' => $teacher,
            'marks' => $marks,
            'allStudents' => $allStudents,
            'marksMap' => $marksMap,
            'reportDate' => now()->format('d/m/Y H:i'),
            'totalStudents' => $allStudents->count(),
            'markedCount' => $marks->count(),
        ];

        $pdf = Pdf::loadView('pages.teacher.exams.report-pdf', $data)
            ->setPaper('a4')
            ->setOption('margin-top', 5)
            ->setOption('margin-bottom', 5);

        return $pdf->download("results_{$subject->subject_name}_{$exam->name}.pdf");
    }

    private function calculateGrade($marks)
    {
        return GradeHelper::getGrade($marks);
    }

    public static function calculateGradeStatic($marks)
    {
       return GradeHelper::getGrade($marks);
    }
}
