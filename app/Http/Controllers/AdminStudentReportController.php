<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Mark;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminStudentReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * List all students for report selection
     */
    public function studentsList(Request $request)
    {
        $query = Student::with('classData', 'user', 'stream');
        
        if ($request->filled('class')) {
            $query->where('class_id', $request->class);
        }
        
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->whereHas('user', function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm);
            })->orWhere('admission_no', 'like', $searchTerm);
        }

        $students = $query->paginate(25);
        $classes = SchoolClass::orderBy('class_name')->get();

        return view('pages.admin.students.list', compact('students', 'classes'));
    }

    /**
     * Student detailed performance report
     */
    public function studentReport($studentId)
    {
        $student = Student::with('classData', 'user', 'stream', 'branch')->findOrFail($studentId);
        $school = \App\Models\School::first();

        // Get all marks for this student
        $allMarks = Mark::with('exam', 'subject', 'student')
            ->where('student_id', $studentId)
            ->orderBy('exam_id', 'desc')
            ->get();

        // Group by exam
        $marksByExam = $allMarks->groupBy('exam_id')->map(function ($marks) {
            return [
                'exam' => $marks->first()->exam,
                'marks' => $marks,
                'total_marks' => $marks->sum('marks'),
                'average_marks' => $marks->avg('marks'),
                'total_subjects' => $marks->count(),
            ];
        });

        // Calculate statistics
        $stats = [
            'total_exams' => $marksByExam->count(),
            'total_subjects_taken' => $allMarks->count(),
            'overall_average' => $allMarks->avg('marks'),
            'highest_single_mark' => $allMarks->max('marks'),
            'lowest_single_mark' => $allMarks->min('marks'),
            'total_marks_obtained' => $allMarks->sum('marks'),
        ];

        // Calculate grades distribution
        $gradeDistribution = $allMarks->groupBy('grade')->map(function ($items) {
            return $items->count();
        });

        // Get class ranking
        $classRanking = $this->getClassRanking($student->class_id, $studentId);
        $studentRank = $classRanking['rank'];
        $totalInClass = $classRanking['total'];

        // Get top subjects (by average marks in those subjects)
        $topSubjects = $allMarks->groupBy('subject_id')->map(function ($marks) {
            return [
                'subject' => $marks->first()->subject,
                'average' => $marks->avg('marks'),
                'count' => $marks->count(),
            ];
        })->sortByDesc('average')->take(5);

        return view('pages.admin.students.report', compact(
            'student',
            'school',
            'allMarks',
            'marksByExam',
            'stats',
            'gradeDistribution',
            'studentRank',
            'totalInClass',
            'topSubjects'
        ));
    }

    /**
     * Download student report as PDF
     */
    public function downloadStudentReportPDF($studentId)
    {
        $student = Student::with('classData', 'user', 'stream', 'branch')->findOrFail($studentId);
        $school = \App\Models\School::first();

        // Get all marks for this student
        $allMarks = Mark::with('exam', 'subject')
            ->where('student_id', $studentId)
            ->orderBy('exam_id', 'desc')
            ->get();

        // Group by exam
        $marksByExam = $allMarks->groupBy('exam_id')->map(function ($marks) {
            return [
                'exam' => $marks->first()->exam,
                'marks' => $marks,
                'total_marks' => $marks->sum('marks'),
                'average_marks' => $marks->avg('marks'),
            ];
        });

        // Calculate statistics
        $stats = [
            'total_exams' => $marksByExam->count(),
            'total_subjects' => $allMarks->count(),
            'overall_average' => $allMarks->avg('marks'),
            'highest_mark' => $allMarks->max('marks'),
            'lowest_mark' => $allMarks->min('marks'),
            'total_obtained' => $allMarks->sum('marks'),
        ];

        // Class ranking
        $classRanking = $this->getClassRanking($student->class_id, $studentId);

        $data = [
            'school' => $school,
            'student' => $student,
            'allMarks' => $allMarks,
            'marksByExam' => $marksByExam,
            'stats' => $stats,
            'studentRank' => $classRanking['rank'],
            'totalInClass' => $classRanking['total'],
            'reportDate' => now()->format('d/m/Y H:i'),
        ];

        $pdf = Pdf::loadView('pages.admin.students.report-pdf', $data)
            ->setPaper('a4')
            ->setOption('margin-top', 5)
            ->setOption('margin-bottom', 5);

        return $pdf->download("student_report_{$student->user->name}.pdf");
    }

    /**
     * Calculate student rank within their class
     */
    private function getClassRanking($classId, $studentId)
    {
        // Get all students in the same class
        $classStudents = Student::where('class_id', $classId)->pluck('id')->toArray();

        // Calculate total marks for each student in this class
        $studentScores = [];
        foreach ($classStudents as $sId) {
            $totalMarks = Mark::where('student_id', $sId)->sum('marks');
            $studentScores[$sId] = $totalMarks;
        }

        // Sort by total marks descending
        arsort($studentScores);

        // Get rank of current student
        $rank = array_search($studentId, array_keys($studentScores)) + 1;

        return [
            'rank' => $rank,
            'total' => count($classStudents),
            'scores' => $studentScores,
        ];
    }
}
