@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Student Fee Management</h2>
            <p class="text-muted mb-0">Monitor individual billing, manage outstanding balances, and process payments.</p>
        </div>
        <div class="d-flex gap-2">
            <div class="input-group shadow-sm" style="width: 300px;">
                <span class="input-group-text bg-white border-0"><i class="fas fa-search text-muted"></i></span>
                <input type="text" class="form-control border-0" placeholder="Search student name or ID...">
            </div>
            <a href="{{ route('finance.index') }}" class="btn btn-light border shadow-sm px-4">
                <i class="fas fa-arrow-left me-2"></i>Dashboard
            </a>
        </div>
    </div>

    <!-- Quick Insights -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-4 me-3">
                        <i class="fas fa-users-gear text-primary fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-0">Total Tracked</h6>
                        <h3 class="fw-bold text-dark mb-0">{{ $studentInvoices->count() }} Students</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-danger bg-opacity-10 p-3 rounded-4 me-3">
                        <i class="fas fa-user-clock text-danger fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-0">In Arrears</h6>
                        <h3 class="fw-bold text-dark mb-0">{{ $studentInvoices->where('balance', '>', 0)->count() }} Students</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-3 rounded-4 me-3">
                        <i class="fas fa-check-double text-success fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-0">Paid in Full</h6>
                        <h3 class="fw-bold text-dark mb-0">{{ $studentInvoices->where('balance', '<=', 0)->count() }} Students</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-4 border-0 px-4">
            <h5 class="fw-bold text-dark mb-0">Billing & Payment Overview</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted small fw-bold text-uppercase border-0">Student Profile</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase border-0">Invoice Ref</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase border-0 text-end">Total Bill</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase border-0 text-end">Balance</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase border-0 text-center pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($studentInvoices as $invoice)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(optional($invoice->student)->first_name) }}&background=random" class="rounded-circle" width="40" height="40">
                                    </div>
                                    <div class="ms-3">
                                        <div class="fw-bold text-dark">{{ optional($invoice->student)->first_name }} {{ optional($invoice->student)->last_name }}</div>
                                        <span class="x-small text-muted">ID: {{ optional($invoice->student)->admission_no ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border fw-medium px-3 py-2 rounded-pill">
                                    {{ $invoice->reference_no }}
                                </span>
                            </td>
                            <td class="text-end fw-bold text-dark">
                                TZS {{ number_format($invoice->total_amount, 0) }}
                            </td>
                            <td class="text-end">
                                @if($invoice->balance > 0)
                                    <span class="fw-bold text-danger">TZS {{ number_format($invoice->balance, 0) }}</span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Settled</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                @if($invoice->balance > 0)
                                    <a href="{{ route('finance.pay', $invoice->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                        <i class="fas fa-hand-holding-dollar me-1"></i> Process Pay
                                    </a>
                                @else
                                    <span class="text-success small fw-bold"><i class="fas fa-circle-check"></i> Complete</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-5 text-muted">No student billing records found.</td></tr>
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