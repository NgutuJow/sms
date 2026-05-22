<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\AcademicSession;
use App\Models\Semester;
use App\Services\ExamService;
use App\Services\MarkingService;
use App\Services\ReportService;
use Illuminate\Http\Request;

class AdminExamController extends Controller
{
    protected $examService;
    protected $markingService;
    protected $reportService;

    public function __construct(ExamService $examService, MarkingService $markingService, ReportService $reportService)
    {
        $this->examService = $examService;
        $this->markingService = $markingService;
        $this->reportService = $reportService;
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display all exams with filters
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
        $options = $this->examService->getAvailableOptions();

        return view('pages.admin.exams.index', [
            'exams' => $exams,
            'sessions' => $options['sessions'],
            'semesters' => $options['semesters'],
            'exam_types' => $options['exam_types']
        ]);
    }

    /**
     * Show create exam form
     */
    public function create()
    {
        $options = $this->examService->getAvailableOptions();

        return view('pages.admin.exams.create', [
            'sessions' => $options['sessions'],
            'semesters' => $options['semesters'],
            'classes' => $options['classes'],
            'exam_types' => $options['exam_types']
        ]);
    }

    /**
     * Store new exam
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'semester_id' => 'required|exists:semesters,id',
            'exam_type' => 'required|in:QUIZ,MIDTERM,FINAL,ASSIGNMENT,PROJECT,PRACTICAL',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'total_marks' => 'required|numeric|min:10|max:500',
            'passing_marks' => 'required|numeric|min:1|lt:total_marks',
            'classes' => 'required|array|min:1',
            'classes.*' => 'exists:school_classes,id'
        ]);

        try {
            $exam = $this->examService->createExam($validated);
            return redirect()->route('admin.exams.show', $exam->id)
                ->with('success', 'Exam created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating exam: ' . $e->getMessage());
        }
    }

    /**
     * Show exam details
     */
    public function show($examId)
    {
        $exam = $this->examService->getExamDetails($examId);
        $statistics = $this->markingService->getExamStatistics($examId);
        $classReports = [];

        foreach ($exam->classes as $class) {
            $classReports[] = [
                'class' => $class,
                'statistics' => $this->markingService->getExamStatistics($examId, $class->id)
            ];
        }

        return view('pages.admin.exams.show', [
            'exam' => $exam,
            'statistics' => $statistics,
            'class_reports' => $classReports
        ]);
    }

    /**
     * Show edit exam form
     */
    public function edit($examId)
    {
        $exam = Exam::findOrFail($examId);
        $options = $this->examService->getAvailableOptions();

        return view('pages.admin.exams.edit', [
            'exam' => $exam,
            'sessions' => $options['sessions'],
            'semesters' => $options['semesters'],
            'classes' => $options['classes'],
            'exam_types' => $options['exam_types'],
            'selected_classes' => $exam->classes->pluck('id')->toArray()
        ]);
    }

    /**
     * Update exam
     */
    public function update(Request $request, $examId)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'semester_id' => 'required|exists:semesters,id',
            'exam_type' => 'required|in:QUIZ,MIDTERM,FINAL,ASSIGNMENT,PROJECT,PRACTICAL',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'total_marks' => 'required|numeric|min:10|max:500',
            'passing_marks' => 'required|numeric|min:1|lt:total_marks',
            'classes' => 'required|array|min:1',
            'classes.*' => 'exists:school_classes,id'
        ]);

        try {
            $exam = $this->examService->updateExam($examId, $validated);
            return redirect()->route('admin.exams.show', $exam->id)
                ->with('success', 'Exam updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating exam: ' . $e->getMessage());
        }
    }

    /**
     * Publish exam for marking
     */
    public function publish(Request $request, $examId)
    {
        try {
            $exam = $this->examService->publishExam($examId);
            return redirect()->back()->with('success', 'Exam published for marking');
        } catch (\Exception $e) {
            return back()->with('error', 'Error publishing exam: ' . $e->getMessage());
        }
    }

    /**
     * Close exam for marking
     */
    public function close(Request $request, $examId)
    {
        try {
            $exam = $this->examService->closeExam($examId);
            return redirect()->back()->with('success', 'Exam closed successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error closing exam: ' . $e->getMessage());
        }
    }

    /**
     * Release results to parents
     */
    public function releaseResults(Request $request, $examId)
    {
        try {
            $exam = $this->examService->releaseResults($examId);
            
            // Notify parents via WhatsApp
            $whatsappService = app(\App\Services\WhatsAppService::class);
            $classes = $exam->classes;
            
            foreach ($classes as $class) {
                $students = $class->students;
                foreach ($students as $student) {
                    $msg = "Habari, Matokeo ya mtihani wa {$exam->name} yametoka. Unaweza kuyaona kupitia portal ya wazazi au shuleni. Ahsante.";
                    $whatsappService->sendMessage($student, $msg, 'system');
                }
            }

            return redirect()->back()->with('success', 'Results released and parents notified!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error releasing results: ' . $e->getMessage());
        }
    }

    /**
     * Delete exam
     */
    public function destroy($examId)
    {
        try {
            $this->examService->deleteExam($examId);
            return redirect()->route('admin.exams.index')
                ->with('success', 'Exam deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting exam: ' . $e->getMessage());
        }
    }

    /**
     * View exam report
     */
    public function viewReport($examId)
    {
        try {
            $report = $this->reportService->generateExamReport($examId);
            return view('pages.admin.exams.report', ['report' => $report]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating report: ' . $e->getMessage());
        }
    }

    /**
     * View class exam report
     */
    public function classReport($examId, $classId)
    {
        try {
            $report = $this->reportService->generateClassReport($examId, $classId);
            return view('pages.admin.exams.class-report', ['report' => $report]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating report: ' . $e->getMessage());
        }
    }

    /**
     * View marks for exam
     */
    public function marks($examId, Request $request)
    {
        $exam = Exam::findOrFail($examId);
        $classId = $request->class_id;
        $subjectId = $request->subject_id;

        $marks = $this->markingService->getExamMarks($examId, $classId, $subjectId);

        return view('pages.admin.exams.marks', [
            'exam' => $exam,
            'marks' => $marks,
            'classes' => $exam->classes,
            'selected_class' => $classId
        ]);
    }

    /**
     * Export exam report to PDF
     */
    public function exportReport($examId, Request $request)
    {
        $report_type = $request->get('type', 'exam');
        $class_id = $request->get('class_id');

        try {
            $report = $this->reportService->exportReport($report_type, $examId, $class_id);

            // TODO: Implement PDF generation using a library like TCPDF or Dompdf
            return response()->json($report);
        } catch (\Exception $e) {
            return back()->with('error', 'Error exporting report: ' . $e->getMessage());
        }
    }

    /**
     * Bulk manage exams (activate, close, delete)
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:publish,close,delete',
            'exam_ids' => 'required|array|min:1',
            'exam_ids.*' => 'exists:exams,id'
        ]);

        try {
            foreach ($validated['exam_ids'] as $examId) {
                switch ($validated['action']) {
                    case 'publish':
                        $this->examService->publishExam($examId);
                        break;
                    case 'close':
                        $this->examService->closeExam($examId);
                        break;
                    case 'delete':
                        $this->examService->deleteExam($examId);
                        break;
                }
            }

            return redirect()->back()->with('success', 'Action performed successfully on ' . count($validated['exam_ids']) . ' exam(s)');
        } catch (\Exception $e) {
            return back()->with('error', 'Error performing bulk action: ' . $e->getMessage());
        }
    }

    /**
     * Admin Results Overview Dashboard
     */
    public function resultsOverview($examId)
    {
        $exam = Exam::with(['academicSession', 'semester'])->findOrFail($examId);
        $school = \App\Models\School::first();

        // Get all marks for this exam
        $allMarks = \App\Models\Mark::with('student', 'subject', 'student.classData')
            ->where('exam_id', $examId)
            ->get();

        // Group by class
        $marksByClass = $allMarks->groupBy(function ($mark) {
            return $mark->student->classData->class_name ?? 'Unknown';
        });

        // Calculate performance statistics
        $stats = [
            'total_students' => \App\Models\Student::count(),
            'marked_students' => $allMarks->count(),
            'pending_students' => \App\Models\Student::count() - $allMarks->count(),
            'average_score' => $allMarks->avg('marks'),
            'highest_score' => $allMarks->max('marks'),
            'lowest_score' => $allMarks->min('marks'),
        ];

        // Grade distribution
        $gradeDistribution = $allMarks->groupBy('grade')->map(function ($items) {
            return $items->count();
        });

        return view('pages.admin.exams.results-overview', compact('exam', 'school', 'allMarks', 'marksByClass', 'stats', 'gradeDistribution'));
    }

    /**
     * Download Admin Results PDF Report
     */
    public function downloadResultsPDF($examId)
    {
        $exam = Exam::with(['academicSession', 'semester'])->findOrFail($examId);
        $school = \App\Models\School::first();

        // Get all marks for this exam
        $allMarks = \App\Models\Mark::with('student', 'subject', 'student.classData')
            ->where('exam_id', $examId)
            ->orderBy('student_id')
            ->get();

        // Calculate stats
        $stats = [
            'total_students' => \App\Models\Student::count(),
            'marked_students' => $allMarks->count(),
            'average_score' => $allMarks->avg('marks'),
            'highest_score' => $allMarks->max('marks'),
            'lowest_score' => $allMarks->min('marks'),
        ];

        // Grade distribution
        $gradeDistribution = $allMarks->groupBy('grade')->map(function ($items) {
            return $items->count();
        })->toArray();

        $data = [
            'school' => $school,
            'exam' => $exam,
            'allMarks' => $allMarks,
            'stats' => $stats,
            'gradeDistribution' => $gradeDistribution,
            'reportDate' => now()->format('d/m/Y H:i'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.admin.exams.results-pdf', $data)
            ->setPaper('a4')
            ->setOption('margin-top', 5)
            ->setOption('margin-bottom', 5);

        return $pdf->download("exam_results_{$exam->name}.pdf");
    }
}
