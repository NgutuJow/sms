<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'from_class',
        'to_class',
        'academic_year',
        'promoted_by',
        'remarks',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function fromClass()
    {
        return $this->belongsTo(SchoolClass::class, 'from_class');
    }

    public function toClass()
    {
        return $this->belongsTo(SchoolClass::class, 'to_class');
    }

    public function promoter()
    {
        return $this->belongsTo(User::class, 'promoted_by');
    }
}