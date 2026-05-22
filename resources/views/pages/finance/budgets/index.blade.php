@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Budget Management</h4>
            <p class="text-muted mb-0">Track department budgets and branch spending.</p>
        </div>
        <a href="{{ route('finance.budgets.create') }}" class="btn btn-primary">+ New Budget</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card p-3 shadow-sm">
                <div class="text-muted">Allocated</div>
                <div class="fs-4 fw-bold">{{ number_format($totalAllocated, 2) }}</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card p-3 shadow-sm">
                <div class="text-muted">Spent</div>
                <div class="fs-4 fw-bold">{{ number_format($totalSpent, 2) }}</div>
            </div>
        </div>
    </div>

    @if($budgets->count())
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Category</th>
                        <th>Allocated</th>
                        <th>Spent</th>
                        <th>Remaining</th>
                        <th>Period</th>
                        <th>Branch</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($budgets as $budget)
                        <tr>
                            <td>{{ $budget->category }}</td>
                            <td>{{ number_format($budget->allocated_amount, 2) }}</td>
                            <td>{{ number_format($budget->spent_amount, 2) }}</td>
                            <td>{{ number_format($budget->allocated_amount - $budget->spent_amount, 2) }}</td>
                            <td>{{ $budget->month }}/{{ $budget->year }}</td>
                            <td>{{ optional($budget->branch)->branch_name ?? 'All branches' }}</td>
                            <td>
                                <a href="{{ route('finance.budgets.show', $budget) }}" class="btn btn-sm btn-outline-primary">View</a>
                                <a href="{{ route('finance.budgets.edit', $budget) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{ $budgets->links() }}
    @else
        <div class="alert alert-info">No budgets defined yet.</div>
    @endif
</div>
@endsection