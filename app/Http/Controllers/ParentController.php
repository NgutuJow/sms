<?php

namespace App\Http\Controllers;
use App\Models\Student;
use App\Models\Invoice;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\Examination;
use App\Models\ExamResult;
use App\Models\Mark;
use App\Models\ChatMessage;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\FeeStructure;
use App\Models\Announcement;
use App\Helpers\GradeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
class ParentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function index()
{
    $user = Auth::user();
    
    // Get all children of this parent
    $students = $user->students()->with(['classData', 'streamData'])->get();
    
    // Attendance summary for all children
    $attendanceSummary = [];
    foreach ($students as $student) {
        $totalDays = Attendance::where('student_id', $student->id)->count();
        $presentDays = Attendance::where('student_id', $student->id)->where('status', 'present')->count();
        $attendancePercentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;
        
        $attendanceSummary[] = [
            'student' => $student,
            'total_days' => $totalDays,
            'present_days' => $presentDays,
            'percentage' => $attendancePercentage
        ];
    }
    
    // Upcoming exams (next 5)
    $upcomingExams = Examination::with(['exam', 'class'])
        ->whereIn('class_id', $students->pluck('classes')->unique())
        ->where('published_date', '>=', now())
        ->orderBy('published_date')
        ->limit(5)
        ->get();
        
    // Fee issues - pending invoices
    $pendingInvoices = Invoice::whereIn('student_id', $students->pluck('id'))
        ->where('status', '!=', 'PAID')
        ->with('student')
        ->get();
        
    return view('pages.parent.index', compact('students', 'attendanceSummary', 'upcomingExams', 'pendingInvoices'));
}
public function studentProfile($id)
{
    $student = Auth::user()->students()->findOrFail($id);

    // Tumia compact au array ile ile uliyoweka
    return view('pages.parent.index', [
        'student' => $student,
        'invoices' => $student->invoices, 
        'payments' => $student->payments,
        // 'attendance' => $student->attendances()->latest()->get(),
        // 'reports' => $student->examReports,
        // 'messages' => $student->messages()->where('user_id', Auth::id())->get(),
    ]);
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        // Get all children of this parent
        $students = $user->students()->with(['classData', 'streamData'])->get();
        
        // Attendance summary for all children
        $attendanceSummary = [];
        foreach ($students as $student) {
            $totalDays = Attendance::where('student_id', $student->id)->count();
            $presentDays = Attendance::where('student_id', $student->id)->where('status', 'present')->count();
            $attendancePercentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;
            
            $attendanceSummary[] = [
                'student' => $student,
                'total_days' => $totalDays,
                'present_days' => $presentDays,
                'percentage' => $attendancePercentage
            ];
        }
        
        // Upcoming exams (next 5)
        $upcomingExams = Examination::with(['exam', 'class'])
            ->whereIn('class_id', $students->pluck('classes')->unique())
            ->where('published_date', '>=', now())
            ->orderBy('published_date')
            ->limit(5)
            ->get();
            
        // Fee issues - pending invoices
        $pendingInvoices = Invoice::whereIn('student_id', $students->pluck('id'))
            ->where('status', '!=', 'PAID')
            ->with('student')
            ->get();

        $announcements = Announcement::where('is_active', true)
            ->where('audience', 'parent')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        return view('pages.parent.index', compact('students', 'attendanceSummary', 'upcomingExams', 'pendingInvoices', 'announcements'));
    }

    public function attendance()
    {
        $user = Auth::user();
        
        // Debug: Check if user is authenticated
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first');
        }
        
        $students = $user->students()->with(['classData', 'streamData'])->get();
        
        // Get attendance records for all children
        $attendanceRecords = [];
        foreach ($students as $student) {
            $records = Attendance::where('student_id', $student->id)
                ->with(['student'])
                ->orderBy('date', 'desc')
                ->get();
                
            $attendanceRecords[$student->id] = $records;
        }
        
        return view('pages.parent.attendance', compact('students', 'attendanceRecords'));
    }

    public function downloadAttendance(Request $request)
    {
        $user = Auth::user();
        $students = $user->students()->with(['classData', 'streamData'])->get();
        
        $period = $request->get('period', 'monthly');
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();
        
        if ($period === 'weekly') {
            $startDate = now()->startOfWeek();
            $endDate = now()->endOfWeek();
        } elseif ($period === 'yearly') {
            $startDate = now()->startOfYear();
            $endDate = now()->endOfYear();
        }
        
        $attendanceData = [];
        foreach ($students as $student) {
            $records = Attendance::where('student_id', $student->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date')
                ->get();
                
            $totalDays = $records->count();
            $presentDays = $records->where('status', 'present')->count();
            $absentDays = $records->where('status', 'absent')->count();
            $percentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;
            
            $attendanceData[] = [
                'student' => $student,
                'records' => $records,
                'summary' => [
                    'total_days' => $totalDays,
                    'present_days' => $presentDays,
                    'absent_days' => $absentDays,
                    'percentage' => $percentage
                ]
            ];
        }
        
        $pdf = Pdf::loadView('pages.parent.attendance_pdf', compact('attendanceData', 'period', 'startDate', 'endDate'));
        
        return $pdf->download('children_attendance_' . $period . '.pdf');
    }

    public function examReports(Request $request)
    {
        $user = Auth::user();
        $students = $user->students()->with(['classData', 'streamData'])->get();

        $examReports = [];

        foreach ($students as $student) {
            $query = Mark::with(['subject', 'exam'])
                ->where('student_id', $student->id);

            if ($request->exam_id) {
                $query->where('exam_id', $request->exam_id);
            }

            $marks = $query->get();

            // Group by exam
            $studentExamReports = [];
            foreach ($marks->groupBy('exam_id') as $examId => $examMarks) {
                $exam = $examMarks->first()->exam;

                $totalMarks = $examMarks->sum('marks');
                $subjectCount = $examMarks->count();
                $average = $subjectCount > 0 ? $totalMarks / $subjectCount : 0;

                $studentExamReports[] = [
                    'exam' => $exam,
                    'marks' => $examMarks,
                    'total_marks' => $totalMarks,
                    'average' => round($average, 2),
                    'grade' => GradeHelper::getGrade($average)
                ];
            }

            $examReports[$student->id] = [
                'student' => $student,
                'reports' => $studentExamReports
            ];
        }

        $exams = Exam::all();

        return view('pages.parent.exam-reports', compact('examReports', 'exams', 'students'));
    }

    public function downloadExamReports(Request $request)
    {
        $user = Auth::user();
        $students = $user->students()->with(['classData', 'streamData'])->get();

        $examReports = [];

        foreach ($students as $student) {
            $query = Mark::with(['subject', 'exam'])
                ->where('student_id', $student->id);

            if ($request->exam_id) {
                $query->where('exam_id', $request->exam_id);
            }

            $marks = $query->get();

            $studentExamReports = [];
            foreach ($marks->groupBy('exam_id') as $examId => $examMarks) {
                $exam = $examMarks->first()->exam;

                $totalMarks = $examMarks->sum('marks');
                $subjectCount = $examMarks->count();
                $average = $subjectCount > 0 ? $totalMarks / $subjectCount : 0;

                $studentExamReports[] = [
                    'exam' => $exam,
                    'marks' => $examMarks,
                    'total_marks' => $totalMarks,
                    'average' => round($average, 2),
                    'grade' => GradeHelper::getGrade($average)
                ];
            }

            $examReports[$student->id] = [
                'student' => $student,
                'reports' => $studentExamReports
            ];
        }

        $exams = Exam::all();

        $pdf = Pdf::loadView('pages.parent.exam-reports-pdf', compact('examReports', 'exams', 'students'));
        return $pdf->download('parent_exam_reports.pdf');
    }

    protected function parentStudentIds()
    {
        return Auth::user()->students()->pluck('id')->toArray();
    }

    public function financeDashboard()
    {
        $user = Auth::user();
        $studentIds = $this->parentStudentIds();
        $students = $user->students()->with(['classData', 'streamData'])->get();

        $invoices = Invoice::whereIn('student_id', $studentIds)->latest()->get();
        $payments = Payment::whereIn('student_id', $studentIds)->latest()->get();

        $totalDue = $invoices->sum('balance');
        $totalBilled = $invoices->sum('total_amount');
        $totalPaid = $payments->sum('amount');
        $overdueCount = $invoices->where('due_date', '<', now())->where('balance', '>', 0)->count();
        $recentPayments = $payments->take(5);

        // Count students without any invoices (need to pay fees)
        $studentsWithInvoices = $invoices->pluck('student_id')->unique();
        $studentsWithoutInvoices = $students->whereNotIn('id', $studentsWithInvoices)->count();

        return view('pages.parent.finance', compact(
            'students',
            'totalDue',
            'totalBilled',
            'totalPaid',
            'overdueCount',
            'recentPayments',
            'studentsWithoutInvoices'
        ));
    }

    public function feeAccounts()
    {
        $user = Auth::user();
        $students = $user->students()->with(['classData', 'streamData', 'invoices', 'payments'])->get();

        $accounts = $students->map(function ($student) {
            $totalInvoiced = $student->invoices->sum('total_amount');
            $totalPaid = $student->payments->sum('amount');
            $balance = $student->invoices->sum('balance');
            $hasInvoices = $student->invoices->count() > 0;

            $feeStructures = FeeStructure::where('class_id', $student->classes)->get();
            $expectedFee = $feeStructures->sum('amount');
            $installmentPlan = $feeStructures->where('allow_installments', 1)->count() > 0 ? 'Yes' : 'No';

            if (!$hasInvoices && $expectedFee > 0) {
                $status = 'Unpaid';
                $dueAmount = $expectedFee;
            } elseif (!$hasInvoices) {
                $status = 'No Fee Structure';
                $dueAmount = 0;
            } else {
                $status = $balance <= 0 ? 'Paid' : ($totalPaid > 0 ? 'Partially Paid' : 'Unpaid');
                $dueAmount = $balance > 0 ? $balance : 0;
            }

            $payInvoice = $student->invoices->where('balance', '>', 0)->sortByDesc('created_at')->first();
            $payRoute = $payInvoice ? route('parent.finance.pay', $payInvoice->id) : ($expectedFee > 0 ? route('parent.finance.payStudent', $student->id) : null);

            return [
                'student' => $student,
                'total_invoiced' => $totalInvoiced,
                'total_paid' => $totalPaid,
                'balance' => $balance,
                'status' => $status,
                'has_invoices' => $hasInvoices,
                'expected_fee' => $expectedFee,
                'due_amount' => $dueAmount,
                'installment_plan' => $installmentPlan,
                'pay_route' => $payRoute,
            ];
        });

        return view('pages.parent.finance-accounts', compact('accounts'));
    }

    public function feeStatements()
    {
        $studentIds = $this->parentStudentIds();
        $invoices = Invoice::whereIn('student_id', $studentIds)
            ->latest()
            ->get();

        $statementGroups = $invoices->groupBy(function ($invoice) {
            return $invoice->academic_year ?: 'Unspecified Academic Year';
        })->map(function ($group, $year) {
            $totalInvoiced = $group->sum('total_amount');
            $totalPaid = $group->sum('paid_amount');
            $totalBalance = $group->sum('balance');
            $status = $totalBalance <= 0 ? 'Paid' : ($totalPaid > 0 ? 'Partially Paid' : 'Unpaid');

            return [
                'year' => $year,
                'invoices' => $group,
                'total_invoiced' => $totalInvoiced,
                'total_paid' => $totalPaid,
                'total_balance' => $totalBalance,
                'status' => $status,
            ];
        })->sortKeysDesc();

        return view('pages.parent.fee-statements', compact('statementGroups'));
    }

    public function feeStatementDetails($id)
    {
        $studentIds = $this->parentStudentIds();
        $invoice = Invoice::with(['student', 'payments'])->whereIn('student_id', $studentIds)->findOrFail($id);

        return view('pages.parent.fee-statement-details', compact('invoice'));
    }

    public function invoices()
    {
        $studentIds = $this->parentStudentIds();
        $invoices = Invoice::whereIn('student_id', $studentIds)->latest()->get();

        return view('pages.parent.invoices', compact('invoices'));
    }

    public function paymentHistory()
    {
        $studentIds = $this->parentStudentIds();
        $payments = Payment::with(['invoice', 'student'])->whereIn('student_id', $studentIds)->latest()->get();

        return view('pages.parent.payment-history', compact('payments'));
    }

    public function receipts()
    {
        $studentIds = $this->parentStudentIds();
        $receipts = \App\Models\Receipt::with(['payment.invoice', 'payment.student'])
            ->whereHas('payment', function ($query) use ($studentIds) {
                $query->whereIn('student_id', $studentIds);
            })
            ->latest()
            ->get();

        return view('pages.parent.receipts', compact('receipts'));
    }

    public function payInvoice($id)
    {
        $studentIds = $this->parentStudentIds();
        $invoice = Invoice::with('student')->whereIn('student_id', $studentIds)->findOrFail($id);

        return view('pages.parent.fee_checkout', compact('invoice'));
    }

    public function payStudentFee($id)
    {
        $studentIds = $this->parentStudentIds();
        $student = Student::with(['classData', 'streamData'])->whereIn('id', $studentIds)->findOrFail($id);

        $feeStructures = FeeStructure::where('class_id', $student->classes)->get();
        if ($feeStructures->isEmpty()) {
            return redirect()->route('parent.finance.accounts')->with('error', 'No fee structure found for this student class.');
        }

        $expectedFee = $feeStructures->sum('amount');
        $invoice = Invoice::where('student_id', $student->id)->where('balance', '>', 0)->latest()->first();

        if (!$invoice) {
            $reference = 'INV-' . time() . '-' . $student->id;
            $invoice = (new \App\Services\InvoiceService())->createInvoice([
                'student_id' => $student->id,
                'academic_year' => $feeStructures->pluck('academic_year')->unique()->implode(', '),
                'total_amount' => $expectedFee,
                'paid_amount' => 0,
                'balance' => $expectedFee,
                'status' => 'UNPAID',
                'reference_no' => $reference,
                'due_date' => now()->addDays(7),
            ]);
        }

        return view('pages.parent.fee_checkout', compact('invoice'));
    }

    public function checkout($id)
    {
        $student = Student::findOrFail($id);

        $invoice = Invoice::where('student_id', $student->id)
            ->where('status', '!=', 'PAID')
            ->first();

        if (!$invoice) {
            return redirect()->back()->with('error', 'No pending invoice found');
        }

        return view('pages.parent.fee_checkout', [
            'student' => $student,
            'invoice' => $invoice
        ]);
    }

    public function messages()
    {
        $user = Auth::user();
        $students = $user->students()->with(['classData', 'streamData'])->get();
        
        $messages = ChatMessage::whereIn('student_id', $students->pluck('id'))
            ->with(['student', 'sender'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('pages.parent.messages', compact('students', 'messages'));
    }

    public function syllabus()
    {
        $user = Auth::user();
        $students = $user->students()->with(['classData.subjects.syllabuses'])->get();
        
        return view('pages.parent.syllabus', compact('students'));
    }
}
