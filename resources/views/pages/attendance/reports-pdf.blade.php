<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #222; margin: 0; padding: 24px; }
        .header { margin-bottom: 24px; }
        .header h1 { margin: 0 0 8px; font-size: 24px; }
        .meta { font-size: 12px; color: #555; margin-bottom: 16px; }
        .summary-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; margin-bottom: 20px; }
        .card { background: #f5f5f5; border: 1px solid #ddd; border-radius: 8px; padding: 12px 14px; }
        .card h3 { margin: 0 0 8px; font-size: 13px; color: #333; }
        .card p { margin: 0; font-size: 18px; font-weight: 700; }
        .table-wrap { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table-wrap th, .table-wrap td { border: 1px solid #ddd; padding: 8px 10px; font-size: 12px; }
        .table-wrap th { background: #f0f0f0; }
        .table-heading { margin: 24px 0 12px; font-size: 16px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Attendance Report</h1>
        <div class="meta">
            <div><strong>Class:</strong> {{ $className ?? 'N/A' }}</div>
            @if(isset($streamName) && $streamName)
                <div><strong>Stream:</strong> {{ $streamName }}</div>
            @endif
            <div><strong>Period:</strong> {{ $periodLabel ?? ucfirst($period) }}</div>
            <div><strong>Range:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d M, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M, Y') }}</div>
            <div><strong>Generated:</strong> {{ \Carbon\Carbon::now()->format('d M, Y H:i') }}</div>
        </div>
    </div>

    <div class="summary-grid">
        <div class="card"><h3>Total</h3><p>{{ $stats['total'] }}</p></div>
        <div class="card"><h3>Present</h3><p>{{ $stats['present'] }}</p></div>
        <div class="card"><h3>Absent</h3><p>{{ $stats['absent'] }}</p></div>
        <div class="card"><h3>Late</h3><p>{{ $stats['late'] }}</p></div>
    </div>

    <p class="table-heading">Attendance Records</p>
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
                    <td colspan="3" style="text-align:center; padding: 14px;">No attendance records available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
