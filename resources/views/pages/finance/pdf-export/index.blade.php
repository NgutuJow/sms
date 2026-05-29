@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h3 class="fw-extrabold text-dark mb-1">Financial Document Center</h3>
            <p class="text-secondary mb-0 small fw-medium">Generate and export professional PDF reports for institutional auditing.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('finance.index') }}" class="btn btn-light border btn-sm px-3 rounded-pill fw-bold shadow-sm">
                <i class="fas fa-chart-line me-2 x-small"></i>Finance Dashboard
            </a>
        </div>
    </div>

    <!-- Export Cards Grid -->
    <div class="row g-4">
        <!-- Financial Performance Summary -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="fas fa-file-invoice-dollar text-primary fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0">Financial Summary</h6>
                            <span class="text-muted x-small font-bold text-uppercase">Income vs Expenses</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-4 flex-grow-1">
                        Generate a high-level summary of all collected fees, operational costs, and net performance within a specific period.
                    </p>
                    <div class="mt-auto">
                        <form action="{{ route('finance.pdf-export.financial') }}" method="GET">
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="x-small fw-bold text-muted text-uppercase mb-1">Start Date</label>
                                    <input type="date" name="start_date" class="form-control form-control-sm bg-light border-0" value="{{ now()->startOfMonth()->format('Y-m-d') }}">
                                </div>
                                <div class="col-6">
                                    <label class="x-small fw-bold text-muted text-uppercase mb-1">End Date</label>
                                    <input type="date" name="end_date" class="form-control form-control-sm bg-light border-0" value="{{ now()->endOfMonth()->format('Y-m-d') }}">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold small">
                                <i class="fas fa-download me-2 x-small"></i>Export Summary
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Expense Ledger -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-danger bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="fas fa-receipt text-danger fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0">Expense Ledger</h6>
                            <span class="text-muted x-small font-bold text-uppercase">Expenditure Audit</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-4 flex-grow-1">
                        Export a detailed list of all institutional expenditures, including descriptions, categories, and reference numbers.
                    </p>
                    <div class="mt-auto">
                        <a href="{{ route('finance.pdf-export.expenses') }}" class="btn btn-outline-danger w-100 rounded-pill fw-bold small">
                            <i class="fas fa-file-pdf me-2 x-small"></i>Download Expense Report
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payroll Audit Report -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-success bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="fas fa-users-viewfinder text-success fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0">Payroll Report</h6>
                            <span class="text-muted x-small font-bold text-uppercase">Staff Salaries</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-4 flex-grow-1">
                        Generate a comprehensive audit of staff salary payments, deductions, and net payouts for the current academic cycle.
                    </p>
                    <div class="mt-auto">
                        <a href="{{ route('finance.pdf-export.payroll') }}" class="btn btn-outline-success w-100 rounded-pill fw-bold small">
                            <i class="fas fa-file-pdf me-2 x-small"></i>Download Payroll Audit
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Year-End Fiscal Summary -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover border-start border-warning border-4">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-warning bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="fas fa-calendar-check text-warning fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0">Year-End Summary</h6>
                            <span class="text-muted x-small font-bold text-uppercase">Annual Fiscal Review</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-4 flex-grow-1">
                        A complete year-by-year fiscal performance review. Ideal for board meetings and end-of-year auditing.
                    </p>
                    <div class="mt-auto">
                        <form action="{{ route('finance.pdf-export.year-end') }}" method="GET">
                            <div class="mb-3">
                                <label class="x-small fw-bold text-muted text-uppercase mb-1">Fiscal Year</label>
                                <select name="year" class="form-select form-select-sm bg-light border-0 rounded-3">
                                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                        <option value="{{ $y }}">{{ $y }} Fiscal Year</option>
                                    @endfor
                                </select>
                            </div>
                            <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold small text-dark">
                                <i class="fas fa-file-export me-2 x-small"></i>Generate Annual Report
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="alert alert-info border-0 rounded-4 shadow-sm mt-5 p-4 d-flex align-items-center">
        <div class="bg-info bg-opacity-10 p-3 rounded-circle me-4">
            <i class="fas fa-info-circle text-info fs-4"></i>
        </div>
        <div>
            <h6 class="fw-bold text-dark mb-1">Automatic Auditing Tip</h6>
            <p class="text-secondary small mb-0">All PDF reports are generated in real-time based on the latest database entries. For accurate reports, ensure all receipts and expenses are properly recorded in the Finance module before exporting.</p>
        </div>
    </div>
</div>

<style>
    .fw-extrabold { font-weight: 800; }
    .x-small { font-size: 0.7rem; }
    .transition-hover {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important;
    }
    .bg-primary.bg-opacity-10 { background-color: rgba(37, 99, 235, 0.1) !important; }
    .bg-danger.bg-opacity-10 { background-color: rgba(220, 38, 38, 0.1) !important; }
    .bg-success.bg-opacity-10 { background-color: rgba(22, 163, 74, 0.1) !important; }
    .bg-warning.bg-opacity-10 { background-color: rgba(234, 179, 8, 0.1) !important; }
    .bg-info.bg-opacity-10 { background-color: rgba(6, 182, 212, 0.1) !important; }
</style>
@endsection