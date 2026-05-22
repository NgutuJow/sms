<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminExamController;
use App\Http\Controllers\AdminStudentReportController;
use App\Http\Controllers\TeacherExamController;
use App\Http\Controllers\ExamResultController;

/*
|--------------------------------------------------------------------------
| ROUTE ZA MWALIMU (Teacher Routes)
|--------------------------------------------------------------------------
| Hizi ndizo zinazohusika na ile peji unayotaka kuingia ya Marking.
*/
Route::middleware(['auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    
    // 1. Dashibodi ya Mitihani ya Mwalimu
    // Link: yoursite.com/teacher/exams
    // Name: teacher.exams.index
    Route::get('exams', [TeacherExamController::class, 'index'])->name('exams.index');
    
    // 2. Peji ya Kuingiza Maksi (Hii ndiyo ile link yako mkuu)
    // Link: yoursite.com/teacher/exams/{id}/mark
    // Name: teacher.exams.mark
    Route::get('exams/{exam}/mark', [TeacherExamController::class, 'mark'])->name('exams.mark');
    
    // 3. Save Marks (Post request ya ku-save maksi zote)
    Route::post('exams/{exam}/save-marks', [TeacherExamController::class, 'saveMarks'])->name('exams.save-marks');
    
    // 4. Save Single Mark (Kwa ajili ya AJAX ukitaka ku-save moja moja)
    Route::post('exams/mark/single', [TeacherExamController::class, 'saveSingleMark'])->name('exams.save-single-mark');
    
    // 5. Bulk Upload Marks
    Route::post('exams/{exam}/bulk-upload', [TeacherExamController::class, 'bulkUpload'])->name('exams.bulk-upload');
    
    // 6. Download Template
    Route::get('exams/{exam}/download-template', [TeacherExamController::class, 'downloadTemplate'])->name('exams.download-template');
    
    // 7. Progress na Report kwa mwalimu
    Route::get('exams/{exam}/progress', [TeacherExamController::class, 'progress'])->name('exams.progress');
    Route::get('exams/{exam}/report', [TeacherExamController::class, 'report'])->name('exams.report');

});


/*
|--------------------------------------------------------------------------
| ROUTE ZA ADMIN (Admin Routes)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // CRUD ya mitihani yote
    Route::resource('exams', AdminExamController::class);
    
    // Admin kutoa ripoti
    Route::get('exams/{exam}/report', [AdminExamController::class, 'viewReport'])->name('exams.report');
    
    // Results overview with charts
    Route::get('exams/{examId}/results-overview', [AdminExamController::class, 'resultsOverview'])->name('exams.results.overview');
    Route::get('exams/{examId}/results-pdf', [AdminExamController::class, 'downloadResultsPDF'])->name('exams.results.pdf');
    
    // Student Performance Reports
    Route::get('students/reports', [AdminStudentReportController::class, 'studentsList'])->name('students.list');
    Route::get('students/{studentId}/report', [AdminStudentReportController::class, 'studentReport'])->name('students.report');
    Route::get('students/{studentId}/report-pdf', [AdminStudentReportController::class, 'downloadStudentReportPDF'])->name('students.report.pdf');
});