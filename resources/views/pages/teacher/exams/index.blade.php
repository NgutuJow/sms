@extends('pages.teacher.layout.layout')

@section('content')
<div class="container-fluid py-3">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-0">Assigned Examinations</h5>
            <p class="text-muted" style="font-size: 12px;">Manage papers and grades for your specific subjects.</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($exams as $exam)
            @php
                $now = now();
                $startDate = \Carbon\Carbon::parse($exam->start_date);
                $endDate = \Carbon\Carbon::parse($exam->end_date);
                
                // PROGRESS LOGIC (Kama picha yako ilivyo)
                if ($now->lt($startDate)) {
                    $progressText = 'UPCOMING';
                    $progressClass = 'warning';
                } elseif ($now->between($startDate, $endDate)) {
                    $progressText = 'ONGOING';
                    $progressClass = 'success';
                } else {
                    $progressText = 'COMPLETED';
                    $progressClass = 'secondary';
                }
            @endphp

            <div class="col-12">
                <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 10px;">
                    <div class="card-body p-0">
                        {{-- Row Moja Inayofanana na Table Header ya Picha Yako --}}
                        <div class="row g-0 align-items-center p-3 border-bottom bg-light bg-opacity-50 d-none d-lg-flex">
                            <div class="col-lg-3"><small class="text-muted fw-bold">EXAMINATION NAME</small></div>
                            <div class="col-lg-2 text-center"><small class="text-muted fw-bold">PROGRESS</small></div>
                            <div class="col-lg-2 text-center"><small class="text-muted fw-bold">MARKS (TOTAL/PASS)</small></div>
                            <div class="col-lg-2 text-center"><small class="text-muted fw-bold">SESSION/SEMESTER</small></div>
                            <div class="col-lg-1 text-center"><small class="text-muted fw-bold">STATUS</small></div>
                            <div class="col-lg-2 text-end"><small class="text-muted fw-bold">ACTIONS</small></div>
                        </div>

                        {{-- Data Row --}}
                        <div class="row g-0 align-items-center p-4">
                            <div class="col-lg-3">
                                <h6 class="fw-bold text-dark mb-1">{{ $exam->name }}</h6>
                                <div class="d-flex gap-2 align-items-center">
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1" style="font-size: 10px;">{{ $exam->exam_type }}</span>
                                    <small class="text-muted text-truncate" style="max-width: 150px;">{{ $exam->description }}</small>
                                </div>
                            </div>

                            <div class="col-lg-2 text-center mt-3 mt-lg-0">
                                <span class="badge bg-{{ $progressClass }} bg-opacity-10 text-{{ $progressClass }} px-3 py-2 rounded-pill fw-bold" style="font-size: 10px;">
                                    ● {{ $progressText }}
                                </span>
                                <div class="mt-1" style="font-size: 10px; color: #dc3545;">
                                    {{ $startDate->format('Y-m-d') }} <br> 
                                    <span class="text-muted">to</span> {{ $endDate->format('Y-m-d') }}
                                </div>
                            </div>

                            <div class="col-lg-2 text-center mt-3 mt-lg-0">
                                <span class="fw-bold text-dark fs-6">{{ $exam->total_marks }}</span>
                                <span class="text-success fw-bold mx-1">/</span>
                                <span class="text-success fw-bold">{{ $exam->passing_marks }}</span>
                            </div>

                            <div class="col-lg-2 text-center mt-3 mt-lg-0">
                                <div class="fw-medium text-dark" style="font-size: 13px;">{{ $exam->academicSession->name ?? '2026/2027' }}</div>
                                <small class="text-muted">{{ $exam->semester->semester_name ?? 'Semester 1' }}</small>
                            </div>

                            <div class="col-lg-1 text-center mt-3 mt-lg-0">
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">
                                    ACTIVE
                                </span>
                            </div>

                            <div class="col-lg-2 text-end mt-3 mt-lg-0">
    <a href="{{ route('teacher-exams.manage', $exam->id) }}" 
       class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm d-inline-flex align-items-center gap-2" 
       style="font-size: 11px;">
        <i class="fa-solid fa-gears"></i> MANAGE
    </a>
</div>
                        </div>

                        {{-- Section ya Masomo Yako Chini (Tuonyeshe kwa kifupi) --}}
                        <div class="px-4 pb-4">
                            <div class="p-3 rounded-3 border bg-light bg-opacity-10">
                                <small class="text-muted fw-bold d-block mb-2">MY ASSIGNED SUBJECTS IN THIS EXAM:</small>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($myClasses as $class)
                                        @foreach($class->subjects as $subject)
                                            <div class="bg-white border rounded px-3 py-2 d-flex align-items-center">
                                                <i class="fa-solid fa-book-open text-primary me-2" style="font-size: 12px;"></i>
                                                <span class="fw-semibold text-dark" style="font-size: 12px;">{{ $subject->subject_name }}</span>
                                                <span class="mx-2 text-muted">|</span>
                                                <span class="text-muted" style="font-size: 11px;">{{ $class->class_name }}</span>
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">No examinations assigned yet.</p>
            </div>
        @endforelse
    </div>
</div>

<style>
    .badge { border-radius: 4px; }
    .card { transition: transform 0.2s; }
    .card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; }
</style>
@endsection