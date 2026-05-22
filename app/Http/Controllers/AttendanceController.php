<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\AcademicSession;
use App\Models\Semester;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // Hakikisha ume-install dompdf

class AttendanceController extends Controller {

    public function index(Request $request)
{
    $classes = SchoolClass::all();
    $session = AcademicSession::where('is_current', 1)->first();
    $semester = Semester::where('is_current', 1)->first();

    if (!$session) {
        return back()->with('error', 'No active academic session found');
    }

    $selectedClass = $request->class_id;
    $date = $request->date ?? date('Y-m-d');
    
    // Define empty arrays kuzuia "Undefined variable" error
    $students = [];
    $attendances = collect();
    $attended = [];
    $absent = [];
    $late = [];
    $stats = ['percent' => 0, 'present' => 0, 'total' => 0];

    if ($selectedClass) {
        $students = Student::where('classes', $selectedClass)->get();
        
        $attendances = Attendance::where('class_id', $selectedClass)
            ->where('academic_session_id', $session->id)
            ->where('semester_id', optional($semester)->id)
            ->where('date', $date)
            ->get()
            ->keyBy('student_id');

        // Panga wanafunzi kwenye makundi yao (Logic ya List)
        foreach ($students as $student) {
            $record = $attendances->get($student->id);
            $status = $record ? $record->status : null;
            
            if ($status == 'present') {
                $attended[] = $student;
            } elseif ($status == 'absent') {
                $absent[] = $student;
            } elseif ($status == 'late') {
                $late[] = $student;
            }
        }

        // Stats kwa ajili ya Dashboard
        $stats['total'] = $students->count();
        $stats['present'] = count($attended);
        $stats['percent'] = $stats['total'] > 0 ? round(($stats['present'] / $stats['total']) * 100) : 0;
    }

    return view('pages.attendance.index', compact(
        'classes', 
        'students', 
        'attendances', 
        'selectedClass', 
        'date', 
        'attended', 
        'absent', 
        'late', 
        'stats'
    ));
}

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'date' => 'required|date',
        ]);

        $session = AcademicSession::where('is_current', 1)->first();
        $semester = Semester::where('is_current', 1)->first();
        $whatsappService = app(\App\Services\WhatsAppService::class);

        DB::beginTransaction();
        try {
            $allStudents = Student::where('classes', $request->class_id)->get();
            $className = optional(SchoolClass::find($request->class_id))->name;
            
            foreach ($allStudents as $student) {
                $status = $request->attendance[$student->id] ?? 'absent';
                
                Attendance::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'date' => $request->date,
                        'academic_session_id' => $session->id,
                    ],
                    [
                        'class_id' => $request->class_id,
                        'semester_id' => optional($semester)->id,
                        'status' => $status,
                        'remarks' => $request->remarks[$student->id] ?? null,
                        'recorded_by' => Auth::id()
                    ]
                );

                // Send automated WhatsApp notification for absence
                if ($status === 'absent' && $student->guardian_phone) {
                    $msg = "Hello, your child {$student->first_name} {$student->last_name} was marked ABSENT for school today ({$request->date}) in class {$className}. Please contact the school for any clarification.";
                    $whatsappService->sendMessage($student, $msg, 'system');
                }
            }

            DB::commit();
            return back()->with('success', 'Attendance processed and notifications sent where applicable.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // PDF Report per Student
    public function studentReport($id)
    {
        $student = Student::findOrFail($id);
        $attendances = Attendance::where('student_id', $id)
            ->with(['session'])
            ->orderBy('date', 'desc')
            ->get();

        $pdf = Pdf::loadView('pages.attendance.report_pdf', compact('student', 'attendances'));
        return $pdf->stream($student->name . '_Attendance_Report.pdf');
    }

    public function review(Request $request)
{
    $classes = SchoolClass::all();
    $selectedClass = $request->class_id;
    $date = $request->date ?? date('Y-m-d');

    $attended = [];
    $absent = [];
    $late = [];

    if ($selectedClass) {
        // Pata mahudhurio yote ya darasa hilo kwa siku hiyo
        $attendances = Attendance::where('class_id', $selectedClass)
            ->where('date', $date)
            ->with('student')
            ->get();

        foreach ($attendances as $record) {
            if ($record->status == 'present') $attended[] = $record;
            elseif ($record->status == 'absent') $absent[] = $record;
            elseif ($record->status == 'late') $late[] = $record;
        }
    }

    return view('pages.attendance.list', compact('classes', 'selectedClass', 'date', 'attended', 'absent', 'late'));
}

    public function reports(Request $request)
{
    $classes = SchoolClass::all();
    $selectedClass = $request->class_id;
    $selectedStream = $request->stream_id;
    $period = $request->period ?? 'week';
    $today = date('Y-m-d');

    $year = $request->year ?? date('Y');
    $month = $request->month ?? date('m');
    $date = $request->date ?? $today;
    $dateFrom = $request->date_from;
    $dateTo = $request->date_to;

    $className = optional(SchoolClass::find($selectedClass))->name;
    $streams = [];

    if ($selectedClass) {
        $streams = \App\Models\Stream::where('school_class_id', $selectedClass)->get();
    }

    switch ($period) {
        case 'day':
            $startDate = $date;
            $endDate = $date;
            break;
        case 'month':
            $startDate = date('Y-m-01', strtotime("{$year}-{$month}-01"));
            $endDate = date('Y-m-t', strtotime($startDate));
            break;
        case 'year':
            $startDate = "{$year}-01-01";
            $endDate = "{$year}-12-31";
            break;
        default:
            $endDate = $dateTo ?? $today;
            $startDate = $dateFrom ?? date('Y-m-d', strtotime("{$endDate} -6 days"));
            break;
    }

    $attendances = collect();
    $stats = ['present' => 0, 'absent' => 0, 'late' => 0, 'total' => 0, 'percent' => 0];
    $chartLabels = [];
    $presentData = [];
    $absentData = [];
    $lateData = [];
    $dailySummary = [];

    if ($selectedClass) {
        $query = Attendance::with('student')->where('class_id', $selectedClass);
        
        if ($selectedStream) {
            $query->where('stream_id', $selectedStream);
        }
        
        $query->whereBetween('date', [$startDate, $endDate]);
        $attendances = $query->orderBy('date')->get();

        $stats['present'] = $attendances->where('status', 'present')->count();
        $stats['absent'] = $attendances->where('status', 'absent')->count();
        $stats['late'] = $attendances->where('status', 'late')->count();
        $stats['total'] = $attendances->count();
        $stats['percent'] = $stats['total'] ? round(($stats['present'] / $stats['total']) * 100) : 0;

        $cursor = strtotime($startDate);
        $endCursor = strtotime($endDate);

        while ($cursor <= $endCursor) {
            $day = date('Y-m-d', $cursor);
            $dayRecords = $attendances->where('date', $day);
            $chartLabels[] = \Carbon\Carbon::parse($day)->format('d M');
            $presentData[] = $dayRecords->where('status', 'present')->count();
            $absentData[] = $dayRecords->where('status', 'absent')->count();
            $lateData[] = $dayRecords->where('status', 'late')->count();
            $dailySummary[] = [
                'date' => $day,
                'present' => $dayRecords->where('status', 'present')->count(),
                'absent' => $dayRecords->where('status', 'absent')->count(),
                'late' => $dayRecords->where('status', 'late')->count(),
                'total' => $dayRecords->count(),
            ];
            $cursor = strtotime('+1 day', $cursor);
        }
    }

    return view('pages.attendance.reports', compact(
        'classes',
        'selectedClass',
        'selectedStream',
        'streams',
        'className',
        'period',
        'date',
        'dateFrom',
        'dateTo',
        'month',
        'year',
        'startDate',
        'endDate',
        'stats',
        'chartLabels',
        'presentData',
        'absentData',
        'lateData',
        'dailySummary',
        'attendances'
    ));
}

    public function downloadReport(Request $request)
{
    $selectedClass = $request->class_id;
    $selectedStream = $request->stream_id;
    $period = $request->period ?? 'week';
    $today = date('Y-m-d');

    $year = $request->year ?? date('Y');
    $month = $request->month ?? date('m');
    $date = $request->date ?? $today;
    $dateFrom = $request->date_from;
    $dateTo = $request->date_to;

    $className = optional(SchoolClass::find($selectedClass))->name;
    $streamName = $selectedStream ? optional(\App\Models\Stream::find($selectedStream))->name : null;

    switch ($period) {
        case 'day':
            $startDate = $date;
            $endDate = $date;
            break;
        case 'month':
            $startDate = date('Y-m-01', strtotime("{$year}-{$month}-01"));
            $endDate = date('Y-m-t', strtotime($startDate));
            break;
        case 'year':
            $startDate = "{$year}-01-01";
            $endDate = "{$year}-12-31";
            break;
        default:
            $endDate = $dateTo ?? $today;
            $startDate = $dateFrom ?? date('Y-m-d', strtotime("{$endDate} -6 days"));
            break;
    }

    $attendances = collect();
    $stats = ['present' => 0, 'absent' => 0, 'late' => 0, 'total' => 0, 'percent' => 0];

    if ($selectedClass) {
        $query = Attendance::with('student')->where('class_id', $selectedClass);
        
        if ($selectedStream) {
            $query->where('stream_id', $selectedStream);
        }
        
        $query->whereBetween('date', [$startDate, $endDate]);
        $attendances = $query->orderBy('date')->get();

        $stats['present'] = $attendances->where('status', 'present')->count();
        $stats['absent'] = $attendances->where('status', 'absent')->count();
        $stats['late'] = $attendances->where('status', 'late')->count();
        $stats['total'] = $attendances->count();
        $stats['percent'] = $stats['total'] ? round(($stats['present'] / $stats['total']) * 100) : 0;
    }

    $periodLabel = ucfirst($period);

    $pdf = Pdf::loadView('pages.attendance.reports-pdf', compact(
        'className',
        'streamName',
        'period',
        'periodLabel',
        'startDate',
        'endDate',
        'stats',
        'attendances'
    ));

    $fileName = "attendance_report_{$period}_{$startDate}_to_{$endDate}.pdf";
    return $pdf->download($fileName);
}
}