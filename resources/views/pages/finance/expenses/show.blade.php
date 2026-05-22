@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Expense Details</h4>
        <div>
            <a href="{{ route('finance.expenses.edit', $expense) }}" class="btn btn-outline-secondary">Edit</a>
            <a href="{{ route('finance.expenses.index') }}" class="btn btn-outline-primary">Back</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Reference:</strong> {{ $expense->reference_no }}</p>
                    <p><strong>Category:</strong> {{ ucfirst($expense->category) }}</p>
                    <p><strong>Amount:</strong> {{ number_format($expense->amount, 2) }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Date:</strong> {{ $expense->expense_date->format('Y-m-d') }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-{{ $expense->status === 'approved' ? 'success' : ($expense->status === 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($expense->status) }}</span></p>
                    <p><strong>Description:</strong> {{ $expense->description ?? 'N/A' }}</p>
                </div>
            </div>

            <form action="{{ route('finance.expenses.destroy', $expense) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection