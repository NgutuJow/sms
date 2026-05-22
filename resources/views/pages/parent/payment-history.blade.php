@extends('pages.parent.layout.layout')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card-custom">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4>Payment History</h4>
                    <p class="text-muted">Track payments made for your children's invoices.</p>
                </div>
                <a href="{{ route('parent.finance.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Student</th>
                            <th>Invoice</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                <td>{{ optional($payment->student)->first_name ?? '-' }} {{ optional($payment->student)->last_name ?? '' }}</td>
                                <td>{{ optional($payment->invoice)->reference_no ?? '-' }}</td>
                                <td>TZS {{ number_format($payment->amount, 2) }}</td>
                                <td>{{ ucfirst($payment->payment_method ?? 'unknown') }}</td>
                                <td>{{ ucfirst($payment->status) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No payments have been recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection