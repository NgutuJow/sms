<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id', 'student_id', 'fine_amount', 'percentage', 'reason', 'due_date', 'applied_date', 'status'
    ];

    protected $casts = [
        'due_date' => 'date',
        'applied_date' => 'date',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
