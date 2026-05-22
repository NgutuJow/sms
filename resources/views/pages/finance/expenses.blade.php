@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Expense Tracker</h3>
            <p class="text-secondary mb-0 small fw-medium">Record and categorize institutional operational costs.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('finance.pdf-export.expenses') }}" class="btn btn-light border btn-sm px-3 rounded-pill fw-bold">
                <i class="fas fa-file-pdf me-2 text-danger small"></i>Export PDF
            </a>
            <a href="{{ route('finance.expenses.create') }}" class="btn btn-primary btn-sm px-3 shadow-sm rounded-pill fw-bold">
                <i class="fas fa-plus me-2 small"></i>Add Expense
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Strip -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-secondary x-small fw-bold text-uppercase tracking-wider mb-0">Total Aggregate</h6>
                        <div class="bg-primary bg-opacity-10 p-2 rounded-3">
                            <i class="fas fa-money-bill-transfer text-primary x-small"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold text-dark mb-0">TZS {{ number_format($totalExpenses, 0) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-secondary x-small fw-bold text-uppercase tracking-wider mb-0">Current Month</h6>
                        <div class="bg-warning bg-opacity-10 p-2 rounded-3">
                            <i class="fas fa-calendar-day text-warning x-small"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold text-dark mb-0">TZS {{ number_format($monthlyExpenses, 0) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-secondary x-small fw-bold text-uppercase tracking-wider mb-0">Top Category</h6>
                        <div class="bg-success bg-opacity-10 p-2 rounded-3">
                            <i class="fas fa-tags text-success x-small"></i>
                        </div>
                    </div>
                    @php 
                        $topCat = \App\Models\Expense::select('category', \DB::raw('SUM(amount) as total'))
                            ->groupBy('category')
                            ->orderBy('total', 'desc')
                            ->first();
                    @endphp
                    <h4 class="fw-bold text-dark mb-0">{{ $topCat ? ucfirst($topCat->category) : 'N/A' }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Minimalist Data Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-0 px-4 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold text-dark mb-0">Expenditure Ledger</h6>
            <div class="search-box">
                <div class="input-group input-group-sm bg-light border-0 rounded-pill px-3">
                    <span class="input-group-text bg-transparent border-0"><i class="fas fa-search text-muted x-small"></i></span>
                    <input type="text" class="form-control bg-transparent border-0 py-1 x-small" placeholder="Search expenses...">
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="bg-light border-bottom">
                        <th class="ps-4 py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0">Reference & Description</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-center">Category</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-end">Amount</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-center">Date</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-center pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($expenses as $expense)
                        <tr class="border-bottom small">
                            <td class="ps-4 py-3">
                                <div class="fw-bold text-dark mb-0">{{ $expense->reference_no }}</div>
                                <span class="x-small text-secondary fw-medium">{{ Str::limit($expense->description, 50) }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border fw-bold rounded-pill px-2 py-1 x-small">
                                    {{ ucfirst($expense->category) }}
                                </span>
                            </td>
                            <td class="text-end fw-bold text-dark">TZS {{ number_format($expense->amount, 0) }}</td>
                            <td class="text-center">
                                <span class="x-small text-secondary fw-bold">{{ optional($expense->expense_date)->format('d M, Y') ?? 'N/A' }}</span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('finance.expenses.edit', $expense) }}" class="btn btn-white border btn-xs rounded-circle p-2 shadow-sm" title="Edit">
                                        <i class="fas fa-pen x-small text-muted"></i>
                                    </a>
                                    <form action="{{ route('finance.expenses.destroy', $expense->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-white border btn-xs rounded-circle p-2 shadow-sm" onclick="return confirm('Archive this expense?')">
                                            <i class="fas fa-trash-alt x-small text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-receipt fa-2x text-light mb-3"></i>
                                    <h6 class="fw-bold text-secondary">No expenses found</h6>
                                    <p class="text-muted small">Start by recording your first operational expenditure.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($expenses, 'hasPages') && $expenses->hasPages())
            <div class="card-footer bg-white border-0 p-4">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .rounded-4 { border-radius: 1rem !important; }
    .x-small { font-size: 0.7rem; }
    .tracking-wider { letter-spacing: 0.05em; }
    .btn-white { background-color: #fff; color: #1e293b; }
    .btn-xs { padding: 0.25rem 0.5rem; font-size: 0.7rem; }
</style>
@endsection
