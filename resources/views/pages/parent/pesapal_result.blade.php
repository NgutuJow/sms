
@extends('pages.parent.layout.layout')

@section('content')
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
                            <h2 class="fw-bold text-dark">Payment Successful!</h2>
                            <p class="text-muted fs-5">{{ $message }}</p>
                        </div>
                        
                        <div class="bg-light p-4 rounded-4 mb-4 mx-md-5">
                            <div class="row text-start g-3">
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Transaction Status</span>
                                    <span class="badge bg-success rounded-pill px-3">Completed</span>
                                </div>
                                <div class="col-sm-6 text-sm-end">
                                    <span class="text-muted small d-block">Payment Method</span>
                                    <span class="fw-bold">Pesapal Online</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <a href="{{ route('parent.finance.receipts') }}" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm">
                                <i class="fas fa-file-invoice me-2"></i> View My Receipts
                            </a>
                            <a href="{{ route('parent.finance.dashboard') }}" class="btn btn-outline-secondary px-5 py-3 rounded-pill fw-bold">
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                @else
                    <div class="text-center p-5">
                        <div class="mb-4">
                            <div class="bg-danger bg-opacity-10 d-inline-block p-4 rounded-circle mb-3">
                                <i class="fas fa-times-circle text-danger display-1"></i>
                            </div>
                            <h2 class="fw-bold text-dark">Payment Failed</h2>
                            <p class="text-muted fs-5">{{ $message }}</p>
                        </div>

                        <div class="alert alert-warning border-0 rounded-4 shadow-sm mb-4 mx-md-5 text-start">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle fs-3 me-3"></i>
                                <div>
                                    <p class="mb-0 fw-bold small">What happened?</p>
                                    <p class="mb-0 x-small text-muted">The transaction was not completed. This could be due to insufficient funds, a cancelled session, or a network timeout. No money has been deducted from your account.</p>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            @if(isset($invoiceId) && $invoiceId != 0)
                                <a href="{{ route('parent.finance.pay', $invoiceId) }}" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm">
                                    <i class="fas fa-redo me-2"></i> Try Again
                                </a>
                            @endif
                            <a href="{{ route('parent.finance.dashboard') }}" class="btn btn-outline-secondary px-5 py-3 rounded-pill fw-bold">
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            <div class="card-footer bg-light border-0 py-3 text-center">
                <p class="mb-0 small text-muted">
                    <i class="fas fa-shield-alt me-1 text-primary"></i> Powered by Pesapal Secure Payments
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-4 { border-radius: 1.5rem !important; }
    .bg-opacity-10 { background-color: rgba(13, 110, 253, 0.1) !important; }
    .bg-success.bg-opacity-10 { background-color: rgba(25, 135, 84, 0.1) !important; }
    .bg-danger.bg-opacity-10 { background-color: rgba(220, 53, 69, 0.1) !important; }
    .x-small { font-size: 0.85rem; }
    .display-1 { font-size: 5rem; }
</style>
@endsection
