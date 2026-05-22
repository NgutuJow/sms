<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    protected $fillable = [
        'exam_id',
        'student_id',
        'class_id',
        'total_marks',
        'average_marks',
        'grade',
        'position',
        'remarks',
        'is_passed'
    ];

    protected $casts = [
        'total_marks' => 'float',
        'average_marks' => 'float',
        'is_passed' => 'boolean'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function marks()
    {
        return $this->hasManyThrough(Mark::class, Student::class, 'id', 'student_id', 'student_id', 'id')
                    ->where('marks.exam_id', $this->exam_id);
    }
}
