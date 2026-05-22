<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Syllabus extends Model
{
    protected $table = 'syllabuses'; 

    protected $fillable = [
        'subject_id', 
        'teacher_id',      // LAZIMA iwepo hapa
        'topic_name', 
        'sub_topic_name',  // LAZIMA iongezwe hapa ili ionekane
        'status', 
        'completion_date',
        'sub_topics', 
        'file_path', // Hakikisha dash ipo kama ilivyo kwenye DB
    ];

    // Tumia CASTS kugeuza tarehe kuwa kitu kinachosomeka (Carbon instance)
    protected $casts = [
    'completion_date' => 'date',
    'sub_topics' => 'array', // Muhimu: Itageuza JSON kuwa array kiotomatiki
];
    // FUTA zile za mwanzo, tumia hii moja tu kwa mwalimu
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    public function subtopics() {
    return $this->hasMany(SubTopic::class);
}
public function classRelation()
    {
        // Badilisha 'class_id' kulingana na column iliyopo kwenye table yako ya timetables
        return $this->belongsTo(SchoolClass::class, 'class_id'); 
    }
}