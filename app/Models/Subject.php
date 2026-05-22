<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'school_class_id', 
        'subject_name', 
        'subject_code', 
        'type'
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function getNameAttribute()
    {
        return $this->subject_name;
    }
    // app/Models/Subject.php

public function teacher()
{
    // Hakikisha column 'teacher_id' ipo kwenye table yako ya subjects
    return $this->belongsTo(Teacher::class, 'teacher_id');
}
// Ndani ya App\Models\Subject.php



public function examPapers() {
    return $this->hasMany(ExamPaper::class, 'subject_id');
}

public function syllabuses() {
    return $this->hasMany(Syllabus::class, 'subject_id');
}
}
