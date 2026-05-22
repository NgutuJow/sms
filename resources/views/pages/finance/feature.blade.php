@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $title }}</h4>
            <p class="text-muted mb-0">{{ $description }}</p>
        </div>
        <a href="{{ route('finance.index') }}" class="btn btn-outline-primary">Back to Dashboard</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <p class="mb-3">This finance feature is visible and ready for next-phase implementation. Use this page as the administration entry point for {{ strtolower($title) }}.</p>
            <ul>
                <li>✅ Admin navigation available</li>
                <li>✅ Feature page created</li>
                <li>✅ Placeholder content for future workflows</li>
            </ul>
            <p class="text-muted small mb-0">If you want, I can now wire the exact CRUD and report workflows for this module.</p>
        </div>
    </div>
</div>
@endsection