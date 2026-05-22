<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examination extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'class_id',
        'subject_id',
        'teacher_id',
        'question_count',
        'duration_minutes',
        'instructions',
        'is_published',
        'published_date',
        'status'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_date' => 'datetime',
        'question_count' => 'integer',
        'duration_minutes' => 'integer'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function marks()
    {
        return $this->hasMany(Mark::class, 'exam_id', 'exam_id');
    }
}

