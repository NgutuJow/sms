<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class BulkImportController extends Controller
{
    public function index()
    {
        return view('pages.students.bulk');
    }

    public function upload(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:csv,txt'
    ]);

    try {
        $path = $request->file('file')->getRealPath();
        $file = fopen($path, 'r');
        
        // Skip header row
        fgetcsv($file);

        DB::beginTransaction();

        while (($row = fgetcsv($file)) !== FALSE) {
            // Skip empty rows or rows that don't have enough columns
            if (empty(array_filter($row)) || count($row) < 25) continue;

            // Create/update guardian
            // Email ipo column index 11
            $guardianEmail = trim($row[11]);
            
            $guardian = User::updateOrCreate(
                ['email' => $guardianEmail],
                [
                    'name'     => $row[10],
                    'phone'    => $row[12] ?? null,
                    'password' => Hash::make('12345678'),
                    'role'     => 'guardian',
                ]
            );

            // Create student
            Student::create([
                'user_id'            => $guardian->id,
                'admission_no'       => $row[0],
                'first_name'         => $row[1],
                'middle_name'        => $row[2] ?? null,
                'last_name'          => $row[3],
                'dob'                => $row[4],
                'gender'             => $row[5],
                'region'             => $row[6],
                'district'           => $row[7],
                'street'             => $row[8] ?? null,
                'address'            => $row[9] ?? null,
                'guardian_name'      => $row[10],
                'guardian_email'     => $row[11],
                'guardian_phone'     => $row[12] ?? null,
                'guardian_type'      => $row[13] ?? null,
                'guardian_occupation' => $row[14] ?? null,
                'guardian_region'    => $row[15] ?? null,
                'guardian_district'  => $row[16] ?? null,
                'guardian_street'    => $row[17] ?? null,
                'guardian_address'   => $row[18] ?? null,
                'education_level'    => $row[19] ?? null,
                'classes'            => $row[20],
                'stream'             => $row[21] ?? null,
                'academic_session'   => $row[22],
                'semester'           => $row[23] ?? null,
                'branches'           => $row[24],
                'school_attended'    => $row[25] ?? null,
                'grade_completed'    => $row[26] ?? null,
                'suspended_before'   => $row[27] ?? null,
                'suspension_reason'  => $row[28] ?? null,
                'status'             => $row[29] ?? 'active',
            ]);
        }

        fclose($file);
        DB::commit();

        return back()->with('success', 'Students imported successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Import failed: ' . $e->getMessage());
    }
}

    public function downloadTemplate()
    {
        $file = public_path('templates/student_import_template.csv');

        return response()->download($file);
    }
}