<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicSession extends Model
{
    protected $fillable = ['name', 'start_date', 'end_date', 'is_current'];

    // Hakikisha jina ni "semesters" (plural)
    public function semesters()
    {
        return $this->hasMany(Semester::class, 'academic_session_id');
    }
}