@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    
    {{-- 1. HEADER & ACTIVE SESSION INFO --}}
    <div class="row align-items-center mb-4">
        <div class="col">
            <h3 class="fw-bold text-dark mb-0">Academic & Curriculum Hub</h3>
            <p class="text-muted small mb-0">Manage school sessions, classes, subjects, and curriculum documents.</p>
        </div>
        <div class="col-auto">
            <div class="d-flex gap-2">
                <div class="bg-white border rounded-3 p-2 px-3 shadow-sm text-center">
                    <div class="text-muted x-small fw-bold text-uppercase">Current Session</div>
                    <div class="fw-bold text-success">
                        {{ $sessions->where('is_current', true)->first()->name ?? 'No Active Session' }}
                    </div>
                </div>
                <div class="bg-white border rounded-3 p-2 px-3 shadow-sm text-center">
                    <div class="text-muted x-small fw-bold text-uppercase">Current Semester</div>
                    <div class="fw-bold text-info">
                        {{ $activeSemester->semester_name ?? 'No Active Semester' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. NOTIFICATIONS (ALERTS) --}}
    <div class="alerts-wrapper">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4 py-3 animate__animated animate__fadeIn" style="border-radius: 12px; border-left: 5px solid #2ecc71 !important;">
                <i class="fas fa-check-circle me-3 fs-4 text-success"></i>
                <div class="fw-medium">{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4 py-3" style="border-radius: 12px; border-left: 5px solid #e74c3c !important;">
                <i class="fas fa-exclamation-circle me-3 fs-4 text-danger"></i>
                <div class="fw-medium">{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-warning border-0 shadow-sm mb-4 py-3" style="border-radius: 12px; border-left: 5px solid #f1c40f !important;">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-info-circle me-3 fs-4 text-warning"></i>
                    <span class="fw-bold">Check Highlighted Fields</span>
                </div>
                <ul class="mb-0 small ps-5">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif
    </div>

    {{-- 3. MAIN ACADEMIC TABS --}}
    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
        <div class="card-header bg-white border-0 p-0">
            <ul class="nav nav-tabs nav-tabs-custom border-bottom px-3" id="academicTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#session-content"><i class="fas fa-calendar-alt me-2"></i>Sessions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#class-content"><i class="fas fa-school me-2"></i>Classes & Streams</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#subject-content"><i class="fas fa-book me-2"></i>Subjects</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-primary fw-bold" data-bs-toggle="tab" href="#syllabus-content"><i class="fas fa-file-pdf me-2"></i>Syllabus Repo</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-info fw-bold" data-bs-toggle="tab" href="#timetable-content"><i class="fas fa-clock me-2"></i>Timetable</a>
                </li>
            </ul>
        </div>

        <div class="card-body p-4 bg-white">
            <div class="tab-content" id="academicTabContent">
                
                {{-- TAB 1: SESSIONS --}}
                <div class="tab-pane fade show active" id="session-content">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0 text-dark">Academic Years</h5>
                        <button class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addSessionModal">
                            <i class="fas fa-plus me-1"></i> New Academic Year
                        </button>
                    </div>
                    <div class="row g-3">
                        @foreach($sessions as $session)
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 h-100 {{ $session->is_current ? 'bg-light-success border-success' : 'bg-white' }} shadow-xs">
                                <div class="d-flex justify-content-between align-items-center gap-2">
                                    <h6 class="fw-bold">{{ $session->name }}</h6>
                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                        @if($session->is_current)
                                            <span class="badge bg-success shadow-sm px-3">Active</span>
                                        @else
                                            <form action="{{ route('academic.session.active', $session->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-xs btn-outline-secondary px-2">Set Active</button>
                                            </form>
                                        @endif

                                        <form action="{{ route('academic.session.destroy', $session->id) }}" method="POST" onsubmit="return confirm('Remove this academic year and its semesters?');" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-outline-danger px-2">Remove</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="mt-3 row g-2">
                                    @foreach($session->semesters as $sem)
                                    <div class="col-6">
                                        <div class="bg-white border rounded p-2 text-center small shadow-xs {{ $sem->is_current ? 'border-success' : '' }}">
                                            <div class="fw-bold text-primary small">{{ $sem->semester_name }}</div>
                                            <div class="text-muted x-small">{{ date('M d', strtotime($sem->start_date)) }} - {{ date('M d', strtotime($sem->end_date)) }}</div>
                                            @if($sem->is_current)
                                                <div class="d-flex flex-column gap-2">
                                                    <span class="badge bg-success mt-2">Active</span>
                                                    <form action="{{ route('academic.semester.destroy', $sem->id) }}" method="POST" onsubmit="return confirm('Remove this semester?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100 py-1">Remove Semester</button>
                                                    </form>
                                                </div>
                                            @else
                                                <div class="d-grid gap-2 mt-2">
                                                    <form action="{{ route('academic.semester.active', $sem->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-primary w-100 py-1">Set Active</button>
                                                    </form>
                                                    <form action="{{ route('academic.semester.destroy', $sem->id) }}" method="POST" onsubmit="return confirm('Remove this semester?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100 py-1">Remove Semester</button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                    <div class="col-12 mt-2">
                                        <button class="btn btn-link btn-sm text-decoration-none p-0 w-100 border-top pt-2 text-dark fw-bold" data-bs-toggle="modal" data-bs-target="#addSemester{{$session->id}}">+ Add Semester</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @include('pages.academic.partials.modal_add_semester')
                        @endforeach
                    </div>
                </div>

                {{-- TAB 2: CLASSES & STREAMS --}}
                <div class="tab-pane fade" id="class-content">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0 text-dark">Class Configuration</h5>
                        <button class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addClassModal">
                            <i class="fas fa-plus me-1"></i> Create Class
                        </button>
                    </div>
                    <div class="row g-3">
                        @foreach($classes as $class)
                        <div class="col-lg-4 col-md-6">
                            <div class="card border shadow-xs h-100" style="border-radius: 12px;">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                        <div>
                                            <h6 class="fw-bold text-primary mb-0">{{ $class->class_name }}</h6>
                                            <span class="x-small text-muted">{{ $class->branch->branch_name }}</span>
                                        </div>
                                        <form action="{{ route('academic.class.destroy', $class->id) }}" method="POST" onsubmit="return confirm('Remove this class and all its streams?');" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                        </form>
                                    </div>
                                    @foreach($class->streams as $stream)
                                    <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded mb-2 border-start border-primary border-3">
                                        <div>
                                            <span class="small fw-bold text-dark">{{ $stream->stream_name }}</span>
                                            <div class="x-small text-muted">{{ $stream->teacher->full_name ?? 'Unassigned' }}</div>
                                        </div>
                                        <form action="{{ route('academic.stream.destroy', $stream->id) }}" method="POST" onsubmit="return confirm('Remove this stream?');" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                        </form>
                                    </div>
                                    @endforeach
                                    <button class="btn btn-link btn-sm w-100 mt-2 text-decoration-none border-top pt-2 text-muted" data-bs-toggle="modal" data-bs-target="#addStream{{$class->id}}">+ New Stream</button>
                                </div>
                            </div>
                        </div>
                        @include('pages.academic.partials.modal_add_stream')
                        @endforeach
                    </div>
                </div>

                {{-- TAB 3: SUBJECTS --}}
                <div class="tab-pane fade" id="subject-content">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Academic Subjects</h5>
                        <button class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addSubjectModal">Add Subject</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover border align-middle">
                            <thead class="bg-light small text-uppercase fw-bold text-muted">
                                <tr><th class="ps-3">Subject Name</th><th>Code</th><th>Class</th><th>Type</th><th class="text-end">Action</th></tr>
                            </thead>
                            <tbody>
                                @foreach($classes as $cl)
                                    @foreach($cl->subjects as $sub)
                                    <tr>
                                        <td class="ps-3 fw-bold">{{ $sub->subject_name }}</td>
                                        <td><span class="badge bg-soft-primary text-primary px-2">{{ $sub->subject_code }}</span></td>
                                        <td>{{ $cl->class_name }}</td>
                                        <td>{{ $sub->type }}</td>
                                        <td class="text-end pe-3">
                                            <button class="btn btn-link text-danger p-0"><i class="fas fa-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 4: SYLLABUS REPOSITORY (PDF UPLOAD) --}}
                <div class="tab-pane fade" id="syllabus-content">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">Syllabus Repository</h5>
                            <p class="text-muted small">Download or view the current curriculum documents in PDF format.</p>
                        </div>
                        <button class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addSyllabusModal">
                            <i class="fas fa-cloud-upload-alt me-1"></i> Upload PDF
                        </button>
                    </div>
                    
                    @foreach($classes as $class)
                    <div class="card border shadow-none mb-4" style="border-radius: 12px; border-left: 4px solid #2563eb !important;">
                        <div class="card-header bg-white py-3 border-0">
                            <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-folder-open me-2 text-primary"></i> Curriculum for {{ $class->class_name }}</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" style="font-size: 13.5px;">
                                    <thead class="bg-light text-muted x-small text-uppercase fw-bold">
                                        <tr>
                                            <th class="ps-4">Subject</th>
                                            <th>Syllabus Document Name</th>
                                            <th>Uploaded Date</th>
                                            <th class="text-end pe-4">File Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $subjectIds = $class->subjects->pluck('id')->toArray();
                                            $classSyllabuses = $syllabuses->whereIn('subject_id', $subjectIds);
                                        @endphp
                                        @forelse($classSyllabuses as $s)
                                        <tr>
                                            <td class="ps-4">
                                                <span class="fw-bold text-dark d-block">{{ $s->subject->subject_name }}</span>
                                                <span class="x-small text-muted">{{ $s->subject->subject_code }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center text-dark">
                                                    <i class="fas fa-file-pdf text-danger me-2 fs-5"></i>
                                                    <span class="fw-medium">{{ $s->topic_name }}</span>
                                                </div>
                                            </td>
                                            <td class="text-muted x-small">
                                                <i class="far fa-clock me-1"></i> {{ $s->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="btn-group">
                                                    <a href="{{ asset('storage/' . $s->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary border shadow-xs" title="View PDF">
                                                        <i class="fas fa-eye me-1"></i> View
                                                    </a>
                                                    <a href="{{ route('academic.syllabus.download', $s->id) }}" class="btn btn-sm btn-outline-success">
    <i class="fas fa-download"></i>
</a>
                                                    <form action="{{ route('academic.syllabus.destroy', $s->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Futa syllabus hii?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger border shadow-xs">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="4" class="text-center py-4 text-muted x-small">Hakuna mtaala uliopakiwa (uploaded) kwa darasa hili bado.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- TAB 5: TIMETABLE --}}
                {{-- TAB: TIMETABLE --}}
<div class="tab-pane fade" id="timetable-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0 text-dark">Class Timetables (PDF Repository)</h5>
        <button class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addTimetableModal">
            <i class="fas fa-file-upload me-1"></i> Upload New Timetable
        </button>
    </div>
    <div class="row g-3">
        @foreach($classes as $class)
            @foreach($class->streams as $stream)
            <div class="col-md-6 col-lg-4">
                <div class="card border shadow-xs h-100" style="border-radius: 12px; border-top: 3px solid #0dcaf0 !important;">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-soft-info p-2 rounded-3 me-3">
                                <i class="fas fa-calendar-alt text-info fs-5"></i>
                            </div>
                            <div class="overflow-hidden">
                                <h6 class="fw-bold mb-0 text-dark">{{ $class->class_name }} - {{ $stream->stream_name }}</h6>
                                {{-- HAPA: Jina la Mwalimu wa Darasa linaonekana --}}
                                <p class="x-small text-primary fw-bold mb-0">
                                    <i class="fas fa-user-tie me-1"></i> 
                                    Teacher: {{ $stream->teacher->full_name ?? 'Not Assigned' }}
                                </p>
                            </div>
                        </div>

                        <div class="bg-light p-2 rounded mb-3">
                            @php
                                $currentTimetable = $timetables->where('stream_id', $stream->id)->first();
                            @endphp
                            
                            @if($currentTimetable)
                                <div class="small fw-bold text-dark"><i class="fas fa-file-pdf text-danger me-2"></i>{{ $currentTimetable->timetable_name ?? 'Official Timetable' }}</div>
                                <div class="x-small text-muted">Uploaded: {{ $currentTimetable->created_at->format('d M, Y') }}</div>
                            @else
                                <div class="small text-muted italic text-center">No PDF uploaded yet</div>
                            @endif
                        </div>

                        <div class="d-grid gap-2">
                            @if($currentTimetable)
                                <a href="{{ asset('storage/' . $currentTimetable->file_path) }}" target="_blank" class="btn btn-info btn-sm text-white small">
                                    <i class="fas fa-eye me-2"></i>View Schedule
                                </a>
                                <form action="{{ route('academic.timetable.destroy', $currentTimetable->id) }}" method="POST" onsubmit="return confirm('Futa ratiba hii?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-link btn-sm text-danger w-100 mt-1">Delete PDF</button>
                                </form>
                            @else
                                <button class="btn btn-outline-secondary btn-sm small disabled">No Schedule Available</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endforeach
    </div>
</div>

            </div>
        </div>
    </div>
</div>

{{-- CSS STYLES --}}
<style>
    .nav-tabs-custom .nav-link {
        border: none; padding: 1.1rem 1.4rem; color: #64748b; font-weight: 600; font-size: 13.5px; position: relative; transition: 0.3s;
    }
    .nav-tabs-custom .nav-link.active { color: #2563eb; background: transparent; }
    .nav-tabs-custom .nav-link.active::after {
        content: ""; position: absolute; bottom: 0; left: 0; width: 100%; height: 3px; background: #2563eb;
    }
    .bg-light-success { background-color: #f0fdf4; }
    .bg-soft-success { background-color: #ecfdf5; color: #059669; }
    .bg-soft-warning { background-color: #fffbeb; color: #d97706; }
    .bg-soft-primary { background-color: #eff6ff; color: #2563eb; }
    .bg-soft-info { background-color: #e0f7fa; color: #0891b2; }
    .shadow-xs { box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .x-small { font-size: 11px; }
    .btn-xs { padding: 0.2rem 0.5rem; font-size: 11px; border-radius: 4px; }
    .btn-white { background: #fff; color: #334155; }
    .btn-white:hover { background: #f8fafc; }
    .table thead th { border-top: none; }
</style>

{{-- INCLUDED MODALS --}}
@include('pages.academic.partials.modal_add_session')
@include('pages.academic.partials.modal_add_class')
@include('pages.academic.partials.modal_add_subject')
@include('pages.academic.partials.modal_add_timetable')
@include('pages.academic.partials.modal_add_syllabus')

@endsection