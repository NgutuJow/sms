@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-lg-5" style="background-color: #f8f9fa; min-height: 100vh;">
    
    {{-- HEADER SECTION --}}
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark mb-1">
                <i class="bi bi-person-badge"></i> Student Performance Reports
            </h2>
            <p class="text-muted small mb-0">View comprehensive reports for student performance across all exams and subjects</p>
        </div>
    </div>

    {{-- SEARCH & FILTER SECTION --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-body">
            <form action="{{ route('admin.students.list') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">Search by Name or Admission No</label>
                    <input type="text" name="search" class="form-control form-control-sm" 
                        placeholder="Enter student name or admission number" value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">Filter by Class</label>
                    <select name="class" class="form-select form-select-sm">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class') == $class->id ? 'selected' : '' }}>
                                {{ $class->class_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill w-100">
                        <i class="bi bi-search me-2"></i>Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- STUDENTS TABLE --}}
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-uppercase text-muted fw-bold small" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                        <th class="ps-4 py-3">Student Information</th>
                        <th class="py-3">Admission No</th>
                        <th class="py-3">Class</th>
                        <th class="py-3">Stream</th>
                        <th class="py-3 text-center">Exams Taken</th>
                        <th class="py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div>
                                    <span class="fw-bold text-dark d-block mb-0">{{ $student->user->name ?? $student->name ?? 'N/A' }}</span>
                                    <small class="text-muted">{{ $student->user->email ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <span class="badge bg-light text-dark px-2">{{ $student->admission_no ?? 'N/A' }}</span>
                        </td>
                        <td class="py-3">
                            <span class="fw-semibold">{{ $student->classData->class_name ?? 'N/A' }}</span>
                        </td>
                        <td class="py-3">
                            <span class="badge bg-info bg-opacity-10 text-info">{{ $student->stream ?? 'N/A' }}</span>
                        </td>
                        <td class="py-3 text-center">
                            @php
                                $examsTaken = \App\Models\Mark::where('student_id', $student->id)
                                    ->distinct('exam_id')
                                    ->count('exam_id');
                            @endphp
                            <span class="badge bg-success">{{ $examsTaken }}</span>
                        </td>
                        <td class="py-3 text-center">
                            <a href="{{ route('admin.students.report', $student->id) }}" 
                                class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold" style="font-size: 11px;">
                                <i class="bi bi-file-earmark-text me-1"></i> View Report
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 2rem; color: #ccc;"></i>
                            <h6 class="text-muted fw-light mt-3">No students found</h6>
                            <p class="text-muted small">Try adjusting your search filters</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    @if($students->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    {{ $students->appends(request()->query())->links('pagination::bootstrap-4') }}
                </ul>
            </nav>
        </div>
    </div>
    @endif
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: #f1f4f9;
        transition: background-color 0.2s ease;
    }
</style>
@endsection
