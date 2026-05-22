
@extends('pages.parent.layout.layout')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card-custom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Finance Dashboard</h4>
                    <p class="text-muted mb-0">Review your children's tuition accounts, invoices and payment history.</p>
                </div>
                <a href="{{ route('parent.finance.accounts') }}" class="btn btn-primary btn-sm">View Fee Accounts</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card-custom">
            <div class="text-muted">Total Amount Billed</div>
            <div class="h3 text-primary">TZS {{ number_format($totalBilled, 2) }}</div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card-custom">
            <div class="text-muted">Total Paid</div>
            <div class="h3 text-success">TZS {{ number_format($totalPaid, 2) }}</div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card-custom">
            <div class="text-muted">Total Outstanding</div>
            <div class="h3 text-warning">TZS {{ number_format($totalDue, 2) }}</div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card-custom">
            <div class="text-muted">Students Awaiting Invoices</div>
            <div class="h3 text-info">{{ $studentsWithoutInvoices }}</div>
            <small class="text-muted">Contact school for fee setup</small>
        </div>
    </div>

    <div class="col-12">
        <div class="card-custom">
            <h5 class="mb-3">Quick Actions</h5>
            <div class="row g-3">
                <div class="col-sm-6 col-md-4">
                    <a href="{{ route('parent.finance.accounts') }}" class="btn btn-outline-primary w-100 py-3">
                        <i class="fa-solid fa-wallet me-2"></i> Student Fee Accounts
                    </a>
                </div>
                <div class="col-sm-6 col-md-4">
                    <a href="{{ route('parent.finance.statements') }}" class="btn btn-outline-success w-100 py-3">
                        <i class="fa-solid fa-file-invoice-dollar me-2"></i> Fee Statements
                    </a>
                </div>
                <div class="col-sm-6 col-md-4">
                    <a href="{{ route('parent.finance.payments') }}" class="btn btn-outline-info w-100 py-3">
                        <i class="fa-solid fa-credit-card me-2"></i> Payment History
                    </a>
                </div>
                <div class="col-sm-6 col-md-4">
                    <a href="{{ route('parent.finance.invoices') }}" class="btn btn-outline-warning w-100 py-3">
                        <i class="fa-solid fa-file-invoice me-2"></i> Invoices
                    </a>
                </div>
                <div class="col-sm-6 col-md-4">
                    <a href="{{ route('parent.finance.receipts') }}" class="btn btn-outline-secondary w-100 py-3">
                        <i class="fa-solid fa-receipt me-2"></i> Receipts
                    </a>
                </div>
                @if($studentsWithoutInvoices > 0)
                <div class="col-sm-6 col-md-4">
                    <a href="{{ route('parent.finance.accounts') }}" class="btn btn-outline-danger w-100 py-3">
                        <i class="fa-solid fa-exclamation-triangle me-2"></i> Setup Payments
                        <span class="badge bg-danger ms-1">{{ $studentsWithoutInvoices }}</span>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card-custom">
            <h5 class="mb-3">Recent Payments</h5>
            @if($recentPayments->count())
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Student</th>
                                <th>Invoice</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPayments as $payment)
                                <tr>
                                    <td>{{ optional($payment->student)->first_name ?? 'Unknown' }} {{ optional($payment->student)->last_name ?? '' }}</td>
                                    <td>{{ optional($payment->invoice)->reference_no ?? 'N/A' }}</td>
                                    <td>TZS {{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ ucfirst($payment->status) }}</td>
                                    <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">No recent payments available.</p>
            @endif
        </div>
    </div>
</div>
@endsection