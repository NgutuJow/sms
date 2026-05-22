<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $table = 'school_classes';

    protected $fillable = ['branch_id', 'class_name'];

    // 1. Uhusiano na Wanafunzi (Huu ni muhimu kwa zile Cards za juu)
    public function students()
    {
        return $this->hasMany(Student::class, 'classes');
    }

    // 2. Uhusiano na Branches
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // 3. Uhusiano na Streams
    public function streams()
    {
        return $this->hasMany(Stream::class, 'school_class_id');
    }

    // 4. Uhusiano na Subjects
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'school_class_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_classes', 'class_id', 'teacher_id');
    }

    public function getNameAttribute()
    {
        return $this->class_name;
    }
}
