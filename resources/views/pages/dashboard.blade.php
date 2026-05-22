@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <!-- Professional Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-5 gap-3">
        <div>
            <h3 class="fw-extrabold text-dark mb-1">Executive Overview</h3>
            <p class="text-secondary mb-0 small fw-medium">Welcome back, Admin. Monitoring system performance for {{ now()->format('F d, Y') }}.</p>
        </div>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-white border btn-sm px-3 rounded-pill fw-bold dropdown-toggle shadow-sm" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-download me-2 text-muted x-small"></i>Export Report
                </button>
                <ul class="dropdown-menu border-0 shadow-sm rounded-3">
                    <li><a class="dropdown-item small" href="{{ route('finance.pdf-export.index') }}">Financial Report</a></li>
                    <li><a class="dropdown-item small" href="{{ route('exam-reports.index') }}">Academic Report</a></li>
                </ul>
            </div>
            <a href="{{ route('academic.attendance.index') }}" class="btn btn-primary btn-sm px-3 shadow-sm rounded-pill fw-bold">
                <i class="fas fa-calendar-check me-2 small"></i>Daily Attendance
            </a>
        </div>
    </div>

    <!-- Analytics Summary Strip -->
    <div class="row g-4 mb-5">
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-3">
                            <i class="fas fa-user-graduate text-primary"></i>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill x-small">+{{ $newAdmissions }} new</span>
                    </div>
                    <h6 class="text-secondary x-small fw-bold text-uppercase tracking-wider mb-2">Total Students</h6>
                    <h3 class="fw-bold text-dark mb-0">{{ number_format($studentsCount) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 p-2 rounded-3">
                            <i class="fas fa-wallet text-success"></i>
                        </div>
                        <span class="text-muted x-small fw-bold">TZS</span>
                    </div>
                    <h6 class="text-secondary x-small fw-bold text-uppercase tracking-wider mb-2">Fees Collected</h6>
                    <h3 class="fw-bold text-dark mb-0">{{ number_format($feesCollected, 0) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-warning bg-opacity-10 p-2 rounded-3">
                            <i class="fas fa-chalkboard-teacher text-warning"></i>
                        </div>
                        <span class="text-muted x-small fw-bold">{{ $branchesCount }} Branches</span>
                    </div>
                    <h6 class="text-secondary x-small fw-bold text-uppercase tracking-wider mb-2">Total Staff</h6>
                    <h3 class="fw-bold text-dark mb-0">{{ number_format($teachersCount) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-info bg-opacity-10 p-2 rounded-3">
                            <i class="fas fa-check-double text-info"></i>
                        </div>
                        <span class="text-muted x-small fw-bold">{{ $attendanceTodayTotal - $attendanceTodayPresent }} Absent</span>
                    </div>
                    <h6 class="text-secondary x-small fw-bold text-uppercase tracking-wider mb-2">Today's Attendance</h6>
                    <h3 class="fw-bold text-dark mb-0">{{ $attendanceRate !== null ? $attendanceRate . '%' : '0%' }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Analytics Section -->
    <div class="row g-4 mb-5">
        <!-- Revenue Trend -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-0 p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold text-dark mb-0">Revenue Analytics</h6>
                        <span class="text-muted x-small">Monthly fee collection trends</span>
                    </div>
                    <div class="btn-group btn-group-sm rounded-pill p-1 bg-light">
                        <button type="button" class="btn btn-white border-0 rounded-pill px-3 fw-bold x-small shadow-sm">6 Months</button>
                        <button type="button" class="btn btn-transparent border-0 rounded-pill px-3 fw-bold x-small">Yearly</button>
                    </div>
                </div>
                <div class="card-body p-4 pt-0">
                    <div style="height: 300px;">
                        <canvas id="feesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Stats -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-0 p-4 pb-0">
                    <h6 class="fw-bold text-dark mb-0">Quick Operations</h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-3 mb-4">
                        <a href="{{ route('students.create') }}" class="btn btn-light border-0 py-3 rounded-4 d-flex align-items-center px-4 hover-lift">
                            <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                <i class="fas fa-user-plus text-primary x-small"></i>
                            </div>
                            <div class="text-start">
                                <div class="fw-bold text-dark small">Enroll Student</div>
                                <div class="text-muted x-small">Add new student to record</div>
                            </div>
                            <i class="fas fa-chevron-right ms-auto text-muted x-small"></i>
                        </a>
                        <a href="{{ route('finance.index') }}" class="btn btn-light border-0 py-3 rounded-4 d-flex align-items-center px-4 hover-lift">
                            <div class="bg-success bg-opacity-10 p-2 rounded-3 me-3">
                                <i class="fas fa-hand-holding-usd text-success x-small"></i>
                            </div>
                            <div class="text-start">
                                <div class="fw-bold text-dark small">Record Payment</div>
                                <div class="text-muted x-small">Post school fee receipt</div>
                            </div>
                            <i class="fas fa-chevron-right ms-auto text-muted x-small"></i>
                        </a>
                    </div>

                    <h6 class="fw-bold text-dark mb-3 small">Urgent Notifications</h6>
                    <div class="vstack gap-2">
                        <div class="d-flex align-items-center p-2 rounded-3 bg-danger bg-opacity-10 border-start border-danger border-4">
                            <i class="fas fa-exclamation-triangle text-danger me-2 x-small"></i>
                            <div class="text-danger x-small fw-bold">{{ number_format($pendingInvoicesCount) }} Unpaid Invoices</div>
                        </div>
                        <div class="d-flex align-items-center p-2 rounded-3 bg-warning bg-opacity-10 border-start border-warning border-4">
                            <i class="fas fa-clock text-warning me-2 x-small"></i>
                            <div class="text-warning x-small fw-bold">{{ $upcomingExams->count() }} Upcoming Examinations</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lower Section -->
    <div class="row g-4">
        <!-- Enrollment by Branch -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-0 p-4">
                    <h6 class="fw-bold text-dark mb-0">Student Enrollment</h6>
                    <span class="text-muted x-small">Distribution across branches</span>
                </div>
                <div class="card-body p-4 pt-0">
                    <canvas id="enrollmentChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Latest Announcements -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-0 p-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0">Bulletin Board</h6>
                    <a href="{{ route('announcements.index') }}" class="text-primary x-small fw-bold text-decoration-none">View All</a>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="list-group list-group-flush">
                        @forelse($announcements as $announcement)
                            <div class="list-group-item border-0 px-0 py-3">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <span class="badge bg-light text-primary rounded-pill x-small fw-bold">{{ ucfirst($announcement->audience) }}</span>
                                    <span class="text-muted x-small fw-medium">{{ $announcement->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="fw-bold text-dark small mb-1">{{ Str::limit($announcement->title, 50) }}</div>
                                <p class="text-muted x-small mb-2">{{ Str::limit(strip_tags($announcement->content), 80) }}</p>
                                @if($announcement->pdf_path)
                                    <a href="{{ route('announcements.download', $announcement->id) }}" class="text-primary x-small fw-bold text-decoration-none border-bottom border-primary">
                                        <i class="fas fa-file-pdf me-1"></i>Document attached
                                    </a>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-bullhorn fa-2x text-light mb-3"></i>
                                <p class="text-muted small">No active announcements</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Branch Performance -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-0 p-4">
                    <h6 class="fw-bold text-dark mb-0">Branch Ledger</h6>
                    <span class="text-muted x-small">Regional performance overview</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 border-0">
                            <thead class="bg-light border-0">
                                <tr class="border-0">
                                    <th class="ps-4 border-0 x-small fw-bold text-uppercase tracking-wider">Branch</th>
                                    <th class="border-0 x-small fw-bold text-uppercase tracking-wider text-end">Students</th>
                                    <th class="pe-4 border-0 x-small fw-bold text-uppercase tracking-wider text-end">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($branchStats as $b)
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 py-3">
                                            <div class="fw-bold text-dark small">{{ $b->branch }}</div>
                                        </td>
                                        <td class="text-end py-3">
                                            <span class="text-dark small fw-medium">{{ number_format($b->students) }}</span>
                                        </td>
                                        <td class="pe-4 text-end py-3">
                                            <span class="text-dark small fw-bold">TZS {{ number_format($b->fees, 0) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fw-extrabold { font-weight: 800; }
    .x-small { font-size: 0.7rem; }
    .tracking-wider { letter-spacing: 0.05em; }
    .rounded-4 { border-radius: 1.2rem !important; }
    .btn-white { background-color: #fff; color: #1e293b; }
    .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-lift:hover { transform: translateY(-3px); box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.08) !important; background: #fff !important; }
    .border-4 { border-left-width: 4px !important; }
</style>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Global Config
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#64748b';

    // Enrollment Chart
    const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
    new Chart(enrollmentCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($enrollmentLabels ?? []) !!},
            datasets: [{
                data: {!! json_encode($enrollmentData ?? []) !!},
                backgroundColor: ['#2563eb', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'],
                borderWidth: 0,
                cutout: '75%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20, font: { size: 10, weight: 'bold' } } }
            }
        }
    });

    // Revenue Chart
    const feesCtx = document.getElementById('feesChart').getContext('2d');
    const gradient = feesCtx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(37, 99, 235, 0.1)');
    gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');

    new Chart(feesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($feesLabels ?? []) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($feesData ?? []) !!},
                borderColor: '#2563eb',
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#2563eb',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                backgroundColor: gradient,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 12,
                    titleFont: { size: 12, weight: 'bold' },
                    bodyFont: { size: 12 },
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9', drawBorder: false },
                    ticks: {
                        font: { size: 10, weight: 'bold' },
                        callback: function(value) { return value.toLocaleString(); }
                    }
                },
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { font: { size: 10, weight: 'bold' } }
                }
            }
        }
    });
</script>
@endsection
