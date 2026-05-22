@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Fee Architect</h3>
            <p class="text-secondary mb-0 small fw-medium">Strategic configuration of institutional billing models.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('finance.fee-structures.create') }}" class="btn btn-primary btn-sm px-3 shadow-sm rounded-pill fw-bold">
                <i class="fas fa-plus me-2 small"></i>New Framework
            </a>
        </div>
    </div>

    <!-- Stats Strip -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="text-secondary x-small fw-bold text-uppercase tracking-wider mb-2">Active Architectures</h6>
                    <h4 class="fw-bold text-dark mb-0">{{ $feeStructures instanceof \Illuminate\Pagination\LengthAwarePaginator ? $feeStructures->total() : $feeStructures->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="text-secondary x-small fw-bold text-uppercase tracking-wider mb-2">Installment Plans</h6>
                    <h4 class="fw-bold text-info mb-0">
                        {{ \App\Models\FeeStructure::where('allow_installments', true)->count() }}
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="text-secondary x-small fw-bold text-uppercase tracking-wider mb-2">Aggregate Value</h6>
                    <h4 class="fw-bold text-success mb-0">TZS {{ number_format(\App\Models\FeeStructure::sum('amount'), 0) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="text-secondary x-small fw-bold text-uppercase tracking-wider mb-2">Fiscal Epochs</h6>
                    <h4 class="fw-bold text-warning mb-0">
                        {{ \App\Models\FeeStructure::distinct('academic_year')->count() }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Minimalist Data Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-0 px-4">
            <h6 class="fw-bold text-dark mb-0">Billing Architectures</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="bg-light border-bottom">
                        <th class="ps-4 py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0">Academic Level</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-center">Fiscal Year</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-end">Amount</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-center">Billing Model</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-center pe-4">Operations</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($feeStructures as $structure)
                        <tr class="border-bottom small">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary fw-bold p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-size: 0.8rem;">
                                        {{ substr(optional($structure->schoolClass)->class_name ?? 'N', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark mb-0 small">{{ optional($structure->schoolClass)->class_name ?? 'Institutional' }}</div>
                                        <span class="x-small text-secondary">{{ $structure->fee_type }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-white border text-dark fw-bold rounded-pill px-2 py-1 x-small shadow-sm">{{ $structure->academic_year }}</span>
                            </td>
                            <td class="text-end fw-bold text-dark">TZS {{ number_format($structure->amount, 0) }}</td>
                            <td class="text-center">
                                @if($structure->allow_installments)
                                    <span class="badge bg-info bg-opacity-10 text-info px-2 py-1 rounded-pill x-small fw-bold">
                                        Multi-Pay
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-pill x-small fw-bold">
                                        Full-Pay
                                    </span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('finance.fee-structures.edit', $structure) }}" class="btn btn-white border btn-xs rounded-circle p-2 shadow-sm">
                                        <i class="fas fa-pen x-small text-muted"></i>
                                    </a>
                                    <form action="{{ route('finance.fee-structures.destroy', $structure) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-white border btn-xs rounded-circle p-2 shadow-sm" onclick="return confirm('Archive this architecture?')">
                                            <i class="fas fa-trash-alt x-small text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="py-4 text-center">
                                    <i class="fas fa-layer-group fa-2x text-light mb-2"></i>
                                    <h6 class="fw-bold text-secondary">No frameworks defined</h6>
                                    <p class="text-muted small">Initialize institutional billing to see data.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($feeStructures instanceof \Illuminate\Pagination\LengthAwarePaginator && $feeStructures->hasPages())
            <div class="card-footer bg-white border-0 p-4">
                {{ $feeStructures->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .rounded-4 { border-radius: 1rem !important; }
    .x-small { font-size: 0.7rem; }
    .tracking-wider { letter-spacing: 0.05em; }
    .btn-xs { padding: 0.25rem 0.5rem; font-size: 0.7rem; }
</style>
@endsection
