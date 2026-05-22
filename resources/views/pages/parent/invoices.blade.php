@extends('pages.parent.layout.layout')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card-custom">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4>Invoices</h4>
                    <p class="text-muted">View all invoices issued for your children.</p>
                </div>
                <a href="{{ route('parent.finance.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice</th>
                            <th>Student</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->reference_no }}</td>
                                <td>{{ optional($invoice->student)->first_name ?? '-' }} {{ optional($invoice->student)->last_name ?? '' }}</td>
                                <td>TZS {{ number_format($invoice->total_amount, 2) }}</td>
                                <td>TZS {{ number_format($invoice->paid_amount, 2) }}</td>
                                <td>TZS {{ number_format($invoice->balance, 2) }}</td>
                                <td>{{ ucfirst($invoice->status) }}</td>
                                <td>
                                    <a href="{{ route('parent.finance.statements.details', $invoice->id) }}" class="btn btn-sm btn-primary">Details</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No invoices found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection