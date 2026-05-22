@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Edit Branch</h4>
        <a href="{{ route('finance.branches.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('finance.branches.update', $branch) }}" method="POST">
                @csrf @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">School</label>
                        <select name="school_id" class="form-control @error('school_id') is-invalid @enderror" required>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ $school->id === $branch->school_id ? 'selected' : '' }}>{{ $school->name }}</option>
                            @endforeach
                        </select>
                        @error('school_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Branch Name</label>
                        <input name="branch_name" value="{{ $branch->branch_name }}" class="form-control @error('branch_name') is-invalid @enderror" required>
                        @error('branch_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Branch Code</label>
                        <input name="branch_code" value="{{ $branch->branch_code }}" class="form-control @error('branch_code') is-invalid @enderror" required>
                        @error('branch_code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Education Level</label>
                        <input name="education_level" value="{{ $branch->education_level }}" class="form-control @error('education_level') is-invalid @enderror" required>
                        @error('education_level') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input name="phone" value="{{ $branch->phone }}" class="form-control @error('phone') is-invalid @enderror" required>
                        @error('phone') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input name="email" type="email" value="{{ $branch->email }}" class="form-control @error('email') is-invalid @enderror">
                        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Region</label>
                        <input name="region" value="{{ $branch->region }}" class="form-control @error('region') is-invalid @enderror" required>
                        @error('region') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">District</label>
                        <input name="district" value="{{ $branch->district }}" class="form-control @error('district') is-invalid @enderror" required>
                        @error('district') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ward</label>
                        <input name="ward" value="{{ $branch->ward }}" class="form-control @error('ward') is-invalid @enderror" required>
                        @error('ward') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Physical Address</label>
                        <input name="physical_address" value="{{ $branch->physical_address }}" class="form-control @error('physical_address') is-invalid @enderror">
                        @error('physical_address') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <button class="btn btn-primary">Update Branch</button>
            </form>
        </div>
    </div>
</div>
@endsection