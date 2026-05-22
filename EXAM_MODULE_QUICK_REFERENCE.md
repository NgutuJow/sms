# Exam Module - Quick Reference Guide

## Installation

1. **Run Migrations**
```bash
php artisan migrate
```

2. **Update Routes** (in `routes/web.php`)
```php
// Add this at the end
require __DIR__.'/exam-routes.php';
```

## Admin Features

### Create Exam
```
POST /admin/exams
```
Required fields:
- name
- academic_session_id
- semester_id
- exam_type (QUIZ, MIDTERM, FINAL, ASSIGNMENT, PROJECT, PRACTICAL)
- total_marks
- passing_marks
- classes[] (array of class IDs)

### Publish Exam
```
POST /admin/exams/{id}/publish
```

### View Exam Report
```
GET /admin/exams/{id}/report
```

### View Class Report
```
GET /admin/exams/{id}/class/{classId}/report
```

### Bulk Actions
```
POST /admin/exams/bulk-action
```
Actions: publish, close, delete

## Teacher Features

### View Assigned Exams
```
GET /teacher/exams
```

### Mark Exam
```
GET /teacher/exams/{id}/mark?class_id=1
POST /teacher/exams/{id}/marks
```

### Save Single Mark (AJAX)
```
POST /teacher/exams/mark/single
```
Body:
```json
{
  "exam_id": 1,
  "student_id": 1,
  "subject_id": 1,
  "class_id": 1,
  "marks": 85,
  "remarks": "Good work"
}
```

### Bulk Upload Marks
```
POST /teacher/exams/{id}/upload-marks
```
File: CSV with student_id, marks, remarks

### Download Template
```
GET /teacher/exams/{id}/download-template?class_id=1
```

### View Progress
```
GET /teacher/exams/{id}/progress
```

### View Report
```
GET /teacher/exams/{id}/report
```

## Results Features

### Generate Results
```
POST /results/exams/{id}/generate
```

### View Exam Results
```
GET /results/exams/{id}
```

### View Class Results
```
GET /results/exams/{id}/class/{classId}
```

### Export to CSV
```
GET /results/exams/{id}/export
```

### Student Views Results
```
GET /my-results
GET /my-results/exam/{id}
```

## API Endpoints

### Get All Exams
```
GET /api/exams
```
Query params: session_id, semester_id, status, per_page

### Get Exam Details
```
GET /api/exams/{id}
```

### Get Exam Marks
```
GET /api/exams/{id}/marks
```
Query params: class_id, subject_id

### Get Exam Statistics
```
GET /api/exams/{id}/statistics
```
Query params: class_id

### Get Exam Report
```
GET /api/exams/{id}/report
```

### Get Exam Results
```
GET /api/exams/{id}/results
```
Query params: class_id, per_page

## Grade Scale

| Grade | Marks | GPA |
|-------|-------|-----|
| A     | 90+   | 4.0 |
| B+    | 80-89 | 3.5 |
| B     | 70-79 | 3.0 |
| C+    | 60-69 | 2.5 |
| C     | 50-59 | 2.0 |
| D     | 40-49 | 1.0 |
| E     | <40   | 0.0 |

## Usage in Code

### In Controller

```php
use App\Services\ExamService;
use App\Services\MarkingService;
use App\Services\ReportService;

class YourController extends Controller
{
    protected $examService;
    protected $markingService;
    protected $reportService;

    public function __construct(
        ExamService $examService,
        MarkingService $markingService,
        ReportService $reportService
    ) {
        $this->examService = $examService;
        $this->markingService = $markingService;
        $this->reportService = $reportService;
    }

    public function example()
    {
        // Get exam
        $exam = $this->examService->getExamDetails($examId);

        // Record marks
        $mark = $this->markingService->recordMarks([
            'student_id' => 1,
            'exam_id' => 1,
            'subject_id' => 1,
            'class_id' => 1,
            'marks' => 85
        ]);

        // Generate report
        $report = $this->reportService->generateExamReport($examId);
    }
}
```

## Middleware Required

Add these middleware to `app/Http/Middleware/`:

```php
// admin.php
public function handle($request, Closure $next)
{
    if (!auth()->user() || !auth()->user()->hasRole('admin')) {
        abort(403);
    }
    return $next($request);
}

// teacher.php
public function handle($request, Closure $next)
{
    if (!auth()->user() || !auth()->user()->hasRole('teacher')) {
        abort(403);
    }
    return $next($request);
}

// student.php
public function handle($request, Closure $next)
{
    if (!auth()->user() || !auth()->user()->hasRole('student')) {
        abort(403);
    }
    return $next($request);
}
```

Register in `app/Http/Kernel.php`:
```php
protected $routeMiddleware = [
    // ...
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
    'teacher' => \App\Http\Middleware\TeacherMiddleware::class,
    'student' => \App\Http\Middleware\StudentMiddleware::class,
];
```

## Key Methods

### ExamService
- `getAllExams(filters)` - List exams
- `getExamDetails(id)` - Get exam with relations
- `createExam(data)` - Create exam
- `publishExam(id)` - Publish for marking
- `closeExam(id)` - Close exam

### MarkingService
- `recordMarks(data)` - Save marks
- `bulkUploadMarks(examId, classId, subjectId, data)` - Bulk upload
- `calculateGrade(marks)` - Get grade (A-E)
- `getExamStatistics(examId, classId)` - Get stats

### ReportService
- `generateStudentReport(studentId, examId)` - Student report
- `generateClassReport(examId, classId)` - Class report
- `generateExamReport(examId)` - Full exam report
- `getStudentPosition(studentId, examId)` - Rank

## Common Tasks

### Task: Create and Publish Exam

```php
// Create
$exam = $examService->createExam([
    'name' => 'Math Test',
    'academic_session_id' => 1,
    'semester_id' => 1,
    'exam_type' => 'QUIZ',
    'total_marks' => 50,
    'passing_marks' => 20,
    'classes' => [1, 2]
]);

// Publish
$examService->publishExam($exam->id);
```

### Task: Mark and Generate Results

```php
// Record marks
foreach ($students as $student) {
    $markingService->recordMarks([
        'student_id' => $student->id,
        'exam_id' => $examId,
        'subject_id' => $subjectId,
        'class_id' => $classId,
        'marks' => rand(40, 100)
    ]);
}

// Generate results
$marks = Mark::where('exam_id', $examId)->get()->groupBy('student_id');
foreach ($marks as $studentId => $studentMarks) {
    $avg = $studentMarks->avg('marks');
    ExamResult::create([
        'exam_id' => $examId,
        'student_id' => $studentId,
        'class_id' => $classId,
        'average_marks' => $avg,
        'grade' => $markingService->calculateGrade($avg),
        'is_passed' => $avg >= $exam->passing_marks
    ]);
}
```

## Views to Create

Create views in `resources/views/pages/`:

1. `admin/exams/index.blade.php`
2. `admin/exams/create.blade.php`
3. `admin/exams/edit.blade.php`
4. `admin/exams/show.blade.php`
5. `admin/exams/report.blade.php`
6. `teacher/exams/index.blade.php`
7. `teacher/exams/mark.blade.php`
8. `teacher/exams/progress.blade.php`
9. `teacher/exams/report.blade.php`
10. `results/index.blade.php`
11. `results/exam-results.blade.php`
12. `results/class-results.blade.php`
13. `results/student-results.blade.php`

## Troubleshooting

### Marks not showing?
- Check if marks table has grade, marked_by, marked_date columns
- Run: `php artisan migrate`

### User not seeing exams?
- Verify teacher.classes relationship is set up
- Check middleware is applied correctly

### Results not generating?
- Ensure marks are recorded first
- Check exam's passing_marks is set correctly

## Performance Tips

1. Use eager loading:
```php
$exams = Exam::with(['classes', 'marks', 'marks.student'])
    ->get();
```

2. Paginate large result sets:
```php
$results = ExamResult::paginate(20);
```

3. Cache statistics:
```php
Cache::remember("exam_{$id}_stats", 3600, fn() => 
    $markingService->getExamStatistics($id)
);
```
