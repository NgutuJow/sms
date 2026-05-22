<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'category', 'allocated_amount', 'spent_amount', 'month', 'year', 'branch_id'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
