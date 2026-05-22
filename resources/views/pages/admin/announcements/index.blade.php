@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h3 class="fw-bold">Announcements Management</h3>
            <p class="text-muted">Create and manage announcements for teachers</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('announcements.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus me-2"></i> Create Announcement
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-3 py-3 fw-semibold text-muted">Title</th>
                        <th class="py-3 fw-semibold text-muted">Description</th>
                        <th class="py-3 fw-semibold text-muted">Created By</th>
                        <th class="py-3 fw-semibold text-muted">Audience</th>
                        <th class="py-3 fw-semibold text-muted">PDF</th>
                        <th class="py-3 fw-semibold text-muted">Status</th>
                        <th class="py-3 fw-semibold text-muted">Date</th>
                        <th class="pe-3 py-3 fw-semibold text-muted text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $announcement)
                    <tr class="align-middle">
                        <td class="ps-3 py-3 fw-medium">{{ $announcement->title }}</td>
                        <td class="py-3 text-muted text-truncate" style="max-width: 200px;">
                            {{ Str::limit($announcement->description, 50) }}
                        </td>
                        <td class="py-3">{{ $announcement->creator->name ?? 'System' }}</td>
                        <td class="py-3">
                            <span class="badge {{ $announcement->audience === 'parent' ? 'bg-info' : 'bg-primary' }}">
                                {{ ucfirst($announcement->audience) }}
                            </span>
                        </td>
                        <td class="py-3">
                            @if($announcement->pdf_path)
                                <a href="{{ route('announcements.download', $announcement->id) }}" class="badge bg-danger">
                                    <i class="fa-solid fa-file-pdf me-1"></i> PDF
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="py-3">
                            <span class="badge {{ $announcement->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="py-3 text-muted">{{ $announcement->created_at->format('M d, Y') }}</td>
                        <td class="pe-3 py-3 text-end">
                            <a href="{{ route('announcements.edit', $announcement->id) }}" class="btn btn-sm btn-outline-primary me-2">
                                <i class="fa-solid fa-pen"></i> Edit
                            </a>
                            <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fa-solid fa-inbox me-2"></i> No announcements yet
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
