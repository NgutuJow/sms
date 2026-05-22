<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;
    protected $fillable = [
    'name',
    'code',
    'email',
    'phone',
    'address',
    'region',
    'district',
    'ward',
    'school_type',
    'status'
];
public function branches()
{
    return $this->hasMany(Branch::class);
}

}
