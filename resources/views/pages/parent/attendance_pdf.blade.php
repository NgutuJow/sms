<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Children Attendance Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2563eb;
            margin: 0;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .student-section {
            margin-bottom: 40px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .student-header {
            background: #f8f9fa;
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }
        .student-name {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }
        .student-info {
            color: #666;
            margin: 5px 0 0 0;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            padding: 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #ddd;
        }
        .stat-item {
            text-align: center;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            display: block;
        }
        .stat-label {
            color: #666;
            font-size: 12px;
        }
        .present { color: #10b981; }
        .absent { color: #ef4444; }
        .total { color: #2563eb; }
        .percentage { color: #f59e0b; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
        }
        .status-present {
            color: #10b981;
            font-weight: bold;
        }
        .status-absent {
            color: #ef4444;
            font-weight: bold;
        }
        .no-records {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Children Attendance Report</h1>
        <p>Period: {{ ucfirst($period) }} | Generated on: {{ now()->format('M d, Y') }}</p>
        <p>Date Range: {{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</p>
    </div>

    @foreach($attendanceData as $data)
    <div class="student-section">
        <div class="student-header">
            <h2 class="student-name">{{ $data['student']->first_name }} {{ $data['student']->last_name }}</h2>
            <p class="student-info">
                Admission No: {{ $data['student']->admission_no }} |
                Class: {{ $data['student']->classData->name ?? 'N/A' }} |
                Stream: {{ $data['student']->streamData->name ?? 'N/A' }}
            </p>
        </div>

        <div class="stats">
            <div class="stat-item">
                <span class="stat-value total">{{ $data['summary']['total_days'] }}</span>
                <span class="stat-label">Total Days</span>
            </div>
            <div class="stat-item">
                <span class="stat-value present">{{ $data['summary']['present_days'] }}</span>
                <span class="stat-label">Present</span>
            </div>
            <div class="stat-item">
                <span class="stat-value absent">{{ $data['summary']['absent_days'] }}</span>
                <span class="stat-label">Absent</span>
            </div>
            <div class="stat-item">
                <span class="stat-value percentage">{{ $data['summary']['percentage'] }}%</span>
                <span class="stat-label">Attendance Rate</span>
            </div>
        </div>

        @if($data['records']->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Status</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['records'] as $record)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($record->date)->format('l') }}</td>
                    <td>
                        @if($record->status === 'present')
                            <span class="status-present">Present</span>
                        @elseif($record->status === 'absent')
                            <span class="status-absent">Absent</span>
                        @else
                            {{ ucfirst($record->status) }}
                        @endif
                    </td>
                    <td>{{ $record->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-records">
            <p>No attendance records found for this period</p>
        </div>
        @endif
    </div>
    @endforeach

    <div class="footer">
        <p>This report was generated automatically by the School Management System</p>
        <p>Parent: {{ auth()->user()->name }} | Generated: {{ now()->format('M d, Y H:i') }}</p>
    </div>
</body>
</html>