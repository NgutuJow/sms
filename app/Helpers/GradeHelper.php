<?php

namespace App\Helpers;

class GradeHelper
{
    public static function getGrade($marks)
    {
        if ($marks >= 75) return 'A';
        if ($marks >= 65) return 'B';
        if ($marks >= 45) return 'C';
        if ($marks >= 30) return 'D';
        return 'F';
    }

    public static function getPoint($marks)
    {
        if ($marks >= 75) return 1;
        if ($marks >= 65) return 2;
        if ($marks >= 45) return 3;
        if ($marks >= 30) return 4;
        return 5;
    }

    public static function getRemarks($grade)
    {
        $remarks = [
            'A' => 'Excellent',
            'B' => 'Very Good',
            'C' => 'Good',
            'D' => 'Satisfactory',
            'F' => 'Fail'
        ];
        return $remarks[$grade] ?? 'N/A';
    }
}