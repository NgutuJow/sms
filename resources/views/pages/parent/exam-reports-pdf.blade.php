<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Exam Reports</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin-bottom: 4px; font-size: 22px; }
        .header p { margin: 0; color: #666; font-size: 12px; }
        .student-card { margin-bottom: 25px; page-break-inside: avoid; }
        .student-title { background: #f1f5f9; padding: 12px 14px; border-radius: 8px; border-left: 4px solid #3b82f6; margin-bottom: 12px; }
        .student-title h2 { font-size: 16px; margin: 0; }
        .student-title small { display: block; color: #6b7280; }
        .info-grid { display: table; width: 100%; margin-bottom: 14px; font-size: 12px; }
        .info-row { display: table-row; }
        .info-cell { display: table-cell; padding: 6px 8px; border: 1px solid #e5e7eb; }
        .section-title { font-size: 14px; margin-bottom: 8px; font-weight: bold; }
        .report-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; font-size: 12px; }
        .report-table th, .report-table td { border: 1px solid #d1d5db; padding: 8px 10px; }
        .report-table th { background: #e2e8f0; text-align: left; }
        .grade-badge { display: inline-block; padding: 4px 8px; border-radius: 4px; color: #fff; font-size: 11px; }
        .grade-success { background: #16a34a; }
        .grade-info { background: #0284c7; }
        .grade-warning { background: #f59e0b; color: #111; }
        .grade-danger { background: #dc2626; }
        .summary-grid { display: table; width: 100%; margin-top: 10px; }
        .summary-cell { display: table-cell; width: 25%; padding: 10px; border: 1px solid #e5e7eb; background: #f8fafc; text-align: center; }
        .summary-value { font-size: 16px; font-weight: bold; color: #1d4ed8; }
        .summary-label { font-size: 11px; color: #6b7280; margin-top: 4px; }
        .footer { margin-top: 24px; font-size: 11px; color: #475569; text-align: center; border-top: 1px solid #e5e7eb; padding-top: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Parent Exam Reports</h1>
        <p>Generated for parent exam review. Includes performance details for all linked children.</p>
        <p style="font-size: 11px; color: #6b7280;">Report generated: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</p>
    </div>

    @foreach($examReports as $data)
        <div class="student-card">
            <div class="student-title">
                <h2>{{ $data['student']->first_name }} {{ $data['student']->last_name }}</h2>
                <small>Admission: {{ $data['student']->admission_no }} | Class: {{ $data['student']->classData->name ?? 'N/A' }} | Stream: {{ $data['student']->streamData->name ?? 'N/A' }}</small>
            </div>

            @if(count($data['reports']) > 0)
                @foreach($data['reports'] as $report)
                    <div class="section-title">{{ $report['exam']->name ?? 'Exam' }} — Average {{ $report['average'] }}% — Grade {{ $report['grade'] }}</div>
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Marks</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($report['marks'] as $mark)
                                <tr>
                                    <td>{{ $mark->subject->name ?? 'N/A' }}</td>
                                    <td>{{ $mark->marks }}</td>
                                    <td>
                                        <span class="grade-badge {{ $mark->grade == 'A' ? 'grade-success' : ($mark->grade == 'B' || $mark->grade == 'B+' ? 'grade-info' : ($mark->grade == 'C' || $mark->grade == 'C+' ? 'grade-warning' : 'grade-danger')) }}">
                                            {{ $mark->grade ?? 'N/A' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td><strong>Total</strong></td>
                                <td><strong>{{ $report['total_marks'] }}</strong></td>
                                <td><strong>{{ $report['grade'] }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                @endforeach

                <div class="summary-grid">
                    <div class="summary-cell">
                        <div class="summary-value">{{ round(collect($data['reports'])->avg('average'), 1) }}%</div>
                        <div class="summary-label">Overall Average</div>
                    </div>
                    <div class="summary-cell">
                        <div class="summary-value">{{ collect($data['reports'])->where('grade', 'A')->count() }}</div>
                        <div class="summary-label">A Grades</div>
                    </div>
                    <div class="summary-cell">
                        <div class="summary-value">{{ count($data['reports']) }}</div>
                        <div class="summary-label">Exams Reported</div>
                    </div>
                    <div class="summary-cell">
                        <div class="summary-value">{{ collect($data['reports'])->whereIn('grade', ['B', 'B+', 'C', 'C+'])->count() }}</div>
                        <div class="summary-label">B-C Grades</div>
                    </div>
                </div>
            @else
                <div style="font-size: 12px; color: #6b7280; padding: 12px; border: 1px dashed #cbd5e1; border-radius: 8px;">No exam result data available yet for this student.</div>
            @endif
        </div>
    @endforeach

    <div class="footer">
        <div>School Management System &bull; Parent Exam Summary Report</div>
    </div>
</body>
</html>