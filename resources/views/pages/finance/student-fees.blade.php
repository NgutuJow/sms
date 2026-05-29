@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 alert-dismissible fade show">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fs-4 me-3"></i>
                <div>
                    <h6 class="mb-0 fw-bold">Success!</h6>
                    <p class="mb-0 small">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 alert-dismissible fade show">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle fs-4 me-3"></i>
                <div>
                    <h6 class="mb-0 fw-bold">Action Failed</h6>
                    <p class="mb-0 small">{{ session('error') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
                                    <button type="button" 
                                            class="btn btn-sm btn-primary rounded-pill px-3" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#payModal{{ $invoice->id }}">
                                        <i class="fas fa-hand-holding-dollar me-1"></i> Process Pay
                                    </button>
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

<!-- Payment Modals -->
@foreach($studentInvoices as $invoice)
    @if($invoice->balance > 0)
        <div class="modal fade" id="payModal{{ $invoice->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Process Pesapal Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('pesapal.initiate') }}" method="POST">
                        @csrf
                        <input type="hidden" name="student_id" value="{{ $invoice->student_id }}">
                        <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                        <input type="hidden" name="line_1" value="School Fee Payment">
                        
                        <div class="modal-body text-start">
                            <div class="bg-light p-3 rounded-3 mb-3">
                                <div class="small text-muted mb-1">Student Name</div>
                                <div class="fw-bold text-dark">{{ optional($invoice->student)->first_name }} {{ optional($invoice->student)->last_name }}</div>
                                <div class="x-small text-muted mt-2">Invoice: {{ $invoice->reference_no }} | Balance: <strong>TZS {{ number_format($invoice->balance, 0) }}</strong></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label x-small fw-bold text-uppercase">Amount to Pay (TZS)</label>
                                <input type="number" name="amount" class="form-control" value="{{ (int)$invoice->balance }}" min="1000" required>
                            </div>

                            <div class="row g-2">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label x-small fw-bold text-uppercase">Payer Email</label>
                                    <input type="email" name="email_address" class="form-control" value="{{ optional($invoice->student)->guardian_email ?? 'info@alphadventist.ac.tz' }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label x-small fw-bold text-uppercase">Payer Phone</label>
                                    <input type="text" name="phone_number" class="form-control" value="{{ optional($invoice->student)->guardian_phone ?? '0745668746' }}" required>
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label x-small fw-bold text-uppercase">First Name</label>
                                    <input type="text" name="first_name" class="form-control" value="{{ optional($invoice->student)->first_name }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label x-small fw-bold text-uppercase">Last Name</label>
                                    <input type="text" name="last_name" class="form-control" value="{{ optional($invoice->student)->last_name }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light rounded-pill px-4 fw-bold small" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold small">
                                <i class="fas fa-external-link-alt me-2"></i>Initiate Pesapal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

<style>
    .rounded-4 { border-radius: 1.25rem !important; }
    .bg-opacity-10 { background-color: rgba(var(--bs-primary-rgb), 0.1) !important; }
    .x-small { font-size: 0.75rem; }
</style>
@endsection
