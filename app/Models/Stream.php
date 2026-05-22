<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    protected $fillable = ['school_class_id', 'stream_name', 'teacher_id'];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
    
    public function students()
    {
        return $this->hasMany(Student::class, 'stream');
    }

    public function getNameAttribute()
    {
        return $this->stream_name;
    }
}

