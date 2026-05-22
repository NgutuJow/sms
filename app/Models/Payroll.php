<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id', 'basic_salary', 'allowances', 'deductions', 'net_salary', 'month', 'year', 'status', 'payment_date'
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
