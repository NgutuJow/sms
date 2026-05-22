<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'student_id','academic_year','total_amount',
        'paid_amount','balance','status','reference_no','due_date'
    ];

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }

    public function items() {
        return $this->hasMany(InvoiceItem::class);
    }
}