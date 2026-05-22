@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h3 class="fw-extrabold text-dark mb-1">School Registry</h3>
            <p class="text-secondary mb-0 small fw-medium">Manage institutional profiles and their core configurations.</p>
        </div>
        <a href="{{ route('school.create') }}" class="btn btn-primary btn-sm px-4 shadow-sm rounded-pill fw-bold">
            <i class="fas fa-plus me-2 small"></i>Register New School
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

    <!-- Schools Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-0 px-4 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold text-dark mb-0">Institutions Ledger</h6>
            <div class="search-box">
                <div class="input-group input-group-sm bg-light border-0 rounded-pill px-3">
                    <span class="input-group-text bg-transparent border-0"><i class="fas fa-search text-muted x-small"></i></span>
                    <input type="text" id="schoolSearch" class="form-control bg-transparent border-0 py-1 x-small" placeholder="Search schools...">
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="schoolsTable">
                <thead>
                    <tr class="bg-light border-bottom">
                        <th class="ps-4 py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0">Institution</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0">Type & Code</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0">Location</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0">Contact</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-center">Status</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-center pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($schools as $school)
                        <tr class="border-bottom small">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                        <i class="fas fa-school text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark mb-0">{{ $school->name }}</div>
                                        <div class="text-muted x-small">Registered on {{ $school->created_at->format('d M, Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border fw-bold rounded-pill px-2 py-1 x-small">
                                    {{ ucfirst(str_replace('_',' ', $school->school_type)) }}
                                </span>
                                <div class="text-muted x-small mt-1 fw-bold">{{ $school->code }}</div>
                            </td>
                            <td>
                                <div class="text-dark fw-medium small">{{ $school->region }}</div>
                                <div class="text-muted x-small">{{ $school->district }}, {{ $school->ward }}</div>
                            </td>
                            <td>
                                <div class="text-dark small fw-medium">{{ $school->phone }}</div>
                                <div class="text-muted x-small">{{ $school->email }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $school->status ? 'success' : 'danger' }} bg-opacity-10 text-{{ $school->status ? 'success' : 'danger' }} border fw-bold rounded-pill px-2 py-1 x-small">
                                    {{ $school->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('school.branches', $school->id) }}" class="btn btn-white border btn-xs rounded-circle p-2 shadow-sm" title="Manage Branches">
                                        <i class="fas fa-sitemap x-small text-muted"></i>
                                    </a>
                                    <a href="{{ route('school.edit', $school->id) }}" class="btn btn-white border btn-xs rounded-circle p-2 shadow-sm" title="Edit School">
                                        <i class="fas fa-pen x-small text-muted"></i>
                                    </a>
                                    <form action="{{ route('school.destroy', $school->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this school?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-white border btn-xs rounded-circle p-2 shadow-sm" title="Delete School">
                                            <i class="fas fa-trash-alt x-small text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-school fa-2x text-light mb-3"></i>
                                    <h6 class="fw-bold text-secondary">No schools found</h6>
                                    <p class="text-muted small">Start by registering your first institution.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .fw-extrabold { font-weight: 800; }
    .x-small { font-size: 0.7rem; }
    .tracking-wider { letter-spacing: 0.05em; }
    .rounded-4 { border-radius: 1rem !important; }
    .btn-white { background-color: #fff; color: #1e293b; }
    .btn-xs { padding: 0.25rem 0.5rem; font-size: 0.7rem; }
    .table-hover tbody tr:hover { background-color: #f8fafc; }
</style>

@push('scripts')
<script>
    $(document).ready(function() {
        // Simple search functionality
        $("#schoolSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#schoolsTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endpush
@endsection
