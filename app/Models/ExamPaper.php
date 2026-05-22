<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamPaper extends Model
{
    use HasFactory;
    protected $fillable = [
    'class_id', 
    'subject_id', 
    'exam_id',
    'teacher_id', 
    'start_date', 
    'end_date', 
    'file_path'
];
 public function exam() {
        return $this->belongsTo(Exam::class, 'exam_id');
    }
     public function teacher() {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
