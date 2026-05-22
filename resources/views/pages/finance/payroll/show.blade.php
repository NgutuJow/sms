@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Payroll Details</h4>
        <div>
            <a href="{{ route('finance.payroll.edit', $payrollRecord) }}" class="btn btn-outline-secondary">Edit</a>
            <a href="{{ route('finance.payroll.index') }}" class="btn btn-outline-primary">Back</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Teacher:</strong> {{ $payrollRecord->teacher->first_name }} {{ $payrollRecord->teacher->last_name }}</p>
                    <p><strong>Pay Period:</strong> {{ $payrollRecord->pay_period }}</p>
                    <p><strong>Payment Date:</strong> {{ $payrollRecord->payment_date ? $payrollRecord->payment_date->format('Y-m-d') : 'Not set' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Status:</strong> <span class="badge bg-{{ $payrollRecord->status === 'paid' ? 'success' : ($payrollRecord->status === 'approved' ? 'info' : 'warning') }}">{{ ucfirst($payrollRecord->status) }}</span></p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <p><strong>Base Salary:</strong></p>
                    <p class="fs-5">{{ number_format($payrollRecord->base_salary, 2) }}</p>
                </div>
                <div class="col-md-3">
                    <p><strong>Allowances:</strong></p>
                    <p class="fs-5">{{ number_format($payrollRecord->allowances, 2) }}</p>
                </div>
                <div class="col-md-3">
                    <p><strong>Deductions:</strong></p>
                    <p class="fs-5">{{ number_format($payrollRecord->deductions, 2) }}</p>
                </div>
                <div class="col-md-3">
                    <p><strong>Net Salary:</strong></p>
                    <p class="fs-5 fw-bold">{{ number_format($payrollRecord->net_salary, 2) }}</p>
                </div>
            </div>

            <form action="{{ route('finance.payroll.destroy', $payrollRecord) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection