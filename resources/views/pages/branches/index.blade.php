@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h3 class="fw-extrabold text-dark mb-1">Branch Management</h3>
            <p class="text-secondary mb-0 small fw-medium">Managing campuses and regional units for <strong>{{ $school->name }}</strong>.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('school.index') }}" class="btn btn-light border btn-sm px-3 rounded-pill fw-bold shadow-sm">
                <i class="fas fa-arrow-left me-2 x-small"></i>Back to Schools
            </a>
            <a href="{{ route('branches.create', $school->id) }}" class="btn btn-primary btn-sm px-4 shadow-sm rounded-pill fw-bold">
                <i class="fas fa-plus me-2 small"></i>Add New Branch
            </a>
        </div>
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

    <!-- Branches Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-0 px-4 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold text-dark mb-0">Regional Branches Ledger</h6>
            <div class="search-box">
                <div class="input-group input-group-sm bg-light border-0 rounded-pill px-3">
                    <span class="input-group-text bg-transparent border-0"><i class="fas fa-search text-muted x-small"></i></span>
                    <input type="text" id="branchSearch" class="form-control bg-transparent border-0 py-1 x-small" placeholder="Search branches...">
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="branchesTable">
                <thead>
                    <tr class="bg-light border-bottom">
                        <th class="ps-4 py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0">Branch Info</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0">Code & Level</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0">Contact</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0">Location</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-center">Status</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-center pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($branches as $branch)
                        <tr class="border-bottom small">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                        <i class="fas fa-building text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark mb-0">{{ $branch->branch_name }}</div>
                                        <div class="text-muted x-small">Created on {{ $branch->created_at->format('d M, Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-primary border fw-bold rounded-pill px-2 py-1 x-small">
                                    {{ $branch->branch_code }}
                                </span>
                                <div class="text-muted x-small mt-1 fw-bold">{{ $branch->education_level }}</div>
                            </td>
                            <td>
                                <div class="text-dark fw-medium small"><i class="fas fa-phone me-1 text-muted x-small"></i> {{ $branch->phone }}</div>
                                <div class="text-muted x-small"><i class="fas fa-envelope me-1 x-small"></i> {{ $branch->email ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div class="text-dark fw-medium small">{{ $branch->region }}</div>
                                <div class="text-muted x-small">{{ $branch->district }}, {{ $branch->ward }}</div>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('branches.toggle', $branch->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-xs rounded-pill px-3 py-1 fw-bold border-0 bg-{{ $branch->status ? 'success' : 'danger' }} bg-opacity-10 text-{{ $branch->status ? 'success' : 'danger' }}" style="font-size: 0.65rem;">
                                        {{ $branch->status ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-1">
                                    <button class="btn btn-white border btn-xs rounded-circle p-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#viewModal{{ $branch->id }}" title="View Details">
                                        <i class="fas fa-eye x-small text-muted"></i>
                                    </button>
                                    <a href="{{ route('branches.edit', $branch->id) }}" class="btn btn-white border btn-xs rounded-circle p-2 shadow-sm" title="Edit Branch">
                                        <i class="fas fa-pen x-small text-muted"></i>
                                    </a>
                                    <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this branch?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-white border btn-xs rounded-circle p-2 shadow-sm" title="Delete Branch">
                                            <i class="fas fa-trash-alt x-small text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Details Modal -->
                        <div class="modal fade" id="viewModal{{ $branch->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow rounded-4">
                                    <div class="modal-header border-0 p-4 pb-0">
                                        <h6 class="fw-bold text-dark mb-0">Branch Specification</h6>
                                        <button type="button" class="btn-close x-small" data-bs-modal="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4 pt-3">
                                        <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-4">
                                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                                <i class="fas fa-school text-primary"></i>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold text-dark mb-0">{{ $branch->branch_name }}</h5>
                                                <span class="badge bg-white text-primary border fw-bold rounded-pill px-2 py-1 x-small mt-1">{{ $branch->branch_code }}</span>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-6">
                                                <label class="x-small fw-bold text-uppercase tracking-wider text-muted d-block">Education Level</label>
                                                <div class="small fw-bold text-dark">{{ $branch->education_level }}</div>
                                            </div>
                                            <div class="col-6">
                                                <label class="x-small fw-bold text-uppercase tracking-wider text-muted d-block">Status</label>
                                                <div class="small fw-bold text-{{ $branch->status ? 'success' : 'danger' }}">
                                                    <i class="fas fa-circle x-small me-1"></i> {{ $branch->status ? 'Operational' : 'Suspended' }}
                                                </div>
                                            </div>
                                            <div class="col-12"><hr class="my-1 border-light"></div>
                                            <div class="col-6">
                                                <label class="x-small fw-bold text-uppercase tracking-wider text-muted d-block">Phone</label>
                                                <div class="small fw-bold text-dark">{{ $branch->phone }}</div>
                                            </div>
                                            <div class="col-6">
                                                <label class="x-small fw-bold text-uppercase tracking-wider text-muted d-block">Email</label>
                                                <div class="small fw-bold text-dark">{{ $branch->email ?? 'N/A' }}</div>
                                            </div>
                                            <div class="col-12"><hr class="my-1 border-light"></div>
                                            <div class="col-12">
                                                <label class="x-small fw-bold text-uppercase tracking-wider text-muted d-block">Location</label>
                                                <div class="small fw-bold text-dark">{{ $branch->region }}, {{ $branch->district }}, {{ $branch->ward }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 p-4 pt-0">
                                        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold x-small" data-bs-dismiss="modal">Close</button>
                                        <a href="{{ route('branches.edit', $branch->id) }}" class="btn btn-primary rounded-pill px-4 fw-bold x-small shadow-sm">Edit Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-sitemap fa-2x text-light mb-3"></i>
                                    <h6 class="fw-bold text-secondary">No branches found</h6>
                                    <p class="text-muted small">Register the first branch for this institution.</p>
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
        $("#branchSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#branchesTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endpush
@endsection
