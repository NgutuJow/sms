<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
protected $fillable = [

    'user_id',
    'student_user_id',
    'admission_no',

    'first_name',
    'middle_name',
    'last_name',

    'dob',
    'gender',

    'region',
    'district',
    'street',
    'address',

    'guardian_name',
    'guardian_email',
    'guardian_phone',
    'guardian_type',
    'guardian_occupation',
    'guardian_region',
    'guardian_district',
    'guardian_street',
    'guardian_address',

    'education_level',

    'classes',
    'stream',
    'academic_session',
    'semester',
    'branches',

    'school_attended',
    'grade_completed',
    'suspended_before',
    'suspension_reason',

    'status',
];
    public function classData()
    {
        return $this->belongsTo(SchoolClass::class, 'classes');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function studentUser()
    {
        return $this->belongsTo(User::class, 'student_user_id');
    }

    public function streamData()
    {
        return $this->belongsTo(Stream::class, 'stream');
    }

    public function branchData()
    {
        return $this->belongsTo(Branch::class, 'branches');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function semesterData()
    {
        return $this->belongsTo(Semester::class, 'semester');
    }

    public function academicSessionData()
    {
        return $this->belongsTo(AcademicSession::class, 'academic_session');
    }

    public function guardian()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

