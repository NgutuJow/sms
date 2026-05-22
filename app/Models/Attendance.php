<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    // --- LAZIMISHA HAPA LIENDE KWENYE TABLE LETU HALISI TULILOTENGENEZA ---
    protected $table = 'student_attendances';

    protected $fillable = [
        'student_id',
        'class_id',
        'stream_id',
        'date',
        'status',
        'remarks',
        'recorded_by',
        'academic_session_id',
        'semester_id'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function classesRelation()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function session()
    {
        return $this->belongsTo(AcademicSession::class, 'academic_session_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function getClassesAttribute()
    {
        return $this->class_id;
    }

    public function setClassesAttribute($value)
    {
        $this->attributes['class_id'] = $value;
    }
}