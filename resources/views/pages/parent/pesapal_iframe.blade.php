
@extends('pages.parent.layout.layout')

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header bg-white py-4 border-0 px-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-4 me-3">
                            <i class="fas fa-shield-alt text-primary fs-3"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold text-dark">Secure Payment Gateway</h4>
                            <p class="text-muted small mb-0">Powered by Pesapal Secure Checkout</p>
                        </div>
                    </div>
                    <div class="d-none d-md-block">
                        <img src="https://www.pesapal.com/assets/img/pesapal-logo.png" alt="Pesapal" height="30">
                    </div>
                </div>
            </div>
            
            <div class="card-body p-0 border-top">
                <div class="row g-0">
                    <!-- Summary Sidebar -->
                    <div class="col-md-4 bg-light p-4 p-md-5">
                        <h5 class="fw-bold mb-4 text-dark border-bottom pb-2">Payment Details</h5>
                        
                        <div class="mb-4 bg-white p-3 rounded-4 shadow-sm">
                            <label class="text-muted small text-uppercase fw-bold d-block mb-1">Student</label>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                                    <i class="fas fa-user-graduate text-primary"></i>
                                </div>
                                <span class="fw-bold text-dark">{{ $student->first_name }} {{ $student->last_name }}</span>
                            </div>
                        </div>

                        <div class="mb-4 border-bottom pb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Invoice Ref:</span>
                                <span class="fw-bold small">{{ $invoice && isset($invoice->reference_no) ? $invoice->reference_no : 'General' }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Academic Year:</span>
                                <span class="fw-bold small">{{ $invoice && isset($invoice->academic_year) ? $invoice->academic_year : 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Net Amount</span>
                                <span class="fw-bold">TZS {{ number_format($amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center text-primary fw-bold mt-2 border-top pt-3">
                                <span class="small">Total to Pay</span>
                                <span class="fs-5">TZS {{ number_format($amount, 2) }}</span>
                            </div>
                        </div>

                        <div class="mt-5 d-none d-md-block">
                            <div class="p-3 border rounded-4 bg-white">
                                <div class="d-flex align-items-center text-success small fw-bold mb-2">
                                    <i class="fas fa-lock me-2"></i> Encrypted Session
                                </div>
                                <p class="text-muted x-small mb-0">Your financial data is protected with industrial-grade 256-bit SSL encryption.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Iframe -->
                    <div class="col-md-8 bg-white p-0 position-relative">
                        <!-- Custom Loader -->
                        <div id="loading-overlay" class="position-absolute top-0 start-0 w-100 h-100 bg-white d-flex flex-column align-items-center justify-content-center" style="z-index: 10;">
                            <div class="spinner-grow text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <h5 class="fw-bold text-dark mb-1">Secure Connection</h5>
                            <p class="text-muted small text-center px-4">Connecting to Pesapal secure servers...</p>
                        </div>
                        
                        <div id="iframe-container" style="min-height: 700px; transition: opacity 0.5s ease;">
                            <iframe 
                                src="{{ $redirect_url }}" 
                                width="100%" 
                                height="700px" 
                                frameborder="0" 
                                scrolling="auto" 
                                id="pesapal-iframe"
                                onload="onIframeLoad()"
                                style="border: none;"
                                allow="payment"
                            ></iframe>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer bg-light py-4 border-0 text-center">
                <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-3">
                    <span class="text-muted small">
                        <i class="fas fa-info-circle me-1"></i> Don't close or refresh this page until the payment is complete.
                    </span>
                    <a href="{{ route('parent.finance.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-4" onclick="return confirm('Are you sure you want to cancel the payment?')">
                        <i class="fas fa-times me-1"></i> Cancel Payment
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function onIframeLoad() {
        console.log("Pesapal iframe loaded successfully.");
        const loader = document.getElementById('loading-overlay');
        if (loader) {
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.style.display = 'none';
            }, 500);
        }
    }

    // Since iframes can have security restrictions that prevent the 'onload' event 
    // from firing when loading from a different domain (like Pesapal),
    // we use a much shorter timer to ensure the UI is usable immediately.
    setTimeout(onIframeLoad, 2500); 
</script>

<style>
    .rounded-4 { border-radius: 1.25rem !important; }
    .bg-opacity-10 { background-color: rgba(13, 110, 253, 0.1) !important; }
    .x-small { font-size: 0.75rem; }
    #loading-overlay {
        transition: opacity 0.5s ease;
    }
    #pesapal-iframe {
        border-radius: 0 0 1.25rem 0;
    }
    @media (max-width: 768px) {
        #pesapal-iframe {
            border-radius: 0;
            height: 800px !important;
        }
    }
</style>
@endsection
