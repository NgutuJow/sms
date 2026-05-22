<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promotion;
use App\Models\Mark;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Exam;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::with('branch')->get();
        $exams = Exam::all();
        $recentPromotions = Promotion::with(['student', 'fromClass', 'toClass', 'promoter'])
            ->latest()
            ->limit(10)
            ->get();
            
        return view('pages.promotions.index', compact('classes', 'exams', 'recentPromotions'));
    }

    public function create(Request $request)
    {
        $studentId = $request->query('student_id');
        
        if (!$studentId) {
            return redirect()->back()->with('error', 'Mwanafunzi hajapatikana.');
        }

        $student = Student::with(['classData', 'academicSessionData'])->findOrFail($studentId);
        $currentClass = $student->classData;
        $classes = SchoolClass::all();
        $exams = Exam::all();
        $sessions = \App\Models\AcademicSession::all();

        return view('pages.promotions.create', compact('student', 'currentClass', 'classes', 'exams', 'sessions'));
    }

   public function store(Request $request)
{
    $request->validate([
        'student_id' => 'required|exists:students,id',
        'to_class_id' => 'required|exists:school_classes,id',
        'academic_session_id' => 'required|exists:academic_sessions,id',
    ]);

    try {
        DB::transaction(function () use ($request) {
            $student = Student::findOrFail($request->student_id);
            $session = \App\Models\AcademicSession::findOrFail($request->academic_session_id);

            // Snapshot log
            DB::table('student_logs')->insert([
                'admission_no'      => $student->admission_no,
                'first_name'        => $student->first_name,
                'last_name'         => $student->last_name,
                'middle_name'       => $student->middle_name ?? '',
                'dob'               => $student->dob,
                'gender'            => $student->gender,
                'region'            => $student->region,
                'district'          => $student->district,
                'street'            => $student->street,
                'address'           => $student->address,
                'guardian_name'     => $student->guardian_name,
                'guardian_email'    => $student->guardian_email,
                'guardian_phone'    => $student->guardian_phone,
                'guardian_occupation'=> $student->guardian_occupation,
                'guardian_address'  => $student->guardian_address,
                'guardian_type'     => $student->guardian_type,
                'guardian_region'   => $student->guardian_region,
                'guardian_street'   => $student->guardian_street,
                'guardian_district' => $student->guardian_district,
                'education_level'   => $student->education_level,
                'classes'           => $student->classes,
                'stream'            => $student->stream,
                'school_attended'   => $student->school_attended,
                'grade_completed'   => $student->grade_completed,
                'suspended_before'  => $student->suspended_before,
                'suspension_reason' => $student->suspension_reason,
                'academic_session'  => $student->academic_session,
                'semester'          => $student->semester,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            Promotion::create([
                'student_id'    => $student->id,
                'from_class'    => $student->classes,
                'to_class'      => $request->to_class_id,
                'academic_year' => $session->name, // Store name in promotion table (string)
                'promoted_by'   => auth()->id() ?? 1,
                'remarks'       => 'Promoted successfully',
            ]);

            $student->update([
                'classes'          => $request->to_class_id,
                'academic_session' => $session->id, // Store ID in student table (foreign key)
                'stream'           => null, // Reset stream for the new class
                'semester'         => null, // Reset semester for the new session
            ]);

            // Generate Invoice for the new class/session
            try {
                $invoiceService = new \App\Services\InvoiceService();
                $invoiceService->generateForStudent($student);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to generate promotion invoice for student {$student->id}: " . $e->getMessage());
            }

            // Notify parent via WhatsApp via WhatsApp
            try {
                $toClass = SchoolClass::find($request->to_class_id);
                $whatsappService = app(\App\Services\WhatsAppService::class);
                $msg = "Hongera! Mwanafunzi {$student->first_name} amepandishwa darasa kwenda darasa la " . ($toClass->class_name ?? 'lililofuata') . " kwa ajili ya mwaka wa masomo {$session->name}. Ahsante kwa kuendelea nasi.";
                $whatsappService->sendMessage($student, $msg, 'system');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Promotion notification failed: " . $e->getMessage());
            }
        });

        return redirect()->route('promotions.index')->with('success', 'Mwanafunzi amepandishwa darasa successfully.');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Kuna kosa limetokea: ' . $e->getMessage());
    }
}

public function bulkStore(Request $request)
{
    $request->validate([
        'student_ids' => 'required|array',
        'to_class_id' => 'required|exists:school_classes,id',
        'academic_session_id' => 'required|exists:academic_sessions,id',
        'exam_id' => 'nullable|exists:exams,id',
        'fee_paid_only' => 'nullable|boolean',
        'min_marks' => 'nullable|numeric|min:0'
    ]);

    $examId = $request->input('exam_id');
    $minMarks = $request->input('min_marks', 0);
    $feePaidOnly = $request->boolean('fee_paid_only', true);
    $session = \App\Models\AcademicSession::findOrFail($request->academic_session_id);
    
    $skipped = [];
    $promotedCount = 0;

    try {
        DB::transaction(function () use ($request, $session, &$skipped, &$promotedCount, $examId, $minMarks, $feePaidOnly) {
            foreach ($request->student_ids as $student_id) {
                $student = Student::with('invoices')->findOrFail($student_id);

                if ($feePaidOnly && ! $this->hasPaidAllFees($student, $student->academic_session)) {
                    $skipped[] = $student->first_name . ' ' . $student->last_name . ' (Fees unpaid)';
                    continue;
                }

                if ($examId) {
                    $average = $this->studentExamAverage($student, $examId);
                    if ($average < $minMarks) {
                        $skipped[] = $student->first_name . ' ' . $student->last_name . ' (Avg: ' . number_format($average, 1) . '%)';
                        continue;
                    }
                }

                // Log entry
                DB::table('student_logs')->insert([
                    'admission_no'      => $student->admission_no,
                    'first_name'        => $student->first_name,
                    'last_name'         => $student->last_name,
                    'middle_name'       => $student->middle_name ?? '',
                    'dob'               => $student->dob,
                    'gender'            => $student->gender,
                    'region'            => $student->region,
                    'district'          => $student->district,
                    'street'            => $student->street,
                    'address'           => $student->address,
                    'guardian_name'     => $student->guardian_name,
                    'guardian_email'    => $student->guardian_email,
                    'guardian_phone'    => $student->guardian_phone,
                    'guardian_occupation'=> $student->guardian_occupation,
                    'guardian_address'  => $student->guardian_address,
                    'guardian_type'     => $student->guardian_type,
                    'guardian_region'   => $student->guardian_region,
                    'guardian_street'   => $student->guardian_street,
                    'guardian_district' => $student->guardian_district,
                    'education_level'   => $student->education_level,
                    'classes'           => $student->classes,
                    'stream'            => $student->stream,
                    'school_attended'   => $student->school_attended,
                    'grade_completed'   => $student->grade_completed,
                    'suspended_before'  => $student->suspended_before,
                    'suspension_reason' => $student->suspension_reason,
                    'academic_session'  => $student->academic_session,
                    'semester'          => $student->semester,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);

                Promotion::create([
                    'student_id' => $student->id,
                    'from_class' => $student->classes,
                    'to_class' => $request->to_class_id,
                    'academic_year' => $session->name,
                    'promoted_by' => auth()->id() ?? 1,
                    'remarks' => $examId ? 'Promoted after exam and fees validation' : 'Promoted in bulk operation'
                ]);

                $student->update([
                    'classes' => $request->to_class_id,
                    'academic_session' => $session->id,
                    'stream' => null, // Reset stream for the new class
                    'semester' => null, // Reset semester for the new session
                ]);

                // Generate Invoice for the new class/session
                try {
                    $invoiceService = new \App\Services\InvoiceService();
                    $invoiceService->generateForStudent($student);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Failed to generate bulk promotion invoice for student {$student->id}: " . $e->getMessage());
                }

                // Notify parent via WhatsApp (Optional for bulk, but good for connectivity)
                try {
                    $toClass = SchoolClass::find($request->to_class_id);
                    $whatsappService = app(\App\Services\WhatsAppService::class);
                    $msg = "Hongera! Mwanafunzi {$student->first_name} amepandishwa darasa kwenda darasa la " . ($toClass->class_name ?? 'lililofuata') . " kwa ajili ya mwaka wa masomo {$session->name}.";
                    $whatsappService->sendMessage($student, $msg, 'system');
                } catch (\Exception $e) {
                    // Silently fail for bulk to avoid stopping the loop, just log
                    \Illuminate\Support\Facades\Log::error("Bulk promotion notification failed for student {$student->id}: " . $e->getMessage());
                }

                $promotedCount++;
            }
        });

        $message = 'Promotion completed for ' . $promotedCount . ' student(s).';
        if (count($skipped) > 0) {
            $message .= ' Skipped: ' . count($skipped) . ' students.';
        }

        return redirect()->route('promotions.index')->with('success', $message);

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Kuna kosa limetokea: ' . $e->getMessage());
    }
}

private function hasPaidAllFees(Student $student, $academicSessionId = null)
{
    $academicSessionId = $academicSessionId ?: $student->academic_session;
    if (! $academicSessionId) {
        return false;
    }
    
    $session = \App\Models\AcademicSession::find($academicSessionId);
    if (!$session) return false;

    $invoices = $student->invoices()->where('academic_year', $session->name)->get();
    if ($invoices->isEmpty()) {
        return true; // If no invoices for this year, we assume no fees due or fully paid/exempt
    }

    return $invoices->sum('balance') <= 0;
}

private function studentExamAverage(Student $student, $examId)
{
    $marks = Mark::where('student_id', $student->id)->where('exam_id', $examId)->get();
    if ($marks->isEmpty()) {
        return 0;
    }

    return $marks->avg('marks');
}

public function show(Request $request, $id)
{
    $class = SchoolClass::findOrFail($id);
    $classes = SchoolClass::all();
    $exams = Exam::all();
    $sessions = \App\Models\AcademicSession::all();
    $selectedExamId = $request->query('exam_id');
    $minMarks = $request->query('min_marks', 0);
    $feePaidOnly = $request->boolean('fee_paid_only', true);

    $students = Student::where('classes', $id)->with(['invoices', 'academicSessionData'])->get();
    
    // Efficiency: filter marks in PHP or limited query
    $marks = Mark::where('class_id', $id)
        ->when($selectedExamId, function($q) use ($selectedExamId) {
            return $q->where('exam_id', $selectedExamId);
        })
        ->get();

    return view('pages.promotions.show', compact('class', 'classes', 'exams', 'sessions', 'students', 'marks', 'minMarks', 'selectedExamId', 'feePaidOnly'));
}
}