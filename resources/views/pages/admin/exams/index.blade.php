@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Exam Management</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('exam-reports.index') }}" class="btn btn-info">
                <i class="fas fa-chart-line me-1"></i> Student Reports
            </a>
            <a href="{{ route('admin.exams.create') }}" class="btn btn-primary ms-2">
                <i class="fas fa-plus"></i> Create Exam
            </a>
        </div>
    </div>

    @if($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Filters</h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label>Session</label>
                    <select name="session_id" class="form-select">
                        <option value="">All Sessions</option>
                        @foreach($sessions as $session)
                            <option value="{{ $session->id }}" {{ request('session_id') == $session->id ? 'selected' : '' }}>
                                {{ $session->title ?? $session->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Semester</label>
                    <select name="semester_id" class="form-select">
                        <option value="">All Semesters</option>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                                {{ $semester->title ?? $semester->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="DRAFT" {{ request('status') == 'DRAFT' ? 'selected' : '' }}>Draft</option>
                        <option value="ACTIVE" {{ request('status') == 'ACTIVE' ? 'selected' : '' }}>Active</option>
                        <option value="CLOSED" {{ request('status') == 'CLOSED' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive mt-4">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Exam Name</th>
                    <th>Type</th>
                    <th>Session</th>
                    <th>Semester</th>
                    <th>Total Marks</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($exams as $exam)
                    <tr>
                        <td><strong>{{ $exam->name }}</strong></td>
                        <td>
                            <span class="badge bg-info">{{ $exam->exam_type }}</span>
                        </td>
                        <td>{{ $exam->academicSession->title ?? $exam->academicSession->name ?? '-' }}</td>
                        <td>{{ $exam->semester->title ?? $exam->semester->name ?? '-' }}</td>
                        <td>{{ $exam->total_marks }}</td>
                        <td>
                            <span class="badge {{ $exam->getStatusBadgeAttribute() }}">
                                {{ $exam->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.exams.show', $exam->id) }}" class="btn btn-sm btn-info" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.exams.edit', $exam->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($exam->isDraft())
                                <form action="{{ route('admin.exams.publish', $exam->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Publish" onclick="return confirm('Publish this exam?')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.exams.destroy', $exam->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Delete this exam?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No exams found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($exams->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $exams->links() }}
        </div>
    @endif
</div>
@endsection
