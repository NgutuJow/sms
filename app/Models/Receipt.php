<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'payment_id','receipt_no','pdf_path','issued_at'
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}