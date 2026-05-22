<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\Examination;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\FeeStructureController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AcademicServiceController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\SyllabusController;
use App\Http\Controllers\StudentServiceController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\SubjectAssignmentController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TeacherAttendanceController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\PdfExportController;
use App\Http\Controllers\TeacherResourceController;
use App\Http\Controllers\InvoiceActionController;
use App\Http\Controllers\TeacherExamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminExamController;



require __DIR__.'/exam-routes.php';

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', function () {
    return view('auth.forgot');
})->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    $status = Password::sendResetLink($request->only('email'));
    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
})->name('password.email');

Route::get('/reset-password/{token}', function ($token, Request $request) {
    return view('auth.reset', ['token' => $token, 'email' => $request->email]);
})->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:8',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60))->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->name('password.update');

// User Management & Profile Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', [UserController::class, 'changePassword'])->name('password.change');
    Route::post('/change-password', [UserController::class, 'updatePassword'])->name('password.update.secure');
    
    Route::middleware(['admin'])->group(function () {
        Route::resource('users', UserController::class);
    });
});
// --- WEKA HIZI SEHEMU YA JUU KIDOGO (Baada ya dashboard route) ---

Route::get('/dashboard', function () {
    // Basic counts and aggregates for the dashboard
    $branchesCount = App\Models\Branch::count();
    $activeBranches = App\Models\Branch::where('status', 1)->count();
    $studentsCount = App\Models\Student::count();
    $newAdmissions = App\Models\Student::where('created_at', '>=', now()->subMonth())->count();
    $teachersCount = App\Models\Teacher::count();
    $classesCount = App\Models\SchoolClass::count();

    // Financials (basic sums)
    $feesCollected = (float) App\Models\Payment::sum('amount');
    $pendingFees = (float) App\Models\Invoice::where('balance', '>', 0)->sum('balance');
    $pendingInvoicesCount = App\Models\Invoice::where('balance', '>', 0)->count();

    // Attendance rate for today (students)
    $today = now()->toDateString();
    $attendanceTodayTotal = App\Models\Attendance::whereDate('date', $today)->count();
    $attendanceTodayPresent = App\Models\Attendance::whereDate('date', $today)->where('status', 'present')->count();
    $attendanceRate = $attendanceTodayTotal > 0 ? round(($attendanceTodayPresent / $attendanceTodayTotal) * 100) : null;

    // Recent/external lists
    $latestExaminations = App\Models\Examination::latest('published_date')->limit(5)->get();
    $recentActivities = App\Models\AuditLog::latest()->limit(5)->get();
    $upcomingExams = App\Models\Examination::where('published_date', '>=', now())->orderBy('published_date')->limit(5)->get();
    $announcements = App\Models\Announcement::where('is_active', 1)->latest()->limit(4)->get();
    // Branch performance: compute students and fees per branch (top 3 for overview)
    $branchStats = App\Models\Branch::get()->map(function($b){
        $students = App\Models\Student::where('branches', $b->id)->count();
        $fees = App\Models\Payment::whereHas('student', function($q) use ($b){
            $q->where('branches', $b->id);
        })->sum('amount');
        return (object) ['branch' => $b->branch_name, 'students' => $students, 'fees' => $fees];
    })->take(3);

    // Enrollment chart: labels and data per branch
    $branches = App\Models\Branch::orderBy('branch_name')->get();
    $enrollmentLabels = $branches->pluck('branch_name')->toArray();
    $enrollmentData = $branches->map(function($b){
        return App\Models\Student::where('branches', $b->id)->count();
    })->toArray();

    // Fees chart: monthly fees for last 6 months
    $feesLabels = [];
    $feesData = [];
    for ($i = 5; $i >= 0; $i--) {
        $month = now()->subMonths($i);
        $label = $month->format('M');
        $feesLabels[] = $label;
        $sum = App\Models\Payment::whereYear('created_at', $month->year)
            ->whereMonth('created_at', $month->month)
            ->sum('amount');
        $feesData[] = (float) $sum;
    }

    return view('pages.dashboard', compact(
        'branchesCount', 'activeBranches', 'studentsCount', 'newAdmissions', 'teachersCount', 'classesCount',
        'feesCollected', 'pendingFees', 'pendingInvoicesCount', 'attendanceRate', 'attendanceTodayTotal', 'attendanceTodayPresent', 'latestExaminations', 'recentActivities', 'upcomingExams', 'announcements', 'branchStats',
        'enrollmentLabels', 'enrollmentData', 'feesLabels', 'feesData'
    ));
})->middleware('auth');

Route::resource('school', SchoolController::class);
Route::get('/school/{school_id}/branches', [BranchController::class, 'index'])->name('school.branches');
Route::get('/school/{school_id}/branches/create', [BranchController::class, 'create'])->name('branches.create');
Route::post('/school/{school_id}/branches/store', [BranchController::class, 'store'])->name('branches.store');

Route::get('/branches/{id}/edit', [BranchController::class, 'edit'])->name('branches.edit');
Route::put('/branches/{id}', [BranchController::class, 'update'])->name('branches.update');
Route::delete('/branches/{id}', [BranchController::class, 'destroy'])->name('branches.destroy');
Route::post('/branches/{id}/toggle-status', [BranchController::class, 'toggleStatus'])->name('branches.toggle');


Route::resource('teachers', TeacherController::class);
Route::post('teachers/{id}/toggle-status', [TeacherController::class, 'toggleStatus'])->name('teachers.toggle');

Route::prefix('academic')->name('academic.')->group(function () {
    // Main Dashboard
    Route::get('/', [AcademicServiceController::class, 'index'])->name('index');

    // Sessions & Semesters
    Route::post('/session/store', [AcademicServiceController::class, 'storeSession'])->name('session.store');
    Route::post('/semester/store', [AcademicServiceController::class, 'storeSemester'])->name('semester.store');
    Route::post('/session/{id}/set-active', [AcademicServiceController::class, 'setSessionActive'])->name('session.active');
    Route::post('/semester/{id}/set-active', [AcademicServiceController::class, 'setSemesterActive'])->name('semester.active');
    Route::delete('/semester/{id}', [AcademicServiceController::class, 'destroySemester'])->name('semester.destroy');
    Route::delete('/session/{id}', [AcademicServiceController::class, 'destroySession'])->name('session.destroy');

    // Classes & Streams
    Route::post('/class/store', [AcademicServiceController::class, 'storeClass'])->name('class.store');
    Route::delete('/class/{id}', [AcademicServiceController::class, 'destroyClass'])->name('class.destroy');
    Route::post('/stream/store', [AcademicServiceController::class, 'storeStream'])->name('stream.store');
    Route::delete('/stream/{id}', [AcademicServiceController::class, 'destroyStream'])->name('stream.destroy');

    // Subjects
    Route::post('/subject/store', [AcademicServiceController::class, 'storeSubject'])->name('subject.store');
});

// Group ya Academic Services
Route::prefix('academic')->name('academic.')->group(function () {

    // --- 1. ATTENDANCE ROUTES ---
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/review', [AttendanceController::class, 'review'])->name('attendance.review');
    Route::get('/attendance/reports', [AttendanceController::class, 'reports'])->name('attendance.reports');
    Route::get('/attendance/reports/download', [AttendanceController::class, 'downloadReport'])->name('attendance.reports.download');
    Route::get('/attendance/report/{id}', [AttendanceController::class, 'studentReport'])->name('attendance.report');
    // Hii ni kwa ajili ya kufuta rekodi ya siku nzima ya darasa fulani
    Route::delete('/attendance/{date}/{stream_id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');

    // AJAX route for streams
    Route::get('/streams-by-class/{classId}', [AcademicServiceController::class, 'getStreamsByClass'])->name('streams.by.class');


    // --- 2. TIMETABLE ROUTES ---
    Route::get('/timetable', [TimetableController::class, 'index'])->name('timetable.index');
    Route::post('/timetable/store', [TimetableController::class, 'store'])->name('timetable.store');
    // Njia ya kurekebisha kipindi (Edit)
    Route::put('/timetable/{id}', [TimetableController::class, 'update'])->name('timetable.update');
    // Njia ya kufuta kipindi (Delete)
    Route::delete('/timetable/{id}', [TimetableController::class, 'destroy'])->name('timetable.destroy');


    // --- 3. SYLLABUS ROUTES ---
    Route::get('/syllabus', [SyllabusController::class, 'index'])->name('syllabus.index');
    Route::post('/syllabus/store', [SyllabusController::class, 'store'])->name('syllabus.store');
    // Njia ya kurekebisha mada (Edit/Status Update)
    Route::put('/syllabus/{id}', [SyllabusController::class, 'update'])->name('syllabus.update');
    // Njia ya kufuta mada (Delete)
    Route::delete('/syllabus/{id}', [SyllabusController::class, 'destroy'])->name('syllabus.destroy');

});




Route::get('/studentList', function () {
    return view('pages.students.list');
})->middleware('auth');

Route::resource('students', StudentController::class);
Route::get('/students/class/{id}', [StudentController::class, 'classStudents'])->name('students.class');
Route::resource('exams', ExamController::class);
Route::resource('subject-assignments', SubjectAssignmentController::class);


Route::get('marks/students/{classId}', [MarkController::class, 'getStudents']);
Route::get('results', [MarkController::class, 'results'])->name('results.index');// Marks Entry
Route::get('marks', [MarkController::class, 'index'])->name('marks.index');
Route::get('marks/create', [MarkController::class, 'create'])->name('marks.create');
Route::post('marks/store', [MarkController::class, 'store'])->name('marks.store');

// Exam Reports Routes
Route::get('exam-reports', [MarkController::class, 'examReports'])->name('exam-reports.index');
Route::get('exam-reports/student/{studentId}', [MarkController::class, 'studentExamReport'])->name('exam-reports.student');
Route::get('exam-reports/student/{studentId}/pdf', [MarkController::class, 'studentExamReportPdf'])->name('exam-reports.student.pdf');

// AJAX loader
Route::get('/marks/load-data', [MarkController::class, 'loadData'])->name('marks.load-data');
Route::get('/results/{class}/{exam}', [MarkController::class, 'processResults']);

Route::get('/promote/{class}/{exam}', [MarkController::class, 'promoteStudents']);

Route::resource('promotions', PromotionController::class);
Route::post('promotions/bulk-store', [PromotionController::class, 'bulkStore'])->name('promotions.bulkStore');

// Parent Routes (moved before resource routes to avoid conflict)
Route::middleware(['auth'])->group(function () {
    Route::prefix('parent')->name('parent.')->group(function () {
        Route::get('/dashboard', [ParentController::class, 'dashboard'])->name('dashboard');
        Route::get('/attendance', [ParentController::class, 'attendance'])->name('attendance');
        Route::get('/attendance/download', [ParentController::class, 'downloadAttendance'])->name('attendance.download');
        Route::get('/exam-reports', [ParentController::class, 'examReports'])->name('exam-reports');
        Route::get('/exam-reports/download', [ParentController::class, 'downloadExamReports'])->name('exam-reports.download');
        
        Route::get('/finance', [ParentController::class, 'financeDashboard'])->name('finance.dashboard');
        Route::get('/finance/accounts', [ParentController::class, 'feeAccounts'])->name('finance.accounts');
        Route::get('/finance/statements', [ParentController::class, 'feeStatements'])->name('finance.statements');
        Route::get('/finance/statements/{id}', [ParentController::class, 'feeStatementDetails'])->name('finance.statements.details');
        Route::get('/finance/invoices', [ParentController::class, 'invoices'])->name('finance.invoices');
        Route::get('/finance/payments', [ParentController::class, 'paymentHistory'])->name('finance.payments');
        Route::get('/finance/receipts', [ParentController::class, 'receipts'])->name('finance.receipts');
        Route::get('/finance/pay/{id}', [ParentController::class, 'payInvoice'])->name('finance.pay');
        Route::get('/finance/pay-student/{id}', [ParentController::class, 'payStudentFee'])->name('finance.payStudent');
        Route::get('/messages', [ParentController::class, 'messages'])->name('messages');
        Route::get('/syllabus', [ParentController::class, 'syllabus'])->name('syllabus');
    });
});

Route::resource('parent', ParentController::class);
Route::get('/parent/student/{id}', [ParentController::class, 'studentProfile'])->name('parent.student.details');



Route::post('/pesapal/initiate', [PaymentController::class, 'initiate'])
    ->name('pesapal.initiate');

Route::match(['get', 'post'], '/pesapal/callback', [PaymentController::class, 'callback'])
    ->name('pesapal.callback');

Route::get('/checkout/{id}', [PaymentController::class, 'checkout'])
    ->name('checkout');

/*
|--------------------------------------------------------------------------
| FINANCE MODULE ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('finance')->name('finance.')->middleware(['auth', 'accountant'])->group(function () {
    Route::get('/', [FinanceController::class, 'index'])->name('index');
    Route::get('/invoices', [FinanceController::class, 'invoices'])->name('invoices');
    
    // Invoice Actions
    Route::post('/invoices/{id}/notify', [\App\Http\Controllers\InvoiceActionController::class, 'notify'])->name('invoices.notify');
    Route::get('/invoices/{id}/download', [\App\Http\Controllers\InvoiceActionController::class, 'download'])->name('invoices.download');
    Route::get('/receipts/{id}/download', [\App\Http\Controllers\InvoiceActionController::class, 'downloadReceipt'])->name('receipts.download');

    Route::resource('fee-structures', FeeStructureController::class)->names('fee-structures');
    Route::get('/student-fees', [FinanceController::class, 'studentFees'])->name('student-fees');
    Route::get('/callback', [FinanceController::class, 'callback'])->name('callback');
    Route::get('/{id}/pay', [FinanceController::class, 'pay'])->name('pay');

    // Expense Management
    Route::resource('expenses', ExpenseController::class)->names('expenses');


    // Payroll Management
    Route::resource('payroll', PayrollController::class)->names('payroll');
    Route::get('/payroll-list', [PayrollController::class, 'index'])->name('payroll.index'); // Backup for direct links


    // Discount Management
    Route::resource('discounts', DiscountController::class)->names('discounts');

    // Fine Management
    Route::resource('fines', FineController::class)->names('fines');

    // Budget Management
    Route::resource('budgets', BudgetController::class)->names('budgets');

    // Audit Logs
    Route::get('/audit-logs', [FinanceController::class, 'auditLogs'])->name('audit-logs.index');

    // Backup
    Route::get('/backup-security', [BackupController::class, 'index'])->name('backup-security.index');
    Route::post('/backup-security', [BackupController::class, 'store'])->name('backup-security.store');
    Route::get('/backup-security/download/{filename}', [BackupController::class, 'download'])->name('backup-security.download');

    // PDF Export
    Route::get('/pdf-export', [PdfExportController::class, 'index'])->name('pdf-export.index');
    Route::get('/pdf-export/summary', [PdfExportController::class, 'financialReport'])->name('pdf-export.financial');
    Route::get('/pdf-export/year-end', [PdfExportController::class, 'yearEndReport'])->name('pdf-export.year-end');
    Route::get('/pdf-export/payroll', [PdfExportController::class, 'payrollReport'])->name('pdf-export.payroll');
    Route::get('/pdf-export/expenses', [PdfExportController::class, 'expenseReport'])->name('pdf-export.expenses');

    // Financial Reports
    Route::get('/reports', [ReportController::class, 'financialReports'])->name('reports');
    Route::get('/reports/year-end', [ReportController::class, 'yearEndSummary'])->name('reports.year-end');

    // Online Payments (Pesapal)
    Route::get('/online-payments', [FinanceController::class, 'feature'])->name('online-payments')->defaults('feature', 'online-payments');
    Route::get('/statements', [FinanceController::class, 'feature'])->name('statements')->defaults('feature', 'statements');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/teacher', [TeacherAttendanceController::class, 'dashboard'])->name('teacher.dashboard');

    Route::prefix('teacher-attendance')->name('teacher-attendance.')->group(function () {
        Route::get('/', [TeacherAttendanceController::class, 'index'])->name('index');
        Route::post('/store', [TeacherAttendanceController::class, 'store'])->name('store');
        Route::get('/review', [TeacherAttendanceController::class, 'review'])->name('review');
        Route::get('/reports', [TeacherAttendanceController::class, 'reports'])->name('reports');
        Route::get('/reports/download', [TeacherAttendanceController::class, 'downloadReport'])->name('reports.download');
        Route::get('/report/{id}', [TeacherAttendanceController::class, 'studentReport'])->name('student.report');
    });

    Route::prefix('teacher/resources')->name('teacher.resources.')->group(function () {
        Route::get('/', [TeacherResourceController::class, 'index'])->name('index');
        Route::get('/view/{id}', [TeacherResourceController::class, 'show'])->name('show');
    });

    Route::post('/teacher/syllabus/{id}/update-status', [TeacherAttendanceController::class, 'updateSyllabusStatus'])->name('syllabus.updateStatus');

    // Announcements Routes (Admin)
    Route::resource('announcements', AnnouncementController::class);
    Route::get('/announcements/download/{announcement}', [AnnouncementController::class, 'downloadPdf'])->name('announcements.download');

    // Parent-Staff WhatsApp Chat Routes
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/student/{studentId}', [ChatController::class, 'show'])->name('show');
        Route::post('/student/{studentId}/send', [ChatController::class, 'send'])->name('send');
        Route::delete('/message/{id}', [ChatController::class, 'destroy'])->name('destroy');
        Route::delete('/student/{studentId}/clear', [ChatController::class, 'clearLogs'])->name('clear');
    });
});

Route::get('/academic/syllabus/download/{id}', [SyllabusController::class, 'download'])->name('academic.syllabus.download');

Route::get('/', function() {
    return view('welcome');
});
Route::get('exams/{exam_id}/subject/{subject_id}/download', [ExamController::class, 'downloadSubject'])
    ->name('exams.subject.download');
// Routes za Idhini (Approve/Deny)
Route::post('exams/{exam_id}/class/{class_id}/subject/{subject_id}/approve', [ExamController::class, 'approveSubject'])
    ->name('exams.subject.approve');

Route::post('exams/{exam_id}/class/{class_id}/subject/{subject_id}/deny', [ExamController::class, 'denySubject'])
    ->name('exams.subject.deny');
// Routes for Exam Approval and Result Release
Route::post('exams/{id}/approve', [ExamController::class, 'approve'])->name('exams.approve');
Route::post('exams/{id}/deny', [ExamController::class, 'deny'])->name('exams.deny');
Route::post('exams/{id}/release-results', [AdminExamController::class, 'releaseResults'])->name('admin.exams.release-results');
Route::get('exams/{id}/classes', [ExamController::class, 'viewClasses'])->name('exams.classes');

// 2. Halafu hii inafuata chini
// Resource route yako
Route::resource('exams', ExamController::class);
Route::resource('teacher-exams', TeacherExamController::class);

// Teacher exam specific routes
Route::get('teacher-exams/{id}/manage', [TeacherExamController::class, 'manage'])->name('teacher-exams.manage');
Route::post('/teacher-exams/paper/store', [TeacherExamController::class, 'storePaper'])->name('teacher-exams.paper.store');
// Route::delete('/teacher-exams/paper/{id}', [TeacherExamController::class, 'destroyPaper'])->name('teacher-exams.paper.destroy');

// Result upload routes
Route::get('teacher-exams/{examId}/subject/{subjectId}/results', [TeacherExamController::class, 'results'])->name('teacher-exams.results');
Route::post('teacher-exams/results/single', [TeacherExamController::class, 'storeSingleResult'])->name('teacher-exams.results.single');
Route::post('teacher-exams/results/bulk', [TeacherExamController::class, 'storeBulkResults'])->name('teacher-exams.results.bulk');
Route::get('teacher-exams/results/{examId}/{subjectId}/template', [TeacherExamController::class, 'downloadResultsTemplate'])->name('teacher-exams.results.template');
Route::get('teacher-exams/{examId}/subject/{subjectId}/report', [TeacherExamController::class, 'downloadResultsReport'])->name('teacher-exams.results.report');
// Hakikisha routes zipo ndani ya auth middleware
// --- CHANGE THIS SECTION IN YOUR web.php ---

Route::middleware(['auth'])->group(function () {

    // Change 'teacher.resources.group' to 'teacher.resources.'
    Route::prefix('teacher/resources')->name('teacher.resources.')->group(function () {
        
        // This now correctly becomes "teacher.resources.index"
        Route::get('/', [TeacherResourceController::class, 'index'])->name('index');
        
        // This now correctly becomes "teacher.resources.show"
        Route::get('/view/{id}', [TeacherResourceController::class, 'show'])->name('show');
    });

});

<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

Route::get('/run-migration-na-seeder', function () {
    try {
        // 1. Zima ukaguzi wa Foreign Keys kwa sekunde chache
        DB::statement('SET CONSTRAINTS ALL DEFERRED');
        
        // 2. Lazimisha kufuta matable yote yaliyopo kwenye database kwa nguvu
        $tables = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
        foreach ($tables as $table) {
            DB::statement("DROP TABLE IF EXISTS " . $table->tablename . " CASCADE");
        }
        
        // 3. Sasa database ipo tupu kabisa! Piga migrate ya kawaida
        Artisan::call('migrate', [
            '--force' => true
        ]);
        
        // 4. Piga na seeder zako sasa
        Artisan::call('db:seed', [
            '--force' => true
        ]);
        
        return "Ushindi mkuu! Database imesafishwa kwa nguvu, matable yote yametengenezwa upya, na seeder imekimbia kikamilifu!";
        
    } catch (\Exception $e) {
        return "Kuna shida imetokea mkuu: " . $e->getMessage();
    }
});