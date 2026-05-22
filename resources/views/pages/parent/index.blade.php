@extends('pages.parent.layout.layout')

@section('content')
<div class="row g-4">
    <!-- Welcome Card -->
    <div class="col-12">
        <div class="card-custom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Welcome back, {{ auth()->user()->name }}!</h4>
                    <p class="text-muted mb-0">Here's an overview of your children's academic progress</p>
                </div>
                <div class="text-end">
                    <div class="fw-bold text-primary">{{ $students->count() }} Children</div>
                    <small class="text-muted">Enrolled</small>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Summary -->
    <div class="col-12">
        <div class="row g-3">
            <div class="col-sm-6 col-md-3">
                <div class="card-custom text-center">
                    <div class="fw-semibold">Overall Attendance</div>
                    <div class="h3 mt-2 text-success">{{ $overallAttendance ?? '—' }}%</div>
                    <small class="text-muted">Past 30 days</small>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card-custom text-center">
                    <div class="fw-semibold">Avg. Performance</div>
                    <div class="h3 mt-2 text-primary">{{ $avgGrade ?? '—' }}</div>
                    <small class="text-muted">Latest term</small>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card-custom text-center">
                    <div class="fw-semibold">Pending Fees</div>
                    <div class="h3 mt-2 text-warning">{{ $pendingInvoices->count() }}</div>
                    <small class="text-muted">Invoices</small>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card-custom text-center">
                    <div class="fw-semibold">Unread Messages</div>
                    <div class="h3 mt-2 text-info">{{ $unreadMessages ?? 0 }}</div>
                    <small class="text-muted">Support & teachers</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcements -->
    <div class="col-12">
        <div class="card-custom">
            <h5 class="mb-3"><i class="fa-solid fa-bullhorn me-2"></i>Parent Announcements</h5>
            @if($announcements->count() > 0)
                @foreach($announcements as $announcement)
                    <div class="mb-3 p-3 border rounded-3 bg-white">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-1">{{ $announcement->title }}</h6>
                                <small class="text-muted">{{ $announcement->created_at->format('M d, Y') }}</small>
                            </div>
                            @if($announcement->pdf_path)
                                <a href="{{ route('announcements.download', $announcement->id) }}" class="btn btn-sm btn-outline-danger">
                                    <i class="fa-solid fa-file-pdf me-1"></i>PDF
                                </a>
                            @endif
                        </div>
                        <p class="mb-0 text-muted">{{ Str::limit($announcement->description, 120) }}</p>
                    </div>
                @endforeach
            @else
                <div class="text-center py-4 text-muted">
                    <i class="fa-solid fa-inbox me-2"></i>No announcements yet
                </div>
            @endif
        </div>
    </div>

    <!-- Children Overview -->
    <div class="col-12">
        <div class="card-custom">
            <h5 class="mb-3"><i class="fa-solid fa-users me-2"></i>Your Children</h5>
            <div class="row g-3">
                @foreach($students as $student)
                <div class="col-md-6 col-lg-4">
                    <div class="card-custom">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; font-size: 18px;">
                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-0">{{ $student->first_name }} {{ $student->last_name }}</h6>
                                        <small class="text-muted">Admission: {{ $student->admission_no }}</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold">{{ $student->classData->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $student->streamData->name ?? '' }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('parent.student.details', $student->id) }}" class="btn btn-outline-primary btn-compact w-100">
                                <i class="fa-solid fa-user me-2"></i>Profile
                            </a>
                            <a href="#" class="btn btn-outline-secondary btn-compact w-100">
                                <i class="fa-solid fa-chart-line me-2"></i>Reports
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Attendance Summary -->
    <div class="col-lg-8">
        <div class="card-custom">
            <h5 class="mb-3"><i class="fa-solid fa-calendar-check me-2"></i>Attendance Summary</h5>
            <div class="row g-3">
                @foreach($attendanceSummary as $summary)
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-body">
                            <h6 class="card-title">{{ $summary['student']->first_name }} {{ $summary['student']->last_name }}</h6>
                            <div class="row text-center mb-2">
                                <div class="col-4">
                                    <div class="h4 mb-0 text-success">{{ $summary['present_days'] }}</div>
                                    <small class="text-muted">Present</small>
                                </div>
                                <div class="col-4">
                                    <div class="h4 mb-0 text-danger">{{ $summary['total_days'] - $summary['present_days'] }}</div>
                                    <small class="text-muted">Absent</small>
                                </div>
                                <div class="col-4">
                                    <div class="h4 mb-0 text-primary">{{ $summary['percentage'] }}%</div>
                                    <small class="text-muted">Rate</small>
                                </div>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: {{ $summary['percentage'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Quick Actions & Upcoming -->
    <div class="col-lg-4">
        <div class="row g-4">
            <!-- Quick Actions -->
            <div class="col-12">
                <div class="card-custom">
                    <h6 class="mb-3">Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('parent.attendance') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fa-solid fa-calendar-check me-2"></i>View Attendance
                        </a>
                        <a href="#" class="btn btn-outline-success btn-sm">
                            <i class="fa-solid fa-file-pen me-2"></i>Exam Results
                        </a>
                        <a href="#" class="btn btn-outline-info btn-sm">
                            <i class="fa-solid fa-paper-plane me-2"></i>Messages
                        </a>
                    </div>
                </div>
            </div>

            <!-- Upcoming Exams -->
            <div class="col-12">
                <div class="card-custom">
                    <h6 class="mb-3">Upcoming Exams</h6>
                    @if($upcomingExams->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($upcomingExams->take(3) as $exam)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-medium">{{ $exam->exam->name ?? 'Exam' }}</div>
                                        <small class="text-muted">{{ $exam->class->name ?? 'Class' }}</small>
                                    </div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($exam->published_date)->format('M d') }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">No upcoming exams</p>
                    @endif
                </div>
            </div>

            <!-- Fee Alerts -->
            @if($pendingInvoices->count() > 0)
            <div class="col-12">
                <div class="card-custom border-warning">
                    <h6 class="mb-3 text-warning"><i class="fa-solid fa-triangle-exclamation me-2"></i>Fee Alerts</h6>
                    <div class="alert alert-warning py-2">
                        <small>{{ $pendingInvoices->count() }} pending invoice{{ $pendingInvoices->count() > 1 ? 's' : '' }}</small>
                    </div>
                    <a href="#" class="btn btn-warning btn-sm w-100">View Details</a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row g-4 mt-2">
    <div class="col-lg-6">
        <div class="card-custom">
            <h5 class="mb-3">Attendance Trends</h5>
            <canvas id="attendanceChart" width="400" height="200"></canvas>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card-custom">
            <h5 class="mb-3">Performance Overview</h5>
            <canvas id="performanceChart" width="400" height="200"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Attendance Chart
const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
new Chart(attendanceCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Attendance %',
            data: [85, 88, 92, 87, 90, 93],
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37, 99, 235, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true, max: 100 }
        }
    }
});

// Performance Chart
const performanceCtx = document.getElementById('performanceChart').getContext('2d');
new Chart(performanceCtx, {
    type: 'bar',
    data: {
        labels: ['Math', 'English', 'Science', 'History', 'Geography'],
        datasets: [{
            label: 'Average Grade',
            data: [85, 78, 92, 88, 83],
            backgroundColor: '#10b981',
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true, max: 100 }
        }
    }
});
</script>
@endpush