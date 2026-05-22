@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Receipt Generation</h2>
            <p class="text-muted mb-0">Review and download generated receipts for payments.</p>
        </div>
        <div>
            <a href="{{ route('finance.index') }}" class="btn btn-outline-primary me-2">Back to Dashboard</a>
            <button class="btn btn-success" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Print All
            </button>
        </div>
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
                <div class="text-muted">Total Receipts</div>
                <div class="fs-4 fw-bold text-primary">{{ $receipts->count() }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card p-3 shadow-sm border-left-success">
                <div class="text-muted">This Month</div>
                <div class="fs-4 fw-bold text-success">{{ $receipts->where('issued_at', '>=', now()->startOfMonth())->count() }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card p-3 shadow-sm border-left-info">
                <div class="text-muted">Total Amount</div>
                <div class="fs-4 fw-bold text-info">{{ number_format($receipts->sum('amount'), 2) }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card p-3 shadow-sm border-left-warning">
                <div class="text-muted">Pending</div>
                <div class="fs-4 fw-bold text-warning">0</div>
            </div>
        </div>
    </div>

    @if($receipts->count() > 0)
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">All Receipts</h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <form class="d-inline">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search receipts...">
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
                            <th>Receipt No</th>
                            <th>Student</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Issued At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($receipts as $receipt)
                            <tr>
                                <td>
                                    <strong>{{ $receipt->receipt_no }}</strong>
                                </td>
                                <td>
                                    @if($receipt->payment && $receipt->payment->student)
                                        {{ $receipt->payment->student->first_name }} {{ $receipt->payment->student->last_name }}
                                        <br><small class="text-muted">{{ $receipt->payment->student->student_id }}</small>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="fw-bold text-success">{{ number_format($receipt->amount ?? 0, 2) }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ ucfirst($receipt->payment_method ?? 'N/A') }}</span>
                                </td>
                                <td>{{ optional($receipt->issued_at)->format('M d, Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('finance.receipt.download', $receipt) }}" class="btn btn-sm btn-outline-primary" title="Download PDF">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-info" title="Print" onclick="printReceipt({{ $receipt->id }})">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        <a href="{{ route('finance.receipt.show', $receipt) }}" class="btn btn-sm btn-outline-secondary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No receipts generated yet</h5>
                <p class="text-muted">Receipts will appear here once payments are processed.</p>
                <a href="{{ route('finance.payments.index') }}" class="btn btn-primary">View Payments</a>
            </div>
        </div>
    @endif
</div>

<script>
function printReceipt(receiptId) {
    window.open('{{ route("finance.receipt.print", ":id") }}'.replace(':id', receiptId), '_blank');
}
</script>
@endsection