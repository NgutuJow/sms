@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Financial Intelligence</h3>
            <p class="text-secondary mb-0 small fw-medium">Analysis of institutional liquidity and fiscal health.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('finance.pdf-export.financial', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="btn btn-light border btn-sm px-3 rounded-pill fw-bold">
                <i class="fas fa-file-pdf me-2 text-danger small"></i>Export PDF
            </a>
            <a href="{{ route('finance.reports.year-end') }}" class="btn btn-primary btn-sm px-3 shadow-sm rounded-pill fw-bold">
                <i class="fas fa-calendar-alt me-2 small"></i>Year-End
            </a>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-body p-0">
            <form action="{{ route('finance.reports') }}" method="GET" class="row g-0">
                <div class="col-md-4 border-end">
                    <div class="p-3">
                        <label class="x-small fw-bold text-secondary text-uppercase tracking-wider mb-1 d-block">Start Date</label>
                        <input type="date" name="start_date" class="form-control form-control-sm border-0 bg-light rounded-3 fw-bold" value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                </div>
                <div class="col-md-4 border-end">
                    <div class="p-3">
                        <label class="x-small fw-bold text-secondary text-uppercase tracking-wider mb-1 d-block">End Date</label>
                        <input type="date" name="end_date" class="form-control form-control-sm border-0 bg-light rounded-3 fw-bold" value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-center justify-content-center bg-light">
                    <div class="p-3 w-100">
                        <button type="submit" class="btn btn-dark btn-sm w-100 py-2 rounded-3 fw-bold">
                            <i class="fas fa-sync-alt me-2 small"></i>RE-CALCULATE
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 text-center">
                <div class="card-body p-4">
                    <h6 class="text-secondary x-small fw-bold text-uppercase tracking-wider mb-2">Total Inflow</h6>
                    <h4 class="fw-bold text-success mb-0">TZS {{ number_format($totalIncome, 0) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 text-center">
                <div class="card-body p-4">
                    <h6 class="text-secondary x-small fw-bold text-uppercase tracking-wider mb-2">OpEx Outflow</h6>
                    <h4 class="fw-bold text-danger mb-0">TZS {{ number_format($totalExpenses, 0) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 text-center">
                <div class="card-body p-4">
                    <h6 class="text-secondary x-small fw-bold text-uppercase tracking-wider mb-2">Human Capital</h6>
                    <h4 class="fw-bold text-warning mb-0">TZS {{ number_format($totalPayroll, 0) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 text-center {{ $netIncome >= 0 ? 'bg-primary' : 'bg-danger' }}">
                <div class="card-body p-4">
                    <h6 class="text-white opacity-75 x-small fw-bold text-uppercase tracking-wider mb-2">Net Performance</h6>
                    <h4 class="fw-bold text-white mb-0">TZS {{ number_format($netIncome, 0) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Chart -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-0 px-4">
                    <h6 class="fw-bold text-dark mb-0">Revenue Velocity</h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <div style="height: 300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Efficiency -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-0 px-4 text-center">
                    <h6 class="fw-bold text-dark mb-0">Collection Efficiency</h6>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
                    <div class="position-relative mb-4" style="width: 150px; height: 150px;">
                        <canvas id="collectionPie"></canvas>
                        <div class="position-absolute top-50 start-50 translate-middle text-center">
                            <h4 class="fw-bold text-dark mb-0">{{ $collectionRate }}%</h4>
                            <span class="text-secondary x-small fw-bold uppercase">Efficient</span>
                        </div>
                    </div>
                    <div class="w-100 px-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-secondary x-small fw-bold uppercase">Invoiced</span>
                            <span class="fw-bold text-dark small">TZS {{ number_format($totalInvoiced, 0) }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-1">
                            <span class="text-secondary x-small fw-bold uppercase">Realized</span>
                            <span class="fw-bold text-success small">TZS {{ number_format($totalIncome, 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-4 { border-radius: 1rem !important; }
    .x-small { font-size: 0.7rem; }
    .tracking-wider { letter-spacing: 0.05em; }
    .uppercase { text-transform: uppercase; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($incomeTrends, 'month')) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode(array_column($incomeTrends, 'total')) !!},
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.05)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#fff',
                pointBorderWidth: 2,
                pointBorderColor: '#2563eb'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { color: '#f1f5f9' },
                    ticks: { font: { size: 10, weight: 'bold' }, color: '#64748b' }
                },
                x: { 
                    grid: { display: false },
                    ticks: { font: { size: 10, weight: 'bold' }, color: '#64748b' }
                }
            }
        }
    });

    const ctxPie = document.getElementById('collectionPie').getContext('2d');
    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [{{ $collectionRate }}, {{ 100 - $collectionRate }}],
                backgroundColor: ['#10b981', '#f1f5f9'],
                borderWidth: 0,
                cutout: '85%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { tooltip: { enabled: false } }
        }
    });
</script>
@endsection
