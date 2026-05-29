@extends('pages.teacher.layout.layout')

@section('content')
<div class="container-fluid py-4">
    <!-- Header remains the same ... -->

    <div class="row">
        <!-- Syllabus List -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-book me-2 text-primary"></i>Subject Syllabus (PDF)</h6>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3 border-0">Topic & Subject</th>
                                <th class="border-0">Class</th> <!-- New Column -->
                                <th class="border-0 text-end pe-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($syllabus as $item)
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-pdf text-danger fs-4 me-2"></i>
                                        <div>
                                            <span class="fw-medium d-block">{{ $item->topic_name }}</span>
                                            <small class="text-muted">{{ $item->subject->subject_name }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <!-- Inaonyesha Class Name -->
                                    <span class="badge bg-label-primary text-primary">
                                        {{ $item->subject->schoolClass->class_name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('academic.syllabus.download', $item->id) }}" class="btn btn-light btn-sm text-primary shadow-sm border">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-4 text-muted">No syllabus found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Timetable Section -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-calendar-alt me-2 text-success"></i>Class Timetables</h6>
                </div>
                <div class="card-body">
                    @forelse($timetables as $table)
                    <div class="p-3 mb-3 bg-white rounded-3 border">
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-shrink-0 bg-success-subtle p-2 rounded text-success">
                                <i class="fas fa-clock fs-4"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 fw-bold">{{ $table->semister_name ?? $table->semester_name ?? 'Semester' }}</h6>
                                <small class="text-primary fw-bold">
                                    {{ optional($table->session)->session_name ?? optional($table->session)->name ?? 'Current Session' }}
                                </small>
                            </div>
                            <div>
                                <a href="{{ route('academic.timetable.download', $table->id) }}" class="btn btn-outline-success btn-sm rounded-pill">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                        <hr class="my-2 opacity-25">
                        <div class="row text-center g-0">
                            <div class="col-6 border-end">
                                <small class="text-muted d-block">Class</small>
                                <span class="fw-medium">{{ $table->stream->schoolClass->class_name ?? 'N/A' }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Stream</small>
                                <span class="fw-medium">{{ $table->stream->stream_name ?? $table->stream_id }}</span>
                            </div>
                        </div>
                        <div class="row text-center g-0 mt-2">
                            <div class="col-6 border-end">
                                <small class="text-muted d-block">Academic Session</small>
                                <span class="fw-medium">{{ optional($table->session)->session_name ?? optional($table->session)->name ?? $table->session->year ?? 'N/A' }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Semester</small>
                                <span class="fw-medium">{{ $table->semister_name ?? $table->semester_name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <p class="text-muted">No timetables available.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection