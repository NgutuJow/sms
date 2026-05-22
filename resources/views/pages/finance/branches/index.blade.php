@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Multi-Branch Finance</h4>
            <p class="text-muted mb-0">Manage school branches and view branch-level finance data.</p>
        </div>
        <a href="{{ route('finance.branches.create') }}" class="btn btn-primary">+ Add Branch</a>
    </div>

    @if($branches->count())
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>School</th>
                        <th>Region</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($branches as $branch)
                        <tr>
                            <td>{{ $branch->branch_name }}</td>
                            <td>{{ $branch->branch_code }}</td>
                            <td>{{ optional($branch->school)->name ?? 'N/A' }}</td>
                            <td>{{ $branch->region }}</td>
                            <td><span class="badge bg-{{ $branch->status ? 'success' : 'secondary' }}">{{ $branch->status ? 'Active' : 'Inactive' }}</span></td>
                            <td>
                                <a href="{{ route('finance.branches.show', $branch) }}" class="btn btn-sm btn-outline-primary">View</a>
                                <a href="{{ route('finance.branches.edit', $branch) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{ $branches->links() }}
    @else
        <div class="alert alert-info">No branches configured yet.</div>
    @endif
</div>
@endsection