@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Audit Logs</h4>
            <p class="text-muted mb-0">Review finance activity and audit trail details.</p>
        </div>
    </div>

    @if($auditLogs->count())
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Model</th>
                        <th>Model ID</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($auditLogs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ optional($log->user)->name ?? 'System' }}</td>
                            <td>{{ ucfirst($log->action) }}</td>
                            <td>{{ $log->model }}</td>
                            <td>{{ $log->model_id }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($log->description, 80) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{ $auditLogs->links() }}
    @else
        <div class="alert alert-info">No audit records found.</div>
    @endif
</div>
@endsection