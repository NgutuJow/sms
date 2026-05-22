<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Exam Report - {{ $student->first_name }} {{ $student->last_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
        }
        
        .header h1 {
            font-size: 24px;
            color: #007bff;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 12px;
            color: #666;
        }
        
        .student-info {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            font-size: 12px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 8px;
            background-color: #f5f5f5;
            width: 25%;
            border: 1px solid #ddd;
        }
        
        .info-value {
            display: table-cell;
            padding: 8px;
            border: 1px solid #ddd;
        }
        
        .exam-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .exam-title {
            font-size: 14px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f0f8ff;
            border-left: 4px solid #007bff;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }
        
        table thead {
            background-color: #007bff;
            color: white;
        }
        
        table th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #007bff;
        }
        
        table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        table tbody tr:last-child {
            background-color: #e8f4ff;
            font-weight: bold;
        }
        
        .grade-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            color: white;
        }
        
        .grade-a {
            background-color: #28a745;
        }
        
        .grade-b {
            background-color: #17a2b8;
        }
        
        .grade-c {
            background-color: #ffc107;
            color: #333;
        }
        
        .grade-d {
            background-color: #fd7e14;
        }
        
        .grade-f {
            background-color: #dc3545;
        }
        
        .summary {
            display: table;
            width: 100%;
            margin-top: 20px;
        }
        
        .summary-item {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            background-color: #f5f5f5;
        }
        
        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }
        
        .summary-label {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #999;
        }
        
        .remarks {
            margin-top: 20px;
            padding: 10px;
            background-color: #fffbea;
            border-left: 4px solid #ffc107;
            font-size: 11px;
        }
        
        .remarks-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>STUDENT EXAMINATION REPORT</h1>
            <p>Academic Performance Summary</p>
        </div>
        
        <!-- Student Information -->
        <div class="student-info">
            <div class="info-row">
                <div class="info-label">Student Name</div>
                <div class="info-value">{{ $student->first_name }} {{ $student->last_name }}</div>
                <div class="info-label">Admission No</div>
                <div class="info-value">{{ $student->admission_no }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Class</div>
                <div class="info-value">{{ $student->classData->name ?? 'N/A' }}</div>
                <div class="info-label">Stream</div>
                <div class="info-value">{{ $student->streamData->name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Report Date</div>
                <div class="info-value">{{ \Carbon\Carbon::now()->format('d M Y') }}</div>
                <div class="info-label">Total Exams</div>
                <div class="info-value">{{ count($examReports) }}</div>
            </div>
        </div>
        
        <!-- Exam Reports -->
        @foreach($examReports as $report)
        <div class="exam-section">
            <div class="exam-title">
                📋 {{ $report['exam']->name ?? 'Examination' }}
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Marks Obtained</th>
                        <th>Grade</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report['marks'] as $mark)
                    <tr>
                        <td>{{ $mark->subject->name ?? 'N/A' }}</td>
                        <td>{{ $mark->marks }}</td>
                        <td>
                            <span class="grade-badge grade-{{ strtolower($mark->grade ?? 'f') }}">
                                {{ $mark->grade ?? 'N/A' }}
                            </span>
                        </td>
                        <td>{{ $mark->remarks ?? '-' }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="1"><strong>Total/Overall</strong></td>
                        <td><strong>{{ $report['total_marks'] }} Marks</strong></td>
                        <td colspan="1">
                            <span class="grade-badge grade-{{ strtolower($report['grade']) }}">
                                {{ $report['grade'] }}
                            </span>
                        </td>
                        <td><strong>{{ $report['average'] }}% Avg</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endforeach
        
        <!-- Summary Statistics -->
        <div style="margin-top: 30px;">
            <h3 style="font-size: 14px; margin-bottom: 15px; color: #007bff;">PERFORMANCE SUMMARY</h3>
            <div class="summary">
                <div class="summary-item">
                    <div class="summary-value">{{ number_format(collect($examReports)->avg('average'), 1) }}%</div>
                    <div class="summary-label">Overall Average</div>
                </div>
                <div class="summary-item">
                    <div class="summary-value">{{ collect($examReports)->where('grade', 'A')->count() }}</div>
                    <div class="summary-label">Grade A Count</div>
                </div>
                <div class="summary-item">
                    <div class="summary-value">{{ collect($examReports)->count() }}</div>
                    <div class="summary-label">Total Exams</div>
                </div>
                <div class="summary-item">
                    <div class="summary-value">{{ collect($examReports)->whereIn('grade', ['B', 'B+', 'C', 'C+'])->count() }}</div>
                    <div class="summary-label">B-C Grades</div>
                </div>
            </div>
        </div>
        
        <!-- Remarks -->
        <div class="remarks">
            <div class="remarks-title">📝 REMARKS</div>
            <div>
                @php
                    $average = collect($examReports)->avg('average');
                    if ($average >= 80) {
                        echo 'Excellent performance! Student demonstrates strong academic understanding and consistent achievement across all subjects.';
                    } elseif ($average >= 70) {
                        echo 'Good performance. Student shows competence in most subjects with room for improvement in specific areas.';
                    } elseif ($average >= 60) {
                        echo 'Satisfactory performance. Student is meeting basic requirements but should focus on improving overall grades.';
                    } elseif ($average >= 50) {
                        echo 'Needs improvement. Student requires additional support and focused effort to enhance academic performance.';
                    } else {
                        echo 'Significant support needed. Student should engage with instructors for academic intervention and support programs.';
                    }
                @endphp
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>This is an official examination report generated by the School Management System.</p>
            <p>Report Generated: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</p>
        </div>
    </div>
</body>
</html>