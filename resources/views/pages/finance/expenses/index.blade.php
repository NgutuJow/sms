@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Expense Management</h2>
            <p class="text-muted mb-0">Track and manage all school operating expenses by category and date.</p>
        </div>
        <a href="{{ route('finance.expenses.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus me-2"></i>Add New Expense
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-md-3">
            <div class="card p-3 shadow-sm border-left-primary">
                <div class="text-muted">Total Expenses</div>
                <div class="fs-4 fw-bold text-primary">{{ number_format($totalExpenses, 2) }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card p-3 shadow-sm border-left-success">
                <div class="text-muted">This Month</div>
                <div class="fs-4 fw-bold text-success">{{ number_format($monthlyExpenses, 2) }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card p-3 shadow-sm border-left-info">
                <div class="text-muted">Categories</div>
                <div class="fs-4 fw-bold text-info">{{ $expenses->pluck('category')->unique()->count() }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card p-3 shadow-sm border-left-warning">
                <div class="text-muted">Pending</div>
                <div class="fs-4 fw-bold text-warning">{{ $expenses->where('status', 'pending')->count() }}</div>
            </div>
        </div>
    </div>

    @if($expenses->count() > 0)
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">All Expenses</h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <form class="d-inline">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search expenses...">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Reference</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $expense)
                            <tr>
                                <td>{{ $expense->reference_no }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ ucfirst($expense->category) }}</span>
                                </td>
                                <td class="fw-bold text-danger">{{ number_format($expense->amount, 2) }}</td>
                                <td>{{ $expense->expense_date->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $expense->status === 'approved' ? 'success' : ($expense->status === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($expense->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('finance.expenses.show', $expense) }}" class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('finance.expenses.edit', $expense) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('finance.expenses.destroy', $expense) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this expense?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($expenses->hasPages())
                <div class="card-footer">
                    {{ $expenses->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No expenses recorded yet</h5>
                <p class="text-muted">Start by adding your first expense entry.</p>
                <a href="{{ route('finance.expenses.create') }}" class="btn btn-primary">Add First Expense</a>
            </div>
        </div>
    @endif
</div>
@endsection