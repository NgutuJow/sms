@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Edit Teacher: {{ $teacher->full_name }}</h3>
            <p class="text-muted small">Update teacher profile and employment records</p>
        </div>
        <a href="{{ route('teachers.index') }}" class="btn btn-outline-secondary px-4">
            <i class="fas fa-times me-1"></i> Cancel
        </a>
    </div>

    <form action="{{ route('teachers.update', $teacher->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 text-primary border-bottom pb-2">Main Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Full Name</label>
                                <input type="text" name="full_name" class="form-control" value="{{ $teacher->full_name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Staff ID</label>
                                <input type="text" name="teacher_id_number" class="form-control" value="{{ $teacher->teacher_id_number }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Branch</label>
                                <select name="branch_id" class="form-select" required>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ $teacher->branch_id == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->branch_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="Male" {{ $teacher->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ $teacher->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">DOB</label>
                                <input type="date" name="dob" class="form-control" value="{{ $teacher->dob }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Designation</label>
                                <input type="text" name="designation" class="form-control" value="{{ $teacher->designation }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Qualification</label>
                                <input type="text" name="qualification" class="form-control" value="{{ $teacher->qualification }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 text-primary border-bottom pb-2">Contact & Address</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ $teacher->phone }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ $teacher->email }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Address</label>
                                <textarea name="address" class="form-control" rows="2">{{ $teacher->address }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4 text-center">
                        <h6 class="fw-bold text-start mb-3">Current Photo</h6>
                        <div class="mb-3">
                            <img src="{{ $teacher->image ? asset('uploads/teachers/'.$teacher->image) : asset('assets/img/default-user.png') }}" 
                                 class="img-thumbnail rounded mb-2" style="width: 150px; height: 150px; object-fit: cover;">
                            <input type="file" name="image" class="form-control">
                            <small class="text-muted d-block mt-1">Leave blank to keep current photo</small>
                        </div>
                        <hr>
                        <div class="text-start">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-select">
                                <option value="1" {{ $teacher->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $teacher->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-3 shadow">
                    <i class="fas fa-sync-alt me-2"></i> Update Teacher Profile
                </button>
            </div>
        </div>
    </form>
</div>
@endsection