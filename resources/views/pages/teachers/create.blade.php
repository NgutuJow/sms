@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    {{-- Header & Navigation --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">Register New Teacher</h3>
            <p class="text-muted small">Fill in all details to add a teacher to the system</p>
        </div>
        <a href="{{ route('teachers.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>

    {{-- Alerts Section --}}
    <div class="alerts-container">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4 animate__animated animate__fadeIn" role="alert" style="border-radius: 12px;">
                <i class="fas fa-check-circle me-3 fs-4"></i>
                <div>
                    <span class="fw-bold">Hongera!</span> {{ session('success') }}
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4 animate__animated animate__fadeIn" role="alert" style="border-radius: 12px;">
                <i class="fas fa-exclamation-octagon me-3 fs-4"></i>
                <div>
                    <span class="fw-bold">Kuna Tatizo!</span> {{ session('error') }}
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-warning border-0 shadow-sm mb-4 animate__animated animate__fadeIn" role="alert" style="border-radius: 12px;">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-info-circle me-3 fs-4"></i>
                    <span class="fw-bold">Tafadhali rekebisha makosa yafuatayo:</span>
                </div>
                <ul class="mb-0 small ps-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <form action="{{ route('teachers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            {{-- Left Side: Personal & Professional --}}
            <div class="col-lg-8">
                {{-- Personal & Employment Card --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-header bg-white border-0 py-3 mt-2">
                        <div class="d-flex align-items-center">
                            
                            <h5 class="fw-bold mb-0">Personal & Employment Details</h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted text-uppercase">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" placeholder="e.g. Adamu Omari" required>
                                @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted text-uppercase">Staff ID Number <span class="text-danger">*</span></label>
                                <input type="text" name="teacher_id_number" class="form-control @error('teacher_id_number') is-invalid @enderror" value="{{ old('teacher_id_number') }}" placeholder="T-100-2026" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted text-uppercase">Branch <span class="text-danger">*</span></label>
                                <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror" required>
                                    <option value="">Choose Branch...</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->branch_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-select" required>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Date of Birth</label>
                                <input type="date" name="dob" class="form-control" value="{{ old('dob') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted text-uppercase">Designation <span class="text-danger">*</span></label>
                                <input type="text" name="designation" class="form-control" placeholder="e.g. Lead Instructor" value="{{ old('designation') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted text-uppercase">Qualification <span class="text-danger">*</span></label>
                                <input type="text" name="qualification" class="form-control" placeholder="e.g. BSc. Computer Science" value="{{ old('qualification') }}" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-muted text-uppercase">Joining Date <span class="text-danger">*</span></label>
                                <input type="date" name="joining_date" class="form-control" value="{{ old('joining_date') ?? date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact Info Card --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-header bg-white border-0 py-3 mt-2">
                        <div class="d-flex align-items-center">
                            
                            <h5 class="fw-bold mb-0">Contact Information</h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted text-uppercase">Phone Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-phone small text-muted"></i></span>
                                    <input type="text" name="phone" class="form-control border-start-0" placeholder="+255..." value="{{ old('phone') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted text-uppercase">Email Address <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope small text-muted"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0" placeholder="example@traininghub.com" value="{{ old('email') }}" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted text-uppercase">Home Address</label>
                                <textarea name="address" class="form-control" rows="2" placeholder="Street, City, Ward...">{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Side: Profile & Status --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-body p-4 text-center">
                        <div class="d-flex align-items-center mb-3">
                             <h6 class="fw-bold mb-0 text-dark">Profile Photo</h6>
                        </div>
                        <div class="mb-3">
                            <div class="border rounded-circle d-inline-flex align-items-center justify-content-center bg-light shadow-sm mb-3" style="width: 120px; height: 120px; overflow: hidden; border: 3px solid #fff !important;">
                                <i class="fas fa-user-circle fa-5x text-muted"></i>
                            </div>
                            <div class="mt-2">
                                <input type="file" name="image" class="form-control form-control-sm">
                                <p class="text-muted x-small mt-2" style="font-size: 11px;">Allowed: JPG, PNG (Max 2MB)</p>
                            </div>
                        </div>
                        <hr class="text-muted opacity-25">
                        <div class="text-start">
                            <label class="form-label fw-bold small text-muted text-uppercase">Account Status</label>
                            <select name="status" class="form-select shadow-sm">
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive / On Leave</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="sticky-top" style="top: 20px; z-index: 1;">
                    <button type="submit" class="btn btn-primary w-100 py-3 shadow-sm fw-bold mb-2" style="border-radius: 12px; background-color: #4834d4; border: none;">
                        <i class="fas fa-save me-2"></i> Save Teacher Details
                    </button>
                    <p class="text-center text-muted small mt-2">All fields marked with <span class="text-danger">*</span> are mandatory.</p>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .bg-primary-light { background-color: rgba(72, 52, 212, 0.1); }
    .bg-success-light { background-color: rgba(39, 174, 96, 0.1); }
    
    .icon-box {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 14px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4834d4;
        box-shadow: 0 0 0 0.25rem rgba(72, 52, 212, 0.1);
    }

    .form-label {
        font-size: 11px;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .input-group-text {
        border-color: #dee2e6;
    }
</style>
@endsection