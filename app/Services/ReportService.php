<?php

namespace App\Services;

use App\Models\Mark;
use App\Models\Student;
use App\Models\Exam;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\DB;

class ReportService
{
    protected $markingService;

    public function __construct(MarkingService $markingService)
    {
        $this->markingService = $markingService;
    }

    /**
     * Generate student exam report
     */
    public function generateStudentReport($studentId, $examId)
    {
        $student = Student::findOrFail($studentId);
        $exam = Exam::findOrFail($examId);

        $marks = Mark::where('student_id', $studentId)
            ->where('exam_id', $examId)
            ->with(['subject', 'exam'])
            ->get();

        $totalMarks = $marks->sum('marks');
        $subjects = $marks->count();
        $averageMarks = $subjects > 0 ? $totalMarks / $subjects : 0;

        return [
            'student' => $student,
            'exam' => $exam,
            'marks' => $marks,
            'total_marks' => $totalMarks,
            'subjects_count' => $subjects,
            'average_marks' => $averageMarks,
            'position' => $this->getStudentPosition($studentId, $examId),
            'remarks' => $this->generateRemarks($averageMarks, $exam->passing_marks)
        ];
    }

    /**
     * Generate class exam report
     */
    public function generateClassReport($examId, $classId)
    {
        $exam = Exam::findOrFail($examId);
        $class = SchoolClass::findOrFail($classId);

        $marks = Mark::where('exam_id', $examId)
            ->where('class_id', $classId)
            ->with(['student', 'subject'])
            ->get();

        $studentMarks = [];
        foreach ($marks->groupBy('student_id') as $studentMarks) {
            $totalMarks = $studentMarks->sum('marks');
            $subjects = $studentMarks->count();
            $studentMarks[] = [
                'student' => $studentMarks->first()->student,
                'total_marks' => $totalMarks,
                'average_marks' => $subjects > 0 ? $totalMarks / $subjects : 0,
                'subjects' => $studentMarks
            ];
        }

        return [
            'exam' => $exam,
            'class' => $class,
            'student_reports' => $studentMarks,
            'statistics' => $this->markingService->getExamStatistics($examId, $classId),
            'grade_distribution' => $this->getGradeDistribution($examId, $classId)
        ];
    }

    /**
     * Generate comprehensive exam report
     */
    public function generateExamReport($examId)
    {
        $exam = Exam::findOrFail($examId);

        $marks = Mark::where('exam_id', $examId)
            ->with(['student', 'subject', 'classData'])
            ->get();

        $classReports = [];
        foreach ($exam->classes as $class) {
            $classReports[] = [
                'class' => $class,
                'report' => $this->generateClassReport($examId, $class->id)
            ];
        }

        return [
            'exam' => $exam,
            'total_students_marked' => Mark::where('exam_id', $examId)->distinct('student_id')->count(),
            'class_reports' => $classReports,
            'overall_statistics' => [
                'average_marks' => Mark::where('exam_id', $examId)->avg('marks'),
                'highest_marks' => Mark::where('exam_id', $examId)->max('marks'),
                'lowest_marks' => Mark::where('exam_id', $examId)->min('marks'),
                'pass_rate' => $this->calculatePassRate($examId, $exam->passing_marks)
            ]
        ];
    }

    /**
     * Generate subject-wise report
     */
    public function generateSubjectReport($examId, $subjectId)
    {
        $marks = Mark::where('exam_id', $examId)
            ->where('subject_id', $subjectId)
            ->with(['student', 'classData'])
            ->get();

        return [
            'exam_id' => $examId,
            'subject_id' => $subjectId,
            'total_marked' => $marks->count(),
            'average_marks' => $marks->avg('marks'),
            'highest_marks' => $marks->max('marks'),
            'lowest_marks' => $marks->min('marks'),
            'marks' => $marks,
            'performance_analysis' => $this->analyzeSubjectPerformance($marks)
        ];
    }

    /**
     * Generate teacher's marking report
     */
    public function generateTeacherReport($teacherId, $examId)
    {
        $markedClasses = DB::table('marks')
            ->where('marked_by', $teacherId)
            ->where('exam_id', $examId)
            ->distinct('class_id')
            ->pluck('class_id');

        $report = [
            'teacher_id' => $teacherId,
            'exam_id' => $examId,
            'classes_marked' => $markedClasses->count(),
            'total_marks_entered' => Mark::where('exam_id', $examId)->where('marked_by', $teacherId)->count(),
            'marks_by_class' => []
        ];

        foreach ($markedClasses as $classId) {
            $classMarks = Mark::where('exam_id', $examId)
                ->where('class_id', $classId)
                ->where('marked_by', $teacherId)
                ->get();

            $report['marks_by_class'][] = [
                'class_id' => $classId,
                'total_marked' => $classMarks->count(),
                'average_marks' => $classMarks->avg('marks')
            ];
        }

        return $report;
    }

    /**
     * Get student position in exam
     */
    public function getStudentPosition($studentId, $examId)
    {
        $studentMarks = Mark::where('student_id', $studentId)
            ->where('exam_id', $examId)
            ->sum('marks');

        $betterStudents = Mark::where('exam_id', $examId)
            ->groupBy('student_id')
            ->selectRaw('student_id, SUM(marks) as total')
            ->having('total', '>', $studentMarks)
            ->distinct()
            ->count();

        return $betterStudents + 1;
    }

    /**
     * Calculate pass rate for exam
     */
    public function calculatePassRate($examId, $passingMarks)
    {
        $totalMarked = Mark::where('exam_id', $examId)->distinct('student_id')->count();
        
        if ($totalMarked == 0) return 0;

        $passed = Mark::where('exam_id', $examId)
            ->groupBy('student_id')
            ->selectRaw('student_id, AVG(marks) as avg_marks')
            ->having('avg_marks', '>=', $passingMarks)
            ->count();

        return ($passed / $totalMarked) * 100;
    }

    /**
     * Get grade distribution
     */
    public function getGradeDistribution($examId, $classId = null)
    {
        $query = Mark::where('exam_id', $examId);

        if ($classId) {
            $query->where('class_id', $classId);
        }

        return $query->groupBy('grade')->selectRaw('grade, COUNT(*) as count')->get();
    }

    /**
     * Analyze subject performance
     */
    public function analyzeSubjectPerformance($marks)
    {
        $total = $marks->count();
        if ($total == 0) {
            return ['excellent' => 0, 'good' => 0, 'average' => 0, 'poor' => 0];
        }

        return [
            'excellent' => ($marks->where('marks', '>=', 80)->count() / $total) * 100,
            'good' => ($marks->whereBetween('marks', [60, 79])->count() / $total) * 100,
            'average' => ($marks->whereBetween('marks', [40, 59])->count() / $total) * 100,
            'poor' => ($marks->where('marks', '<', 40)->count() / $total) * 100
        ];
    }

    /**
     * Export report to array (for API)
     */
    public function exportReport($reportType, $examId, $classId = null)
    {
        switch ($reportType) {
            case 'exam':
                return $this->generateExamReport($examId);
            case 'class':
                return $classId ? $this->generateClassReport($examId, $classId) : null;
            case 'subject':
                return $classId ? $this->generateSubjectReport($examId, $classId) : null;
            default:
                return null;
        }
    }

    /**
     * Generate remarks based on performance
     */
    private function generateRemarks($averageMarks, $passingMarks)
    {
        if ($averageMarks >= 80) {
            return 'Excellent performance. Keep up the good work!';
        } elseif ($averageMarks >= 60) {
            return 'Good performance. Strive to improve further.';
        } elseif ($averageMarks >= $passingMarks) {
            return 'Satisfactory performance. More effort needed.';
        } else {
            return 'Below passing mark. Needs improvement.';
        }
    }
}
