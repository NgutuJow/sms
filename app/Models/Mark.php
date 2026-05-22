<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    protected $fillable = [
        'student_id',
        'class_id',
        'subject_id',
        'exam_id',
        'marks',
        'grade',
        'marked_by',
        'marked_date',
        'remarks'
    ];

    protected $casts = [
        'marks' => 'float',
        'marked_date' => 'datetime'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function classData()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    public function getGradeBadgeAttribute()
    {
        $badges = [
            'A' => 'badge-success',
            'B+' => 'badge-info',
            'B' => 'badge-info',
            'C+' => 'badge-warning',
            'C' => 'badge-warning',
            'D' => 'badge-danger',
            'E' => 'badge-danger'
        ];
        return $badges[$this->grade] ?? 'badge-secondary';
    }

    public function isMarked()
    {
        return $this->marks !== null;
    }

    public function scopePending($query)
    {
        return $query->whereNull('marks');
    }

    public function scopeMarked($query)
    {
        return $query->whereNotNull('marks');
    }
}