@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Financial Overview</h3>
            <p class="text-muted mb-0 small">Monitor collections, manage structures, and analyze fiscal performance.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('finance.pdf-export.financial') }}" class="btn btn-light border btn-sm px-3">
                <i class="fas fa-file-pdf me-2 text-danger"></i>Summary PDF
            </a>
            <a href="{{ route('finance.reports') }}" class="btn btn-primary btn-sm px-3 shadow-sm">
                <i class="fas fa-chart-line me-2"></i>Detailed Analytics
            </a>
        </div>
    </div>

    <!-- Enhanced Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 p-2 rounded-3">
                            <i class="fas fa-sack-dollar text-success fs-5"></i>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 x-small">Life-time</span>
                    </div>
                    <h6 class="text-muted x-small fw-bold text-uppercase mb-1">Total Collected</h6>
                    <h4 class="fw-bold text-dark mb-0">TZS {{ number_format($totalFeesCollected, 0) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-danger bg-opacity-10 p-2 rounded-3">
                            <i class="fas fa-hand-holding-dollar text-danger fs-5"></i>
                        </div>
                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2 py-1 x-small">Pending</span>
                    </div>
                    <h6 class="text-muted x-small fw-bold text-uppercase mb-1">Outstanding</h6>
                    <h4 class="fw-bold text-dark mb-0">TZS {{ number_format($pendingFees, 0) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-3">
                            <i class="fas fa-calendar-check text-primary fs-5"></i>
                        </div>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1 x-small">Today</span>
                    </div>
                    <h6 class="text-muted x-small fw-bold text-uppercase mb-1">Daily Collection</h6>
                    <h4 class="fw-bold text-dark mb-0">TZS {{ number_format($todayCollections, 0) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-info bg-opacity-10 p-2 rounded-3">
                            <i class="fas fa-file-invoice text-info fs-5"></i>
                        </div>
                        <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-2 py-1 x-small">Active</span>
                    </div>
                    <h6 class="text-muted x-small fw-bold text-uppercase mb-1">Total Invoices</h6>
                    <h4 class="fw-bold text-dark mb-0">{{ number_format($totalInvoices) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Recent Payments -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-header bg-white py-3 border-0 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0">Recent Transactions</h6>
                    <a href="{{ route('finance.invoices') }}" class="btn btn-sm btn-light border rounded-pill px-3 x-small">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-2 text-muted x-small fw-bold text-uppercase border-0">Student</th>
                                <th class="py-2 text-muted x-small fw-bold text-uppercase border-0 text-end">Amount</th>
                                <th class="py-2 text-muted x-small fw-bold text-uppercase border-0 text-center">Status</th>
                                <th class="py-2 text-muted x-small fw-bold text-uppercase border-0 text-center pe-4">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayments as $payment)
                                <tr class="small">
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-dark">{{ optional($payment->invoice->student)->first_name }} {{ optional($payment->invoice->student)->last_name }}</div>
                                        <span class="x-small text-muted">ID: {{ optional($payment->invoice->student)->admission_no ?? '---' }}</span>
                                    </td>
                                    <td class="text-end fw-bold text-dark">TZS {{ number_format($payment->amount, 0) }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : 'warning' }} bg-opacity-10 text-{{ $payment->status === 'completed' ? 'success' : 'warning' }} px-2 py-1 rounded-pill x-small">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center text-muted x-small pe-4">{{ $payment->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-5 text-muted small">No transactions found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Defaulters -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-0 px-4">
                    <h6 class="fw-bold text-dark mb-0">Top Defaulters</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($topDefaulters as $invoice)
                            <div class="list-group-item border-0 px-4 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-danger bg-opacity-10 p-2 rounded-3 me-3">
                                            <i class="fas fa-user-slash text-danger x-small"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark small">{{ optional($invoice->student)->first_name }} {{ optional($invoice->student)->last_name }}</div>
                                            <span class="x-small text-muted">{{ optional($invoice->student->classData)->name ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-danger small">TZS {{ number_format($invoice->balance, 0) }}</div>
                                        <span class="x-small text-muted">Owed</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted small">Great! No defaulters.</div>
                        @endforelse
                    </div>
                </div>
                <div class="card-footer bg-white border-0 p-4 pt-0">
                    <a href="{{ route('finance.student-fees') }}" class="btn btn-outline-danger btn-sm w-100 rounded-pill fw-bold">
                        Review All Defaulters
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Modules -->
    <h6 class="fw-bold text-dark mb-4 mt-5 text-uppercase x-small tracking-wider">Core Management Modules</h6>
    <div class="row g-3">
        @php
            $modules = [
                ['route' => 'finance.fee-structures.index', 'icon' => 'fa-list-check', 'color' => 'primary', 'title' => 'Fee Structures', 'desc' => 'Manage classes and plans.'],
                ['route' => 'finance.student-fees', 'icon' => 'fa-users-gear', 'color' => 'success', 'title' => 'Student Accounts', 'desc' => 'Billing and receipting.'],
                ['route' => 'finance.payroll.index', 'icon' => 'fa-users-viewfinder', 'color' => 'info', 'title' => 'Payroll Center', 'desc' => 'Staff compensation.'],
                ['route' => 'finance.expenses.index', 'icon' => 'fa-money-bill-transfer', 'color' => 'warning', 'title' => 'Expense Tracker', 'desc' => 'School operational costs.'],
                ['route' => 'finance.discounts.index', 'icon' => 'fa-tag', 'color' => 'secondary', 'title' => 'Discounts', 'desc' => 'Bursaries and waivers.'],
                ['route' => 'finance.reports', 'icon' => 'fa-chart-pie', 'color' => 'dark', 'title' => 'Financial Intel', 'desc' => 'Summaries and growth.'],
            ];
        @endphp

        @foreach($modules as $mod)
            <div class="col-md-4 col-xl-2">
                <a href="{{ route($mod['route']) }}" class="card border-0 shadow-sm rounded-4 h-100 text-decoration-none module-card">
                    <div class="card-body p-3 text-center">
                        <div class="bg-{{ $mod['color'] }} bg-opacity-10 p-2 rounded-3 d-inline-block mb-2">
                            <i class="fas {{ $mod['icon'] }} text-{{ $mod['color'] }} fs-6"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1 small">{{ $mod['title'] }}</h6>
                        <p class="text-muted x-small mb-0">{{ $mod['desc'] }}</p>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

<style>
    .rounded-4 { border-radius: 1rem !important; }
    .x-small { font-size: 0.7rem; }
    .module-card { transition: all 0.2s ease-in-out; }
    .module-card:hover { transform: translateY(-3px); box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,0.05) !important; }
    .tracking-wider { letter-spacing: 0.05em; }
</style>
@endsection