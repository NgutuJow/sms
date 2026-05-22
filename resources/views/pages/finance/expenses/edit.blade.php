@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Edit Expense</h4>
        <a href="{{ route('finance.expenses.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('finance.expenses.update', $expense) }}" method="POST">
                @csrf @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-control @error('category') is-invalid @enderror" required>
                            <option value="utilities" {{ $expense->category === 'utilities' ? 'selected' : '' }}>Utilities</option>
                            <option value="maintenance" {{ $expense->category === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="supplies" {{ $expense->category === 'supplies' ? 'selected' : '' }}>Supplies</option>
                            <option value="equipment" {{ $expense->category === 'equipment' ? 'selected' : '' }}>Equipment</option>
                            <option value="other" {{ $expense->category === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('category') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" name="amount" step="0.01" value="{{ $expense->amount }}" class="form-control @error('amount') is-invalid @enderror" required>
                        @error('amount') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Expense Date</label>
                        <input type="date" name="expense_date" value="{{ $expense->expense_date->format('Y-m-d') }}" class="form-control @error('expense_date') is-invalid @enderror" required>
                        @error('expense_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="pending" {{ $expense->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $expense->status === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $expense->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ $expense->description }}</textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update Expense</button>
            </form>
        </div>
    </div>
</div>
@endsection