@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Expense Report</h4>
        <a href="{{ route('finance.index') }}" class="btn btn-outline-secondary">Back to Finance</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-12">
            <div class="card p-3 shadow-sm">
                <div class="text-muted">Total Expenses by Category</div>
                @if($totalByCategory->count() > 0)
                    <div class="row">
                        @foreach($totalByCategory as $item)
                            <div class="col-md-3">
                                <div class="mt-3">
                                    <p class="mb-1">{{ ucfirst($item->category) }}</p>
                                    <p class="fs-5 fw-bold">{{ number_format($item->total, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Reference</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                        <tr>
                            <td>{{ $expense->reference_no }}</td>
                            <td>{{ ucfirst($expense->category) }}</td>
                            <td>{{ number_format($expense->amount, 2) }}</td>
                            <td>{{ $expense->expense_date->format('Y-m-d') }}</td>
                            <td>
                                <span class="badge bg-{{ $expense->status === 'approved' ? 'success' : ($expense->status === 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($expense->status) }}
                                </span>
                            </td>
                            <td>{{ Str::limit($expense->description, 50) ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No expenses found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection