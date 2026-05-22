@extends('pages.parent.layout.layout')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card-custom">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4>Statement Details</h4>
                    <p class="text-muted">Invoice details and payment history for this fee statement.</p>
                </div>
                <a href="{{ route('parent.finance.statements') }}" class="btn btn-outline-secondary btn-sm">Back to Statements</a>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="mb-2"><strong>Invoice:</strong> {{ $invoice->reference_no }}</div>
                    <div class="mb-2"><strong>Student:</strong> {{ $invoice->student->first_name }} {{ $invoice->student->last_name }}</div>
                    <div class="mb-2"><strong>Period:</strong> {{ $invoice->academic_year ?: 'Annual fee' }}</div>
                    <div class="mb-2"><strong>Status:</strong> {{ ucfirst($invoice->status) }}</div>
                </div>
                <div class="col-md-6">
                    <div class="mb-2"><strong>Total Amount:</strong> TZS {{ number_format($invoice->total_amount, 2) }}</div>
                    <div class="mb-2"><strong>Paid Amount:</strong> TZS {{ number_format($invoice->paid_amount, 2) }}</div>
                    <div class="mb-2"><strong>Balance:</strong> TZS {{ number_format($invoice->balance, 2) }}</div>
                </div>
            </div>

            @if($invoice->payments->count())
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Payment Date</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->payments as $payment)
                                <tr>
                                    <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                    <td>TZS {{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ ucfirst($payment->payment_method ?? 'unknown') }}</td>
                                    <td>{{ ucfirst($payment->status) }}</td>
                                    <td>{{ $payment->provider_ref ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No payments recorded for this invoice.</p>
            @endif

            @if($invoice->balance > 0)
                <a href="{{ route('parent.finance.pay', $invoice->id) }}" class="btn btn-primary mt-4">Pay Now</a>
            @endif
        </div>
    </div>
</div>
@endsection