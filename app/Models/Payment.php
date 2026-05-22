<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'student_id','invoice_id','amount','currency',
        'payment_method','provider','provider_ref','status','meta'
    ];

    protected $casts = [
        'meta' => 'array'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function receipt()
    {
        return $this->hasOne(Receipt::class);
    }
}