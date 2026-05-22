<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\ExamResult;
use App\Services\ExamService;
use App\Services\MarkingService;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ExamApiController extends Controller
{
    protected $examService;
    protected $markingService;
    protected $reportService;

    public function __construct(ExamService $examService, MarkingService $markingService, ReportService $reportService)
    {
        $this->examService = $examService;
        $this->markingService = $markingService;
        $this->reportService = $reportService;
        $this->middleware('auth:sanctum');
    }

    /**
     * Get all exams with filters
     */
    public function index(Request $request)
    {
        $filters = [
            'session_id' => $request->session_id,
            'semester_id' => $request->semester_id,
            'status' => $request->status,
            'per_page' => $request->per_page ?? 15
        ];

        $exams = $this->examService->getAllExams($filters);

        return response()->json([
            'success' => true,
            'data' => $exams->items(),
            'pagination' => [
                'total' => $exams->total(),
                'per_page' => $exams->perPage(),
                'current_page' => $exams->currentPage(),
                'last_page' => $exams->lastPage()
            ]
        ]);
    }

    /**
     * Get exam details with marks and statistics
     */
    public function show($examId)
    {
        $exam = $this->examService->getExamDetails($examId);
        $statistics = $this->markingService->getExamStatistics($examId);

        return response()->json([
            'success' => true,
            'data' => [
                'exam' => $exam,
                'statistics' => $statistics
            ]
        ]);
    }

    /**
     * Get exam marks
     */
    public function marks($examId, Request $request)
    {
        $marks = $this->markingService->getExamMarks(
            $examId,
            $request->class_id,
            $request->subject_id
        );

        return response()->json([
            'success' => true,
            'data' => $marks,
            'count' => $marks->count()
        ]);
    }

    /**
     * Get student exam marks
     */
    public function studentMarks($examId, $studentId)
    {
        $marks = $this->markingService->getStudentExamMarks($studentId, $examId);

        return response()->json([
            'success' => true,
            'data' => $marks,
            'total_marks' => $marks->sum('marks'),
            'subjects_count' => $marks->count()
        ]);
    }

    /**
     * Get exam statistics
     */
    public function statistics($examId, Request $request)
    {
        $statistics = $this->markingService->getExamStatistics($examId, $request->class_id);

        return response()->json([
            'success' => true,
            'data' => $statistics
        ]);
    }

    /**
     * Get exam report
     */
    public function report($examId)
    {
        $report = $this->reportService->generateExamReport($examId);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Get class exam report
     */
    public function classReport($examId, $classId)
    {
        $report = $this->reportService->generateClassReport($examId, $classId);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Get student exam result
     */
    public function studentResult($examId, $studentId)
    {
        $result = ExamResult::where('exam_id', $examId)
            ->where('student_id', $studentId)
            ->with(['exam', 'student', 'class'])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Get all results for exam
     */
    public function examResults($examId, Request $request)
    {
        $query = ExamResult::where('exam_id', $examId)
            ->with(['student', 'class']);

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        $results = $query->orderBy('position')->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $results->items(),
            'pagination' => [
                'total' => $results->total(),
                'per_page' => $results->perPage(),
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage()
            ]
        ]);
    }
}
