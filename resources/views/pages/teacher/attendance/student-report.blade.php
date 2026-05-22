@extends('pages.teacher.layout.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title">Student Attendance Report</h2>
        <p class="text-muted mb-0">Detailed attendance history for {{ $student->full_name ?? ($student->first_name.' '.$student->last_name) }}.</p>
    </div>
    <a href="{{ route('teacher-attendance.review') }}" class="btn btn-outline-secondary btn-compact"><i class="fas fa-arrow-left me-2"></i>Back to Review</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-custom shadow-sm p-3">
            <div class="small text-uppercase text-muted mb-2">Student</div>
            <div class="fw-semibold">{{ $student->full_name ?? ($student->first_name.' '.$student->last_name) }}</div>
            <div class="small text-muted">{{ $student->admission_no ?? 'ID not set' }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-custom shadow-sm p-3">
            <div class="small text-uppercase text-muted mb-2">Class & Stream</div>
            <div class="fw-semibold">{{ optional($student->classData)->class_name ?? 'N/A' }} {{ optional($student->streamData)->name ?? '' }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-custom shadow-sm p-3">
            <div class="small text-uppercase text-muted mb-2">Total Records</div>
            <div class="fs-4 fw-semibold">{{ $attendances->count() }}</div>
        </div>
    </div>
</div>

<div class="card card-custom shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Class</th>
                    <th>Semester</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $attendance)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d M, Y') }}</td>
                        <td>
                            <span class="badge {{ $attendance->status == 'present' ? 'bg-success' : ($attendance->status == 'absent' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </td>
                        <td>{{ optional($attendance->schoolClass)->class_name ?? 'N/A' }}</td>
                        <td>{{ optional($attendance->semester)->name ?? 'N/A' }}</td>
                        <td>{{ $attendance->remarks ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No attendance records found for this student.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
