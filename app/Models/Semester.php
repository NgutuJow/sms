<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    // Hizi tu ndizo field zilizopo kwenye database
    protected $fillable = [
        'academic_session_id',
        'semester_name',
        'start_date',
        'end_date',
        'is_current',
    ];

    protected $attributes = [
        'is_current' => false,
    ];

    protected $casts = [
        'is_current' => 'boolean',
    ];

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class, 'academic_session_id');
    }
}