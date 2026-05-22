# Professional Exam Module Documentation

## Overview

The Professional Exam Module is a comprehensive examination management system built for Laravel that provides complete functionality for:
- **Admin**: Create, manage, publish exams and view comprehensive reports
- **Teachers**: Mark exams, track marking progress, generate class and subject reports
- **Students**: View their exam results and performance analysis

## Features

### 1. Exam Management (Admin)
- Create exams with customizable settings (exam type, total marks, passing marks)
- Assign exams to multiple classes
- Set exam dates and duration
- Publish exams for marking
- Close exams to prevent further marking
- View exam statistics and reports
- Bulk manage exams (publish, close, delete)

### 2. Exam Marking (Teacher)
- Mark exams for assigned classes
- Single mark entry or bulk upload (CSV)
- Automatic grade calculation
- Add remarks to marks
- Track marking progress
- Download marks template for CSV upload
- View marking reports and class-wise performance

### 3. Results Management
- Automatic result generation from marks
- Student ranking and positioning
- Grade distribution analysis
- Pass/fail determination
- Comprehensive reporting
- Export results to CSV

## Architecture

### Services
The module uses service classes for business logic:

#### ExamService (`app/Services/ExamService.php`)
- `getAllExams()` - Get exams with filters
- `getExamDetails()` - Get single exam with related data
- `createExam()` - Create new exam
- `updateExam()` - Update exam
- `publishExam()` - Publish exam for marking
- `closeExam()` - Close exam for marking
- `deleteExam()` - Delete exam
- `getClassExams()` - Get exams for specific class

#### MarkingService (`app/Services/MarkingService.php`)
- `recordMarks()` - Record marks for a student
- `bulkUploadMarks()` - Bulk upload marks from CSV
- `calculateGrade()` - Calculate grade from marks
- `getExamMarks()` - Get marks for exam with filters
- `getStudentExamMarks()` - Get marks for specific student
- `getExamStatistics()` - Get exam statistics
- `getTeacherMarkingProgress()` - Track teacher's marking progress
- `getPendingMarks()` - Get marks pending entry

#### ReportService (`app/Services/ReportService.php`)
- `generateStudentReport()` - Generate individual student report
- `generateClassReport()` - Generate class exam report
- `generateExamReport()` - Generate comprehensive exam report
- `generateSubjectReport()` - Generate subject-wise report
- `generateTeacherReport()` - Generate teacher's marking report
- `getStudentPosition()` - Get student's ranking
- `calculatePassRate()` - Calculate class pass rate

### Controllers

#### AdminExamController
**Routes**: `/admin/exams`
- `index()` - List all exams
- `create()` - Show create form
- `store()` - Store new exam
- `show()` - Show exam details
- `edit()` - Show edit form
- `update()` - Update exam
- `publish()` - Publish exam
- `close()` - Close exam
- `destroy()` - Delete exam
- `viewReport()` - View exam report
- `classReport()` - View class report
- `marks()` - View marks
- `exportReport()` - Export report

#### TeacherExamController
**Routes**: `/teacher/exams`
- `index()` - View assigned exams
- `mark()` - Show marking interface
- `saveMarks()` - Save marks for students
- `saveSingleMark()` - Save single mark (AJAX)
- `bulkUpload()` - Bulk upload marks
- `progress()` - View marking progress
- `report()` - View marking report
- `downloadTemplate()` - Download CSV template
- `studentReport()` - View student report

#### ExamResultController
**Routes**: `/results`
- `index()` - List all results
- `show()` - Show single result
- `generate()` - Generate results from marks
- `examResults()` - View results for exam
- `classResults()` - View class results
- `studentResults()` - Student views their results
- `exportCSV()` - Export to CSV

### Models

#### Exam
Relations:
- `academicSession()` - Belongs to AcademicSession
- `semester()` - Belongs to Semester
- `classes()` - Belongs to many SchoolClass
- `marks()` - Has many Mark
- `createdBy()` - Belongs to User
- `results()` - Has many ExamResult

#### Mark
Relations:
- `student()` - Belongs to Student
- `subject()` - Belongs to Subject
- `exam()` - Belongs to Exam
- `classData()` - Belongs to SchoolClass
- `markedBy()` - Belongs to User

#### ExamResult
Relations:
- `exam()` - Belongs to Exam
- `student()` - Belongs to Student
- `class()` - Belongs to SchoolClass
- `marks()` - Has many Mark (through)

#### Examination
Relations:
- `exam()` - Belongs to Exam
- `class()` - Belongs to SchoolClass
- `subject()` - Belongs to Subject
- `teacher()` - Belongs to Teacher
- `marks()` - Has many Mark

## Usage Examples

### Admin: Creating an Exam

```php
$examService = new ExamService();

$exam = $examService->createExam([
    'name' => 'Mathematics Midterm',
    'description' => 'Midterm exam for all classes',
    'academic_session_id' => 1,
    'semester_id' => 1,
    'exam_type' => 'MIDTERM',
    'start_date' => '2024-05-15',
    'end_date' => '2024-05-17',
    'total_marks' => 100,
    'passing_marks' => 40,
    'classes' => [1, 2, 3] // Class IDs
]);

// Publish exam
$examService->publishExam($exam->id);
```

### Teacher: Recording Marks

```php
$markingService = new MarkingService();

// Single mark
$mark = $markingService->recordMarks([
    'student_id' => 1,
    'exam_id' => 1,
    'subject_id' => 1,
    'class_id' => 1,
    'marks' => 85,
    'remarks' => 'Good performance'
]);

// Bulk upload
$result = $markingService->bulkUploadMarks(
    $examId,
    $classId,
    $subjectId,
    [
        ['student_id' => 1, 'marks' => 85],
        ['student_id' => 2, 'marks' => 92],
        ['student_id' => 3, 'marks' => 78],
    ]
);
```

### Generating Results

```php
$report = $this->reportService->generateExamReport($examId);

// Access report data
echo $report['overall_statistics']['average_marks'];
echo $report['overall_statistics']['pass_rate'];
```

## Database Schema

### Exams Table
```sql
ALTER TABLE exams ADD (
    description TEXT,
    exam_type VARCHAR(50) DEFAULT 'MIDTERM',
    start_date TIMESTAMP,
    end_date TIMESTAMP,
    total_marks INT DEFAULT 100,
    passing_marks INT DEFAULT 40,
    created_by BIGINT UNSIGNED,
    status VARCHAR(50) DEFAULT 'DRAFT'
);
```

### Marks Table
```sql
ALTER TABLE marks ADD (
    grade VARCHAR(2),
    marked_by BIGINT UNSIGNED,
    marked_date TIMESTAMP,
    remarks TEXT
);
```

### Exam Results Table
```sql
CREATE TABLE exam_results (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    exam_id BIGINT UNSIGNED,
    student_id BIGINT UNSIGNED,
    class_id BIGINT UNSIGNED,
    total_marks FLOAT,
    average_marks FLOAT,
    grade VARCHAR(2),
    position INT,
    remarks TEXT,
    is_passed BOOLEAN,
    timestamps,
    FOREIGN KEY (exam_id) REFERENCES exams(id),
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (class_id) REFERENCES school_classes(id)
);
```

### Exam Class Table
```sql
CREATE TABLE exam_class (
    exam_id BIGINT UNSIGNED,
    class_id BIGINT UNSIGNED,
    PRIMARY KEY (exam_id, class_id),
    FOREIGN KEY (exam_id) REFERENCES exams(id),
    FOREIGN KEY (class_id) REFERENCES school_classes(id)
);
```

## Grade Calculation

Grades are automatically calculated based on marks:
- **A**: 90-100
- **B+**: 80-89
- **B**: 70-79
- **C+**: 60-69
- **C**: 50-59
- **D**: 40-49
- **E**: Below 40

## API Endpoints

### Get All Exams
```
GET /api/exams?session_id=1&semester_id=1&status=ACTIVE
```

### Get Exam Details
```
GET /api/exams/{id}
```

### Get Exam Marks
```
GET /api/exams/{id}/marks?class_id=1&subject_id=1
```

### Get Student Exam Result
```
GET /api/exams/{id}/student/{student_id}/result
```

### Get Exam Results
```
GET /api/exams/{id}/results
```

### Get Exam Report
```
GET /api/exams/{id}/report
```

## Setup Instructions

1. **Run Migrations**
```bash
php artisan migrate
```

2. **Register Routes**
Add to `routes/web.php`:
```php
require __DIR__.'/exam-routes.php';
```

3. **Use Services in Controllers**
```php
$examService = new \App\Services\ExamService();
$markingService = new \App\Services\MarkingService();
$reportService = new \App\Services\ReportService();
```

## File Structure

```
app/
├── Services/
│   ├── ExamService.php
│   ├── MarkingService.php
│   └── ReportService.php
├── Http/
│   ├── Controllers/
│   │   ├── AdminExamController.php
│   │   ├── TeacherExamController.php
│   │   ├── ExamResultController.php
│   │   ├── ExaminationController.php
│   │   └── Api/
│   │       └── ExamApiController.php
├── Models/
│   ├── Exam.php
│   ├── Mark.php
│   ├── ExamResult.php
│   └── Examination.php
database/
└── migrations/
    ├── 2024_05_03_create_exam_class_table.php
    ├── 2024_05_03_update_marks_table_add_fields.php
    ├── 2024_05_03_update_exams_table_add_fields.php
    ├── 2024_05_03_create_exam_results_table.php
    └── 2024_05_03_update_examinations_table_add_fields.php
routes/
└── exam-routes.php
```

## Security Considerations

- All routes are protected with `auth` middleware
- Admin routes require `admin` middleware
- Teacher routes require `teacher` middleware
- Student result routes require `student` middleware
- Sanctum authentication for API endpoints

## Future Enhancements

1. Question bank integration
2. Online exam taking capability
3. Automated proctoring
4. Exam analytics dashboard
5. Mobile app support
6. Email notifications
7. SMS alerts for results
8. PDF report generation

## Support

For issues or questions about the exam module, contact the development team or create an issue in the project repository.
