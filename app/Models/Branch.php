<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'branch_name',
        'branch_code',
        'branch_type',
        'education_level',
        'email',
        'phone',
        'alternative_phone',
        'region',
        'district',
        'ward',
        'street',
        'physical_address',
        'postal_address',
        
        'status',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}