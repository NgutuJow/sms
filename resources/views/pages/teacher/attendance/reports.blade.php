@extends('pages.teacher.layout.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title">Attendance Reports</h2>
        <p class="text-muted mb-0">Explore daily, weekly, monthly, and yearly attendance performance for <strong>{{ $className }} {{ $streamName ? '• '.$streamName : '' }}</strong>.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('teacher-attendance.reports.download', request()->all()) }}" class="btn btn-danger btn-compact"><i class="fas fa-file-pdf me-2"></i>Download PDF</a>
        <a href="{{ route('teacher-attendance.index') }}" class="btn btn-outline-secondary btn-compact"><i class="fas fa-arrow-left me-2"></i>Back to Attendance</a>
    </div>
</div>

<div class="card card-custom shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('teacher-attendance.reports') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label small text-muted">Reporting Period</label>
                <select id="period" name="period" class="form-select" onchange="toggleRangeFields()">
                    <option value="day" {{ $period === 'day' ? 'selected' : '' }}>Day</option>
                    <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Week</option>
                    <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Month</option>
                    <option value="year" {{ $period === 'year' ? 'selected' : '' }}>Year</option>
                </select>
            </div>
            <div class="col-md-2 period-field period-day">
                <label class="form-label small text-muted">Date</label>
                <input type="date" name="date" value="{{ $date }}" class="form-control">
            </div>
            <div class="col-md-2 period-field period-week">
                <label class="form-label small text-muted">From</label>
                <input type="date" name="date_from" value="{{ $dateFrom ?? $startDate }}" class="form-control">
            </div>
            <div class="col-md-2 period-field period-week">
                <label class="form-label small text-muted">To</label>
                <input type="date" name="date_to" value="{{ $dateTo ?? $endDate }}" class="form-control">
            </div>
            <div class="col-md-2 period-field period-month">
                <label class="form-label small text-muted">Month</label>
                <input type="month" name="month" value="{{ $year . '-' . ($month < 10 ? '0'.$month : $month) }}" class="form-control">
            </div>
            <div class="col-md-2 period-field period-year">
                <label class="form-label small text-muted">Year</label>
                <input type="number" name="year" value="{{ $year }}" min="2000" max="2100" class="form-control">
            </div>
            <div class="col-md-1 text-end">
                <button type="submit" class="btn btn-primary btn-compact w-100"><i class="fas fa-sync-alt"></i></button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-custom shadow-sm p-3 bg-primary text-white">
            <div class="small text-uppercase opacity-75">Present</div>
            <div class="fs-4 fw-semibold">{{ $stats['present'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-custom shadow-sm p-3 bg-danger text-white">
            <div class="small text-uppercase opacity-75">Absent</div>
            <div class="fs-4 fw-semibold">{{ $stats['absent'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-custom shadow-sm p-3 bg-warning text-dark">
            <div class="small text-uppercase opacity-75">Late</div>
            <div class="fs-4 fw-semibold">{{ $stats['late'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-custom shadow-sm p-3 bg-success text-white">
            <div class="small text-uppercase opacity-75">Attendance Rate</div>
            <div class="fs-4 fw-semibold">{{ $stats['percent'] }}%</div>
        </div>
    </div>
</div>

<div class="card card-custom shadow-sm mb-4">
    <div class="card-body">
        <canvas id="attendanceChart" height="120"></canvas>
    </div>
</div>

<div class="card card-custom shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="mb-1">Daily Summary</h5>
                <small class="text-muted">From {{ \Carbon\Carbon::parse($startDate)->format('d M, Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('d M, Y') }}</small>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Present</th>
                        <th>Absent</th>
                        <th>Late</th>
                        <th>Total Records</th>
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
                            <td colspan="5" class="text-center text-muted py-4">No attendance records found for this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function toggleRangeFields() {
        const period = document.getElementById('period').value;
        document.querySelectorAll('.period-field').forEach(field => field.classList.add('d-none'));
        document.querySelectorAll('.period-' + period).forEach(field => field.classList.remove('d-none'));
    }

    document.addEventListener('DOMContentLoaded', () => {
        toggleRangeFields();

        const attendanceChart = document.getElementById('attendanceChart').getContext('2d');
        new Chart(attendanceChart, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [
                    {
                        label: 'Present',
                        data: @json($presentData),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.12)',
                        tension: 0.3,
                        fill: true,
                    },
                    {
                        label: 'Absent',
                        data: @json($absentData),
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.12)',
                        tension: 0.3,
                        fill: true,
                    },
                    {
                        label: 'Late',
                        data: @json($lateData),
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.12)',
                        tension: 0.3,
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    });
</script>
@endsection
