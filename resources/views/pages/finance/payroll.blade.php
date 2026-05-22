@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Payroll Management</h2>
            <p class="text-muted mb-0">Manage teacher salaries, allowances, and deductions efficiently.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('finance.payroll.create') }}" class="btn btn-primary shadow-sm px-4">
                <i class="fas fa-plus me-2"></i>Generate Payroll
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 text-center">
                    <h6 class="text-muted small fw-bold text-uppercase mb-2">Current Month Payout</h6>
                    <h3 class="fw-bold text-primary mb-0">TZS {{ number_format($payrolls->where('month', date('F'))->sum('net_salary'), 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 text-center">
                    <h6 class="text-muted small fw-bold text-uppercase mb-2">Staff Members Paid</h6>
                    <h3 class="fw-bold text-dark mb-0">{{ $payrolls->where('month', date('F'))->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 text-center">
                    <h6 class="text-muted small fw-bold text-uppercase mb-2">Avg. Salary</h6>
                    <h3 class="fw-bold text-success mb-0">TZS {{ number_format($payrolls->avg('net_salary'), 0) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-4 border-0 px-4">
            <h5 class="fw-bold text-dark mb-0">Recent Salary Disbursements</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted small fw-bold text-uppercase border-0">Staff Member</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase border-0 text-center">Period</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase border-0 text-end">Gross</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase border-0 text-end">Deductions</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase border-0 text-end">Net Pay</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase border-0 text-center pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $pr)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                                        <i class="fas fa-user-tie text-primary small"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ optional($pr->teacher)->name ?? 'N/A' }}</div>
                                        <span class="x-small text-muted">ID: {{ optional($pr->teacher)->employee_id ?? '---' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark fw-medium">{{ $pr->month }} {{ $pr->year }}</span>
                            </td>
                            <td class="text-end small">TZS {{ number_format($pr->basic_salary + $pr->allowances, 0) }}</td>
                            <td class="text-end text-danger small">-TZS {{ number_format($pr->deductions, 0) }}</td>
                            <td class="text-end fw-bold text-dark">TZS {{ number_format($pr->net_salary, 2) }}</td>
                            <td class="text-center pe-4">
                                @if($pr->status == 'paid')
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                        <i class="fas fa-check-circle me-1"></i> Paid
                                    </span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                                        <i class="fas fa-clock me-1"></i> Pending
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/amber/payroll.svg" alt="No Payroll" height="150" class="mb-3">
                                <h5 class="text-muted">No payroll records found.</h5>
                                <p class="text-muted small">Generate your first payroll for the current month.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .rounded-4 { border-radius: 1.25rem !important; }
    .bg-opacity-10 { background-color: rgba(var(--bs-primary-rgb), 0.1) !important; }
    .x-small { font-size: 0.75rem; }
</style>
@endsection
