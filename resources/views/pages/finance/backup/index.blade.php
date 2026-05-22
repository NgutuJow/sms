@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Finance Backups</h4>
            <p class="text-muted mb-0">Create and download finance database backups.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form action="{{ route('finance.backup-security.store') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Create New Backup</button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">Available Backups</div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Filename</th>
                        <th>Size</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($files as $file)
                        <tr>
                            <td>{{ basename($file) }}</td>
                            <td>{{ number_format(Storage::disk('local')->size($file) / 1024, 2) }} KB</td>
                            <td>
                                <a href="{{ route('finance.backup-security.download', basename($file)) }}" class="btn btn-sm btn-outline-primary">Download</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No backup files available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection