@extends('layouts.app')

@section('content')
<style>
    :root {
        --admin-bg: #f8f9fc;
        --card-border-radius: 15px;
        --primary-accent: #4e73df;
    }

    body { background-color: var(--admin-bg); }

    /* Header Styling */
    .report-header {
        background: white;
        padding: 1.5rem;
        border-radius: var(--card-border-radius);
        border-left: 5px solid var(--primary-accent);
    }

    /* Stats Cards with Glassmorphism touch */
    .stat-card {
        border: none;
        border-radius: var(--card-border-radius);
        transition: transform 0.2s;
    }
    .stat-card:hover { transform: translateY(-5px); }
    
    .icon-shape {
        width: 48px;
        height: 48px;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }

    /* Modern Table */
    .table-card {
        border-radius: var(--card-border-radius);
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
    }
    
    .table thead th {
        background-color: #f8f9fc;
        text-transform: uppercase;
        font-size: 0.7rem;
        font-weight: 700;
        color: #4e73df;
        border: none;
    }

    .badge-soft { font-weight: 600; padding: 0.5em 0.8em; border-radius: 8px; }
</style>

<div class="container-fluid py-4">
    
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="report-header shadow-sm d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-dark mb-0">
                        <i class="fas fa-chart-line text-primary me-2"></i>{{ $exam->name }}
                    </h2>
                    <span class="text-muted fw-medium">
                        <i class="far fa-calendar-alt me-1"></i> {{ $exam->academicSession->session_name ?? 'N/A' }} 
                        <span class="mx-2">|</span> 
                        <i class="fas fa-layer-group me-1"></i> {{ $exam->semester->semester_name ?? 'N/A' }}
                    </span>
                </div>
                <div>
                    <a href="{{ route('admin.exams.results.pdf', $exam->id) }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                        <i class="fas fa-file-pdf me-2"></i>Generate Report
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card bg-gradient-primary shadow text-white" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 text-uppercase small">Total Students</h6>
                            <h2 class="fw-bold mb-0">{{ $stats['total_students'] }}</h2>
                        </div>
                        <div class="icon-shape"><i class="fas fa-users fs-4"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-gradient-success shadow text-white" style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 text-uppercase small">Completed</h6>
                            <h2 class="fw-bold mb-0">{{ $stats['marked_students'] }}</h2>
                        </div>
                        <div class="icon-shape"><i class="fas fa-check-double fs-4"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-gradient-warning shadow text-white" style="background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 text-uppercase small">Pending</h6>
                            <h2 class="fw-bold mb-0">{{ $stats['pending_students'] }}</h2>
                        </div>
                        <div class="icon-shape"><i class="fas fa-hourglass-half fs-4"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-gradient-info shadow text-white" style="background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 text-uppercase small">Avg Score</h6>
                            <h2 class="fw-bold mb-0">{{ number_format($stats['average_score'], 1) }}</h2>
                        </div>
                        <div class="icon-shape"><i class="fas fa-percentage fs-4"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        {{-- Distribution Chart --}}
        <div class="col-lg-8">
            <div class="card table-card h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">Grade Distribution Analysis</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 300px;">
                        <canvas id="gradeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Score Details --}}
        <div class="col-lg-4">
            <div class="card table-card h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">Performance Range</h6>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <h1 class="display-4 fw-bold text-dark">{{ $stats['highest_score'] ?? '0' }}</h1>
                        <p class="text-muted text-uppercase small">Highest Achieved</p>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted fw-bold small text-uppercase">Lowest Score</span>
                        <span class="badge bg-danger rounded-pill px-3">{{ $stats['lowest_score'] ?? '0' }}</span>
                    </div>
                    <div class="p-3 bg-light rounded-3">
                        <p class="small text-muted mb-0 italic">"Overview of the general academic performance across all classes."</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Class Breakdown Table --}}
    <div class="card table-card mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary">Class Performance Breakdown</h6>
        </div>
        <div class="table-responsive p-0">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Class Name</th>
                        <th class="text-center">Total Students</th>
                        <th class="text-center">Average</th>
                        <th class="text-center">Pass Rate</th>
                        <th class="text-center">A</th>
                        <th class="text-center">B</th>
                        <th class="text-center">C</th>
                        <th class="text-center">D/F</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($marksByClass as $className => $marks)
                    <tr>
                        <td class="ps-4 py-3 fw-bold text-dark">{{ $className }}</td>
                        <td class="text-center">{{ $marks->count() }}</td>
                        <td class="text-center">
                            <span class="fw-bold">{{ number_format($marks->avg('marks'), 1) }}</span>
                        </td>
                        <td class="text-center">
                            @php
                                $passCount = $marks->where('marks', '>=', 40)->count();
                                $passRate = ($marks->count() > 0) ? ($passCount / $marks->count() * 100) : 0;
                                $color = $passRate >= 70 ? 'success' : ($passRate >= 50 ? 'warning' : 'danger');
                            @endphp
                            <div class="progress" style="height: 8px; width: 100px; margin: 0 auto 5px;">
                                <div class="progress-bar bg-{{ $color }}" style="width: {{ $passRate }}%"></div>
                            </div>
                            <small class="fw-bold text-{{ $color }}">{{ number_format($passRate, 1) }}%</small>
                        </td>
                        <td class="text-center"><span class="badge bg-success opacity-75">{{ $marks->where('grade', 'A')->count() }}</span></td>
                        <td class="text-center"><span class="badge bg-primary opacity-75">{{ $marks->where('grade', 'B')->count() }}</span></td>
                        <td class="text-center"><span class="badge bg-warning opacity-75">{{ $marks->where('grade', 'C')->count() }}</span></td>
                        <td class="text-center">
                            <span class="badge bg-danger opacity-75">{{ $marks->whereIn('grade', ['D', 'F'])->count() }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const gradeData = @json($gradeDistribution);
        new Chart(document.getElementById('gradeChart'), {
            type: 'bar', // Changed to bar for more professional look
            data: {
                labels: Object.keys(gradeData).map(g => 'Grade ' + g),
                datasets: [{
                    label: 'Number of Students',
                    data: Object.values(gradeData),
                    backgroundColor: ['#1cc88a', '#4e73df', '#f6c23e', '#e74a3b', '#858796'],
                    borderRadius: 8,
                    barThickness: 40
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { drawBorder: false, color: '#f1f1f1' } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endsection