@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <style>
        #streamSelect,
        #classSelect,
        #periodSelect {
            color: #000 !important;
            background-color: #fff !important;
        }

        #streamSelect option,
        #classSelect option,
        #periodSelect option {
            color: #000 !important;
            background-color: #fff !important;
        }
    </style>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold"><i class="fas fa-file-invoice-dollar text-primary"></i> Attendance Reports</h4>
            <small class="text-muted">View daily, weekly, monthly, and yearly class attendance and download PDF reports.</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('academic.attendance.index') }}" class="btn btn-outline-secondary btn-sm">Back to Attendance</a>
            <a href="{{ route('academic.attendance.reports.download', request()->all()) }}" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf me-1"></i> Download PDF
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('academic.attendance.reports') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-uppercase">Class</label>
                    <select name="class_id" class="form-select" required id="classSelect">
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-uppercase">Stream</label>
                    <select name="stream_id" class="form-select" id="streamSelect">
                        <option value="">All Streams</option>
                        @if($selectedClass && isset($streams))
                            @foreach($streams as $stream)
                                <option value="{{ $stream->id }}" {{ $selectedStream == $stream->id ? 'selected' : '' }}>{{ $stream->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold small text-uppercase">Period</label>
                    <select name="period" class="form-select" onchange="toggleReportFields()" id="periodSelect">
                        <option value="day" {{ $period === 'day' ? 'selected' : '' }}>Day</option>
                        <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Week</option>
                        <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Month</option>
                        <option value="year" {{ $period === 'year' ? 'selected' : '' }}>Year</option>
                    </select>
                </div>
                <div class="col-md-2 period-field period-day">
                    <label class="form-label fw-bold small text-uppercase">Date</label>
                    <input type="date" name="date" value="{{ $date ?? date('Y-m-d') }}" class="form-control">
                </div>
                <div class="col-md-2 period-field period-week">
                    <label class="form-label fw-bold small text-uppercase">From</label>
                    <input type="date" name="date_from" value="{{ $dateFrom ?? $startDate ?? '' }}" class="form-control">
                </div>
                <div class="col-md-2 period-field period-week">
                    <label class="form-label fw-bold small text-uppercase">To</label>
                    <input type="date" name="date_to" value="{{ $dateTo ?? $endDate ?? '' }}" class="form-control">
                </div>
                <div class="col-md-2 period-field period-month">
                    <label class="form-label fw-bold small text-uppercase">Month</label>
                    <input type="month" name="month" value="{{ $year . '-' . ($month < 10 ? '0'.$month : $month) }}" class="form-control">
                </div>
                <div class="col-md-2 period-field period-year">
                    <label class="form-label fw-bold small text-uppercase">Year</label>
                    <input type="number" name="year" value="{{ $year ?? date('Y') }}" min="2000" max="2100" class="form-control">
                </div>
                <div class="col-md-2 text-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    @if($selectedClass)
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted mb-2">Total Records</h6>
                        <h3>{{ $stats['total'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted mb-2">Present</h6>
                        <h3>{{ $stats['present'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted mb-2">Absent</h6>
                        <h3>{{ $stats['absent'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted mb-2">Late</h6>
                        <h3>{{ $stats['late'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-1">Summary</h5>
                        <small class="text-muted">{{ $className }} · {{ ucfirst($period) }} report</small>
                    </div>
                    <span class="badge bg-secondary">{{ \Carbon\Carbon::parse($startDate)->format('d M, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M, Y') }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
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
                                    <td colspan="5" class="text-center">No records found for this period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Attendance Details</h5>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
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
                                    <td colspan="3" class="text-center">No attendance details available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info text-center shadow-sm">Select a class first to view attendance reports.</div>
    @endif
</div>

<script>
    function toggleReportFields() {
        const period = document.getElementById('periodSelect').value;
        document.querySelectorAll('.period-field').forEach(el => el.classList.add('d-none'));
        document.querySelectorAll('.period-' + period).forEach(el => el.classList.remove('d-none'));
    }

    function loadStreams(classId, selectedStreamId = '') {
        const streamSelect = document.getElementById('streamSelect');
        streamSelect.innerHTML = '<option value="">Loading...</option>';

        if (!classId) {
            streamSelect.innerHTML = '<option value="">All Streams</option>';
            return;
        }

        fetch(`/academic/streams-by-class/${classId}`)
            .then(response => response.json())
            .then(data => {
                streamSelect.innerHTML = '<option value="">All Streams</option>';
                data.forEach(stream => {
                    const option = document.createElement('option');
                    option.value = stream.id;
                    option.textContent = stream.name;
                    if (selectedStreamId && selectedStreamId === String(stream.id)) {
                        option.selected = true;
                    }
                    streamSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading streams:', error);
                streamSelect.innerHTML = '<option value="">Error loading streams</option>';
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleReportFields();

        const preSelectedStream = '{{ $selectedStream ?? '' }}';
        const classSelect = document.getElementById('classSelect');
        classSelect.addEventListener('change', function() {
            loadStreams(this.value, '');
        });

        // Load streams if class is already selected
        if (classSelect.value) {
            loadStreams(classSelect.value, preSelectedStream);
        }
    });
</script>
@endsection
