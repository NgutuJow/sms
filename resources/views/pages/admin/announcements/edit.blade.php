@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h3 class="fw-bold">Edit Announcement</h3>
            <p class="text-muted">Update the announcement details</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('announcements.update', $announcement->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label fw-medium">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" 
                           value="{{ old('title', $announcement->title) }}" placeholder="Enter announcement title" required>
                    @error('title')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-medium">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" 
                              rows="5" placeholder="Enter announcement description" required>{{ old('description', $announcement->description) }}</textarea>
                    @error('description')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="audience" class="form-label fw-medium">Audience <span class="text-danger">*</span></label>
                    <select class="form-select @error('audience') is-invalid @enderror" id="audience" name="audience" required>
                        <option value="">-- Select Audience --</option>
                        <option value="staff" {{ old('audience', $announcement->audience) === 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="parent" {{ old('audience', $announcement->audience) === 'parent' ? 'selected' : '' }}>Parent</option>
                    </select>
                    @error('audience')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="pdf" class="form-label fw-medium">Upload PDF (Optional)</label>
                    @if($announcement->pdf_path)
                        <div class="alert alert-info mb-2">
                            <i class="fa-solid fa-file-pdf me-2"></i>
                            <a href="{{ route('announcements.download', $announcement->id) }}">Current PDF</a>
                        </div>
                    @endif
                    <input type="file" class="form-control @error('pdf') is-invalid @enderror" id="pdf" name="pdf" 
                           accept=".pdf" placeholder="Select PDF file to replace">
                    <small class="text-muted d-block mt-1">Maximum file size: 10MB. Leave empty to keep current file.</small>
                    @error('pdf')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save me-2"></i> Update Announcement
                    </button>
                    <a href="{{ route('announcements.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left me-2"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
