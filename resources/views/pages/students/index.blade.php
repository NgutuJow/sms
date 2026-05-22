@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-lg-5">
    
    {{-- HEADER: Minimal & Sharp --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 border-bottom pb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Student Enrollment</h4>
            <p class="text-muted small mb-0">Manage student registration and class enrollment counts.</p>
        </div>
        <div class="d-flex gap-2 mt-3 mt-md-0">
      {{-- resources/views/pages/students/index.blade.php (or wherever the button is) --}} 
<a href="/bulk-import" class="btn btn-outline-primary px-4 shadow-sm fw-medium">
    <i class="bi bi-upload me-2"></i>Bulk Upload
</a>
            <a href="/students/create" class="btn btn-primary px-4 shadow-sm fw-medium">
                <i class="bi bi-plus-lg me-2"></i>Register Student
            </a>
        </div>
    </div>

    {{-- ALERTS: Subtle & Clean --}}
    @if(session('success'))
        <div class="alert alert-white border-start border-success border-4 shadow-sm d-flex align-items-center mb-4 py-3" role="alert">
            <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
            <div class="text-dark fw-medium">{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- CLASS GRID: Professional Cards --}}
    <div class="row g-4">
        @foreach ($classes as $class)
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-xs h-100" style="border-radius: 12px; background: #fff; border: 1px solid #f0f0f0 !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="x-small text-uppercase fw-bold text-muted letter-spacing-1">Class Overview</span>
                        <span class="badge bg-light text-success border px-2 py-1" style="font-size: 10px;">Active</span>
                    </div>
                    
                    <h5 class="fw-bold text-dark mb-1">{{ $class->class_name }}</h5>
                    <p class="text-muted small mb-4">Total Students: <span class="fw-bold text-dark">{{ \App\Models\Student::where('classes', $class->id)->count() }}</span></p>

                    <div class="border rounded-3 overflow-hidden mb-4">
                        <div class="row g-0">
                            <div class="col-6 border-end bg-light-subtle p-2 text-center">
                                <div class="fw-bold text-dark small">0</div>
                                <div class="text-muted" style="font-size: 10px;">PAID</div>
                            </div>
                            <div class="col-6 bg-light-subtle p-2 text-center">
                                <div class="fw-bold text-danger small">{{ \App\Models\Student::where('classes', $class->id)->count() }}</div>
                                <div class="text-muted" style="font-size: 10px;">UNPAID</div>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('students.class', $class->id) }}" class="btn btn-primary btn-sm w-100 py-2 fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">
                        Manage Students <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- SIMPLE MODAL --}}
<div class="modal fade" id="uploadCSVModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            @csrf
            <div class="modal-header border-bottom-0">
                <h6 class="modal-title fw-bold">Upload Student List (CSV)</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Chagua File</label>
                    <input type="file" name="csv_file" class="form-control border-light-subtle bg-light shadow-none" required>
                </div>
                <p class="text-muted x-small mb-0 italic">Hakikisha file lako linafuata muundo wa template ya shule.</p>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm">Upload File</button>
            </div>
        </form>
    </div>
</div>

<style>
    body { background-color: #f8f9fa; }
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.04); }
    .x-small { font-size: 11px; }
    .letter-spacing-1 { letter-spacing: 1px; }
    
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.05) !important;
    }
    
    .bg-light-subtle { background-color: #fafafa; }
    .btn-primary { 
        background-color: #0d6efd; 
        border-color: #0d6efd; 
    }
    .btn-outline-primary {
        color: #0d6efd;
        border-color: #0d6efd;
    }
    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: #fff;
    }
</style>
@endsection