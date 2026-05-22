@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">User Management</h3>
            <p class="text-muted mb-0 small">Create and manage institutional staff and their roles.</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm px-3 shadow-sm rounded-pill fw-bold">
            <i class="fas fa-plus me-2 small"></i>Add New User
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="bg-light border-bottom">
                        <th class="ps-4 py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0">User Info</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-center">Role</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-center">Phone</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-center">Joined Date</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-center pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($users as $user)
                        <tr class="border-bottom small">
                            <td class="ps-4 py-3">
                                <div class="fw-bold text-dark mb-0">{{ $user->name }}</div>
                                <span class="x-small text-secondary fw-medium">{{ $user->email }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $user->role === 'admin' ? 'primary' : ($user->role === 'teacher' ? 'success' : ($user->role === 'accountant' ? 'info' : ($user->role === 'student' ? 'warning' : 'secondary'))) }} bg-opacity-10 text-{{ $user->role === 'admin' ? 'primary' : ($user->role === 'teacher' ? 'success' : ($user->role === 'accountant' ? 'info' : ($user->role === 'student' ? 'warning' : 'secondary'))) }} border fw-bold rounded-pill px-2 py-1 x-small">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="text-center text-muted">{{ $user->phone ?? 'N/A' }}</td>
                            <td class="text-center">
                                <span class="x-small text-secondary fw-bold">{{ $user->created_at->format('d M, Y') }}</span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-white border btn-xs rounded-circle p-2 shadow-sm" title="Edit">
                                        <i class="fas fa-pen x-small text-muted"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-white border btn-xs rounded-circle p-2 shadow-sm" onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i class="fas fa-trash-alt x-small text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-users fa-2x text-light mb-3"></i>
                                    <h6 class="fw-bold text-secondary">No users found</h6>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-0 p-4">
            {{ $users->links() }}
        </div>
    </div>
</div>

<style>
    .rounded-4 { border-radius: 1rem !important; }
    .x-small { font-size: 0.7rem; }
    .tracking-wider { letter-spacing: 0.05em; }
    .btn-white { background-color: #fff; color: #1e293b; }
    .btn-xs { padding: 0.25rem 0.5rem; font-size: 0.7rem; }
</style>
@endsection
