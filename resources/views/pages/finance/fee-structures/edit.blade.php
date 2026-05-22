@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Edit Fee Structure</h4>
            <p class="text-muted mb-0">Update the fee structure details for this class.</p>
        </div>
        <a href="{{ route('finance.fee-structures.index') }}" class="btn btn-outline-primary">Back to Fee Structures</a>
    </div>

    <div class="card shadow-sm p-4">
        <form action="{{ route('finance.fee-structures.update', $feeStructure) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="class_id" class="form-label">Class</label>
                    <select id="class_id" name="class_id" class="form-control" required>
                        <option value="">Select class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $feeStructure->class_id == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="academic_year" class="form-label">Academic Year</label>
                    <input type="text" id="academic_year" name="academic_year" class="form-control" value="{{ $feeStructure->academic_year }}" required>
                </div>
                <div class="col-md-6">
                    <label for="fee_type" class="form-label">Fee Type</label>
                    <input type="text" id="fee_type" name="fee_type" class="form-control" value="{{ $feeStructure->fee_type }}" required>
                </div>
                <div class="col-md-6">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" step="0.01" id="amount" name="amount" class="form-control" value="{{ $feeStructure->amount }}" required>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Update Fee Structure</button>
                <a href="{{ route('finance.fee-structures.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection