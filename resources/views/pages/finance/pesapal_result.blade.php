@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    @if($success)
                        <div class="text-center p-5">
                            <div class="mb-4">
                                <div class="bg-success bg-opacity-10 d-inline-block p-4 rounded-circle mb-3">
                                    <i class="fas fa-check-circle text-success display-1"></i>
                                </div>
                                <h2 class="fw-bold text-dark">Transaction Successful</h2>
                                <p class="text-muted fs-5">{{ $message }}</p>
                            </div>
                            
                            <div class="bg-light p-4 rounded-4 mb-4 mx-md-5">
                                <div class="row text-start g-3">
                                    <div class="col-sm-6">
                                        <span class="text-muted small d-block">System Status</span>
                                        <span class="badge bg-success rounded-pill px-3">Sync Completed</span>
                                    </div>
                                    <div class="col-sm-6 text-sm-end">
                                        <span class="text-muted small d-block">Audit Method</span>
                                        <span class="fw-bold">Pesapal Gateway V3</span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                <a href="{{ route('finance.invoices') }}" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm">
                                    <i class="fas fa-file-invoice-dollar me-2"></i> Manage Invoices
                                </a>
                                <a href="{{ route('finance.student-fees') }}" class="btn btn-outline-secondary px-5 py-3 rounded-pill fw-bold">
                                    Back to Fee Management
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-center p-5">
                            <div class="mb-4">
                                <div class="bg-danger bg-opacity-10 d-inline-block p-4 rounded-circle mb-3">
                                    <i class="fas fa-times-circle text-danger display-1"></i>
                                </div>
                                <h2 class="fw-bold text-dark">Payment Processing Failed</h2>
                                <p class="text-muted fs-5">{{ $message }}</p>
                            </div>

                            <div class="alert alert-warning border-0 rounded-4 shadow-sm mb-4 mx-md-5 text-start">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle fs-3 me-3 text-warning"></i>
                                    <div>
                                        <p class="mb-0 fw-bold small text-dark">Audit Information</p>
                                        <p class="mb-0 x-small text-muted">The gateway was unable to finalize the transaction. This could be due to a timeout, manual cancellation, or server rejection. No financial sync has occurred.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                <a href="{{ route('finance.student-fees') }}" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm">
                                    <i class="fas fa-redo me-2"></i> Return to Fee List
                                </a>
                                <a href="{{ route('finance.index') }}" class="btn btn-outline-secondary px-5 py-3 rounded-pill fw-bold">
                                    Finance Dashboard
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-light border-0 py-3 text-center">
                    <p class="mb-0 small text-muted">
                        <i class="fas fa-shield-alt me-1 text-primary"></i> Institutional Transaction Ledger | Powered by Pesapal
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-4 { border-radius: 1.5rem !important; }
    .bg-opacity-10 { background-color: rgba(37, 99, 235, 0.1) !important; }
    .bg-success.bg-opacity-10 { background-color: rgba(22, 163, 74, 0.1) !important; }
    .bg-danger.bg-opacity-10 { background-color: rgba(220, 38, 38, 0.1) !important; }
    .x-small { font-size: 0.85rem; }
    .display-1 { font-size: 5rem; }
</style>
@endsection
