<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #222; margin: 0; padding: 24px; }
        .header { margin-bottom: 24px; }
        .header h1 { margin: 0 0 8px; font-size: 24px; }
        .meta { font-size: 13px; color: #555; margin-bottom: 18px; }
        .summary { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 24px; }
        .card { background: #f7f7f7; border: 1px solid #ddd; border-radius: 6px; padding: 12px 14px; width: calc(25% - 10px); box-sizing: border-box; }
        .card h3 { margin: 0 0 6px; font-size: 14px; color: #333; }
        .card p { margin: 0; font-size: 18px; font-weight: 700; }
        .table-wrap { width: 100%; border-collapse: collapse; margin-top: 12px; }
        .table-wrap th,
        .table-wrap td { border: 1px solid #ddd; padding: 8px 10px; text-align: left; font-size: 12px; }
        .table-wrap th { background: #f0f0f0; }
        .text-right { text-align: right; }
        .small { font-size: 11px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Attendance Report</h1>
        <div class="meta">
            <div><strong>Class:</strong> {{ $className }}</div>
            <div><strong>Stream:</strong> {{ $streamName }}</div>
            <div><strong>Period:</strong> {{ $periodLabel }}</div>
            <div><strong>Range:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d M, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M, Y') }}</div>
            <div class="small">Generated on {{ \Carbon\Carbon::now()->format('d M, Y H:i') }}</div>
        </div>
    </div>

    <div class="summary">
        <div class="card">
            <h3>Total Records</h3>
            <p>{{ $stats['total'] }}</p>
        </div>
        <div class="card">
            <h3>Present</h3>
            <p>{{ $stats['present'] }}</p>
        </div>
        <div class="card">
            <h3>Absent</h3>
            <p>{{ $stats['absent'] }}</p>
        </div>
        <div class="card">
            <h3>Late</h3>
            <p>{{ $stats['late'] }}</p>
        </div>
        <div class="card">
            <h3>Attendance Rate</h3>
            <p>{{ $stats['percent'] }}%</p>
        </div>
    </div>

    <table class="table-wrap">
        <thead>
            <tr>
                <th>Date</th>
                <th>Present</th>
                <th>Absent</th>
                <th>Late</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dailySummary as $row)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($row['date'])->format('d M, Y') }}</td>
                    <td>{{ $row['present'] }}</td>
                    <td>{{ $row['absent'] }}</td>
                    <td>{{ $row['late'] }}</td>
                    <td>{{ $row['total'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-right">No attendance records found for this period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2 style="margin-top: 30px; font-size: 18px;">Attendance Records</h2>
    <table class="table-wrap">
        <thead>
            <tr>
                <th>Date</th>
                <th>Student Name</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $record)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($record->date)->format('d M, Y') }}</td>
                    <td>{{ optional($record->student)->full_name ?? optional($record->student)->first_name ?? 'Unknown Student' }}</td>
                    <td>{{ ucfirst($record->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-right">No attendance record details to display.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
