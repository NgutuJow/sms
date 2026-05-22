@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Examination Details</h2>
            <p class="text-muted">View the examination configuration and status.</p>
        </div>
        <div>
            <a href="{{ route('examinations.index') }}" class="btn btn-secondary">Back to List</a>
            <a href="{{ route('examinations.edit', $examination->id) }}" class="btn btn-warning">Edit</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card p-4 mb-4">
                <h5 class="mb-3">Core Details</h5>
                <dl class="row">
                    <dt class="col-sm-4">Exam</dt>
                    <dd class="col-sm-8">{{ $examination->exam->name ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Class</dt>
                    <dd class="col-sm-8">{{ $examination->class->name ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Subject</dt>
                    <dd class="col-sm-8">{{ $examination->subject->name ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Teacher</dt>
                    <dd class="col-sm-8">{{ $examination->teacher->name ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Questions</dt>
                    <dd class="col-sm-8">{{ $examination->question_count }}</dd>

                    <dt class="col-sm-4">Duration</dt>
                    <dd class="col-sm-8">{{ $examination->duration_minutes }} minutes</dd>

                    <dt class="col-sm-4">Status</dt>
                    <dd class="col-sm-8">
                        <span class="badge bg-{{ $examination->status === 'PUBLISHED' ? 'success' : 'secondary' }}">
                            {{ $examination->status }}
                        </span>
                    </dd>
                    <dt class="col-sm-4">Published</dt>
                    <dd class="col-sm-8">{{ $examination->published_date ? \Carbon\Carbon::parse($examination->published_date)->format('d M Y H:i') : 'Not published' }}</dd>
                </dl>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card p-4">
                <h5 class="mb-3">Instructions</h5>
                <p class="text-muted">{{ $examination->instructions ?? 'No instructions provided.' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
