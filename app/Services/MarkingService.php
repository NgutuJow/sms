<?php

namespace App\Services;

use App\Models\Mark;
use App\Models\Student;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\ExamResult;
use Illuminate\Support\Facades\DB;

class MarkingService
{
    /**
     * Record marks for a student
     */
    public function recordMarks(array $data)
    {
        $mark = Mark::updateOrCreate(
            [
                'student_id' => $data['student_id'],
                'exam_id' => $data['exam_id'],
                'subject_id' => $data['subject_id'],
                'class_id' => $data['class_id']
            ],
            [
                'marks' => $data['marks'],
                'marked_by' => auth()->id(),
                'marked_date' => now(),
                'remarks' => $data['remarks'] ?? null
            ]
        );

        // Calculate grade and update
        $mark->grade = $this->calculateGrade($mark->marks);
        $mark->save();

        return $mark;
    }

    /**
     * Bulk upload marks for multiple students
     */
    public function bulkUploadMarks($examId, $classId, $subjectId, array $marksData)
    {
        $exam = Exam::findOrFail($examId);
        $results = ['success' => 0, 'failed' => 0, 'errors' => []];

        DB::beginTransaction();
        try {
            foreach ($marksData as $index => $data) {
                try {
                    $this->recordMarks([
                        'student_id' => $data['student_id'],
                        'exam_id' => $examId,
                        'subject_id' => $subjectId,
                        'class_id' => $classId,
                        'marks' => $data['marks'],
                        'remarks' => $data['remarks'] ?? null
                    ]);
                    $results['success']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = "Row " . ($index + 1) . ": " . $e->getMessage();
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $results;
    }

    /**
     * Calculate grade based on marks
     */
    public function calculateGrade($marks)
    {
        if ($marks >= 90) return 'A';
        if ($marks >= 80) return 'B+';
        if ($marks >= 70) return 'B';
        if ($marks >= 60) return 'C+';
        if ($marks >= 50) return 'C';
        if ($marks >= 40) return 'D';
        return 'E';
    }

    /**
     * Calculate grade points
     */
    public function calculateGradePoints($marks)
    {
        if ($marks >= 90) return 4.0;
        if ($marks >= 80) return 3.5;
        if ($marks >= 70) return 3.0;
        if ($marks >= 60) return 2.5;
        if ($marks >= 50) return 2.0;
        if ($marks >= 40) return 1.0;
        return 0.0;
    }

    /**
     * Get marks for an exam and class
     */
    public function getExamMarks($examId, $classId = null, $subjectId = null)
    {
        $query = Mark::where('exam_id', $examId)
            ->with(['student', 'subject', 'markedBy']);

        if ($classId) {
            $query->where('class_id', $classId);
        }

        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        return $query->get();
    }

    /**
     * Get student marks for an exam
     */
    public function getStudentExamMarks($studentId, $examId)
    {
        return Mark::where('student_id', $studentId)
            ->where('exam_id', $examId)
            ->with(['subject', 'exam', 'markedBy'])
            ->get();
    }

    /**
     * Calculate exam statistics for a class
     */
    public function getExamStatistics($examId, $classId = null)
    {
        $query = Mark::where('exam_id', $examId);

        if ($classId) {
            $query->where('class_id', $classId);
        }

        $marks = $query->get();

        if ($marks->isEmpty()) {
            return [
                'total_students' => 0,
                'marked_students' => 0,
                'average_marks' => 0,
                'highest_marks' => 0,
                'lowest_marks' => 0,
                'grade_distribution' => []
            ];
        }

        $markValues = $marks->pluck('marks')->filter(function ($m) { return $m !== null; });

        return [
            'total_students' => Mark::where('exam_id', $examId)->where('class_id', $classId ?? $marks->first()->class_id)->distinct('student_id')->count(),
            'marked_students' => $markValues->count(),
            'average_marks' => $markValues->avg(),
            'highest_marks' => $markValues->max(),
            'lowest_marks' => $markValues->min(),
            'grade_distribution' => $marks->groupBy('grade')->map->count()
        ];
    }

    /**
     * Get teacher's marking progress
     */
    public function getTeacherMarkingProgress($teacherId, $examId)
    {
        // Get all classes taught by teacher
        $classes = DB::table('teacher_classes')
            ->where('teacher_id', $teacherId)
            ->pluck('class_id');

        $progress = [];

        foreach ($classes as $classId) {
            // Get marks entered for this class
            $totalMarked = Mark::where('exam_id', $examId)
                ->where('class_id', $classId)
                ->whereNotNull('marks')
                ->count();

            $totalStudents = Student::where('classes', $classId)->count();

            $progress[] = [
                'class_id' => $classId,
                'marked' => $totalMarked,
                'total' => $totalStudents,
                'percentage' => $totalStudents > 0 ? ($totalMarked / $totalStudents) * 100 : 0
            ];
        }

        return $progress;
    }

    /**
     * Update mark remarks
     */
    public function updateMarkRemarks($markId, $remarks)
    {
        $mark = Mark::findOrFail($markId);
        $mark->remarks = $remarks;
        $mark->save();
        return $mark;
    }

    /**
     * Get marks pending review
     */
    public function getPendingMarks($examId, $classId = null)
    {
        $query = Mark::where('exam_id', $examId)
            ->whereNull('marks')
            ->with(['student', 'subject', 'classData']);

        if ($classId) {
            $query->where('class_id', $classId);
        }

        return $query->get();
    }
}
