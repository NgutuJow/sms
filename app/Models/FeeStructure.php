<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SchoolClass;

class FeeStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'academic_year',
        'fee_type',
        'amount',
        'allow_installments',
        'number_of_installments',
        'installment_dates',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Dynamically add installment fields if they exist in the database
        if (\Schema::hasColumn('fee_structures', 'allow_installments')) {
            $this->fillable = array_merge($this->fillable, [
                'allow_installments',
                'number_of_installments',
                'installment_dates',
            ]);
        }
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}
