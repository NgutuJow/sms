@extends('pages.parent.layout.layout')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card-custom">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4>Receipts</h4>
                    <p class="text-muted">Download receipts for payments made through Pesapal.</p>
                </div>
                <a href="{{ route('parent.finance.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Receipt No</th>
                            <th>Student</th>
                            <th>Invoice</th>
                            <th>Amount</th>
                            <th>Issued At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($receipts as $receipt)
                            <tr>
                                <td>{{ $receipt->receipt_no }}</td>
                                <td>{{ optional($receipt->payment->student)->first_name ?? '-' }} {{ optional($receipt->payment->student)->last_name ?? '' }}</td>
                                <td>{{ optional($receipt->payment->invoice)->reference_no ?? '-' }}</td>
                                <td>TZS {{ number_format(optional($receipt->payment)->amount ?? 0, 2) }}</td>
                                <td>{{ $receipt->issued_at ? \Carbon\Carbon::parse($receipt->issued_at)->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No receipts available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection