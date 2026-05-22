<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $fillable = [
    'user_id', 'branch_id', 'teacher_id_number', 'full_name', 
    'email', 'phone', 'gender', 'designation', 
    'qualification', 'joining_date', 'image', 'status'
];

// app/Models/Teacher.php
public function user()
{
    return $this->belongsTo(User::class);
}

public function streams()
{
    return $this->hasMany(Stream::class, 'teacher_id');
}

public function getStreamAttribute()
{
    return $this->streams()->first();
}


public function branch() {
    return $this->belongsTo(Branch::class);
}
public function classes()
{
    return $this->belongsToMany(SchoolClass::class, 'teacher_classes', 'teacher_id', 'class_id');
}

public function getNameAttribute()
{
    return $this->full_name;
}

}
