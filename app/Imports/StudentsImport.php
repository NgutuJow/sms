<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Prepend 255 to guardian phone
        $guardianPhone = $row['guardian_phone'] ?? null;
        if ($guardianPhone) {
            $guardianPhone = preg_replace('/[^0-9]/', '', $guardianPhone);
            if (str_starts_with($guardianPhone, '0')) {
                $guardianPhone = '255' . substr($guardianPhone, 1);
            } elseif (!str_starts_with($guardianPhone, '255')) {
                $guardianPhone = '255' . $guardianPhone;
            }
        }

        $guardian = User::firstOrCreate(
            [
                'email' => $row['guardian_email']
            ],
            [
                'name' => $row['guardian_name'],
                'phone' => $guardianPhone,
                'password' => Hash::make('12345678'),
                'role' => 'guardian'
            ]
        );

        return new Student([

            'user_id' => $guardian->id,

            'admission_no' => $row['admission_no'],
            'first_name' => $row['first_name'],
            'middle_name' => $row['middle_name'],
            'last_name' => $row['last_name'],

            'dob' => $row['dob'],
            'gender' => $row['gender'],

            'region' => $row['region'],
            'district' => $row['district'],
            'street' => $row['street'],
            'address' => $row['address'],

            'guardian_name' => $row['guardian_name'],
            'guardian_email' => $row['guardian_email'],
            'guardian_phone' => $guardianPhone,

            'guardian_type' => $row['guardian_type'],
            'guardian_occupation' => $row['guardian_occupation'],
            'guardian_region' => $row['guardian_region'],
            'guardian_district' => $row['guardian_district'],
            'guardian_street' => $row['guardian_street'],
            'guardian_address' => $row['guardian_address'],

            'education_level' => $row['education_level'],

            'classes' => $row['classes'],
            'stream' => $row['stream'],

            'academic_session' => $row['academic_session'],
            'semester' => $row['semester'],
            'branches' => $row['branches'],

            'school_attended' => $row['school_attended'],
            'grade_completed' => $row['grade_completed'],

            'suspended_before' => $row['suspended_before'],
            'suspension_reason' => $row['suspension_reason'],
        ]);
    }
}