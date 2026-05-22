@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Branch Details</h4>
        <div>
            <a href="{{ route('finance.branches.edit', $branch) }}" class="btn btn-outline-secondary">Edit</a>
            <a href="{{ route('finance.branches.index') }}" class="btn btn-outline-primary">Back</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <p><strong>Name:</strong> {{ $branch->branch_name }}</p>
            <p><strong>Code:</strong> {{ $branch->branch_code }}</p>
            <p><strong>School:</strong> {{ optional($branch->school)->name ?? 'N/A' }}</p>
            <p><strong>Level:</strong> {{ $branch->education_level }}</p>
            <p><strong>Phone:</strong> {{ $branch->phone }}</p>
            <p><strong>Email:</strong> {{ $branch->email ?? 'N/A' }}</p>
            <p><strong>Region:</strong> {{ $branch->region }}</p>
            <p><strong>District:</strong> {{ $branch->district }}</p>
            <p><strong>Ward:</strong> {{ $branch->ward }}</p>
            <p><strong>Physical Address:</strong> {{ $branch->physical_address ?? 'N/A' }}</p>
            <p><strong>Status:</strong> <span class="badge bg-{{ $branch->status ? 'success' : 'secondary' }}">{{ $branch->status ? 'Active' : 'Inactive' }}</span></p>

            <form action="{{ route('finance.branches.destroy', $branch) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button class="btn btn-danger" onclick="return confirm('Delete this branch?')">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection