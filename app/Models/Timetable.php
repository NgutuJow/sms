<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // MUHIMU: Hakikisha hii ipo

class Timetable extends Model
{
    use HasFactory;

    protected $fillable = [
        'stream_id', 
        'subject_id', 
        'teacher_id', 
        'session_id', // Hakikisha hii imeongezwa hapa
        'day_of_week', 
        'start_time', 
        'end_time', 
        'timetable_name', 
        'file_path'
    ];

    // 1. Relationship na Stream
    public function stream(): BelongsTo 
    {
        return $this->belongsTo(Stream::class, 'stream_id');
    }

    public function subject(): BelongsTo 
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function teacher(): BelongsTo 
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function classRelation(): BelongsTo
    {
        // Ikiwa unatumia class kupitia stream, unaweza usihitaji hii column moja kwa moja
        return $this->belongsTo(SchoolClass::class, 'class_id'); 
    }

    public function session(): BelongsTo
    {
        // Hakikisha column kwenye database inaitwa 'session_id'
        return $this->belongsTo(AcademicSession::class, 'session_id');
    }
}