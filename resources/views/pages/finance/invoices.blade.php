@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Invoices & Billing</h3>
            <p class="text-secondary mb-0 small fw-medium">Centralized ledger for student financial obligations.</p>
        </div>
        <div class="d-flex gap-2">
            <div class="search-box">
                <div class="input-group input-group-sm bg-white border rounded-pill px-3 shadow-sm">
                    <span class="input-group-text bg-transparent border-0"><i class="fas fa-search text-muted small"></i></span>
                    <input type="text" class="form-control bg-transparent border-0 py-1" placeholder="Search invoices...">
                </div>
            </div>
            <a href="{{ route('finance.index') }}" class="btn btn-light border btn-sm px-3 rounded-pill fw-bold">
                <i class="fas fa-arrow-left me-2"></i>Dashboard
            </a>
        </div>
    </div>

    <!-- Stats Strip -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="text-secondary x-small fw-bold text-uppercase tracking-wider mb-2">Total Receivables</h6>
                    <h4 class="fw-bold text-dark mb-0">TZS {{ number_format($invoices->sum('total_amount'), 0) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="text-secondary x-small fw-bold text-uppercase tracking-wider mb-2">Total Collected</h6>
                    <h4 class="fw-bold text-success mb-0">TZS {{ number_format($invoices->sum('paid_amount'), 0) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="text-secondary x-small fw-bold text-uppercase tracking-wider mb-2">Pending Balance</h6>
                    <h4 class="fw-bold text-danger mb-0">TZS {{ number_format($invoices->sum('balance'), 0) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="text-secondary x-small fw-bold text-uppercase tracking-wider mb-2">Invoice Count</h6>
                    <h4 class="fw-bold text-primary mb-0">{{ $invoices->count() }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="bg-light border-bottom">
                        <th class="ps-4 py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0">Reference / Student</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-end">Total Amount</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-end">Paid</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-end">Balance</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-center">Status</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-center pe-4">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($invoices as $invoice)
                    <tr class="border-bottom small">
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-light p-2 rounded-circle me-3">
                                    <i class="fas fa-file-invoice text-primary small"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark mb-0">{{ $invoice->reference_no }}</div>
                                    <span class="x-small text-secondary">{{ optional($invoice->student)->first_name }} {{ optional($invoice->student)->last_name }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="text-end fw-bold text-dark">TZS {{ number_format($invoice->total_amount, 0) }}</td>
                        <td class="text-end text-success">TZS {{ number_format($invoice->paid_amount, 0) }}</td>
                        <td class="text-end fw-bold {{ $invoice->balance > 0 ? 'text-danger' : 'text-success' }}">
                            TZS {{ number_format($invoice->balance, 0) }}
                        </td>
                        <td class="text-center">
                            @php
                                $statusClass = [
                                    'paid' => 'bg-success text-success',
                                    'partial' => 'bg-warning text-warning',
                                    'unpaid' => 'bg-danger text-danger'
                                ][$invoice->status] ?? 'bg-secondary text-secondary';
                            @endphp
                            <span class="badge {{ $statusClass }} bg-opacity-10 px-2 py-1 rounded-pill x-small fw-bold">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>
                        <td class="text-center pe-4">
                            @if($invoice->balance > 0)
                                <a href="{{ route('finance.pay', $invoice->id) }}" class="btn btn-primary btn-xs rounded-pill px-3 fw-bold">
                                    Pay
                                </a>
                            @else
                                <div class="text-success small">
                                    <i class="fas fa-check"></i>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .rounded-4 { border-radius: 1rem !important; }
    .x-small { font-size: 0.7rem; }
    .tracking-wider { letter-spacing: 0.05em; }
    .btn-xs { padding: 0.25rem 0.75rem; font-size: 0.75rem; }
</style>
@endsection