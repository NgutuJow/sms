@extends('layouts.app')

@section('content')
<!-- Import Fonts & Icons once in your layout if possible, otherwise keep here -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
    .page-header h2 { letter-spacing: -0.02em; color: #1e293b; }
    
    /* Modern Card & Table */
    .exam-card { border-radius: 16px; border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); overflow: hidden; background: white; }
    .table thead th { background-color: #f8fafc; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; color: #64748b; border-top: none; padding: 16px; }
    .table tbody td { padding: 16px; vertical-align: middle; color: #334155; border-bottom: 1px solid #f1f5f9; }
    
    /* Soft Badges */
    .badge-soft-primary { background-color: #e0e7ff; color: #4338ca; }
    .badge-soft-success { background-color: #dcfce7; color: #15803d; }
    .badge-soft-warning { background-color: #fef9c3; color: #a16207; }
    .badge-soft-secondary { background-color: #f1f5f9; color: #475569; }
    
    /* Action Buttons */
    .btn-action { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; transition: all 0.2s; border: 1px solid #e2e8f0; background: white; color: #64748b; }
    .btn-action:hover { background-color: #f8fafc; color: #0f172a; border-color: #cbd5e1; }
    .btn-action.delete:hover { background-color: #fef2f2; color: #dc2626; border-color: #fecaca; }
    
    /* Search/Filter placeholder area */
    .status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 6px; }
</style>

<div class="container-fluid py-4 px-lg-5">
    
    {{-- HEADER SECTION --}}
    <div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1">Examinations</h2>
            <p class="text-muted small mb-0">Manage examination metadata, schedules, and student performance.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="/exam-reports" class="btn btn-white border shadow-sm rounded-pill px-3 fw-semibold text-dark">
                <i class="bi bi-graph-up-arrow me-2"></i>View Reports
            </a>
            <a href="{{ route('exams.create') }}" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                <i class="bi bi-plus-lg me-2"></i>New Examination
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-3 fs-5"></i>
            <div class="fw-medium">{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- TABLE CARD --}}
    <div class="card exam-card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Examination Detail</th>
                        <th>Type</th>
                        <th>Schedule</th>
                        <th>Status</th>
                        <th class="text-center">Marks</th>
                        <th>Academic Period</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($examinations as $exam)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-light text-primary rounded-3 p-2 me-3">
                                    {{-- <i class="bi bi-journal-text fs-5"></i> --}}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $exam->name }}</div>
                                    <div class="text-muted mt-1" style="font-size: 11px;">
                                        <i class="bi bi-person me-1"></i> {{ $exam->createdBy->name ?? 'Administrator' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="badge badge-soft-primary px-2 py-1 uppercase" style="font-size: 10px;">
                                {{ strtoupper($exam->exam_type) }}
                            </span>
                        </td>

                        <td>
                            <div class="d-flex flex-column gap-1">
                                <span class="small"><i class="bi bi-calendar2-event me-2 text-muted"></i>{{ \Carbon\Carbon::parse($exam->start_date)->format('M d, Y') }}</span>
                                <span class="small text-muted"><i class="bi bi-arrow-right me-2 opacity-0"></i>{{ \Carbon\Carbon::parse($exam->end_date)->format('M d, Y') }}</span>
                            </div>
                        </td>

                        <td>
                            @php
                                $now = now();
                                $isActive = $now->between($exam->start_date, $exam->end_date);
                            @endphp

                            @if($isActive)
                                <span class="badge badge-soft-success rounded-pill px-3 py-1 fw-bold" style="font-size: 0.65rem;">
                                    <span class="status-dot bg-success"></span> ONGOING
                                </span>
                            @elseif($now->lt($exam->start_date))
                                <span class="badge badge-soft-warning rounded-pill px-3 py-1 fw-bold" style="font-size: 0.65rem;">
                                    <span class="status-dot bg-warning"></span> UPCOMING
                                </span>
                            @else
                                <span class="badge badge-soft-secondary rounded-pill px-3 py-1 fw-bold" style="font-size: 0.65rem;">
                                    <span class="status-dot bg-secondary"></span> COMPLETED
                                </span>
                            @endif
                        </td>

                        <td class="text-center">
                            <div class="fw-bold">{{ $exam->total_marks }}</div>
                            <div class="text-success" style="font-size: 11px;">Min: {{ $exam->passing_marks }}</div>
                        </td>

                        <td>
                            <div class="small fw-medium">{{ $exam->academicSession->name ?? 'N/A' }}</div>
                            <div class="text-muted small" style="font-size: 11px;">{{ is_object($exam->semester) ? $exam->semester->semester_name : ($exam->semester ?? 'N/A') }}</div>
                        </td>

                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('exams.classes', $exam->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold" style="font-size: 11px;">
                                    <i class="bi bi-people me-1"></i> Classes
                                </a>
                                
                                <a href="{{ route('exams.edit', $exam->id) }}" class="btn-action" title="Edit Schedule">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('exams.destroy', $exam->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Je, una uhakika unataka kufuta mtihani huu?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action delete" title="Remove">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="py-4">
                                <i class="bi bi-folder2-open display-4 text-light"></i>
                                <h6 class="text-muted fw-light mt-3">No examination available in system.</h6>
                                <a href="{{ route('exams.create') }}" class="btn btn-sm btn-primary mt-2">Anza kwa kutengeneza mpya</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection