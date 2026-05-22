<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Mark;
use App\Models\SchoolClass;
use App\Models\SubjectAssignment;
use App\Models\Exam;
use App\Helpers\GradeHelper;
use Barryvdh\DomPDF\Facade\Pdf;

class MarkController extends Controller
{
    /*
    |------------------------------------------------
    | INDEX - VIEW MARKS
    |------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = Mark::with([
            'student',
            'subject',
            'exam',
            'classData'
        ]);

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->exam_id) {
            $query->where('exam_id', $request->exam_id);
        }

        $marks = $query->latest()->get();

        $classes = SchoolClass::all();
        $exams   = Exam::all();

        return view('pages.marks.index', compact('marks', 'classes', 'exams'));
    }

    /*
    |------------------------------------------------
    | CREATE - MARK ENTRY PAGE
    |------------------------------------------------
    */
    public function create()
    {
        $classes = SchoolClass::all();
        $exams   = Exam::all();

        return view('pages.marks.create', compact('classes', 'exams'));
    }

    /*
    |------------------------------------------------
    | LOAD STUDENTS + SUBJECTS (AJAX)
    |------------------------------------------------
    */
   public function loadData(Request $request)
{
    $classId = $request->class_id;

    if (!$classId) {
        return response()->json(['students' => [], 'subjects' => []]);
    }

    // Hakikisha jina la table ni 'students' na column ni 'classes'
    $students = \App\Models\Student::where('classes', $classId)->get();

    // Hakikisha jina la table ni 'subject_assignments' na relationship ya 'subject' ipo
    $subjects = \App\Models\SubjectAssignment::with('subject')
                ->where('class_id', $classId)
                ->get();

    return response()->json([
        'students' => $students,
        'subjects' => $subjects,
        'debug' => [
            'student_count' => $students->count(),
            'subject_count' => $subjects->count()
        ]
    ]);
}

    /*
    |------------------------------------------------
    | STORE MARKS
    |------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'exam_id'  => 'required',
            'marks'    => 'required|array'
        ]);

        foreach ($request->marks as $studentId => $subjects) {
            foreach ($subjects as $subjectId => $mark) {

                Mark::updateOrCreate([
                    'student_id' => $studentId,
                    'subject_id' => $subjectId,
                    'exam_id'    => $request->exam_id,
                    'class_id'   => $request->class_id,
                ], [
                    'marks' => $mark ?? 0
                ]);
            }
        }

        return redirect()
            ->back()
            ->with('success', 'Marks saved successfully');
    }

public function processResults($classId, $examId)
{
    $students = Student::where('classes', $classId)->get();

    $results = [];

    foreach ($students as $student) {

        $marks = Mark::where('student_id', $student->id)
            ->where('exam_id', $examId)
            ->get();

        $total = 0;
        $count = 0;

        foreach ($marks as $mark) {
            $total += $mark->marks;
            $count++;
        }

        $average = $count > 0 ? $total / $count : 0;

        $results[] = [
            'student' => $student->first_name . ' ' . $student->last_name,
            'total' => $total,
            'average' => round($average, 2),
            'grade' => GradeHelper::getGrade($average),
        ];
    }

    // sort by performance (positioning)
    usort($results, function ($a, $b) {
        return $b['average'] <=> $a['average'];
    });

    return view('pages.results.index', compact('results'));
}


public function promoteStudents($classId, $examId)
{
    $students = Student::with('invoices')->where('classes', $classId)->get();

    foreach ($students as $student) {
        $marks = Mark::where('student_id', $student->id)
            ->where('exam_id', $examId)
            ->get();

        $total = 0;
        $count = 0;

        foreach ($marks as $mark) {
            $total += $mark->marks;
            $count++;
        }

        $average = $count > 0 ? $total / $count : 0;
        $hasPaidFees = $this->hasPaidAllFees($student, $student->academic_session);

        if ($average >= 50 && $hasPaidFees) {
            $this->promote($student);
        } elseif ($average >= 50 && ! $hasPaidFees) {
            $student->status = 'Hold - Fees unpaid';
            $student->save();
        } else {
            $this->repeatClass($student);
        }
    }

    return back()->with('success', 'Promotion processed successfully');
}

    /*
    |------------------------------------------------
    | EXAM REPORTS - DETAILED STUDENT PERFORMANCE
    |------------------------------------------------
    */
    public function examReports(Request $request)
    {
        $classes = SchoolClass::all();
        $exams = Exam::all();

        $query = Mark::with(['student', 'subject', 'exam', 'classData']);

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->exam_id) {
            $query->where('exam_id', $request->exam_id);
        }

        $marks = $query->get();

        // Group by student for detailed reports
        $studentReports = [];
        foreach ($marks->groupBy('student_id') as $studentId => $studentMarks) {
            $student = $studentMarks->first()->student;
            $exam = $studentMarks->first()->exam;

            $totalMarks = $studentMarks->sum('marks');
            $subjectCount = $studentMarks->count();
            $average = $subjectCount > 0 ? $totalMarks / $subjectCount : 0;

            $studentReports[] = [
                'student' => $student,
                'exam' => $exam,
                'marks' => $studentMarks,
                'total_marks' => $totalMarks,
                'average' => round($average, 2),
                'grade' => GradeHelper::getGrade($average),
                'rank' => 0 // Will be calculated below
            ];
        }

        // Sort by average for ranking
        usort($studentReports, function ($a, $b) {
            return $b['average'] <=> $a['average'];
        });

        // Assign ranks
        $rank = 1;
        foreach ($studentReports as &$report) {
            $report['rank'] = $rank++;
        }

        return view('pages.reports.exam-reports', compact('studentReports', 'classes', 'exams'));
    }

    /*
    |------------------------------------------------
    | STUDENT EXAM REPORT - INDIVIDUAL STUDENT
    |------------------------------------------------
    */
    public function studentExamReport($studentId, Request $request)
    {
        $student = Student::with(['classData', 'streamData'])->findOrFail($studentId);

        $query = Mark::with(['subject', 'exam'])
            ->where('student_id', $studentId);

        if ($request->exam_id) {
            $query->where('exam_id', $request->exam_id);
        }

        $marks = $query->get();

        // Group by exam
        $examReports = [];
        foreach ($marks->groupBy('exam_id') as $examId => $examMarks) {
            $exam = $examMarks->first()->exam;

            $totalMarks = $examMarks->sum('marks');
            $subjectCount = $examMarks->count();
            $average = $subjectCount > 0 ? $totalMarks / $subjectCount : 0;

            $examReports[] = [
                'exam' => $exam,
                'marks' => $examMarks,
                'total_marks' => $totalMarks,
                'average' => round($average, 2),
                'grade' => GradeHelper::getGrade($average)
            ];
        }

        $exams = Exam::all();

        return view('pages.reports.student-exam-report', compact('student', 'examReports', 'exams'));
    }

    public function studentExamReportPdf($studentId)
    {
        $student = Student::with(['classData', 'streamData'])->findOrFail($studentId);

        $marks = Mark::with(['subject', 'exam'])
            ->where('student_id', $studentId)
            ->get();

        // Group by exam
        $examReports = [];
        foreach ($marks->groupBy('exam_id') as $examId => $examMarks) {
            $exam = $examMarks->first()->exam;

            $totalMarks = $examMarks->sum('marks');
            $subjectCount = $examMarks->count();
            $average = $subjectCount > 0 ? $totalMarks / $subjectCount : 0;

            $examReports[] = [
                'exam' => $exam,
                'marks' => $examMarks,
                'total_marks' => $totalMarks,
                'average' => round($average, 2),
                'grade' => GradeHelper::getGrade($average)
            ];
        }

        $pdf = Pdf::loadView('pages.reports.student-exam-report-pdf', compact('student', 'examReports'));
        return $pdf->download('exam_report_' . $student->first_name . '_' . $student->last_name . '.pdf');
    }

private function hasPaidAllFees(Student $student, $academicSessionId = null)
{
    $academicSessionId = $academicSessionId ?: $student->academic_session;
    if (! $academicSessionId) {
        return false;
    }

    $session = \App\Models\AcademicSession::find($academicSessionId);
    if (!$session) return false;

    $invoices = $student->invoices()->where('academic_year', $session->name)->get();
    if ($invoices->isEmpty()) {
        return true; // No invoices for this year means no fees due or fully paid
    }

    return $invoices->sum('balance') <= 0;
}

private function promote($student)
{
    $currentClass = $student->classes;

    // class progression map
    $map = [
        1 => 2,
        2 => 3,
        3 => 4,
        4 => 'graduate_o_level',
        5 => 6,
        6 => 'graduate_a_level',
    ];

    if (isset($map[$currentClass])) {

        if ($map[$currentClass] == 'graduate_o_level') {

            $student->status = 'Graduated O-Level';

        } elseif ($map[$currentClass] == 'graduate_a_level') {

            $student->status = 'Graduated A-Level';

        } else {

            $student->classes = $map[$currentClass];
        }

        $student->save();
    }
}

private function repeatClass($student)
{
    // just mark as repeated (or keep same class)
    $student->status = 'Repeat';
    $student->save();
}
}