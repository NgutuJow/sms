@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Edit Budget</h4>
        <a href="{{ route('finance.budgets.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('finance.budgets.update', $budget) }}" method="POST">
                @csrf @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <input name="category" value="{{ $budget->category }}" class="form-control @error('category') is-invalid @enderror" required>
                        @error('category') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Allocated Amount</label>
                        <input name="allocated_amount" type="number" step="0.01" value="{{ $budget->allocated_amount }}" class="form-control @error('allocated_amount') is-invalid @enderror" required>
                        @error('allocated_amount') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Spent Amount</label>
                        <input name="spent_amount" type="number" step="0.01" value="{{ $budget->spent_amount }}" class="form-control @error('spent_amount') is-invalid @enderror">
                        @error('spent_amount') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Month</label>
                        <input name="month" value="{{ $budget->month }}" class="form-control @error('month') is-invalid @enderror" required>
                        @error('month') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Year</label>
                        <input name="year" value="{{ $budget->year }}" class="form-control @error('year') is-invalid @enderror" required>
                        @error('year') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Branch</label>
                        <select name="branch_id" class="form-control @error('branch_id') is-invalid @enderror">
                            <option value="">All branches</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ $branch->id === $budget->branch_id ? 'selected' : '' }}>{{ $branch->branch_name }}</option>
                            @endforeach
                        </select>
                        @error('branch_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <button class="btn btn-primary">Update Budget</button>
            </form>
        </div>
    </div>
</div>
@endsection