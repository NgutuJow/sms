<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Student;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\AcademicSession;
use App\Models\Semester;
use App\Models\Branch;
use App\Models\Stream;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::latest()->get();
        $classes = SchoolClass::all();
        $academicSessions = AcademicSession::all();
        $streams = Stream::all();

        return view('pages.students.index', compact('students', 'classes', 'academicSessions', 'streams'));
    }

    public function create()
    {
        $classes = SchoolClass::all();
        $streams = Stream::all();
        $academicSessions = AcademicSession::all();
        $semesters = Semester::all();
        $branches = Branch::all();

        return view('pages.students.create', compact('classes', 'streams', 'academicSessions', 'semesters', 'branches'));
    }

 public function store(Request $request)
{
    $validated = $request->validate([
        'admission_no' => 'required|unique:students',
        'first_name' => 'required',
        'middle_name' => 'nullable',
        'last_name' => 'required',
        'dob' => 'required|date',
        'gender' => 'required',

        'region' => 'required',
        'district' => 'required',
        'street' => 'nullable',
        'address' => 'nullable',

        'guardian_name' => 'required',
        'guardian_email' => 'required|email',
        'guardian_phone' => 'nullable',
        'guardian_type' => 'nullable',
        'guardian_occupation' => 'nullable',
        'guardian_region' => 'nullable',
        'guardian_district' => 'nullable',
        'guardian_street' => 'nullable',
        'guardian_address' => 'nullable',

        'education_level' => 'nullable',

        'classes' => 'required|exists:school_classes,id',
        'stream' => 'nullable|exists:streams,id',
        'academic_session' => 'required|exists:academic_sessions,id',
        'semester' => 'nullable|exists:semesters,id',
        'branches' => 'required|exists:branches,id',

        'school_attended' => 'nullable',
        'grade_completed' => 'nullable',
        'suspended_before' => 'nullable',
        'suspension_reason' => 'nullable',
    ]);

    DB::beginTransaction();

    try {

        // Sanitize and Prepend 255 to guardian phone
        $guardianPhone = $request->guardian_phone;
        if ($guardianPhone) {
            $guardianPhone = preg_replace('/[^0-9]/', '', $guardianPhone);
            if (str_starts_with($guardianPhone, '0')) {
                $guardianPhone = '255' . substr($guardianPhone, 1);
            } elseif (!str_starts_with($guardianPhone, '255')) {
                $guardianPhone = '255' . $guardianPhone;
            }
        }

        // guardian
        $guardian = User::firstOrCreate(
            ['email' => $request->guardian_email],
            [
                'name' => $request->guardian_name,
                'phone' => $guardianPhone,
                'password' => Hash::make('12345678'),
                'role' => 'guardian',
            ]
        );

        // Create Student User Account
        $studentUser = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => strtolower($request->admission_no) . "@school.com", // Unique student email
            'phone' => $request->guardian_phone, // Optionally use guardian phone if student has none
            'password' => Hash::make('student123'), // Default student password
            'role' => 'student',
        ]);

        // FULL student data (IMPORTANT PART)
        $studentData = [
            'user_id' => $guardian->id,
            'student_user_id' => $studentUser->id,

            'admission_no' => $request->admission_no,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'dob' => $request->dob,
            'gender' => $request->gender,

            'region' => $request->region,
            'district' => $request->district,
            'street' => $request->street,
            'address' => $request->address,

            'guardian_name' => $request->guardian_name,
            'guardian_email' => $request->guardian_email,
            'guardian_phone' => $guardianPhone,
            'guardian_type' => $request->guardian_type,
            'guardian_occupation' => $request->guardian_occupation,
            'guardian_region' => $request->guardian_region,
            'guardian_district' => $request->guardian_district,
            'guardian_street' => $request->guardian_street,
            'guardian_address' => $request->guardian_address,

            'education_level' => $request->education_level,

            'classes' => $request->classes,
            'stream' => $request->stream,
            'academic_session' => $request->academic_session,
            'semester' => $request->semester,
            'branches' => $request->branches,

            'school_attended' => $request->school_attended,
            'grade_completed' => $request->grade_completed,
            'suspended_before' => $request->suspended_before,
            'suspension_reason' => $request->suspension_reason,

            'status' => 'active',
        ];

        $student = Student::create($studentData);

        // Generate Invoice for the student based on Fee Structure
        try {
            $invoiceService = new \App\Services\InvoiceService();
            $invoiceService->generateForStudent($student);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to generate initial invoice for student {$student->id}: " . $e->getMessage());
        }

        DB::commit();

        return redirect()->route('students.index')
            ->with('success', 'Student registered successfully');

    } catch (\Exception $e) {
        DB::rollBack();

        return back()->withInput()
            ->with('error', $e->getMessage());
    }
}
    public function classStudents($id)
    {
        $class = SchoolClass::findOrFail($id);
        $students = Student::where('classes', $id)
            ->with(['classData', 'streamData', 'branchData', 'invoices', 'payments', 'semesterData', 'academicSessionData'])
            ->get();

        $studentsCount = $students->count();
        $paidCount = 0;
        $partiallyPaidCount = 0;
        $unpaidCount = 0;
        $noInvoiceCount = 0;
        $totalDue = 0;

        foreach ($students as $student) {
            $totalPaid = $student->payments->sum('amount');
            $balance = $student->invoices->sum('balance');
            $hasInvoices = $student->invoices->count() > 0;

            if (! $hasInvoices) {
                $noInvoiceCount++;
            } elseif ($balance <= 0) {
                $paidCount++;
            } elseif ($totalPaid > 0) {
                $partiallyPaidCount++;
            } else {
                $unpaidCount++;
            }

            $totalDue += max($balance, 0);
        }

        return view('pages.students.list', compact(
            'class',
            'students',
            'studentsCount',
            'paidCount',
            'partiallyPaidCount',
            'unpaidCount',
            'noInvoiceCount',
            'totalDue'
        ));
    }

    public function show($id)
    {
        // optional
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);

        return view('pages.students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $student->update($request->all());

        return redirect()->route('students.class', $student->classes)
            ->with('success', 'Student updated successfully');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $classId = $student->classes;

        $student->delete();

        return redirect()->route('students.class', $classId)
            ->with('success', 'Student deleted successfully');
    }
}
