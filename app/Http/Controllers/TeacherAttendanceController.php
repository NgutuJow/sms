<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\AcademicSession;
use App\Models\Semester;
use App\Models\Exam;
use App\Models\Stream;
use App\Models\Teacher;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TeacherAttendanceController extends Controller
{
   public function index(Request $request)
{
    $user = Auth::user();
    $errors = [];
    
    // Initialize default values for all variables
    $className = 'Not Configured';
    $streamName = 'Not Configured';
    $selectedClass = null;
    $date = $request->date ?? date('Y-m-d');
    $session = null;
    $semester = null;
    $students = collect();
    $attendances = collect();
    $attended = [];
    $absent = [];
    $late = [];
    $stats = [
        'total' => 0,
        'present' => 0,
        'absent' => 0,
        'late' => 0,
        'percent' => 0
    ];

    if (!$user) {
        return redirect()->route('login')->with('error', 'Please log in to continue.');
    }

    $teacher = $user->teacher;

    if (!$teacher) {
        $teacher = Teacher::where('user_id', $user->id)
            ->orWhere('email', $user->email)
            ->orWhere('phone', $user->phone)
            ->first();
    }

    if (!$teacher) {
        return redirect()->route('teacher.dashboard')->with('error', 'Your teacher profile is not set up yet. Please contact administrator.');
    }

    $stream = optional($teacher)->stream;
    $classData = optional($stream)->schoolClass;

    if (!$stream || !$classData) {
        $errors = ['Your assigned class or stream is not configured yet. Please contact administrator.'];
        return view('pages.teacher.attendance.index', compact(
            'students',
            'attendances',
            'selectedClass',
            'date',
            'attended',
            'absent',
            'late',
            'stats',
            'className',
            'streamName',
            'session',
            'semester',
            'errors'
        ))->with('error', 'Configuration incomplete');
    }

    $className = $classData->class_name ?? 'Unknown Class';
    $streamName = $stream->stream_name ?? $stream->name ?? 'No Stream';
    $selectedClass = $classData->id;

    $session = AcademicSession::where('is_current', 1)->first();
    $semester = Semester::where('is_current', 1)->first();

    if (!$session) {
        $errors = ['No active academic session found. Please contact administrator.'];
        return view('pages.teacher.attendance.index', compact(
            'students',
            'attendances',
            'selectedClass',
            'date',
            'attended',
            'absent',
            'late',
            'stats',
            'className',
            'streamName',
            'session',
            'semester',
            'errors'
        ))->with('error', 'No active session');
    }

    $students = Student::where('stream', $stream->id)->get();

    $attendances = Attendance::where('class_id', $selectedClass)
        ->where('academic_session_id', $session->id)
        ->where('semester_id', optional($semester)->id)
        ->where('date', $date)
        ->get()
        ->keyBy('student_id');

    $attended = [];
    $absent = [];
    $late = [];

    foreach ($students as $student) {
        $status = $attendances[$student->id]->status ?? null;

        if ($status === 'present') {
            $attended[] = $student;
        } elseif ($status === 'absent') {
            $absent[] = $student;
        } elseif ($status === 'late') {
            $late[] = $student;
        }
    }

    $stats = [
        'total' => $students->count(),
        'present' => count($attended),
        'absent' => count($absent),
        'late' => count($late),
        'percent' => $students->count()
            ? round((count($attended) / $students->count()) * 100)
            : 0
    ];

    return view('pages.teacher.attendance.index', compact(
        'students',
        'attendances',
        'selectedClass',
        'date',
        'attended',
        'absent',
        'late',
        'stats',
        'className',
        'streamName',
        'session',
        'semester',
        'errors'
    ));
}

    public function store(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher || !$teacher->stream) {
            return back()->with('error', 'Teacher profile or stream assignment is missing.');
        }

        $request->validate([
            'class_id' => 'required',
            'date' => 'required|date',
        ]);

        $session = AcademicSession::where('is_current', 1)->first();
        $semester = Semester::where('is_current', 1)->first();

        if (!$session) {
            return back()->with('error', 'No active academic session found.');
        }

        DB::beginTransaction();

        try {
            $students = Student::where('classes', $request->class_id)
                ->where('stream', $teacher->stream->id)
                ->get();

            foreach ($students as $student) {
                $status = $request->attendance[$student->id] ?? 'absent';

                Attendance::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'date' => $request->date,
                        'academic_session_id' => $session->id,
                    ],
                    [
                        'class_id' => $request->class_id,
                        'stream_id' => $teacher->stream->id,
                        'semester_id' => optional($semester)->id,
                        'status' => $status,
                        'remarks' => $request->remarks[$student->id] ?? null,
                        'recorded_by' => Auth::id()
                    ]
                );
            }

            DB::commit();
            return back()->with('success', 'Attendance saved');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
public function review(Request $request)
{
    $user = auth()->user();
    $teacher = $user->teacher;

    // 1. Logic ya usalama
    $className = null;
    $streamName = null;

    if ($user->role === 'teacher') {
        $stream = optional($teacher)->stream;
        $selectedClass = optional($stream)->schoolClass->id;
        $className = optional($stream->schoolClass)->class_name;
        $streamName = optional($stream)->name;
        $classes = \App\Models\SchoolClass::where('id', $selectedClass)->get();
    } else {
        $selectedClass = $request->class_id;
        $classes = \App\Models\SchoolClass::all();
    }

    $dateFrom = $request->date_from;
    $dateTo   = $request->date_to;
    $status   = $request->status;
    $studentId = $request->student_id;

    // 2. Anza query
    $query = \App\Models\Attendance::with(['student', 'classesRelation']);

    if ($selectedClass) {
        $query->where('class_id', $selectedClass);
    }

    if ($dateFrom && $dateTo) {
        $query->whereBetween('date', [$dateFrom, $dateTo]);
    } elseif ($dateFrom) {
        $query->whereDate('date', $dateFrom);
    }

    if ($status) {
        $query->where('status', $status);
    }

    if ($studentId) {
        $query->where('student_id', $studentId);
    }

    // 3. Pata matokeo yote kwa kutumia get()
    $records = $query->latest('date')->get();

    // Stats
    $stats = [
        'present' => (clone $query)->where('status', 'present')->count(),
        'absent'  => (clone $query)->where('status', 'absent')->count(),
        'late'    => (clone $query)->where('status', 'late')->count(),
    ];

    return view('pages.teacher.attendance.review', compact(
        'records',
        'classes',
        'selectedClass',
        'dateFrom',
        'dateTo',
        'status',
        'studentId',
        'stats',
        'className',
        'streamName'
    ));
}

public function reports(Request $request)
{
    $user = auth()->user();
    $teacher = $user->teacher;

    if (!$teacher) {
        return back()->with('error', 'No teacher profile found.');
    }

    $stream = $teacher->stream;
    $classData = optional($stream)->schoolClass;

    if (!$classData) {
        return back()->with('error', 'Teacher is not assigned to a class or stream yet.');
    }

    $selectedClass = $classData->id;
    $className = $classData->class_name;
    $streamName = $stream->stream_name ?? 'N/A';

    $session = AcademicSession::where('is_current', 1)->first();
    $semester = Semester::where('is_current', 1)->first();

    if (!$session) {
        return back()->with('error', 'No active academic session found.');
    }

    $period = $request->period ?? 'week';
    $today = date('Y-m-d');

    $year = $request->year ?? date('Y');
    $month = $request->month ?? date('m');
    $date = $request->date ?? $today;
    $dateFrom = $request->date_from;
    $dateTo = $request->date_to;

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

    $query = Attendance::with('student')
        ->where('class_id', $selectedClass)
        ->where('academic_session_id', optional($session)->id);

    if ($semester) {
        $query->where('semester_id', $semester->id);
    }

    $query->whereBetween('date', [$startDate, $endDate]);
    $attendances = $query->orderBy('date')->get();

    $present = $attendances->where('status', 'present')->count();
    $absent = $attendances->where('status', 'absent')->count();
    $late = $attendances->where('status', 'late')->count();
    $total = $attendances->count();
    $percent = $total ? round(($present / $total) * 100) : 0;

    $range = [];
    $cursor = strtotime($startDate);
    $endCursor = strtotime($endDate);

    while ($cursor <= $endCursor) {
        $range[] = date('Y-m-d', $cursor);
        $cursor = strtotime('+1 day', $cursor);
    }

    $chartLabels = [];
    $presentData = [];
    $absentData = [];
    $lateData = [];
    $dailySummary = [];

    foreach ($range as $day) {
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
    }

    $stats = [
        'present' => $present,
        'absent' => $absent,
        'late' => $late,
        'total' => $total,
        'percent' => $percent,
    ];

    return view('pages.teacher.attendance.reports', compact(
        'className',
        'streamName',
        'selectedClass',
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
        'dailySummary'
    ));
}

public function downloadReport(Request $request)
{
    $user = auth()->user();
    $teacher = $user->teacher;

    if (!$teacher) {
        return back()->with('error', 'No teacher profile found.');
    }

    $stream = $teacher->stream;
    $classData = optional($stream)->schoolClass;

    if (!$classData) {
        return back()->with('error', 'Teacher is not assigned to a class or stream yet.');
    }

    $selectedClass = $classData->id;
    $className = $classData->class_name;
    $streamName = $stream->stream_name ?? 'N/A';

    $session = AcademicSession::where('is_current', 1)->first();
    $semester = Semester::where('is_current', 1)->first();

    if (!$session) {
        return back()->with('error', 'No active academic session found.');
    }

    $period = $request->period ?? 'week';
    $today = date('Y-m-d');

    $year = $request->year ?? date('Y');
    $month = $request->month ?? date('m');
    $date = $request->date ?? $today;
    $dateFrom = $request->date_from;
    $dateTo = $request->date_to;

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

    $query = Attendance::with('student')
        ->where('class_id', $selectedClass)
        ->where('academic_session_id', optional($session)->id);

    if ($semester) {
        $query->where('semester_id', $semester->id);
    }

    $query->whereBetween('date', [$startDate, $endDate]);
    $attendances = $query->orderBy('date')->get();

    $present = $attendances->where('status', 'present')->count();
    $absent = $attendances->where('status', 'absent')->count();
    $late = $attendances->where('status', 'late')->count();
    $total = $attendances->count();
    $percent = $total ? round(($present / $total) * 100) : 0;

    $range = [];
    $cursor = strtotime($startDate);
    $endCursor = strtotime($endDate);

    while ($cursor <= $endCursor) {
        $range[] = date('Y-m-d', $cursor);
        $cursor = strtotime('+1 day', $cursor);
    }

    $dailySummary = [];

    foreach ($range as $day) {
        $dayRecords = $attendances->where('date', $day);
        $dailySummary[] = [
            'date' => $day,
            'present' => $dayRecords->where('status', 'present')->count(),
            'absent' => $dayRecords->where('status', 'absent')->count(),
            'late' => $dayRecords->where('status', 'late')->count(),
            'total' => $dayRecords->count(),
        ];
    }

    $stats = [
        'present' => $present,
        'absent' => $absent,
        'late' => $late,
        'total' => $total,
        'percent' => $percent,
    ];

    $periodLabel = ucfirst($period);

    $pdf = Pdf::loadView('pages.teacher.attendance.reports-pdf', compact(
        'className',
        'streamName',
        'period',
        'periodLabel',
        'startDate',
        'endDate',
        'stats',
        'dailySummary',
        'attendances'
    ));

    $fileName = "attendance_report_{$period}_{$startDate}_to_{$endDate}.pdf";
    return $pdf->download($fileName);
}

public function studentReport($id)
{
    $user = auth()->user();
    $teacher = $user->teacher;

    $student = Student::with(['streamData', 'classData'])->findOrFail($id);

    $stream = optional($teacher)->stream;
    if ($teacher && $stream && $student->stream != $stream->id) {
        abort(403, 'Unauthorized access to student attendance report.');
    }

    $attendances = Attendance::where('student_id', $id)
        ->with(['schoolClass', 'session', 'semester'])
        ->orderBy('date', 'desc')
        ->get();

    return view('pages.teacher.attendance.student-report', compact('student', 'attendances'));
}

public function resources()
{
    $user = Auth::user();
    $teacher = $user->teacher;

    if (!$teacher) {
        return back()->with('error', 'Wasifu wa mwalimu haujapatikana.');
    }

    // 1. Pata Ratiba (Timetable) - Inaunganisha na Stream na Class
    $timetables = \App\Models\Timetable::where('teacher_id', $teacher->id)
        ->with(['subject', 'stream.schoolClass']) // schoolClass ni relationship iliyo ndani ya Stream
        ->orderByRaw("FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
        ->get();

    // 2. Pata Syllabus - Hapa tunasoma majina ya Topic na Sub-topics
    $syllabuses = \App\Models\Syllabus::where('teacher_id', $teacher->id)
        ->with(['subject'])
        ->get();

    return view('pages.teacher.resources', compact('timetables', 'syllabuses'));
}
public function updateSyllabusStatus(Request $request, $id)
{
    $syllabus = \App\Models\Syllabus::findOrFail($id);

    // Validatate status inayokuja
    $request->validate([
        'status' => 'required|in:Pending,Completed'
    ]);

    $syllabus->update([
        'status' => $request->status,
        'completion_date' => $request->status == 'Completed' ? now() : null
    ]);

    return back()->with('success', 'Status ya Syllabus imesasishwa!');
}

public function dashboard()
{
    $user = auth()->user();
    $teacher = $user->teacher;

    if (!$teacher) {
        return redirect()->back()->with('error', 'No teacher profile found');
    }

    // 1. Tafuta Stream
    $classStream = \App\Models\Stream::where('teacher_id', $teacher->id)
        ->with('schoolClass')
        ->first();

    // Kama classStream ni null, hapa ndipo tatizo lilipo (Check teacher_id kwenye table ya streams)
    if (!$classStream) {
        return view('pages.teacher.dashboard', [
            'className' => 'Not Assigned',
            'streamName' => 'N/A',
            'stats' => ['total' => 0, 'stream_total' => 0, 'percent' => 0, 'present' => 0],
            'exams' => [],
            'upcomingExams' => 0
        ]);
    }

    $className = $classStream->schoolClass->class_name;
    $streamName = $classStream->stream_name;

    // 2. Count Students using stream_id relationship (more reliable)
    $totalInStream = Student::where('stream', $classStream->id)->count();

    // 3. Get current session and semester
    $session = AcademicSession::where('is_current', 1)->first();
    $semester = Semester::where('is_current', 1)->first();

    // 4. Calculate overall attendance percentage for stream
    $attendancePercent = 0;
    $presentCount = 0;

    if ($session && $totalInStream > 0) {
        // Count total attendance records for stream in current session
        $attendanceQuery = Attendance::where('stream_id', $classStream->id)
            ->where('academic_session_id', $session->id);

        if ($semester) {
            $attendanceQuery->where('semester_id', $semester->id);
        }

        $totalAttendanceRecords = $attendanceQuery->count();
        $presentCount = (clone $attendanceQuery)->where('status', 'present')->count();

        // Calculate percentage if there are records
        if ($totalAttendanceRecords > 0) {
            $attendancePercent = round(($presentCount / $totalAttendanceRecords) * 100);
        }
    }

    // 5. Fetch active exams for current session
    $examsQuery = Exam::where('academic_session_id', optional($session)->id);

    if ($semester) {
        $examsQuery->where('semester_id', $semester->id);
    }

    $exams = $examsQuery->orderBy('created_at', 'desc')->get();
    $upcomingExams = $exams->count();

// 6. Fetch staff announcements for dashboard
        $announcements = Announcement::where('is_active', true)
            ->where('audience', 'staff')
        ->with('creator')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    // 7. Get students in stream with today's attendance
    $today = date('Y-m-d');
    $students = Student::where('stream', $classStream->id)->get();

    $attendances = Attendance::where('stream_id', $classStream->id)
        ->where('date', $today)
        ->get()
        ->keyBy('student_id');

    // 8. Categorize students by attendance status
    $attended = [];
    $absent = [];
    $late = [];

    foreach ($students as $student) {
        $status = $attendances[$student->id]->status ?? null;

        if ($status === 'present') $attended[] = $student;
        elseif ($status === 'absent') $absent[] = $student;
        elseif ($status === 'late') $late[] = $student;
    }

    $stats = [
        'percent' => $attendancePercent,
        'present' => $presentCount,
        'total'   => $totalInStream,
        'stream_total' => $totalInStream
    ];

    return view('pages.teacher.dashboard', compact('className', 'streamName', 'stats', 'exams', 'upcomingExams', 'announcements', 'attended', 'absent', 'late'));
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
}
