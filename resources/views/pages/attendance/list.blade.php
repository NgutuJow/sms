@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold"><i class="fas fa-clipboard-check text-primary"></i> Attendance Review</h4>
        <a href="{{ route('academic.attendance.index') }}" class="btn btn-outline-secondary btn-sm">Back to Marking</a>
    </div>

    {{-- FILTER BAR --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('academic.attendance.review') }}" class="row g-2">
                <div class="col-md-4">
                    <select name="class_id" class="form-select" required>
                        <option value="">Select Class...</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="date" value="{{ $date }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Review List</button>
                </div>
            </form>
        </div>
    </div>

    @if($selectedClass)
    <div class="row">
        {{-- LIST YA WALIOHUDHURIA --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">✅ Attended ({{ count($attended) }})</div>
                <ul class="list-group list-group-flush">
                    @forelse($attended as $record)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $record->student->name }}</span>
                            <form action="{{ route('academic.attendance.update_status') }}" method="POST">
                                @csrf
                                <input type="hidden" name="student_id" value="{{ $record->student_id }}">
                                <input type="hidden" name="date" value="{{ $date }}">
                                <input type="hidden" name="status" value="absent">
                                <button class="btn btn-link btn-sm text-danger p-0" title="Change to Absent">Mark Absent</button>
                            </form>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No students present.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- LIST YA ABSENTEES --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-danger text-white">❌ Absent ({{ count($absent) }})</div>
                <ul class="list-group list-group-flush">
                    @forelse($absent as $record)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $record->student->name }}</span>
                            <form action="{{ route('academic.attendance.update_status') }}" method="POST">
                                @csrf
                                <input type="hidden" name="student_id" value="{{ $record->student_id }}">
                                <input type="hidden" name="date" value="{{ $date }}">
                                <input type="hidden" name="status" value="present">
                                <button class="btn btn-link btn-sm text-success p-0" title="Change to Present">Mark Present</button>
                            </form>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Everyone is present!</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- LIST YA LATE COMERS --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark">⏰ Late ({{ count($late) }})</div>
                <ul class="list-group list-group-flush">
                    @forelse($late as $record)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $record->student->name }}</span>
                            <form action="{{ route('academic.attendance.update_status') }}" method="POST">
                                @csrf
                                <input type="hidden" name="student_id" value="{{ $record->student_id }}">
                                <input type="hidden" name="date" value="{{ $date }}">
                                <input type="hidden" name="status" value="present">
                                <button class="btn btn-link btn-sm text-success p-0" title="Change to Present">Mark Present</button>
                            </form>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No late students.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-search fa-3x text-muted mb-3"></i>
        <p class="text-muted">Select a class and date to review the attendance list.</p>
    </div>
    @endif
</div>
@endsection