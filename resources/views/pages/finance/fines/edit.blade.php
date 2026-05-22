@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Edit Fine</h4>
        <a href="{{ route('finance.fines.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('finance.fines.update', $fine) }}" method="POST">
                @csrf @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fine Percentage (%)</label>
                        <input type="number" name="percentage" step="0.01" value="{{ $fine->percentage }}" class="form-control @error('percentage') is-invalid @enderror" required>
                        @error('percentage') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" value="{{ $fine->due_date->format('Y-m-d') }}" class="form-control @error('due_date') is-invalid @enderror" required>
                        @error('due_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="pending" {{ $fine->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="waived" {{ $fine->status === 'waived' ? 'selected' : '' }}>Waived</option>
                            <option value="paid" {{ $fine->status === 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                        @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Reason</label>
                        <textarea name="reason" class="form-control" rows="3">{{ $fine->reason }}</textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update Fine</button>
            </form>
        </div>
    </div>
</div>
@endsection