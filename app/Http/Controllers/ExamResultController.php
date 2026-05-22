<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Services\MarkingService;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamResultController extends Controller
{
    protected $markingService;
    protected $reportService;

    public function __construct(MarkingService $markingService, ReportService $reportService)
    {
        $this->markingService = $markingService;
        $this->reportService = $reportService;
        $this->middleware('auth');
    }

    /**
     * Display all results or filter by exam
     */
    public function index(Request $request)
    {
        $query = ExamResult::with(['exam', 'student', 'class']);

        if ($request->exam_id) {
            $query->where('exam_id', $request->exam_id);
        }

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->student_id) {
            $query->where('student_id', $request->student_id);
        }

        $results = $query->latest()->paginate(20);
        $exams = Exam::all();
        $classes = SchoolClass::all();

        return view('pages.results.index', [
            'results' => $results,
            'exams' => $exams,
            'classes' => $classes
        ]);
    }

    /**
     * Show single result
     */
    public function show($resultId)
    {
        $result = ExamResult::with(['exam', 'student', 'class', 'marks'])->findOrFail($resultId);

        return view('pages.results.show', ['result' => $result]);
    }

    /**
     * Generate results for an exam
     */
    public function generate($examId, Request $request)
    {
        $exam = Exam::findOrFail($examId);

        try {
            DB::beginTransaction();

            $marks = \App\Models\Mark::where('exam_id', $examId)
                ->with(['student', 'classData'])
                ->get()
                ->groupBy('student_id');

            foreach ($marks as $studentId => $studentMarks) {
                $totalMarks = $studentMarks->sum('marks');
                $subjectsCount = $studentMarks->count();
                $averageMarks = $subjectsCount > 0 ? $totalMarks / $subjectsCount : 0;
                $grade = $this->markingService->calculateGrade($averageMarks);
                $isPassed = $averageMarks >= $exam->passing_marks;
                $position = $this->reportService->getStudentPosition($studentId, $examId);
                $classId = $studentMarks->first()->class_id;

                ExamResult::updateOrCreate(
                    [
                        'exam_id' => $examId,
                        'student_id' => $studentId,
                        'class_id' => $classId
                    ],
                    [
                        'total_marks' => $totalMarks,
                        'average_marks' => $averageMarks,
                        'grade' => $grade,
                        'position' => $position,
                        'is_passed' => $isPassed,
                        'remarks' => $this->getRemarks($averageMarks, $exam->passing_marks)
                    ]
                );
            }

            DB::commit();

            return redirect()->route('results.exam', $examId)
                ->with('success', 'Results generated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error generating results: ' . $e->getMessage());
        }
    }

    /**
     * View results for a specific exam
     */
    public function examResults($examId, Request $request)
    {
        $exam = Exam::findOrFail($examId);

        $query = ExamResult::where('exam_id', $examId)
            ->with(['student', 'class'])
            ->orderBy('position');

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->search) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                  ->orWhere('last_name', 'like', "%{$request->search}%")
                  ->orWhere('admission_no', 'like', "%{$request->search}%");
            });
        }

        $results = $query->paginate(20);
        $classes = $exam->classes;

        return view('pages.results.exam-results', [
            'exam' => $exam,
            'results' => $results,
            'classes' => $classes,
            'selected_class' => $request->class_id
        ]);
    }

    /**
     * View results for a specific class
     */
    public function classResults($examId, $classId, Request $request)
    {
        $exam = Exam::findOrFail($examId);
        $class = SchoolClass::findOrFail($classId);

        $results = ExamResult::where('exam_id', $examId)
            ->where('class_id', $classId)
            ->with(['student', 'exam'])
            ->orderBy('position')
            ->paginate(20);

        $statistics = [
            'total_students' => $results->total(),
            'average_marks' => ExamResult::where('exam_id', $examId)
                ->where('class_id', $classId)
                ->avg('average_marks'),
            'highest_marks' => ExamResult::where('exam_id', $examId)
                ->where('class_id', $classId)
                ->max('average_marks'),
            'lowest_marks' => ExamResult::where('exam_id', $examId)
                ->where('class_id', $classId)
                ->min('average_marks'),
            'pass_rate' => ExamResult::where('exam_id', $examId)
                ->where('class_id', $classId)
                ->where('is_passed', true)
                ->count() / max($results->total(), 1) * 100
        ];

        return view('pages.results.class-results', [
            'exam' => $exam,
            'class' => $class,
            'results' => $results,
            'statistics' => $statistics
        ]);
    }

    /**
     * Student views their results
     */
    public function studentResults()
    {
        $user = auth()->user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'Not a student');
        }

        $results = ExamResult::where('student_id', $student->id)
            ->with(['exam', 'class'])
            ->latest('exam_id')
            ->paginate(15);

        return view('pages.results.student-results', ['results' => $results]);
    }

    /**
     * Student views single result
     */
    public function studentResult($examId)
    {
        $user = auth()->user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'Not a student');
        }

        $result = ExamResult::where('exam_id', $examId)
            ->where('student_id', $student->id)
            ->with(['exam', 'student', 'class'])
            ->firstOrFail();

        $marks = \App\Models\Mark::where('exam_id', $examId)
            ->where('student_id', $student->id)
            ->with(['subject', 'exam'])
            ->get();

        return view('pages.results.student-result', [
            'result' => $result,
            'marks' => $marks
        ]);
    }

    /**
     * Export results to CSV
     */
    public function exportCSV($examId, Request $request)
    {
        $exam = Exam::findOrFail($examId);
        $classId = $request->class_id;

        $results = ExamResult::where('exam_id', $examId);

        if ($classId) {
            $results->where('class_id', $classId);
        }

        $results = $results->with(['student', 'class'])->get();

        $filename = "exam_results_{$examId}_" . now()->format('Y-m-d') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $callback = function() use ($exam, $results) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Exam', $exam->name]);
            fputcsv($file, []);
            fputcsv($file, [
                'Student ID',
                'Admission No',
                'Name',
                'Class',
                'Total Marks',
                'Average Marks',
                'Grade',
                'Position',
                'Status',
                'Remarks'
            ]);

            foreach ($results as $result) {
                fputcsv($file, [
                    $result->student->id,
                    $result->student->admission_no,
                    $result->student->first_name . ' ' . $result->student->last_name,
                    $result->class->name,
                    $result->total_marks,
                    round($result->average_marks, 2),
                    $result->grade,
                    $result->position,
                    $result->is_passed ? 'PASS' : 'FAIL',
                    $result->remarks
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Delete all results for an exam
     */
    public function deleteResults($examId)
    {
        try {
            ExamResult::where('exam_id', $examId)->delete();
            return redirect()->back()->with('success', 'Results deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting results: ' . $e->getMessage());
        }
    }

    /**
     * Get remarks based on performance
     */
    private function getRemarks($averageMarks, $passingMarks)
    {
        if ($averageMarks >= 80) {
            return 'Excellent. Outstanding performance';
        } elseif ($averageMarks >= 70) {
            return 'Very Good. Commendable performance';
        } elseif ($averageMarks >= 60) {
            return 'Good. Satisfactory performance';
        } elseif ($averageMarks >= $passingMarks) {
            return 'Satisfactory. More effort needed';
        } else {
            return 'Below passing. Needs improvement';
        }
    }
}
