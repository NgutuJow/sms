@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Payroll Report</h4>
        <a href="{{ route('finance.index') }}" class="btn btn-outline-secondary">Back to Finance</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-12">
            <div class="card p-3 shadow-sm">
                <div class="text-muted">Total Paid Salaries</div>
                <div class="fs-4 fw-bold">{{ number_format($totalPaid, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Teacher</th>
                        <th>Pay Period</th>
                        <th>Base Salary</th>
                        <th>Allowances</th>
                        <th>Deductions</th>
                        <th>Net Salary</th>
                        <th>Payment Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrollRecords as $record)
                        <tr>
                            <td>{{ $record->teacher->first_name }} {{ $record->teacher->last_name }}</td>
                            <td>{{ $record->pay_period }}</td>
                            <td>{{ number_format($record->base_salary, 2) }}</td>
                            <td>{{ number_format($record->allowances, 2) }}</td>
                            <td>{{ number_format($record->deductions, 2) }}</td>
                            <td><strong>{{ number_format($record->net_salary, 2) }}</strong></td>
                            <td>{{ $record->payment_date ? $record->payment_date->format('Y-m-d') : 'Not set' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">No paid payroll records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection