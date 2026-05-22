@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Edit Discount</h4>
        <a href="{{ route('finance.discounts.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('finance.discounts.update', $discount) }}" method="POST">
                @csrf @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Student</label>
                        <select name="student_id" class="form-control @error('student_id') is-invalid @enderror" required>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ $student->id === $discount->student_id ? 'selected' : '' }}>{{ $student->first_name }} {{ $student->last_name }}</option>
                            @endforeach
                        </select>
                        @error('student_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Discount Type</label>
                        <input name="discount_type" value="{{ $discount->discount_type }}" class="form-control @error('discount_type') is-invalid @enderror" required>
                        @error('discount_type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Amount</label>
                        <input name="amount" type="number" step="0.01" value="{{ $discount->amount }}" class="form-control @error('amount') is-invalid @enderror" required>
                        @error('amount') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Percentage</label>
                        <input name="percentage" type="number" step="0.01" value="{{ $discount->percentage }}" class="form-control @error('percentage') is-invalid @enderror">
                        @error('percentage') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Valid From</label>
                        <input name="valid_from" type="date" value="{{ $discount->valid_from->format('Y-m-d') }}" class="form-control @error('valid_from') is-invalid @enderror" required>
                        @error('valid_from') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Valid To</label>
                        <input name="valid_to" type="date" value="{{ optional($discount->valid_to)->format('Y-m-d') }}" class="form-control @error('valid_to') is-invalid @enderror">
                        @error('valid_to') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Reason</label>
                        <textarea name="reason" rows="3" class="form-control @error('reason') is-invalid @enderror">{{ $discount->reason }}</textarea>
                        @error('reason') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <button class="btn btn-primary">Update Discount</button>
            </form>
        </div>
    </div>
</div>
@endsection