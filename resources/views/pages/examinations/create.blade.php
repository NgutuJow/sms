@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-lg-5">
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Create Examination</h2>
            <p class="text-muted small mb-0">Define exam metadata and schedule for classes.</p>
        </div>
        <a href="{{ route('exams.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-medium">
            <i class="bi bi-arrow-left me-2"></i>Back to List
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <ul class="mb-0 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM CARD --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('exams.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    {{-- Basic Info --}}
                    <div class="col-12 mb-2">
                        <h6 class="text-uppercase fw-bold text-primary small mb-0">Basic Information</h6>
                        <hr class="mt-2">
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-bold small">Examination Name</label>
                        <input type="text" name="name" class="form-control form-control-lg bg-light border-0 shadow-none" 
                               placeholder="e.g. Mid-Term Examination 2026" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold small">Exam Type</label>
                        <select name="exam_type" class="form-select form-control-lg bg-light border-0 shadow-none" required>
                            <option value="">Select Type</option>
                            <option value="Mid-Term">Mid-Term</option>
                            <option value="Terminal">Terminal</option>
                            <option value="Annual">Annual</option>
                            <option value="Quiz">Quiz</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold small">Description / Instructions</label>
                        <textarea name="description" class="form-control bg-light border-0 shadow-none" rows="3" 
                                  placeholder="Briefly describe the rules of this exam...">{{ old('description') }}</textarea>
                    </div>

                    {{-- Schedule & Marks --}}
                    <div class="col-12 mt-5 mb-2">
                        <h6 class="text-uppercase fw-bold text-primary small mb-0">Schedule & Grading</h6>
                        <hr class="mt-2">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold small">Start Date</label>
                        <input type="date" name="start_date" class="form-control bg-light border-0 shadow-none" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold small">End Date</label>
                        <input type="date" name="end_date" class="form-control bg-light border-0 shadow-none" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold small">Total Marks</label>
                        <input type="number" name="total_marks" class="form-control bg-light border-0 shadow-none" placeholder="100" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold small">Passing Marks</label>
                        <input type="number" name="passing_marks" class="form-control bg-light border-0 shadow-none" placeholder="40" required>
                    </div>

                    {{-- Linking --}}
                    <div class="col-12 mt-5 mb-2">
                        <h6 class="text-uppercase fw-bold text-primary small mb-0">Academic Linkage</h6>
                        <hr class="mt-2">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small">Academic Session</label>
                        <select name="academic_session_id" class="form-select bg-light border-0 shadow-none" required>
                            <option value="">Select Session</option>
                            @foreach($sessions as $session)
                                <option value="{{ $session->id }}">{{ $session->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small">Semester</label>
                        <select name="semester_id" class="form-select bg-light border-0 shadow-none" required>
                            <option value="">Select Semester</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}">{{ $semester->semester_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 mt-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" id="status" value="active" checked>
                            <label class="form-check-label fw-bold small" for="status">Set as Active</label>
                        </div>
                    </div>
                </div>

                <div class="mt-5 border-top pt-4 text-end">
                    <button type="reset" class="btn btn-light px-4 me-2 rounded-pill fw-medium">Reset</button>
                    <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold">
                        <i class="bi bi-check-circle me-2"></i>Save Examination
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-control-lg, .form-select-lg {
        font-size: 0.95rem;
    }
    input::placeholder, textarea::placeholder {
        color: #adb5bd !important;
        font-size: 0.85rem;
    }
    .form-label {
        color: #495057;
    }
</style>
@endsection