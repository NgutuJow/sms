
@extends('pages.parent.layout.layout')

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-lg-11">
        <!-- Main Form Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white border-0 py-4 px-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-4 me-3">
                            <i class="fas fa-file-invoice-dollar text-primary fs-3"></i>
                        </div>
                        <div>
                            <h3 class="mb-1 fw-bold text-dark">Fee Payment</h3>
                            <p class="text-muted mb-0">Complete your payment securely with Pesapal</p>
                        </div>
                    </div>
                    <div class="d-none d-md-block text-end">
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2 border">
                            <i class="fas fa-lock text-success me-1"></i> SSL Encrypted
                        </span>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="row g-0">
                    <!-- Billing Details Section -->
                    <div class="col-lg-7 p-4 p-md-5">
                        @if(session('error'))
                            <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center" role="alert">
                                <i class="fas fa-exclamation-circle me-3 fs-4"></i>
                                <div>{{ session('error') }}</div>
                            </div>
                        @endif

                        <form action="{{ route('pesapal.initiate') }}" method="POST">
                            @csrf
                            <input type="hidden" name="student_id" value="{{ $invoice->student->id }}">
                            <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                            <input type="hidden" name="amount" value="{{ number_format($invoice->balance, 2, '.', '') }}">

                            <h5 class="fw-bold mb-4 text-dark border-bottom pb-2">Billing Information</h5>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">First Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-user text-muted"></i></span>
                                        <input type="text" name="first_name" class="form-control border-0 bg-light py-2" value="{{ old('first_name', $invoice->student->first_name) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">Last Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-user text-muted"></i></span>
                                        <input type="text" name="last_name" class="form-control border-0 bg-light py-2" value="{{ old('last_name', $invoice->student->last_name) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-envelope text-muted"></i></span>
                                        <input type="email" name="email_address" class="form-control border-0 bg-light py-2" value="{{ old('email_address', $invoice->student->guardian_email ?? auth()->user()->email) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-phone text-muted"></i></span>
                                        <input type="text" name="phone_number" class="form-control border-0 bg-light py-2" value="{{ old('phone_number', $invoice->student->guardian_phone ?? auth()->user()->phone) }}" required placeholder="e.g. 255712345678">
                                    </div>
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm transition-all">
                                        Proceed to Secure Payment <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Order Summary Section -->
                    <div class="col-lg-5 bg-light p-4 p-md-5 border-start">
                        <h5 class="fw-bold mb-4 text-dark border-bottom pb-2">Order Summary</h5>
                        
                        <!-- Student Profile Small Card -->
                        <div class="bg-white p-3 rounded-4 shadow-sm mb-4 border">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white p-3 rounded-circle me-3 shadow-sm">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $invoice->student->first_name }} {{ $invoice->student->last_name }}</h6>
                                    <span class="text-muted small">Admission: {{ $invoice->student->admission_no }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="details-list">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Invoice Ref:</span>
                                <span class="fw-bold text-dark">{{ $invoice->reference_no }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Due Date:</span>
                                <span class="fw-bold text-dark">{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') : 'N/A' }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3 border-top pt-3">
                                <span class="text-muted">Current Balance:</span>
                                <span class="fw-bold text-dark">TZS {{ number_format($invoice->balance, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-4 border-top pt-3 bg-white p-3 rounded-3 mt-4">
                                <span class="fw-bold text-primary">Amount to Pay:</span>
                                <span class="fw-bold text-primary fs-4">TZS {{ number_format($invoice->balance, 2) }}</span>
                            </div>
                        </div>

                        <div class="alert alert-warning border-0 rounded-4 py-3 px-4 mt-auto">
                            <div class="d-flex">
                                <i class="fas fa-shield-alt fs-3 text-warning me-3"></i>
                                <div>
                                    <p class="mb-0 small fw-bold">Secure Transactions</p>
                                    <p class="mb-0 x-small text-muted">Your payment is processed through Pesapal's secure infrastructure. We do not store your card or mobile money details.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('parent.finance.dashboard') }}" class="btn btn-link text-muted text-decoration-none">
                <i class="fas fa-chevron-left me-1"></i> Cancel and Return to Portal
            </a>
        </div>
    </div>
</div>

<style>
    .rounded-4 { border-radius: 1.25rem !important; }
    .bg-opacity-10 { background-color: rgba(13, 110, 253, 0.1) !important; }
    .x-small { font-size: 0.75rem; }
    .transition-all { transition: all 0.3s ease; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3) !important; }
    .input-group-text { border: none; }
    .form-control:focus { box-shadow: none; background-color: #f1f4f9; border-color: #0d6efd; }
</style>
@endsection
