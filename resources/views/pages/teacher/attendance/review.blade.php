@extends('pages.teacher.layout.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title">Attendance Review</h2>
        <p class="text-muted mb-0">Search attendance history and filter by date range, status, or student ID.</p>
    </div>
    <a href="{{ route('teacher-attendance.index') }}" class="btn btn-outline-primary btn-compact"><i class="fas fa-arrow-left me-2"></i>Back to Daily Attendance</a>
</div>

<div class="card card-custom shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('teacher-attendance.review') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-muted">Class</label>
                <input type="text" readonly class="form-control" value="{{ $className ?? ($classes->first()->class_name ?? 'N/A') }} {{ $streamName ? '• ' . $streamName : '' }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">From</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">To</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="present" {{ $status == 'present' ? 'selected' : '' }}>Present</option>
                    <option value="absent" {{ $status == 'absent' ? 'selected' : '' }}>Absent</option>
                    <option value="late" {{ $status == 'late' ? 'selected' : '' }}>Late</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">Student ID</label>
                <input type="text" name="student_id" value="{{ $studentId }}" class="form-control" placeholder="Search" />
            </div>
            <div class="col-md-1 text-end">
                <button type="submit" class="btn btn-primary btn-compact w-100"><i class="fas fa-filter"></i></button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-custom shadow-sm p-3 bg-success text-white">
            <div class="small text-uppercase opacity-75">Present</div>
            <div class="fs-4 fw-semibold">{{ $stats['present'] }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-custom shadow-sm p-3 bg-danger text-white">
            <div class="small text-uppercase opacity-75">Absent</div>
            <div class="fs-4 fw-semibold">{{ $stats['absent'] }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-custom shadow-sm p-3 bg-warning text-dark">
            <div class="small text-uppercase opacity-75">Late</div>
            <div class="fs-4 fw-semibold">{{ $stats['late'] }}</div>
        </div>
    </div>
</div>

<div class="card card-custom shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Student</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Class</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($record->date)->format('d M, Y') }}</td>
                        <td class="fw-semibold">{{ $record->student->full_name ?? ($record->student->first_name.' '.$record->student->last_name) }}</td>
                        <td>
                            <span class="badge {{ $record->status == 'present' ? 'bg-success' : ($record->status == 'absent' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                {{ ucfirst($record->status) }}
                            </span>
                        </td>
                        <td>{{ $record->remarks ?? '—' }}</td>
                        <td>
                            {{ $record->classesRelation->class_name ?? 'N/A' }}
                            @if(optional($record->student->streamData)->name)
                                • {{ optional($record->student->streamData)->name }}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">No attendance records found for the selected filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
