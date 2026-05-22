<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\AcademicSession;
use App\Models\Semester;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Collection;

class ExamService
{
    /**
     * Get all exams with filters
     */
    public function getAllExams(array $filters = [])
    {
        $query = Exam::with(['academicSession', 'semester', 'classes', 'marks']);

        if (!empty($filters['session_id'])) {
            $query->where('academic_session_id', $filters['session_id']);
        }

        if (!empty($filters['semester_id'])) {
            $query->where('semester_id', $filters['semester_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get single exam details
     */
    public function getExamDetails($examId)
    {
        return Exam::with([
            'academicSession',
            'semester',
            'classes',
            'marks.student',
            'marks.subject',
            'createdBy'
        ])->findOrFail($examId);
    }

    /**
     * Create new exam
     */
    public function createExam(array $data)
    {
        $exam = Exam::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'academic_session_id' => $data['academic_session_id'],
            'semester_id' => $data['semester_id'],
            'exam_type' => $data['exam_type'] ?? 'MIDTERM', // MIDTERM, FINAL, QUIZ, ASSIGNMENT
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'total_marks' => $data['total_marks'] ?? 100,
            'passing_marks' => $data['passing_marks'] ?? 40,
            'created_by' => auth()->id(),
            'status' => 'DRAFT'
        ]);

        // Attach classes if provided
        if (!empty($data['classes'])) {
            $exam->classes()->sync($data['classes']);
        }

        return $exam;
    }

    /**
     * Update exam
     */
    public function updateExam($examId, array $data)
    {
        $exam = Exam::findOrFail($examId);

        $exam->update([
            'name' => $data['name'] ?? $exam->name,
            'description' => $data['description'] ?? $exam->description,
            'academic_session_id' => $data['academic_session_id'] ?? $exam->academic_session_id,
            'semester_id' => $data['semester_id'] ?? $exam->semester_id,
            'exam_type' => $data['exam_type'] ?? $exam->exam_type,
            'start_date' => $data['start_date'] ?? $exam->start_date,
            'end_date' => $data['end_date'] ?? $exam->end_date,
            'total_marks' => $data['total_marks'] ?? $exam->total_marks,
            'passing_marks' => $data['passing_marks'] ?? $exam->passing_marks,
        ]);

        if (!empty($data['classes'])) {
            $exam->classes()->sync($data['classes']);
        }

        return $exam;
    }

    /**
     * Publish exam for marking
     */
    public function publishExam($examId)
    {
        $exam = Exam::findOrFail($examId);
        $exam->update(['status' => 'ACTIVE']);
        return $exam;
    }

    /**
     * Close exam for marking
     */
    public function closeExam($examId)
    {
        $exam = Exam::findOrFail($examId);
        $exam->update(['status' => 'CLOSED']);
        return $exam;
    }

    /**
     * Release exam results to parents
     */
    public function releaseResults($examId)
    {
        $exam = Exam::with('classes.students')->findOrFail($examId);
        $exam->update(['status' => 'PUBLISHED']);
        return $exam;
    }

    /**
     * Delete exam
     */
    public function deleteExam($examId)
    {
        $exam = Exam::findOrFail($examId);
        $exam->marks()->delete();
        $exam->classes()->detach();
        $exam->delete();
        return true;
    }

    /**
     * Get exams for a specific class
     */
    public function getClassExams($classId)
    {
        return Exam::whereHas('classes', function ($query) use ($classId) {
            $query->where('school_classes.id', $classId);
        })->with(['academicSession', 'semester'])->latest()->get();
    }

    /**
     * Get available sessions and semesters for exam creation
     */
    public function getAvailableOptions()
    {
        return [
            'sessions' => AcademicSession::where('is_current', 1)->get(),
            'semesters' => Semester::all(),
            'classes' => SchoolClass::all(),
            'exam_types' => ['QUIZ', 'MIDTERM', 'FINAL', 'ASSIGNMENT', 'PROJECT', 'PRACTICAL']
        ];
    }
}
