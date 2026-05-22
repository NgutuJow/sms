@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<div class="container-fluid py-4 px-lg-5" style="font-family: 'Inter', sans-serif; background-color: #f8f9fa; min-height: 100vh;">
    
    {{-- TOP NAVIGATION & SUMMARY --}}
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h3 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Examination Approval</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item small"><a href="{{ route('exams.index') }}" class="text-decoration-none text-muted">Examinations</a></li>
                    <li class="breadcrumb-item small active text-primary fw-medium">{{ $exam->name }}</li>
                </ol>
            </nav>
            <div class="mt-2 d-flex gap-2">
                <span class="badge bg-white text-dark border shadow-sm px-3 py-2 rounded-pill small">
                    <i class="bi bi-calendar-event text-primary me-1"></i> {{ $exam->academicSession->name ?? 'N/A' }}
                </span>
                <span class="badge bg-white text-dark border shadow-sm px-3 py-2 rounded-pill small">
                    <i class="bi bi-clock-history text-primary me-1"></i> 
                    {{-- Hapa nimebadilisha ili isilete array ya JSON --}}
                    {{ is_object($exam->semester) ? $exam->semester->semester_name : ($exam->semester ?? 'Semester N/A') }}
                </span>
            </div>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <div class="d-inline-flex gap-2 w-100 justify-content-md-end">
                <div class="input-group input-group-sm w-50 shadow-sm rounded-pill overflow-hidden border-0">
                    <span class="input-group-text bg-white border-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" id="classSearch" class="form-control border-0 py-2" placeholder="Search class name...">
                </div>
                <a href="{{ route('exams.index') }}" class="btn btn-white border shadow-sm rounded-pill px-4 btn-sm fw-semibold bg-white">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>
    </div>

    {{-- GRID YA MADARASA --}}
    <div class="row g-4" id="classGrid">
        @forelse($examClasses as $class)
        <div class="col-12 col-md-6 col-xl-4 class-card-container">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                
                <div class="card-body p-4">
                    {{-- Class Header --}}
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-light">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 me-3">
                                <i class="bi bi-mortarboard-fill fs-5"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-0 class-name">{{ $class->class_name }}</h5>
                        </div>
                        <span class="badge bg-light text-muted px-2 py-1 small">{{ $class->subjects->count() }} Subjects</span>
                    </div>

                    {{-- List ya Masomo --}}
                    <div class="subject-list">
                        @forelse($class->subjects as $subject)
                            @php
                                $paper = \App\Models\ExamPaper::where('exam_id', $exam->id)
                                            ->where('subject_id', $subject->id)
                                            ->where('class_id', $class->id)
                                            ->first();
                            @endphp

                            <div class="p-3 mb-3 rounded-3 border bg-white subject-item shadow-none position-relative">
                                
                                {{-- Status Badge (Uncommented and Functional) --}}
                                <div class="position-absolute" style="top: 10px; right: 10px;">
                                    @if($paper)
                                        @if($paper->status == 'approved')
                                            <span class="badge bg-success-subtle text-success border border-success border-opacity-25" style="font-size: 8px;">
                                                <i class="bi bi-check-circle-fill"></i> APPROVED
                                            </span>
                                        @elseif($paper->status == 'denied')
                                            <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-25" style="font-size: 8px;">
                                                <i class="bi bi-x-circle-fill"></i> DENIED
                                            </span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-25" style="font-size: 8px;">
                                                <i class="bi bi-clock-fill"></i> PENDING
                                            </span>
                                        @endif
                                    @endif
                                </div>

                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="overflow-hidden">
                                        <h6 class="mb-0 fw-bold text-dark text-truncate" style="font-size: 0.9rem; max-width: 160px;">
                                            {{ $subject->subject_name }}
                                        </h6>
                                        <div class="text-muted" style="font-size: 11px;">
                                            <i class="bi bi-person me-1"></i> {{ $subject->teacher->full_name ?? 'Not Assigned' }}
                                        </div>
                                    </div>
                                    
                                    @if($paper)
                                        <a href="{{ asset('storage/' . $paper->file_path) }}" target="_blank" 
                                           class="btn btn-white btn-sm border shadow-sm rounded-circle p-1" title="View PDF">
                                            <i class="bi bi-file-pdf text-danger fs-6"></i>
                                        </a>
                                    @endif
                                </div>

                                {{-- Manage Link Logic (Uncommented) --}}
                                <div class="mt-2 mb-3 py-1">
                                    @if($paper && $paper->status == 'approved')
                                        <a href="{{ route('exams.manage.paper', $paper->id) }}" class="text-primary fw-bold text-decoration-none" style="font-size: 11px;">
                                            <i class="bi bi-arrow-right-circle-fill me-1"></i> MANAGE EXAM SESSION
                                        </a>
                                    @else
                                        <span class="text-muted opacity-50" style="font-size: 11px; cursor: not-allowed;">
                                            <i class="bi bi-lock-fill me-1"></i> Manage (Requires Approval)
                                        </span>
                                    @endif
                                </div>

                                {{-- Action Buttons (Uncommented) --}}
                                <div class="row g-2">
                                    <div class="col-6">
                                        <form action="{{ route('exams.subject.approve', [$exam->id, $class->id, $subject->id]) }}" method="POST">
                                            @csrf
                                            <button class="btn {{ ($paper && $paper->status == 'approved') ? 'btn-success' : 'btn-outline-success' }} btn-sm w-100 fw-bold py-2" 
                                                    style="font-size: 10px;" {{ !$paper ? 'disabled' : '' }}>
                                                {{ ($paper && $paper->status == 'approved') ? 'APPROVED' : 'APPROVE' }}
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-6">
                                        <form action="{{ route('exams.subject.deny', [$exam->id, $class->id, $subject->id]) }}" method="POST">
                                            @csrf
                                            <button class="btn {{ ($paper && $paper->status == 'denied') ? 'btn-danger' : 'btn-outline-danger' }} btn-sm w-100 fw-bold py-2" 
                                                    style="font-size: 10px;" {{ !$paper ? 'disabled' : '' }}>
                                                {{ ($paper && $paper->status == 'denied') ? 'DENIED' : 'DENY' }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-muted small">No subjects assigned to this class.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <img src="https://illustrations.popsy.co/gray/empty-folder.svg" alt="No data" style="width: 180px;" class="mb-3 opacity-50">
            <h5 class="text-muted fw-light">No classes found in this session.</h5>
        </div>
        @endforelse
    </div>
</div>

<style>
    body { background-color: #f8f9fa; }
    .card:hover { transform: translateY(-5px); transition: all 0.3s ease; box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important; }
    .subject-item { transition: all 0.2s; border-color: #f1f3f5 !important; }
    .subject-item:hover { border-color: #0d6efd !important; background-color: #fcfdfe !important; }
    .bg-success-subtle { background-color: #e6fffa !important; }
    .bg-danger-subtle { background-color: #fff5f5 !important; }
    .bg-warning-subtle { background-color: #fffaf0 !important; }
    .btn-white { background-color: #fff; }
    .btn-white:hover { background-color: #f8f9fa; }
    .breadcrumb-item + .breadcrumb-item::before { content: "›"; font-size: 1.2rem; }
</style>

<script>
    document.getElementById('classSearch').addEventListener('keyup', function() {
        let searchValue = this.value.toLowerCase();
        let cards = document.querySelectorAll('.class-card-container');
        
        cards.forEach(card => {
            let className = card.querySelector('.class-name').textContent.toLowerCase();
            card.style.display = className.includes(searchValue) ? "" : "none";
        });
    });
</script>
@endsection