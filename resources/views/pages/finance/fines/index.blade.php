@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Fine Management</h4>
            <p class="text-muted mb-0">Manage late payment fines and penalties.</p>
        </div>
        <a href="{{ route('finance.fines.create') }}" class="btn btn-primary">+ Add Fine</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-md-3">
            <div class="card p-3 shadow-sm">
                <div class="text-muted">Pending Fines</div>
                <div class="fs-4 fw-bold">{{ number_format($totalFines, 2) }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card p-3 shadow-sm">
                <div class="text-muted">Paid Fines</div>
                <div class="fs-4 fw-bold">{{ number_format($paidFines, 2) }}</div>
            </div>
        </div>
    </div>

    @if($fines->count() > 0)
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Invoice</th>
                            <th>Fine Amount</th>
                            <th>Percentage</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fines as $fine)
                            <tr>
                                <td>{{ optional($fine->student)->first_name }} {{ optional($fine->student)->last_name }}</td>
                                <td>{{ optional($fine->invoice)->reference_no }}</td>
                                <td>{{ number_format($fine->fine_amount, 2) }}</td>
                                <td>{{ $fine->percentage }}%</td>
                                <td>
                                    <span class="badge bg-{{ $fine->status === 'paid' ? 'success' : ($fine->status === 'waived' ? 'secondary' : 'warning') }}">
                                        {{ ucfirst($fine->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('finance.fines.show', $fine) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    <a href="{{ route('finance.fines.edit', $fine) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{ $fines->links() }}
    @else
        <div class="alert alert-info">No fines recorded yet.</div>
    @endif
</div>
@endsection