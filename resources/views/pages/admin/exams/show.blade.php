@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>{{ $exam->name }}</h2>
            <p class="text-muted">{{ $exam->exam_type }} | {{ $exam->academicSession->title ?? $exam->academicSession->name }}</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.exams.edit', $exam->id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Exam Details</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Status:</dt>
                        <dd class="col-sm-9">
                            <span class="badge {{ $exam->getStatusBadgeAttribute() }}">{{ $exam->status }}</span>
                        </dd>

                        <dt class="col-sm-3">Total Marks:</dt>
                        <dd class="col-sm-9">{{ $exam->total_marks }}</dd>

                        <dt class="col-sm-3">Passing Marks:</dt>
                        <dd class="col-sm-9">{{ $exam->passing_marks }}</dd>

                        <dt class="col-sm-3">Semester:</dt>
                        <dd class="col-sm-9">{{ $exam->semester->title ?? $exam->semester->name }}</dd>

                        <dt class="col-sm-3">Description:</dt>
                        <dd class="col-sm-9">{{ $exam->description ?? 'N/A' }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <h6>Marked Students</h6>
                            <h4>{{ $statistics['marked_students'] ?? 0 }}</h4>
                        </div>
                        <div class="col-md-3 text-center">
                            <h6>Average Marks</h6>
                            <h4>{{ round($statistics['average_marks'] ?? 0, 2) }}</h4>
                        </div>
                        <div class="col-md-3 text-center">
                            <h6>Highest Marks</h6>
                            <h4>{{ $statistics['highest_marks'] ?? 0 }}</h4>
                        </div>
                        <div class="col-md-3 text-center">
                            <h6>Lowest Marks</h6>
                            <h4>{{ $statistics['lowest_marks'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    @if($exam->isDraft())
                        <form action="{{ route('admin.exams.publish', $exam->id) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Publish this exam?')">
                                <i class="fas fa-check"></i> Publish Exam
                            </button>
                        </form>
                    @endif

                    @if($exam->isActive())
                        <form action="{{ route('admin.exams.close', $exam->id) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100" onclick="return confirm('Close this exam?')">
                                <i class="fas fa-lock"></i> Close Exam
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.exams.marks', $exam->id) }}" class="btn btn-info w-100 mb-2">
                        <i class="fas fa-list"></i> View Marks
                    </a>

                    <a href="{{ route('admin.exams.report', $exam->id) }}" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-chart-bar"></i> View Report
                    </a>

                    <form action="{{ route('admin.exams.destroy', $exam->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Delete this exam?')">
                            <i class="fas fa-trash"></i> Delete Exam
                        </button>
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Classes</h5>
                </div>
                <div class="card-body">
                    @forelse($exam->classes as $class)
                        <span class="badge bg-secondary">{{ $class->name }}</span>
                    @empty
                        <p class="text-muted mb-0">No classes assigned</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @if($class_reports)
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Class-wise Reports</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Class</th>
                            <th>Average Marks</th>
                            <th>Highest Marks</th>
                            <th>Lowest Marks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($class_reports as $report)
                            <tr>
                                <td>{{ $report['class']->name }}</td>
                                <td>{{ round($report['statistics']['average_marks'] ?? 0, 2) }}</td>
                                <td>{{ $report['statistics']['highest_marks'] ?? 0 }}</td>
                                <td>{{ $report['statistics']['lowest_marks'] ?? 0 }}</td>
                                <td>
                                    <a href="{{ route('admin.exams.class-report', [$exam->id, $report['class']->id]) }}" class="btn btn-xs btn-primary">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
