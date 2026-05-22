@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Budget Details</h4>
        <div>
            <a href="{{ route('finance.budgets.edit', $budget) }}" class="btn btn-outline-secondary">Edit</a>
            <a href="{{ route('finance.budgets.index') }}" class="btn btn-outline-primary">Back</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <p><strong>Category:</strong> {{ $budget->category }}</p>
            <p><strong>Allocated:</strong> {{ number_format($budget->allocated_amount, 2) }}</p>
            <p><strong>Spent:</strong> {{ number_format($budget->spent_amount, 2) }}</p>
            <p><strong>Remaining:</strong> {{ number_format($budget->allocated_amount - $budget->spent_amount, 2) }}</p>
            <p><strong>Period:</strong> {{ $budget->month }}/{{ $budget->year }}</p>
            <p><strong>Branch:</strong> {{ optional($budget->branch)->branch_name ?? 'All branches' }}</p>
            <form action="{{ route('finance.budgets.destroy', $budget) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button class="btn btn-danger" onclick="return confirm('Delete this budget?')">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection