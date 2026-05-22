<?php
$file = 'routes/exam-routes.php';
$content = file_get_contents($file);

// Find the last closing "});" and insert before it
$lastBrace = strrpos($content, "});");

if ($lastBrace !== false) {
    $newRoutes = "
    // Student Performance Reports
    Route::get('students/reports', [AdminStudentReportController::class, 'studentsList'])->name('students.list');
    Route::get('students/{studentId}/report', [AdminStudentReportController::class, 'studentReport'])->name('students.report');
    Route::get('students/{studentId}/report-pdf', [AdminStudentReportController::class, 'downloadStudentReportPDF'])->name('students.report.pdf');
";
    
    $newContent = substr_replace($content, $newRoutes . "\n", $lastBrace, 0);
    file_put_contents($file, $newContent);
    echo "Routes added successfully!\n";
} else {
    echo "Could not find closing brace\n";
}
?>
