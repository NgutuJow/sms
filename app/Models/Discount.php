<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'discount_type', 'amount', 'percentage', 'reason', 'valid_from', 'valid_to'
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_to' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
