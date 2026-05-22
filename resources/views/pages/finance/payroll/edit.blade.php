@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Edit Payroll Record</h4>
        <a href="{{ route('finance.payroll.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('finance.payroll.update', $payrollRecord) }}" method="POST">
                @csrf @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Base Salary</label>
                        <input type="number" name="base_salary" step="0.01" value="{{ $payrollRecord->base_salary }}" class="form-control @error('base_salary') is-invalid @enderror" required>
                        @error('base_salary') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Allowances</label>
                        <input type="number" name="allowances" step="0.01" value="{{ $payrollRecord->allowances }}" class="form-control @error('allowances') is-invalid @enderror">
                        @error('allowances') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Deductions</label>
                        <input type="number" name="deductions" step="0.01" value="{{ $payrollRecord->deductions }}" class="form-control @error('deductions') is-invalid @enderror">
                        @error('deductions') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="draft" {{ $payrollRecord->status === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="approved" {{ $payrollRecord->status === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="paid" {{ $payrollRecord->status === 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                        @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Payment Date</label>
                        <input type="date" name="payment_date" value="{{ $payrollRecord->payment_date ? $payrollRecord->payment_date->format('Y-m-d') : '' }}" class="form-control @error('payment_date') is-invalid @enderror">
                        @error('payment_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update Payroll Record</button>
            </form>
        </div>
    </div>
</div>
@endsection