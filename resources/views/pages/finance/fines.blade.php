@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Fine Management</h3>
                    <div class="card-tools">
                        <a href="{{ route('finance.fines.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Fine
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Fine Type</th>
                                <th>Amount</th>
                                <th>Reason</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fines as $fine)
                            <tr>
                                <td>{{ $fine->student->name ?? 'N/A' }}</td>
                                <td>{{ $fine->fine_type }}</td>
                                <td>${{ number_format($fine->amount, 2) }}</td>
                                <td>{{ $fine->reason }}</td>
                                <td>{{ $fine->due_date->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge badge-{{ $fine->status == 'paid' ? 'success' : 'danger' }}">
                                        {{ ucfirst($fine->status) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info">Edit</button>
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No fines found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection