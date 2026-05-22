<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'name',
        'description',
        'academic_session_id',
        'semester_id',
        'exam_type',
        'start_date',
        'end_date',
        'total_marks',
        'passing_marks',
        'created_by',
        'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'total_marks' => 'integer',
        'passing_marks' => 'integer'
    ];

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class, 'academic_session_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'exam_class', 'exam_id', 'class_id');
    }

    public function examinations()
    {
        return $this->hasMany(Examination::class);
    }

    public function classData()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'DRAFT' => 'badge-secondary',
            'ACTIVE' => 'badge-primary',
            'CLOSED' => 'badge-danger',
            'PUBLISHED' => 'badge-success'
        ];
        return $badges[$this->status] ?? 'badge-secondary';
    }

    public function isActive()
    {
        return $this->status === 'ACTIVE';
    }

    public function isClosed()
    {
        return $this->status === 'CLOSED';
    }

    public function isDraft()
    {
        return $this->status === 'DRAFT';
    }
}