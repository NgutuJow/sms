<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Stream;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentServiceController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::withCount('students')->get();
        $students = Student::with(['user', 'class'])->latest()->paginate(15);
        return view('pages.students.index', compact('students', 'classes'));
    }

    public function create()
    {
        // Tunahitaji mikoa/madarasa kama bado unataka kuyaonyesha
        $classis = SchoolClass::all(); 
        $streams = Stream::all();
        return view('pages.students.create', compact('classis', 'streams'));
    }

    public function store(Request $request)
    {
        // 1. Validation (Imebaki kwa Personal Info pekee)
        $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'admission_no' => 'required|unique:students,admission_no',
            'dob'          => 'required|date',
            'gender'       => 'required|in:Male,Female',
            'region'       => 'required',
            'district'     => 'required',
        ]);

        try {
            DB::transaction(function () use ($request) {
                
                // 2. Kutengeneza User wa Mwanafunzi (kwa ajili ya Login)
                $studentUser = User::create([
                    'name'     => $request->first_name . ' ' . $request->last_name,
                    'email'    => strtolower($request->admission_no) . "@school.com", // Email ya kipekee
                    'password' => Hash::make('student123'), // Default password
                    'role'     => 'student'
                ]);

                // 3. Kutengeneza Profile ya Mwanafunzi (Student Table)
                Student::create([
                    'user_id'      => $studentUser->id,
                    'admission_no' => $request->admission_no,
                    'first_name'   => $request->first_name,
                    'middle_name'  => $request->middle_name,
                    'last_name'    => $request->last_name,
                    'dob'          => $request->dob,
                    'gender'       => $request->gender,
                    'region'       => $request->region,
                    'district'     => $request->district,
                    'street'       => $request->street,
                    'status'       => 'active',
                ]);
            });

            // Alert ya Mafanikio
            return redirect()->route('students.index')->with('success', 'Mwanafunzi amesajiliwa kikamilifu!');

        } catch (\Exception $e) {
            // Alert ya Kosa
            return redirect()->back()
                ->withInput()
                ->with('error', 'Imeshindikana kusajili. Kosa: ' . $e->getMessage());
        }
    }
}