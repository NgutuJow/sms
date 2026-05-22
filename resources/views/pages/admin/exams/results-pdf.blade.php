<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Exam Results Report</title>
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
            margin: 20px 0 10px;
            color: #1e40af;
        }
        
        .report-meta {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-bottom: 20px;
            background: #f0f4f8;
            padding: 8px 12px;
            border-radius: 4px;
        }
        
        .meta-item {
            flex: 1;
        }
        
        .meta-label {
            font-weight: bold;
            color: #1e40af;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        
        .stat-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 10px;
            text-align: center;
        }
        
        .stat-label {
            font-size: 10px;
            color: #666;
            font-weight: bold;
            margin-bottom: 4px;
        }
        
        .stat-value {
            font-size: 18px;
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
            margin-bottom: 20px;
            font-size: 11px;
        }
        
        th {
            background: #e8eef5;
            color: #1e40af;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #1e40af;
        }
        
        td {
            padding: 7px 8px;
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
        
        .footer {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
        
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            font-size: 11px;
        }
        
        .signature-line {
            width: 25%;
            text-align: center;
            border-top: 1px solid #333;
            padding-top: 20px;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .class-section {
            margin-bottom: 25px;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 10px;
        }
        
        .class-title {
            background: #e8eef5;
            padding: 8px;
            margin: -10px -10px 10px -10px;
            font-weight: bold;
            color: #1e40af;
            border-radius: 4px 4px 0 0;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="school-name">{{ $school->name ?? 'School Name' }}</div>
        <div class="school-details">
            <p>{{ $school->address ?? 'School Address' }} | Phone: {{ $school->phone ?? '' }}</p>
            <p>Email: {{ $school->email ?? '' }} | Website: {{ $school->website ?? '' }}</p>
            <p style="margin-top: 5px; border-top: 1px solid #ccc; padding-top: 5px;">EXAMINATION RESULTS REPORT</p>
        </div>
    </div>

    <!-- Report Title and Meta -->
    <div class="report-title">{{ $exam->name }}</div>
    
    <div class="report-meta">
        <div class="meta-item">
            <span class="meta-label">Academic Session:</span> {{ $exam->academicSession->session_name ?? 'N/A' }}
        </div>
        <div class="meta-item">
            <span class="meta-label">Semester:</span> {{ $exam->semester->semester_name ?? 'N/A' }}
        </div>
        <div class="meta-item">
            <span class="meta-label">Report Date:</span> {{ $reportDate }}
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-label">Total Students</div>
            <div class="stat-value">{{ $stats['total_students'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Marked</div>
            <div class="stat-value">{{ $stats['marked_students'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Average</div>
            <div class="stat-value">{{ number_format($stats['average_score'], 1) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Highest</div>
            <div class="stat-value">{{ $stats['highest_score'] ?? 'N/A' }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Lowest</div>
            <div class="stat-value">{{ $stats['lowest_score'] ?? 'N/A' }}</div>
        </div>
    </div>

    <!-- Grade Distribution -->
    @if(count($gradeDistribution) > 0)
    <div class="section-title">Grade Distribution</div>
    <table>
        <tr>
            <th>Grade</th>
            @foreach($gradeDistribution as $grade => $count)
            <th class="text-center">{{ $grade }}</th>
            @endforeach
            <th class="text-center">Total</th>
        </tr>
        <tr>
            <td><strong>Count</strong></td>
            @php $total = 0; @endphp
            @foreach($gradeDistribution as $grade => $count)
            @php $total += $count; @endphp
            <td class="text-center grade-{{ strtolower($grade) }}">{{ $count }}</td>
            @endforeach
            <td class="text-center"><strong>{{ $total }}</strong></td>
        </tr>
    </table>
    @endif

    <!-- All Results Table -->
    <div class="section-title">Detailed Results</div>
    @if(count($allMarks) > 0)
    <table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Admission No</th>
                <th>Class</th>
                <th>Subject</th>
                <th class="text-right">Marks</th>
                <th class="text-center">Grade</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allMarks as $mark)
            <tr>
                <td>{{ $mark->student->user->name ?? $mark->student->name ?? 'N/A' }}</td>
                <td>{{ $mark->student->admission_no ?? '-' }}</td>
                <td>{{ $mark->student->classData->class_name ?? 'N/A' }}</td>
                <td>{{ $mark->subject->name ?? 'N/A' }}</td>
                <td class="text-right"><strong>{{ $mark->marks }}</strong></td>
                <td class="text-center grade-{{ strtolower($mark->grade) }}">{{ $mark->grade }}</td>
                <td>{{ $mark->remarks ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="text-align: center; padding: 20px; color: #999;">No results available</p>
    @endif

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-line">
            <strong>Administrator</strong>
        </div>
        <div class="signature-line">
            <strong>Authorized By</strong>
        </div>
        <div class="signature-line">
            <strong>Headmaster/Principal</strong>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>This is an official examination results report. For inquiries, contact the administration office.</p>
        <p style="margin-top: 5px; font-size: 9px;">Generated on {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
