@extends('pages.teacher.layout.layout')

@section('content')

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-end mb-4">
    <div>
        <h5 class="fw-bold mb-0">Dashboard Overview</h5>
        <p class="text-muted mb-0" style="font-size: 13px;">
            Karibu tena, Mwl. <strong>{{ auth()->user()->name }} 
            @if(isset($className))
                — {{ $className }} {{ $streamName ? "($streamName)" : "" }}
            @endif
            </strong>.
        </p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary btn-compact shadow-sm bg-white">
            <i class="fa-solid fa-file-export me-1"></i> Reports
        </button>
        <button class="btn btn-primary btn-compact shadow-sm">
            <i class="fa-solid fa-plus me-1"></i> New Resource
        </button>
    </div>
</div>

<!-- Teacher Stats Grid -->
<div class="row g-3 mb-4">
    <!-- Attendance -->
    <div class="col-md-2">
        <div class="card-custom border-0 shadow-sm">
            <small class="text-muted fw-bold" style="font-size: 10px; text-transform: uppercase;">Attendance Today</small>
            <div class="h5 fw-bold mb-0 mt-1">{{ $stats['percent'] ?? 0 }}%</div>
            <div style="font-size: 10px;" class="text-success mt-1">
                <i class="fa-solid fa-user-check"></i> {{ $stats['present'] ?? 0 }} Students
            </div>
        </div>
    </div>
    
    <!-- Total Students -->
   <!-- Kadi ya Wanafunzi wa Darasa Zima -->


<!-- Kadi ya Wanafunzi wa Stream (Mkondo) -->
<div class="col-md-2">
    <div class="card-custom border-0 shadow-sm">
        <small class="text-primary fw-bold" style="font-size: 10px; text-transform: uppercase;">Students in Stream</small>
        <div class="h5 fw-bold mb-0 mt-1 text-primary">{{ $stats['stream_total'] ?? 0 }}</div>
        <div style="font-size: 10px;" class="text-muted mt-1">In Stream: {{ $streamName ?? '--' }}</div>
    </div>
</div>

    <!-- Active Exams -->
    <div class="col-md-2">
        <div class="card-custom border-0 shadow-sm">
            <small class="text-muted fw-bold" style="font-size: 10px; text-transform: uppercase;">Active Exams</small>
            <div class="h5 fw-bold mb-0 mt-1">{{ $upcomingExams ?? 0 }}</div>
            <div style="font-size: 10px;" class="text-{{ $upcomingExams > 0 ? 'danger' : 'muted' }} mt-1">
                {{ $upcomingExams > 0 ? 'Scheduled' : 'None' }}
            </div>
        </div>
    </div>

    <!-- Subjects Assigned -->
    <div class="col-md-2">
        <div class="card-custom border-0 shadow-sm">
            <small class="text-muted fw-bold" style="font-size: 10px; text-transform: uppercase;">Subjects</small>
            <div class="h5 fw-bold mb-0 mt-1">{{ count($subjects ?? []) }}</div>
            <div style="font-size: 10px;" class="text-info mt-1">
                @forelse($subjects ?? [] as $subject)
                    <div class="badge bg-info-subtle text-info me-1 mb-1">{{ $subject->subject_name ?? 'N/A' }}</div>
                @empty
                    <span class="text-muted">None assigned</span>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Current Class Session -->
    <div class="col-md-4">
        <div class="card-custom border-0 shadow-sm bg-primary bg-opacity-10 border-start border-primary border-4 rounded-3">
            <small class="text-primary fw-bold" style="font-size: 10px; text-transform: uppercase;">Current Class Session</small>
            {{-- Hapa ndio tumetumia Null Coalescing operator (??) kuzuia Error --}}
<div class="h6 fw-bold mb-0 mt-1 text-dark">
    {{ $className ?? 'No Class' }} 
   
</div>            <div style="font-size: 11px;" class="text-primary mt-1 fw-medium">
                <i class="fa-solid fa-layer-group me-1"></i> Streams: {{ $streamName ?? '--' }}
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Recent Student Activity -->
    <div class="col-md-8">
        <div class="card-custom border-0 shadow-sm p-0 overflow-hidden">
            <div class="px-3 py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0" style="font-size: 14px;">Recent Student Activity</h6>
                <button class="btn btn-link btn-sm text-decoration-none p-0" style="font-size: 12px;">View All Classes</button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size: 13px;">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3 py-2 fw-semibold text-muted border-0">Student Name</th>
                            <th class="py-2 fw-semibold text-muted border-0">Class</th>
                            <th class="py-2 fw-semibold text-muted border-0">Status</th>
                            <th class="pe-3 py-2 fw-semibold text-muted border-0 text-end">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Present Students -->
                        @forelse($attended ?? [] as $student)
                        <tr class="align-middle">
                            <td class="ps-3 py-2 fw-medium">{{ $student->first_name ?? '' }} {{ $student->last_name ?? '' }}</td>
                            <td class="py-2 text-muted">{{ $className ?? 'N/A' }}</td>
                            <td class="py-2">
                                <span class="badge bg-success-subtle text-success fw-normal px-2 py-1" style="font-size: 11px;">Present</span>
                            </td>
                            <td class="pe-3 py-2 text-end text-muted" style="font-size: 11px;">{{ date('d M') }}</td>
                        </tr>
                        @empty
                        @endforelse

                        <!-- Absent Students -->
                        @forelse($absent ?? [] as $student)
                        <tr class="align-middle">
                            <td class="ps-3 py-2 fw-medium">{{ $student->first_name ?? '' }} {{ $student->last_name ?? '' }}</td>
                            <td class="py-2 text-muted">{{ $className ?? 'N/A' }}</td>
                            <td class="py-2">
                                <span class="badge bg-danger-subtle text-danger fw-normal px-2 py-1" style="font-size: 11px;">Absent</span>
                            </td>
                            <td class="pe-3 py-2 text-end text-muted" style="font-size: 11px;">{{ date('d M') }}</td>
                        </tr>
                        @empty
                        @endforelse

                        <!-- Late Students -->
                        @forelse($late ?? [] as $student)
                        <tr class="align-middle">
                            <td class="ps-3 py-2 fw-medium">{{ $student->first_name ?? '' }} {{ $student->last_name ?? '' }}</td>
                            <td class="py-2 text-muted">{{ $className ?? 'N/A' }}</td>
                            <td class="py-2">
                                <span class="badge bg-warning-subtle text-warning fw-normal px-2 py-1" style="font-size: 11px;">Late</span>
                            </td>
                            <td class="pe-3 py-2 text-end text-muted" style="font-size: 11px;">{{ date('d M') }}</td>
                        </tr>
                        @empty
                        @endforelse

                        <!-- No records message -->
                        @if(empty($attended) && empty($absent) && empty($late))
                        <tr class="align-middle">
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i class="fa-solid fa-inbox me-2"></i> No attendance recorded yet
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Announcements -->
    <div class="col-md-4">
        <div class="card-custom border-0 shadow-sm">
            <h6 class="fw-bold mb-3" style="font-size: 14px;">Announcements</h6>
            
            @forelse($announcements ?? [] as $announcement)
            <div class="d-flex align-items-start gap-3 p-2 rounded-3 bg-light mb-2 border-bottom">
                <div class="bg-warning-subtle text-warning p-2 rounded-circle" style="font-size: 12px; width: 32px; height: 32px; text-align: center;">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <div style="width: 100%;">
                    <div class="fw-bold" style="font-size: 12px;">{{ $announcement->title }}</div>
                    <p class="text-muted mb-2" style="font-size: 11px; line-height: 1.4;">{{ Str::limit($announcement->description, 50) }}</p>
                    @if($announcement->pdf_path)
                        <a href="{{ route('announcements.download', $announcement->id) }}" class="btn btn-sm btn-link p-0" style="font-size: 10px;">
                            <i class="fa-solid fa-file-pdf text-danger me-1"></i> Download PDF
                        </a>
                    @endif
                </div>
            </div>
            @empty
            <div class="d-flex align-items-start gap-3 p-2 rounded-3 bg-light mb-2 border-bottom">
                <div class="bg-warning-subtle text-warning p-2 rounded-circle" style="font-size: 12px; width: 32px; height: 32px; text-align: center;">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <div>
                    <div class="fw-bold" style="font-size: 12px;">No Announcements</div>
                    <p class="text-muted mb-0" style="font-size: 11px; line-height: 1.4;">Hakuna tangazo mpya kwa sasa.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

@endsection