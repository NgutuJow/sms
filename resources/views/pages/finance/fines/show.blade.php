@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Fine Details</h4>
        <div>
            <a href="{{ route('finance.fines.edit', $fine) }}" class="btn btn-outline-secondary">Edit</a>
            <a href="{{ route('finance.fines.index') }}" class="btn btn-outline-primary">Back</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Student:</strong> {{ $fine->student->first_name }} {{ $fine->student->last_name }}</p>
                    <p><strong>Invoice:</strong> {{ $fine->invoice->reference_no }}</p>
                    <p><strong>Fine Amount:</strong> {{ number_format($fine->fine_amount, 2) }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Percentage:</strong> {{ $fine->percentage }}%</p>
                    <p><strong>Due Date:</strong> {{ $fine->due_date->format('Y-m-d') }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-{{ $fine->status === 'paid' ? 'success' : ($fine->status === 'waived' ? 'secondary' : 'warning') }}">{{ ucfirst($fine->status) }}</span></p>
                </div>
            </div>

            <p><strong>Reason:</strong> {{ $fine->reason ?? 'N/A' }}</p>

            <form action="{{ route('finance.fines.destroy', $fine) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection