@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Discounts</h4>
            <p class="text-muted mb-0">Manage scholarship and fee waiver rules for students.</p>
        </div>
        <a href="{{ route('finance.discounts.create') }}" class="btn btn-primary">+ New Discount</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card p-3 shadow-sm">
                <div class="text-muted">Total Discount Value</div>
                <div class="fs-4 fw-bold">{{ number_format($totalDiscount, 2) }}</div>
            </div>
        </div>
    </div>

    @if($discounts->count())
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Student</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Percentage</th>
                        <th>Valid From</th>
                        <th>Valid To</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($discounts as $discount)
                        <tr>
                            <td>{{ optional($discount->student)->first_name }} {{ optional($discount->student)->last_name }}</td>
                            <td>{{ ucfirst($discount->discount_type) }}</td>
                            <td>{{ number_format($discount->amount, 2) }}</td>
                            <td>{{ $discount->percentage ? $discount->percentage . '%' : 'N/A' }}</td>
                            <td>{{ $discount->valid_from->format('Y-m-d') }}</td>
                            <td>{{ optional($discount->valid_to)->format('Y-m-d') ?? 'No expiry' }}</td>
                            <td>
                                <a href="{{ route('finance.discounts.show', $discount) }}" class="btn btn-sm btn-outline-primary">View</a>
                                <a href="{{ route('finance.discounts.edit', $discount) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{ $discounts->links() }}
    @else
        <div class="alert alert-info">No discounts configured yet.</div>
    @endif
</div>
@endsection