<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Results Report - {{ $subject->subject_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background: white;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        .header {
            border-bottom: 3px solid #003366;
            padding-bottom: 15px;
            margin-bottom: 20px;
            text-align: center;
        }

        .school-name {
            font-size: 22px;
            font-weight: bold;
            color: #003366;
            margin-bottom: 5px;
        }

        .school-code {
            font-size: 11px;
            color: #666;
            margin-bottom: 8px;
        }

        .school-contact {
            font-size: 10px;
            color: #666;
            line-height: 1.4;
        }

        .school-contact span {
            display: inline;
            margin: 0 10px;
        }

        /* Report Info */
        .report-info {
            background: #f5f5f5;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            padding: 4px 0;
            border-bottom: 1px solid #ddd;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: bold;
            color: #003366;
            min-width: 150px;
        }

        .info-value {
            color: #333;
            flex: 1;
        }

        /* Table */
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .results-table th {
            background: #003366;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #003366;
        }

        .results-table td {
            padding: 8px 10px;
            border: 1px solid #ddd;
        }

        .results-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .results-table tr:hover {
            background: #f0f0f0;
        }

        .results-table .no-mark {
            background: #fff3cd;
            color: #856404;
            font-style: italic;
            text-align: center;
        }

        .grade {
            font-weight: bold;
            text-align: center;
            padding: 6px;
            border-radius: 3px;
        }

        .grade-a { background: #d4edda; color: #155724; }
        .grade-b { background: #d1ecf1; color: #0c5460; }
        .grade-c { background: #fff3cd; color: #856404; }
        .grade-d { background: #f8d7da; color: #721c24; }
        .grade-f { background: #f5c6cb; color: #721c24; font-weight: bold; }

        /* Summary */
        .summary {
            background: #f5f5f5;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .summary-title {
            font-weight: bold;
            color: #003366;
            margin-bottom: 8px;
            font-size: 12px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .summary-item {
            background: white;
            padding: 8px;
            border-left: 4px solid #003366;
            border-radius: 2px;
        }

        .summary-label {
            color: #666;
            font-size: 10px;
        }

        .summary-value {
            font-size: 16px;
            font-weight: bold;
            color: #003366;
        }

        /* Footer */
        .footer {
            border-top: 2px solid #003366;
            padding-top: 10px;
            font-size: 9px;
            color: #666;
            text-align: center;
            margin-top: 20px;
        }

        .stamp-area {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px dashed #999;
            display: flex;
            justify-content: space-around;
            font-size: 10px;
        }

        .stamp-box {
            text-align: center;
            min-width: 150px;
        }

        .stamp-line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 5px;
            min-width: 100px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .container {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="school-name">{{ $school->name ?? 'SCHOOL NAME' }}</div>
            <div class="school-code">{{ $school->code ?? '' }}</div>
            <div class="school-contact">
                @if($school)
                    <span>📧 {{ $school->email ?? '--' }}</span>
                    <span>📞 {{ $school->phone ?? '--' }}</span>
                    <br>
                    <span>📍 {{ $school->address ?? '' }} {{ $school->district ?? '' }}, {{ $school->region ?? '' }}</span>
                @endif
            </div>
        </div>

        <!-- Report Info -->
        <div class="report-info">
            <div class="info-row">
                <span class="info-label">Report Title:</span>
                <span class="info-value">Examination Results Report</span>
            </div>
            <div class="info-row">
                <span class="info-label">Subject:</span>
                <span class="info-value">{{ $subject->subject_name }} ({{ $subject->schoolClass->class_name ?? '' }})</span>
            </div>
            <div class="info-row">
                <span class="info-label">Examination:</span>
                <span class="info-value">{{ $exam->name }} - {{ $exam->exam_type ?? '' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Teacher:</span>
                <span class="info-value">{{ $teacher->first_name ?? '' }} {{ $teacher->last_name ?? '' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Report Date:</span>
                <span class="info-value">{{ $reportDate }}</span>
            </div>
        </div>

        <!-- Results Table -->
        <table class="results-table">
            <thead>
                <tr>
                    <th style="width: 8%;">S/N</th>
                    <th style="width: 15%;">Admission No</th>
                    <th style="width: 30%;">Student Name</th>
                    <th style="width: 12%;">Marks</th>
                    <th style="width: 12%;">Grade</th>
                    <th style="width: 23%;">Stream</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allStudents as $index => $student)
                    @php
                        $studentMark = $marks->where('student_id', $student->id)->first();
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $student->admission_no }}</strong></td>
                        <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                        <td>
                            @if($studentMark)
                                <strong>{{ $studentMark->marks }}/100</strong>
                            @else
                                <span class="no-mark">Not Marked</span>
                            @endif
                        </td>
                        <td>
                            @if($studentMark)
                                <span class="grade grade-{{ strtolower($studentMark->grade) }}">
                                    {{ $studentMark->grade }}
                                </span>
                            @else
                                <span class="no-mark">--</span>
                            @endif
                        </td>
                        <td>{{ $student->streamData->stream_name ?? '--' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #999;">No students found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary">
            <div class="summary-title">📊 Summary Statistics</div>
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-label">Total Students</div>
                    <div class="summary-value">{{ $totalStudents }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Marked</div>
                    <div class="summary-value">{{ $markedCount }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Pending</div>
                    <div class="summary-value">{{ $totalStudents - $markedCount }}</div>
                </div>
            </div>
        </div>

        <!-- Signature Area -->
        <div class="stamp-area">
            <div class="stamp-box">
                <div class="stamp-line">Teacher Signature</div>
            </div>
            <div class="stamp-box">
                <div class="stamp-line">Head Teacher</div>
            </div>
            <div class="stamp-box">
                <div class="stamp-line">Date</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This is an official school document. Generated on {{ $reportDate }}</p>
            <p>© {{ now()->year }} {{ $school->name ?? 'School Management System' }}</p>
        </div>
    </div>
</body>
</html>
