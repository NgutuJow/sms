@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Payroll Intelligence</h3>
            <p class="text-secondary mb-0 small fw-medium">Strategic oversight of institutional human capital expenditure.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('finance.pdf-export.payroll') }}" class="btn btn-light border btn-sm px-3 rounded-pill fw-bold">
                <i class="fas fa-file-pdf me-2 text-danger small"></i>Export
            </a>
            <a href="{{ route('finance.payroll.create') }}" class="btn btn-primary btn-sm px-3 shadow-sm rounded-pill fw-bold">
                <i class="fas fa-plus me-2 small"></i>Generate
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert bg-success bg-opacity-10 border-0 shadow-sm rounded-4 alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle text-success me-2 small"></i>
                <div class="fw-bold text-success small">{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Grid -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="text-secondary x-small fw-bold text-uppercase tracking-wider mb-2">Gross Disbursed</h6>
                    <h4 class="fw-bold text-dark mb-0">TZS {{ number_format($totalSalaries, 0) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="text-secondary x-small fw-bold text-uppercase tracking-wider mb-2">Pending Approval</h6>
                    <h4 class="fw-bold text-warning mb-0">TZS {{ number_format($pendingSalaries, 0) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="text-secondary x-small fw-bold text-uppercase tracking-wider mb-2">Staff Workforce</h6>
                    <h4 class="fw-bold text-info mb-0">{{ \App\Models\Teacher::count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="text-secondary x-small fw-bold text-uppercase tracking-wider mb-2">Current Month</h6>
                    <h4 class="fw-bold text-primary mb-0">TZS {{ number_format($payrollRecords->where('pay_period', now()->format('Y-m'))->sum('net_salary'), 0) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Minimalist Data Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-0 px-4 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold text-dark mb-0">Disbursement Ledger</h6>
            <div class="search-box">
                <div class="input-group input-group-sm bg-light border-0 rounded-pill px-3">
                    <span class="input-group-text bg-transparent border-0"><i class="fas fa-search text-muted x-small"></i></span>
                    <input type="text" class="form-control bg-transparent border-0 py-1 x-small" placeholder="Search staff...">
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="bg-light border-bottom">
                        <th class="ps-4 py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0">Personnel</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-center">Period</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-end">Net Payable</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-center">Status</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-center pe-4">Operations</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($payrollRecords as $payroll)
                        <tr class="border-bottom small">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary fw-bold rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-size: 0.8rem;">
                                        {{ substr(optional($payroll->teacher)->first_name, 0, 1) }}{{ substr(optional($payroll->teacher)->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark mb-0 small">{{ optional($payroll->teacher)->first_name }} {{ optional($payroll->teacher)->last_name }}</div>
                                        <span class="x-small text-secondary">{{ optional($payroll->teacher)->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-white border text-dark fw-bold rounded-pill px-2 py-1 x-small shadow-sm">
                                    {{ \Carbon\Carbon::parse($payroll->pay_period)->format('M Y') }}
                                </span>
                            </td>
                            <td class="text-end fw-bold text-dark small">TZS {{ number_format($payroll->net_salary, 0) }}</td>
                            <td class="text-center">
                                @php
                                    $statusConfig = [
                                        'paid' => ['bg' => 'bg-success', 'label' => 'Finalized'],
                                        'approved' => ['bg' => 'bg-info', 'label' => 'Authorized'],
                                        'draft' => ['bg' => 'bg-warning', 'label' => 'Drafting']
                                    ][$payroll->status] ?? ['bg' => 'bg-secondary', 'label' => 'Unknown'];
                                @endphp
                                <span class="badge {{ $statusConfig['bg'] }} bg-opacity-10 text-{{ str_replace('bg-', '', $statusConfig['bg']) }} px-2 py-1 rounded-pill x-small fw-bold">
                                    {{ $statusConfig['label'] }}
                                </span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('finance.payroll.show', $payroll) }}" class="btn btn-white border btn-xs rounded-circle p-2 shadow-sm">
                                        <i class="fas fa-eye x-small text-muted"></i>
                                    </a>
                                    <a href="{{ route('finance.payroll.edit', $payroll) }}" class="btn btn-white border btn-xs rounded-circle p-2 shadow-sm">
                                        <i class="fas fa-edit x-small text-muted"></i>
                                    </a>
                                    <form action="{{ route('finance.payroll.destroy', $payroll) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-white border btn-xs rounded-circle p-2 shadow-sm" onclick="return confirm('Archive this record?')">
                                            <i class="fas fa-trash-alt x-small text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-folder-open fa-2x text-light mb-3"></i>
                                    <h6 class="fw-bold text-secondary">No records found</h6>
                                    <p class="text-muted small">Start by generating your first payroll.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payrollRecords->hasPages())
            <div class="card-footer bg-white border-0 p-4">
                {{ $payrollRecords->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .rounded-4 { border-radius: 1rem !important; }
    .x-small { font-size: 0.7rem; }
    .tracking-wider { letter-spacing: 0.05em; }
    .btn-white { background-color: #fff; color: #1e293b; }
    .btn-xs { padding: 0.25rem 0.5rem; font-size: 0.7rem; }
</style>
@endsection