<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $this->createDefaultUsers();

        // Schools, branches, classes, streams, sessions, semesters
        $schools = $this->createSchools();
        $branches = $this->createBranches($schools);
        $classes = $this->createClasses($branches);
        $sessions = $this->createAcademicSessions();
        $semesters = $this->createSemesters($sessions);
        $teachers = $this->createTeachers($branches, $faker);
        $streams = $this->createStreams($classes, $teachers);
        $subjects = $this->createSubjects($classes);
        $feeStructures = $this->createFeeStructures($classes, $sessions);

        $this->createStudents($classes, $streams, $sessions, $semesters, $branches, $faker);
        $this->createInvoices($feeStructures, $faker);
        $payments = $this->createPayments($faker);
        $this->createReceipts($payments);
        $exams = $this->createExams($sessions, $semesters, $classes, $faker);
        $this->createMarksAndResults($exams, $subjects, $faker);
        $this->createAttendances($faker);
    }

    private function createDefaultUsers()
    {
        $password = Hash::make('password123');

        $defaults = [
            ['name' => 'System Administrator', 'email' => 'admin@school.local', 'role' => 'admin'],
            ['name' => 'Accountant', 'email' => 'accountant@school.local', 'role' => 'accountant'],
            ['name' => 'Teacher Demo', 'email' => 'teacher@school.local', 'role' => 'teacher'],
            ['name' => 'Student Demo', 'email' => 'student@school.local', 'role' => 'student'],
            ['name' => 'Parent Demo', 'email' => 'parent@school.local', 'role' => 'parent'],
        ];

        foreach ($defaults as $d) {
            $existing = DB::table('users')->where('email', $d['email'])->first();
            if ($existing) {
                DB::table('users')->where('id', $existing->id)->update([
                    'name' => $d['name'],
                    'password' => $password,
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('users')->insert([
                    'name' => $d['name'],
                    'email' => $d['email'],
                    'password' => $password,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                    'role' => $d['role'],
                ]);
            }
        }
    }

    public function createSchools(): array
    {
        $schools = [
            [
                'code'        => 'RSA001',
                'name'        => 'Riverside Academy',
                'address'     => 'Plot 11 Riverside Road',
                'district'    => 'Kinondoni',
                'region'      => 'Dar es Salaam',
                'ward'        => 'Mikocheni',
                'email'       => 'info@riverside.ac',
                'phone'       => '0755123456',
                'school_type' => 'secondary',
                'status'      => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'code'        => 'HPS002',
                'name'        => 'Horizon Primary School',
                'address'     => 'Mtaa wa Horizon',
                'district'    => 'Ilemela',
                'region'      => 'Mwanza',
                'ward'        => 'Buswelu',
                'email'       => 'contact@horizonprimary.ac',
                'phone'       => '0755234567',
                'school_type' => 'primary',
                'status'      => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'code'        => 'SIS003',
                'name'        => 'Sunrise International',
                'address'     => 'Block 2, Arusha Road',
                'district'    => 'Arusha',
                'region'      => 'Arusha',
                'ward'        => 'Kimandolu',
                'email'       => 'hello@sunriseint.ac',
                'phone'       => '0755345678',
                'school_type' => 'all',
                'status'      => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['code' => $school['code']], 
                $school                      
            );
        }

        return DB::table('schools')->get()->toArray();
    }

    private function createBranches(array $schools): array
    {
        $branchData = [];
        $districts = ['Ilala', 'Nyamagana', 'Kilimanjaro'];
        $wards = ['Mwembe', 'Pamba', 'Unga'];

        foreach ($schools as $school) {
            foreach (['A', 'B'] as $suffix) {
                $branchData[] = [
                    'school_id' => $school->id,
                    'branch_name' => $suffix === 'A' ? $school->name . ' - A' : $school->name . ' - B',
                    'branch_code' => strtoupper(Str::slug($school->code . '-' . $suffix)),
                    'branch_type' => $suffix === 'A' ? 'Day' : 'Boarding',
                    'education_level' => $school->school_type,
                    'email' => $suffix === 'A' ? 'branchA@' . Str::slug($school->name) . '.ac' : 'branchB@' . Str::slug($school->name) . '.ac',
                    'phone' => '07' . rand(10000000, 99999999),
                    'alternative_phone' => '07' . rand(10000000, 99999999),
                    'region' => $school->region,
                    'district' => $districts[array_rand($districts)],
                    'ward' => $wards[array_rand($wards)],
                    'street' => 'Street ' . rand(1, 20),
                    'physical_address' => 'Block ' . rand(1, 30),
                    'postal_address' => 'P.O. Box ' . rand(1000, 9999),
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('branches')->insert($branchData);
        return DB::table('branches')->get()->toArray();
    }

    private function createClasses(array $branches): array
    {
        $classNames = ['Form 1', 'Form 2', 'Form 3', 'Form 4', 'Form 5'];
        $classData = [];

        foreach ($branches as $branch) {
            foreach ($classNames as $className) {
                $classData[] = [
                    'branch_id' => $branch->id,
                    'class_name' => $className,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('school_classes')->insert($classData);
        return DB::table('school_classes')->get()->toArray();
    }

    private function createAcademicSessions(): array
    {
        $sessionData = [
            ['name' => '2025/2026', 'start_date' => '2025-09-01', 'end_date' => '2026-08-31', 'is_current' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => '2024/2025', 'start_date' => '2024-09-01', 'end_date' => '2025-08-31', 'is_current' => 0, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('academic_sessions')->insert($sessionData);
        return DB::table('academic_sessions')->get()->toArray();
    }

    private function createSemesters(array $sessions): array
    {
        $semesterData = [];

        foreach ($sessions as $session) {
            $semesterData[] = [
                'academic_session_id' => $session->id,
                'semester_name' => 'Semester 1',
                'start_date' => $session->start_date,
                'end_date' => Carbon::parse($session->start_date)->addMonths(4)->format('Y-m-d'),
                'is_current' => $session->is_current,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $semesterData[] = [
                'academic_session_id' => $session->id,
                'semester_name' => 'Semester 2',
                'start_date' => Carbon::parse($session->start_date)->addMonths(5)->format('Y-m-d'),
                'end_date' => $session->end_date,
                'is_current' => $session->is_current,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('semesters')->insert($semesterData);
        return DB::table('semesters')->get()->toArray();
    }

    private function createTeachers(array $branches, $faker): array
    {
        $designations = ['Head of Department', 'Subject Teacher', 'Form Teacher', 'Coordinator'];
        $qualificationOptions = ['Diploma', 'Bachelor', 'Master', 'Certificate'];
        $teacherData = [];
        $teacherPassword = Hash::make('password123');

        foreach ($branches as $branch) {
            for ($index = 1; $index <= 2; $index++) {
                $teacherData[] = [
                    'branch_id' => $branch->id,
                    'teacher_id_number' => 'TCHR-' . strtoupper(Str::random(6)) . '-' . $index,
                    'full_name' => $faker->name(),
                    'email' => $faker->unique()->safeEmail(),
                    'phone' => '07' . rand(10000000, 99999999),
                    'gender' => $faker->randomElement(['Male', 'Female']),
                    'dob' => $faker->date('Y-m-d', '-25 years'),
                    'designation' => $faker->randomElement($designations),
                    'qualification' => $faker->randomElement($qualificationOptions),
                    'joining_date' => $faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
                    'status' => 1,
                    'image' => null,
                    'address' => $faker->address(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('teachers')->insert($teacherData);

        $teachers = DB::table('teachers')->get();
        foreach ($teachers as $teacher) {
            if (!$teacher->email) {
                continue;
            }

            $user = DB::table('users')->where('email', $teacher->email)->first();
            if ($user) {
                DB::table('users')->where('id', $user->id)->update([
                    'name' => $teacher->full_name,
                    'password' => $teacherPassword,
                    'role' => 'teacher',
                    'updated_at' => now(),
                ]);
                $userId = $user->id;
            } else {
                $userId = DB::table('users')->insertGetId([
                    'name' => $teacher->full_name,
                    'email' => $teacher->email,
                    'password' => $teacherPassword,
                    'role' => 'teacher',
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('teachers')->where('id', $teacher->id)->update(['user_id' => $userId]);
        }

        return DB::table('teachers')->get()->toArray();
    }

    private function createStreams(array $classes, array $teachers): array
    {
        $streamNames = ['A', 'B'];
        $streamData = [];

        foreach ($classes as $class) {
            foreach ($streamNames as $streamName) {
                $randomTeacher = $teachers[array_rand($teachers)];
                $streamData[] = [
                    'school_class_id' => $class->id,
                    'stream_name' => $streamName,
                    'teacher_id' => $randomTeacher->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('streams')->insert($streamData);
        return DB::table('streams')->get()->toArray();
    }

    private function createSubjects(array $classes): array
    {
        $subjectNames = ['Mathematics', 'English', 'Biology', 'Chemistry', 'Physics'];
        $subjectData = [];

        foreach ($classes as $class) {
            foreach ($subjectNames as $subjectName) {
                $subjectData[] = [
                    'school_class_id' => $class->id,
                    'subject_name' => $subjectName,
                    'subject_code' => strtoupper(Str::slug(substr($subjectName, 0, 3) . '-' . $class->id)),
                    'type' => 'Theory',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('subjects')->insert($subjectData);
        return DB::table('subjects')->get()->toArray();
    }

    private function createFeeStructures(array $classes, array $sessions): array
    {
        $feeTypes = ['Tuition', 'Library Fee'];
        $structures = [];
        $lastSession = end($sessions);
        $sessionName = $lastSession->name;

        foreach ($classes as $class) {
            foreach ($feeTypes as $feeType) {
                $structures[] = [
                    'class_id' => $class->id,
                    'academic_year' => $sessionName,
                    'fee_type' => $feeType,
                    'amount' => rand(200000, 500000),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('fee_structures')->insert($structures);
        return DB::table('fee_structures')->get()->toArray();
    }

    private function createStudents(array $classes, array $streams, array $sessions, array $semesters, array $branches, $faker)
    {
        $currentSession = collect($sessions)->firstWhere('is_current', 1);
        $currentSessionId = $currentSession ? $currentSession->id : 1;
        
        $semester = collect($semesters)->firstWhere('academic_session_id', $currentSessionId);
        $semesterId = $semester ? $semester->id : 1;
        
        $studentCount = 30;
        $studentPassword = Hash::make('password123');
        
        $streamByClass = [];
        foreach ($streams as $stream) {
            $streamByClass[$stream->school_class_id][] = $stream->id;
        }

        for ($i = 1; $i <= $studentCount; $i++) {
            $randomClass = $classes[array_rand($classes)];
            $classId = $randomClass->id;
            $streamId = !empty($streamByClass[$classId]) ? $streamByClass[$classId][array_rand($streamByClass[$classId])] : 1;
            $branchId = $randomClass->branch_id;
            
            $dob = $faker->dateTimeBetween('-18 years', '-11 years')->format('Y-m-d');
            $gender = $faker->randomElement(['Male', 'Female']);
            $firstName = $faker->firstName($gender === 'Male' ? 'male' : 'female');
            $lastName = $faker->lastName();
            $admissionNo = 'ADM' . str_pad($i, 4, '0', STR_PAD_LEFT);

            $guardianName = $faker->name();
            $guardianEmail = $faker->safeEmail();
            $guardianPhone = '07' . rand(10000000, 99999999);

            $guardianUser = DB::table('users')->where('email', $guardianEmail)->first();
            if ($guardianUser) {
                DB::table('users')->where('id', $guardianUser->id)->update([
                    'name' => $guardianName,
                    'password' => Hash::make('password123'),
                    'role' => 'parent',
                    'updated_at' => now(),
                ]);
                $guardianId = $guardianUser->id;
            } else {
                $guardianId = DB::table('users')->insertGetId([
                    'name' => $guardianName,
                    'email' => $guardianEmail,
                    'password' => Hash::make('password123'),
                    'role' => 'parent',
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $studentEmail = strtolower($firstName . '.' . $lastName . $i . '@schooltest.local');
            $existingStudent = DB::table('students')->where('admission_no', $admissionNo)->first();

            if ($existingStudent) {
                $studentUserId = $existingStudent->student_user_id;
                if ($studentUserId) {
                    DB::table('users')->where('id', $studentUserId)->update([
                        'name' => $firstName . ' ' . $lastName,
                        'email' => $studentEmail,
                        'email_verified_at' => now(),
                        'password' => $studentPassword,
                        'role' => 'student',
                        'updated_at' => now(),
                    ]);
                }
                
                DB::table('students')->where('id', $existingStudent->id)->update([
                    'user_id' => $guardianId,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'dob' => $dob,
                    'gender' => $gender,
                    'guardian_name' => $guardianName,
                    'guardian_email' => $guardianEmail,
                    'guardian_phone' => $guardianPhone,
                    'classes' => $classId,
                    'stream' => $streamId,
                    'academic_session' => $currentSessionId,
                    'semester' => $semesterId,
                    'branches' => $branchId,
                    'status' => 'active',
                    'updated_at' => now(),
                ]);
                continue;
            }

            $studentUserId = DB::table('users')->insertGetId([
                'name' => $firstName . ' ' . $lastName,
                'email' => $studentEmail,
                'email_verified_at' => now(),
                'password' => $studentPassword,
                'role' => 'student',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('students')->insert([
                'user_id' => $guardianId,
                'student_user_id' => $studentUserId,
                'admission_no' => $admissionNo,
                'first_name' => $firstName,
                'middle_name' => $faker->optional()->firstName(),
                'last_name' => $lastName,
                'dob' => $dob,
                'gender' => $gender,
                'region' => $faker->randomElement(['Dar es Salaam', 'Arusha', 'Mwanza', 'Morogoro']),
                'district' => $faker->word(),
                'street' => $faker->streetName(),
                'address' => $faker->address(),
                'guardian_name' => $guardianName,
                'guardian_email' => $guardianEmail,
                'guardian_phone' => $guardianPhone,
                'guardian_type' => $faker->randomElement(['Parent', 'Guardian', 'Aunt', 'Uncle']),
                'guardian_occupation' => $faker->jobTitle(),
                'guardian_region' => $faker->randomElement(['Dar es Salaam', 'Arusha', 'Mwanza']),
                'guardian_district' => $faker->city(),
                'guardian_street' => $faker->streetName(),
                'guardian_address' => $faker->address(),
                'education_level' => 'Secondary',
                'classes' => $classId,
                'stream' => $streamId,
                'academic_session' => $currentSessionId,
                'semester' => $semesterId,
                'branches' => $branchId,
                'school_attended' => $faker->company . ' School',
                'grade_completed' => 'Grade ' . rand(6, 10),
                'suspended_before' => 'No',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function createInvoices(array $feeStructures, $faker): array
    {
        $invoiceData = [];
        $invoiceItems = [];
        $feeByClass = collect($feeStructures)->groupBy('class_id');
        $invoiceCount = 30;

        $studentRecords = DB::table('students')->select('id', 'classes')->get()->keyBy('id');
        if ($studentRecords->isEmpty()) return [];
        
        $studentIds = $studentRecords->keys()->toArray();

        for ($i = 0; $i < $invoiceCount; $i++) {
            $studentId = $studentIds[array_rand($studentIds)];
            $student = $studentRecords[$studentId];
            
            $feeList = $feeByClass[$student->classes] ?? collect($feeStructures)->where('class_id', $student->classes);
            if ($feeList->isEmpty()) continue;

            $chosenFees = $feeList->random(min(3, $feeList->count()));
            $totalAmount = $chosenFees->sum('amount');
            $paidAmount = $faker->optional(0.8, 0)->randomFloat(2, 0, $totalAmount);
            $status = $paidAmount <= 0 ? 'unpaid' : ($paidAmount >= $totalAmount ? 'paid' : 'partial');
            $balance = max(0, $totalAmount - $paidAmount);

            $invoiceData[] = [
                'student_id' => $studentId,
                'academic_year' => '2025/2026',
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'balance' => $balance,
                'status' => $status,
                'reference_no' => 'INV-' . strtoupper(Str::random(8)) . '-' . $i,
                'due_date' => Carbon::now()->addDays(rand(7, 30))->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (empty($invoiceData)) return [];

        $referenceNos = array_column($invoiceData, 'reference_no');
        $this->insertChunked('invoices', $invoiceData, 50);
        
        $insertedInvoices = DB::table('invoices')->whereIn('reference_no', $referenceNos)->get();

        foreach ($insertedInvoices as $invoice) {
            $student = $studentRecords[$invoice->student_id];
            $feeList = $feeByClass[$student->classes] ?? collect($feeStructures)->where('class_id', $student->classes);
            if ($feeList->isEmpty()) continue;
            
            $chosenFees = $feeList->random(min(3, $feeList->count()));
            foreach ($chosenFees as $fee) {
                $invoiceItems[] = [
                    'invoice_id' => $invoice->id,
                    'fee_structure_id' => $fee->id,
                    'amount' => $fee->amount,
                ];
            }
        }

        $this->insertChunked('invoice_items', $invoiceItems, 50);
        return DB::table('invoices')->whereIn('id', $insertedInvoices->pluck('id'))->get()->toArray();
    }

    private function createPayments($faker): array
    {
        $paymentData = [];

        $invoiceRecords = DB::table('invoices')
            ->leftJoin('payments', 'payments.invoice_id', '=', 'invoices.id')
            ->whereNull('payments.invoice_id')
            ->select('invoices.id', 'invoices.student_id', 'invoices.paid_amount')
            ->get();

        foreach ($invoiceRecords as $invoice) {
            if ($invoice->paid_amount <= 0) {
                continue;
            }

            $paymentData[] = [
                'student_id' => $invoice->student_id,
                'invoice_id' => $invoice->id,
                'amount' => $invoice->paid_amount,
                'currency' => 'TZS',
                'payment_method' => $faker->randomElement(['Cash', 'Mobile Money', 'Bank Transfer']),
                'provider' => $faker->randomElement(['Tigo Pesa', 'M-Pesa', 'Airtel Money', 'Bank']),
                'provider_ref' => strtoupper(Str::random(10)),
                'status' => 'completed',
                'meta' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (empty($paymentData)) return [];

        $this->insertChunked('payments', $paymentData, 50);
        return DB::table('payments')->orderBy('id', 'desc')->limit(count($paymentData))->get()->toArray();
    }

    private function createReceipts(array $payments)
    {
        $receiptData = [];

        foreach ($payments as $payment) {
            $receiptData[] = [
                'payment_id' => $payment->id,
                'receipt_no' => 'RCT-' . strtoupper(Str::random(8)),
                'pdf_path' => null,
                'issued_at' => Carbon::now()->subDays(rand(0, 20))->toDateTimeString(),
            ];
        }

        if (!empty($receiptData)) {
            $this->insertChunked('receipts', $receiptData, 100);
        }
    }

    private function createExams(array $sessions, array $semesters, array $classes, $faker)
    {
        $examNames = ['Midterm Exam', 'End Term Exam', 'First Test'];
        $created = [];

        $currentSession = collect($sessions)->firstWhere('is_current', 1);
        if (!$currentSession) return collect();
        
        $semesterIds = collect($semesters)->where('academic_session_id', $currentSession->id)->pluck('id')->toArray();
        if (empty($semesterIds)) return collect();

        foreach ($examNames as $examName) {
            $created[] = [
                'name' => $examName,
                'academic_session_id' => $currentSession->id,
                'semester_id' => $semesterIds[array_rand($semesterIds)],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('exams')->insert($created);
        $exams = DB::table('exams')->orderBy('id', 'desc')->limit(count($examNames))->get();

        $examClass = [];
        foreach ($exams as $exam) {
            foreach ($classes as $class) {
                $examClass[] = [
                    'exam_id' => $exam->id,
                    'class_id' => $class->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        $this->insertChunked('exam_class', $examClass, 100);
        return $exams;
    }

    private function createMarksAndResults($exams, array $subjects, $faker)
    {
        if ($exams->isEmpty()) return;
        
        $examIds = $exams->pluck('id')->toArray();
        $subjectByClass = [];
        foreach ($subjects as $subject) {
            $subjectByClass[$subject->school_class_id][] = $subject->id;
        }

        $markData = [];
        $resultData = [];

        foreach (DB::table('students')->select('id', 'classes')->cursor() as $student) {
            if (empty($subjectByClass[$student->classes])) {
                continue;
            }

            foreach ($examIds as $examId) {
                $subjectIds = $subjectByClass[$student->classes];
                $scores = [];

                foreach ($subjectIds as $subjectId) {
                    $score = $faker->numberBetween(30, 100);
                    $scores[] = $score;
                    $markData[] = [
                        'student_id' => $student->id,
                        'class_id' => $student->classes,
                        'subject_id' => $subjectId,
                        'exam_id' => $examId,
                        'marks' => $score,
                        'grade' => $this->gradeForScore($score),
                        'marked_by' => null,
                        'marked_date' => now()->subDays(rand(1, 30)),
                        'remarks' => $faker->optional()->sentence(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    if (count($markData) >= 100) {
                        $this->insertChunked('marks', $markData, 100);
                        $markData = [];
                    }
                }

                $total = array_sum($scores);
                $average = count($scores) ? $total / count($scores) : 0;
                $resultData[] = [
                    'exam_id' => $examId,
                    'student_id' => $student->id,
                    'class_id' => $student->classes,
                    'total_marks' => $total,
                    'average_marks' => round($average, 2),
                    'grade' => $this->gradeForScore($average),
                    'position' => null,
                    'remarks' => $this->remarksForAverage($average),
                    'is_passed' => $average >= 50,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($resultData) >= 100) {
                    $this->insertChunked('exam_results', $resultData, 100);
                    $resultData = [];
                }
            }
        }

        if (!empty($markData)) {
            $this->insertChunked('marks', $markData, 100);
        }

        if (!empty($resultData)) {
            $this->insertChunked('exam_results', $resultData, 100);
        }
    }

    private function createAttendances($faker)
    {
        $attendanceData = [];
        $dates = [];
        $start = Carbon::now()->subDays(30);

        $currentSession = DB::table('academic_sessions')->where('is_current', 1)->first();
        $currentSemester = DB::table('semesters')->where('academic_session_id', optional($currentSession)->id)->first();

        for ($day = 0; $day < 10; $day++) {
            $dates[] = $start->copy()->addDays($day)->format('Y-m-d');
        }

        foreach (DB::table('students')->select('id', 'classes')->cursor() as $student) {
            $sampleDates = (array)array_rand(array_flip($dates), min(5, count($dates)));
            foreach ($sampleDates as $date) {
                $attendanceData[] = [
                    'student_id' => $student->id,
                    'class_id' => $student->classes,
                    'academic_session_id' => optional($currentSession)->id,
                    'semester_id' => optional($currentSemester)->id,
                    'date' => $date,
                    'status' => $faker->randomElement(['present', 'absent', 'late']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($attendanceData) >= 100) {
                    $this->insertChunked('student_attendances', $attendanceData, 100);
                    $attendanceData = [];
                }
            }
        }

        if (!empty($attendanceData)) {
            $this->insertChunked('student_attendances', $attendanceData, 100);
        }
    }

    private function gradeForScore($score): string
    {
        if ($score >= 80) return 'A';
        if ($score >= 70) return 'B';
        if ($score >= 60) return 'C';
        if ($score >= 50) return 'D';
        return 'F';
    }

    private function remarksForAverage($average): string
    {
        if ($average >= 75) return 'Excellent Work!';
        if ($average >= 60) return 'Good Progress.';
        if ($average >= 50) return 'Satisfactory performance.';
        return 'Needs regular improvement.';
    }

    private function insertChunked(string $table, array $data, int $chunkSize = 100)
    {
        if (empty($data)) return;
        
        foreach (array_chunk($data, $chunkSize) as $chunk) {
            DB::table($table)->insert($chunk);
        }
    }
}