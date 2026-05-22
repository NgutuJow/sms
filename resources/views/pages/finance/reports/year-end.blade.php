@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Fiscal Year Retrospective</h3>
            <p class="text-secondary mb-0 small fw-medium">Consolidated annual performance audit.</p>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('finance.reports.year-end') }}" method="GET" class="d-flex gap-2">
                <select name="year" class="form-select form-select-sm border-0 shadow-sm rounded-pill px-3 fw-bold bg-white" onchange="this.form.submit()">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
            <a href="{{ route('finance.pdf-export.year-end', ['year' => $year]) }}" class="btn btn-light border btn-sm shadow-sm px-3 rounded-pill fw-bold">
                <i class="fas fa-file-pdf me-2 text-danger small"></i>Export Audit
            </a>
        </div>
    </div>

    <!-- Executive Summary -->
    <div class="row g-3 mb-4 text-center">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="text-secondary x-small fw-bold text-uppercase tracking-wider mb-2">Annual Revenue</h6>
                    <h4 class="fw-bold text-success mb-0">TZS {{ number_format($summary['income'], 0) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="text-secondary x-small fw-bold text-uppercase tracking-wider mb-2">Annual Expenditure</h6>
                    <h4 class="fw-bold text-danger mb-0">TZS {{ number_format($summary['expenses'] + $summary['payroll'], 0) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            @php $net = $summary['income'] - ($summary['expenses'] + $summary['payroll']); @endphp
            <div class="card border-0 shadow-sm rounded-4 h-100 {{ $net >= 0 ? 'bg-primary' : 'bg-danger' }}">
                <div class="card-body p-4">
                    <h6 class="text-white opacity-75 x-small fw-bold text-uppercase tracking-wider mb-2">Fiscal Surplus/Deficit</h6>
                    <h4 class="fw-bold text-white mb-0">TZS {{ number_format($net, 0) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-dark">
                <div class="card-body p-4">
                    <h6 class="text-white opacity-50 x-small fw-bold text-uppercase tracking-wider mb-2">Outstanding Assets</h6>
                    <h4 class="fw-bold text-white mb-0">TZS {{ number_format($summary['outstanding'], 0) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Breakdown -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-0 px-4">
            <h6 class="fw-bold text-dark mb-0">Monthly Performance Matrix</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="bg-light border-bottom">
                        <th class="ps-4 py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0">Fiscal Period</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-end">Inflow (Revenue)</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-end">Outflow (Exp)</th>
                        <th class="py-3 text-secondary x-small fw-bold text-uppercase tracking-wider border-0 text-end pe-4">Net Result</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($monthlyData as $data)
                        <tr class="border-bottom small">
                            <td class="ps-4 py-3 fw-bold text-dark">{{ $data['month'] }}</td>
                            <td class="text-end fw-bold text-success">TZS {{ number_format($data['income'], 0) }}</td>
                            <td class="text-end fw-bold text-danger">TZS {{ number_format($data['expense'], 0) }}</td>
                            <td class="text-end pe-4">
                                @php $diff = $data['income'] - $data['expense']; @endphp
                                <span class="badge {{ $diff >= 0 ? 'bg-primary' : 'bg-danger' }} bg-opacity-10 text-{{ $diff >= 0 ? 'primary' : 'danger' }} px-3 py-1 rounded-pill fw-bold x-small">
                                    {{ $diff >= 0 ? '+' : '' }}TZS {{ number_format($diff, 0) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .rounded-4 { border-radius: 1rem !important; }
    .x-small { font-size: 0.7rem; }
    .tracking-wider { letter-spacing: 0.05em; }
</style>
@endsection
