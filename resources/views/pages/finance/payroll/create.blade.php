@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Add Payroll Record</h4>
        <a href="{{ route('finance.payroll.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('finance.payroll.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Teacher</label>
                        <select name="teacher_id" class="form-control @error('teacher_id') is-invalid @enderror" required>
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">
                                    {{ $teacher->first_name }} {{ $teacher->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('teacher_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pay Period</label>
                        <input type="month" name="pay_period" value="{{ $currentPayPeriod }}" class="form-control @error('pay_period') is-invalid @enderror" required>
                        @error('pay_period') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Base Salary</label>
                        <input type="number" name="base_salary" step="0.01" class="form-control @error('base_salary') is-invalid @enderror" required>
                        @error('base_salary') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Allowances</label>
                        <input type="number" name="allowances" step="0.01" value="0" class="form-control @error('allowances') is-invalid @enderror">
                        @error('allowances') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Deductions</label>
                        <input type="number" name="deductions" step="0.01" value="0" class="form-control @error('deductions') is-invalid @enderror">
                        @error('deductions') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Create Payroll Record</button>
            </form>
        </div>
    </div>
</div>
@endsection