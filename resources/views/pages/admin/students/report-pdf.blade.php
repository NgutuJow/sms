<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Student Performance Report</title>
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
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 20px;
        }
        
        .school-name {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5px;
        }
        
        .school-details {
            font-size: 11px;
            color: #666;
        }
        
        .school-details p {
            margin: 2px 0;
        }
        
        .report-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0 15px;
            color: #1e40af;
        }
        
        .student-info {
            background: #f0f4f8;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 11px;
        }
        
        .student-info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }
        
        .info-item {
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            color: #1e40af;
            font-size: 10px;
        }
        
        .info-value {
            font-size: 12px;
            margin-top: 2px;
        }
        
        .rank-box {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 4px;
            padding: 10px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .rank-value {
            font-size: 28px;
            font-weight: bold;
            color: #d39e00;
        }
        
        .rank-label {
            font-size: 10px;
            color: #856404;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 8px;
            margin-bottom: 20px;
        }
        
        .stat-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 3px;
            padding: 8px;
            text-align: center;
        }
        
        .stat-label {
            font-size: 9px;
            color: #666;
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .stat-value {
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
        }
        
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #fff;
            background: #1e40af;
            padding: 8px 10px;
            margin-top: 15px;
            margin-bottom: 10px;
            border-radius: 3px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10px;
        }
        
        th {
            background: #e8eef5;
            color: #1e40af;
            padding: 7px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #1e40af;
        }
        
        td {
            padding: 6px 7px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .grade-a { background-color: #d4edda; color: #155724; font-weight: bold; }
        .grade-b { background-color: #d1ecf1; color: #0c5460; font-weight: bold; }
        .grade-c { background-color: #fff3cd; color: #856404; font-weight: bold; }
        .grade-d { background-color: #f8d7da; color: #721c24; font-weight: bold; }
        .grade-f { background-color: #e2e3e5; color: #383d41; font-weight: bold; }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .exam-section {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .exam-header {
            background: #e8eef5;
            padding: 8px 10px;
            border-bottom: 1px solid #1e40af;
            font-weight: bold;
            color: #1e40af;
            font-size: 11px;
        }
        
        .exam-body {
            padding: 0;
        }
        
        .footer {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="school-name">{{ $school->name ?? 'School Name' }}</div>
        <div class="school-details">
            <p>{{ $school->address ?? 'School Address' }} | Phone: {{ $school->phone ?? '' }}</p>
            <p>Email: {{ $school->email ?? '' }}</p>
            <p style="margin-top: 5px; border-top: 1px solid #ccc; padding-top: 5px;">STUDENT PERFORMANCE REPORT</p>
        </div>
    </div>

    <!-- Report Title -->
    <div class="report-title">{{ $student->user->name ?? $student->name ?? 'Student' }} - Comprehensive Performance Report</div>

    <!-- Student Information -->
    <div class="student-info">
        <div class="student-info-grid">
            <div class="info-item">
                <div class="info-label">Admission No:</div>
                <div class="info-value">{{ $student->admission_no ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Class:</div>
                <div class="info-value">{{ $student->classData->class_name ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Stream:</div>
                <div class="info-value">{{ $student->stream ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Branch:</div>
                <div class="info-value">{{ $student->branch->name ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Report Generated:</div>
                <div class="info-value">{{ $reportDate }}</div>
            </div>
        </div>
    </div>

    <!-- Class Ranking -->
    <div class="rank-box">
        <div class="rank-value">#{{ $studentRank }}</div>
        <div class="rank-label">CLASS RANK (Out of {{ $totalInClass }} Students)</div>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-label">Total Exams</div>
            <div class="stat-value">{{ $stats['total_exams'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Subjects</div>
            <div class="stat-value">{{ $stats['total_subjects'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Average</div>
            <div class="stat-value">{{ number_format($stats['overall_average'], 1) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Highest</div>
            <div class="stat-value">{{ $stats['highest_mark'] ?? 'N/A' }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Lowest</div>
            <div class="stat-value">{{ $stats['lowest_mark'] ?? 'N/A' }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Marks</div>
            <div class="stat-value">{{ $stats['total_obtained'] ?? 0 }}</div>
        </div>
    </div>

    <!-- Results by Exam -->
    <div class="section-title">Detailed Results by Examination</div>
    
    @forelse($marksByExam as $examId => $examData)
    <div class="exam-section">
        <div class="exam-header">
            {{ $examData['exam']->name ?? 'Exam' }} 
            | {{ $examData['exam']->academicSession->session_name ?? '' }} | {{ $examData['exam']->semester->semester_name ?? '' }}
            | Total: {{ $examData['total_marks'] }} | Average: {{ number_format($examData['average_marks'], 1) }}
        </div>
        <div class="exam-body">
            <table>
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th class="text-right">Marks</th>
                        <th class="text-center">Grade</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($examData['marks'] as $mark)
                    <tr>
                        <td>{{ $mark->subject->name ?? 'N/A' }}</td>
                        <td class="text-right"><strong>{{ $mark->marks }}</strong></td>
                        <td class="text-center grade-{{ strtolower($mark->grade) }}">{{ $mark->grade }}</td>
                        <td>{{ $mark->remarks ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @empty
    <p style="text-align: center; padding: 20px; color: #999;">No exam results available</p>
    @endforelse

    <!-- Footer -->
    <div class="footer">
        <p>This is an official student performance report. For inquiries, contact the administration office.</p>
        <p style="margin-top: 5px; font-size: 9px;">Generated on {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
