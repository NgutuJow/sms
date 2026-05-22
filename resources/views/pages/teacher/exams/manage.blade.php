@extends('pages.teacher.layout.layout')

@section('content')
<!-- Google Fonts & FontAwesome -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container-fluid py-4" style="font-family: 'Inter', sans-serif; background-color: #f8f9fa; min-height: 100vh;">
    
    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background-color: #ecfdf5; color: #065f46;">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Header Section --}}
    <div class="row align-items-center mb-4 pb-3 border-bottom g-3">
        <div class="col-md-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1" style="font-size: 11px; font-weight: 500;">
                    <li class="breadcrumb-item"><a href="{{ route('teacher-exams.index') }}" class="text-decoration-none text-primary text-uppercase">Examinations</a></li>
                    <li class="breadcrumb-item active text-muted text-uppercase">{{ $exam->name }}</li>
                </ol>
            </nav>
            <h4 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Paper & Result Management</h4>
            <p class="text-muted mb-0 small">
                <i class="fa-solid fa-calendar-day me-1"></i> Academic Session: <span class="fw-semibold text-dark">2026/2027</span> 
                <span class="mx-2 text-silver">|</span> 
                <i class="fa-solid fa-tag me-1"></i> Type: <span class="badge bg-light text-primary border">{{ $exam->exam_type }}</span>
            </p>
        </div>
        <div class="col-md-5 text-md-end">
            @php
                $now = now();
                // Hapa tunahakikisha session ya 2026/2027 inaonekana active
                $is2026Session = (str_contains($exam->academicSession->name ?? '', '2026') || str_contains($exam->academicSession->name ?? '', '2027'));
                $isActive = $now->between($exam->start_date, $exam->end_date) || $is2026Session;
            @endphp
            
            @if($isActive)
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-4 py-2 rounded-pill fw-bold" style="font-size: 11px;">
                    <i class="fa-solid fa-circle-dot me-1 fa-beat-flash"></i> 2026/2027 ACTIVE SESSION
                </span>
            @else
                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-4 py-2 rounded-pill fw-bold" style="font-size: 11px;">
                    <i class="fa-solid fa-circle-xmark me-1"></i> INACTIVE SESSION
                </span>
            @endif
        </div>
    </div>

    {{-- Main List Card --}}
    <div class="card border-0 shadow-sm shadow-hover" style="border-radius: 16px; border: 1px solid rgba(0,0,0,0.05) !important;">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr class="bg-light bg-opacity-50">
                        <th class="ps-4 py-3 border-0 text-muted fw-bold small text-uppercase" style="letter-spacing: 0.5px;">Subject Info</th>
                        <th class="py-3 border-0 text-center text-muted fw-bold small text-uppercase">Status</th>
                        <th class="py-3 border-0 text-center text-muted fw-bold small text-uppercase">Schedule</th>
                        <th class="py-3 border-0 text-center text-muted fw-bold small text-uppercase">Timeline</th>
                        <th class="pe-4 py-3 border-0 text-end text-muted fw-bold small text-uppercase">Management</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mySubjects as $subject)
                        @php $paper = $subject->examPapers->where('exam_id', $exam->id)->first(); @endphp
                        <tr>
                            <td class="ps-4 py-4">
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                        <i class="fa-solid fa-book-open-reader fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark">{{ $subject->subject_name }}</h6>
                                        <span class="text-muted small fw-medium">{{ $subject->schoolClass->class_name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($paper)
                                    <span class="badge rounded-pill px-3 py-2 fw-bold" style="background-color: #dcfce7; color: #15803d; font-size: 10px;">
                                        <i class="fa-solid fa-check-circle me-1"></i> PAPER UPLOADED
                                    </span>
                                @else
                                    <span class="badge rounded-pill px-3 py-2 fw-bold" style="background-color: #fee2e2; color: #b91c1c; font-size: 10px;">
                                        <i class="fa-solid fa-clock me-1"></i> PAPER PENDING
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($paper)
                                    <div class="d-inline-block text-start">
                                        <div class="small fw-bold text-dark"><i class="fa-regular fa-calendar-check text-primary me-1"></i> {{ \Carbon\Carbon::parse($paper->start_date)->format('d M, Y') }}</div>
                                        <div class="text-muted" style="font-size: 11px;"><i class="fa-regular fa-clock me-1"></i> {{ \Carbon\Carbon::parse($paper->start_date)->format('H:i') }} - {{ \Carbon\Carbon::parse($paper->end_date)->format('H:i') }}</div>
                                    </div>
                                @else
                                    <span class="text-muted small fst-italic">Not scheduled</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <small class="text-muted d-block" style="font-size: 11px;">Date Modified:</small>
                                <small class="fw-bold text-dark">{{ $paper ? $paper->updated_at->format('d M, Y') : '--' }}</small>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    {{-- Result Upload Button --}}
                                    <a href="{{ route('teacher-exams.results', [$exam->id, $subject->id]) }}"
                                       class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold" style="font-size: 11px;">
                                        <i class="fa-solid fa-file-invoice me-1"></i> UPLOAD RESULTS
                                    </a>

                                    {{-- NEW: View Report PDF Button --}}
                                    <a href="{{ route('teacher-exams.results.report', [$exam->id, $subject->id]) }}"
                                       class="btn btn-sm btn-outline-info rounded-pill px-3 fw-bold" style="font-size: 11px;">
                                        <i class="fa-solid fa-file-pdf me-1"></i> VIEW REPORT
                                    </a>

                                    @if(!$paper)
                                        <button class="btn btn-primary btn-sm rounded-pill px-3 fw-bold shadow-sm" 
                                                style="font-size: 11px;"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#uploadModal{{ $subject->id }}">
                                            <i class="fa-solid fa-cloud-arrow-up me-1"></i> UPLOAD PAPER
                                        </button>
                                    @else
                                        <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                            <a href="{{ asset('storage/' . $paper->file_path) }}" target="_blank" 
                                               class="btn btn-white btn-sm border-end px-3" title="View Paper">
                                                <i class="fa-solid fa-eye text-primary"></i>
                                            </a>
                                            <form action="{{ route('teacher-exams.paper.destroy', $paper->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Futa paper hii?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-white btn-sm px-3" title="Delete">
                                                    <i class="fa-solid fa-trash-can text-danger"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Content remains similar but styled --}}
                        <!-- (Modal code stays here, just update button classes to match new style) -->
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fa-solid fa-folder-open text-muted mb-3" style="font-size: 40px; opacity: 0.3;"></i>
                                    <p class="text-muted fw-medium">No subjects found for this examination.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .shadow-hover { transition: all 0.3s ease; }
    .shadow-hover:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important; }
    .icon-shape { transition: all 0.3s ease; }
    tr:hover .icon-shape { background-color: #0d6efd !important; color: white !important; }
    .btn-white { background-color: white; border: 1px solid #edf2f7; }
    .btn-white:hover { background-color: #f8fafc; border-color: #cbd5e0; }
    .text-silver { color: #cbd5e0; }
    .table thead th { border-bottom: 1px solid #edf2f7 !important; }
    .table tbody td { border-bottom: 1px solid #f8fafc !important; }
</style>
@endsection