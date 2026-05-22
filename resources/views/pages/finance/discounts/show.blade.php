@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Discount Detail</h4>
        <div>
            <a href="{{ route('finance.discounts.edit', $discount) }}" class="btn btn-outline-secondary">Edit</a>
            <a href="{{ route('finance.discounts.index') }}" class="btn btn-outline-primary">Back</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <p><strong>Student:</strong> {{ optional($discount->student)->first_name }} {{ optional($discount->student)->last_name }}</p>
            <p><strong>Type:</strong> {{ $discount->discount_type }}</p>
            <p><strong>Amount:</strong> {{ number_format($discount->amount, 2) }}</p>
            <p><strong>Percentage:</strong> {{ $discount->percentage ? $discount->percentage . '%' : 'N/A' }}</p>
            <p><strong>Valid From:</strong> {{ $discount->valid_from->format('Y-m-d') }}</p>
            <p><strong>Valid To:</strong> {{ optional($discount->valid_to)->format('Y-m-d') ?? 'No expiry' }}</p>
            <p><strong>Reason:</strong> {{ $discount->reason ?? 'N/A' }}</p>

            <form action="{{ route('finance.discounts.destroy', $discount) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button class="btn btn-danger" onclick="return confirm('Delete this discount?')">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection