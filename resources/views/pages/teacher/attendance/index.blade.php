@extends('pages.teacher.layout.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title">Attendance Management</h2>
        <p class="text-muted mb-0">Mark daily attendance for <strong>{{ $className ?? 'Not Configured' }}</strong> {{ ($streamName ?? false) ? '• ' . $streamName : '' }}.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('teacher-attendance.reports') }}" class="btn btn-outline-primary btn-compact"><i class="fas fa-chart-line me-2"></i>Attendance Reports</a>
        <a href="{{ route('teacher-attendance.review') }}" class="btn btn-outline-secondary btn-compact"><i class="fas fa-search me-2"></i>Review History</a>
    </div>
</div>

@if($errors && count($errors) > 0)
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Configuration Issues:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($message = Session::get('error'))
    <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-custom shadow-sm p-3">
            <div class="small text-uppercase text-muted mb-2">Academic Session</div>
            <div class="fw-semibold">{{ optional($session)->name ?? 'N/A' }}</div>
            <div class="small text-muted">Semester: {{ optional($semester)->name ?? 'N/A' }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-custom shadow-sm p-3">
            <div class="small text-uppercase text-muted mb-2">Selected Date</div>
            <div class="fw-semibold">{{ \Carbon\Carbon::parse($date ?? date('Y-m-d'))->format('d M, Y') }}</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-custom shadow-sm p-3 bg-success text-white">
            <div class="small text-uppercase opacity-75 mb-2">Present</div>
            <div class="fs-4 fw-semibold">{{ $stats['present'] ?? 0 }}</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-custom shadow-sm p-3 bg-danger text-white">
            <div class="small text-uppercase opacity-75 mb-2">Absent</div>
            <div class="fs-4 fw-semibold">{{ $stats['absent'] ?? 0 }}</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-custom shadow-sm p-3 bg-info text-white">
            <div class="small text-uppercase opacity-75 mb-2">Attendance Rate</div>
            <div class="fs-4 fw-semibold">{{ $stats['percent'] ?? 0 }}%</div>
        </div>
    </div>
</div>

<div class="card card-custom shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('teacher-attendance.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted">Record Date</label>
                <input type="date" name="date" value="{{ $date ?? date('Y-m-d') }}" class="form-control" onchange="this.form.submit()">
            </div>
            <div class="col-md-8 text-end">
                <p class="mb-0 text-muted">Use the date picker to switch the attendance record for any day. Only students in your assigned class and stream appear here.</p>
            </div>
        </form>
    </div>
</div>

<div class="card card-custom shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('teacher-attendance.store') }}" method="POST">
            @csrf
            <input type="hidden" name="class_id" value="{{ $selectedClass ?? '' }}">
            <input type="hidden" name="date" value="{{ $date ?? date('Y-m-d') }}">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Gender</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $index => $student)
                            @php
                                $record = $attendances->get($student->id);
                                $currentStatus = $record ? $record->status : 'absent';
                                $isMarked = (bool) $record;
                            @endphp
                            <tr class="{{ $isMarked ? 'border-start border-4 border-success' : '' }}">
                                <td class="fw-semibold">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $student->image ? asset('uploads/students/'.$student->image) : asset('assets/images/user.png') }}" class="rounded-circle" width="42" height="42" alt="Student image">
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $student->full_name ?? trim($student->first_name.' '.$student->middle_name.' '.$student->last_name) }}</div>
                                            <small class="text-muted">{{ optional($student->streamData)->name ?? 'Stream not assigned' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-secondary bg-opacity-10 text-secondary">{{ ucfirst($student->gender) }}</span></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <input type="radio" class="btn-check" name="attendance[{{ $student->id }}]" id="present_{{ $student->id }}" value="present" {{ $currentStatus === 'present' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success btn-sm" for="present_{{ $student->id }}">Present</label>

                                        <input type="radio" class="btn-check" name="attendance[{{ $student->id }}]" id="absent_{{ $student->id }}" value="absent" {{ $currentStatus === 'absent' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-danger btn-sm" for="absent_{{ $student->id }}">Absent</label>

                                        <input type="radio" class="btn-check" name="attendance[{{ $student->id }}]" id="late_{{ $student->id }}" value="late" {{ $currentStatus === 'late' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-warning btn-sm" for="late_{{ $student->id }}">Late</label>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="remarks[{{ $student->id }}]" value="{{ old('remarks.'.$student->id, $record->remarks ?? '') }}" class="form-control form-control-sm" placeholder="Optional remarks">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">No students found for this assignment.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($students->count())
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary btn-compact px-4"><i class="fas fa-save me-2"></i>Save Attendance</button>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection
