@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-lg-5" style="background-color: #f8f9fa; min-height: 100vh;">
    
    {{-- HEADER SECTION --}}
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="bi bi-graph-up"></i> {{ $student->user->name ?? $student->name ?? 'Student' }} - Performance Report
            </h2>
            <small class="text-muted">
                <strong>Admission No:</strong> {{ $student->admission_no ?? 'N/A' }} | 
                <strong>Class:</strong> {{ $student->classData->class_name ?? 'N/A' }} |
                <strong>Rank:</strong> <span class="badge bg-warning">{{ $studentRank }} of {{ $totalInClass }}</span>
            </small>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.students.report.pdf', $student->id) }}" class="btn btn-danger">
                <i class="bi bi-file-pdf"></i> Download PDF
            </a>
            <a href="{{ route('admin.students.list') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    {{-- STATISTICS CARDS --}}
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-primary text-white border-0">
                <div class="card-body">
                    <h6 class="card-title text-white-50 small">Total Exams</h6>
                    <h3 class="mb-0">{{ $stats['total_exams'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-info text-white border-0">
                <div class="card-body">
                    <h6 class="card-title text-white-50 small">Subjects</h6>
                    <h3 class="mb-0">{{ $stats['total_subjects_taken'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-success text-white border-0">
                <div class="card-body">
                    <h6 class="card-title text-white-50 small">Avg Marks</h6>
                    <h3 class="mb-0">{{ number_format($stats['overall_average'], 1) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-warning text-white border-0">
                <div class="card-body">
                    <h6 class="card-title text-white-50 small">Highest</h6>
                    <h3 class="mb-0">{{ $stats['highest_single_mark'] ?? 'N/A' }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-danger text-white border-0">
                <div class="card-body">
                    <h6 class="card-title text-white-50 small">Lowest</h6>
                    <h3 class="mb-0">{{ $stats['lowest_single_mark'] ?? 'N/A' }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-secondary text-white border-0">
                <div class="card-body">
                    <h6 class="card-title text-white-50 small">Total Marks</h6>
                    <h3 class="mb-0">{{ $stats['total_marks_obtained'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- CLASS RANK --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body text-center py-4">
                    <div style="font-size: 2rem; color: #1e40af; font-weight: bold;">
                        #{{ $studentRank }}
                    </div>
                    <h6 class="text-muted mt-2">Class Ranking</h6>
                    <p class="text-muted small mb-0">Out of {{ $totalInClass }} students</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body">
                    <h6 class="card-title">Grade Distribution</h6>
                    <canvas id="gradeChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body">
                    <h6 class="card-title mb-3">Student Details</h6>
                    <dl class="row small mb-0">
                        <dt class="col-sm-5">Branch:</dt>
                        <dd class="col-sm-7"><strong>{{ $student->branch->name ?? 'N/A' }}</strong></dd>
                        <dt class="col-sm-5">Stream:</dt>
                        <dd class="col-sm-7"><strong>{{ $student->stream ?? 'N/A' }}</strong></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    {{-- TOP SUBJECTS --}}
    @if($topSubjects->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0"><i class="bi bi-star-fill text-warning"></i> Top Performing Subjects</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($topSubjects as $subject)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center p-3 border rounded-3">
                                <div style="flex: 1;">
                                    <h6 class="mb-1">{{ $subject['subject']->name ?? 'N/A' }}</h6>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar" role="progressbar" 
                                            style="width: {{ ($subject['average'] / 100) * 100 }}%;" 
                                            aria-valuenow="{{ $subject['average'] }}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                                <div class="ms-3 text-end">
                                    <h5 class="mb-0 text-primary">{{ number_format($subject['average'], 1) }}</h5>
                                    <small class="text-muted">avg ({{ $subject['count'] }} exams)</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- RESULTS BY EXAM --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0"><i class="bi bi-file-text"></i> Results by Examination</h6>
                </div>
                <div class="card-body p-0">
                    @forelse($marksByExam as $examId => $examData)
                    <div class="border-bottom p-4">
                        <div class="row align-items-center mb-3">
                            <div class="col-md-6">
                                <h6 class="mb-0"><strong>{{ $examData['exam']->name ?? 'Exam' }}</strong></h6>
                                <small class="text-muted">{{ $examData['exam']->academicSession->session_name ?? '' }} | {{ $examData['exam']->semester->semester_name ?? '' }}</small>
                            </div>
                            <div class="col-md-6 text-end">
                                <span class="badge bg-primary me-2">Total: {{ $examData['total_marks'] }}</span>
                                <span class="badge bg-success">Avg: {{ number_format($examData['average_marks'], 1) }}</span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Subject</th>
                                        <th class="text-center">Marks</th>
                                        <th class="text-center">Grade</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($examData['marks'] as $mark)
                                    <tr>
                                        <td>{{ $mark->subject->name ?? 'N/A' }}</td>
                                        <td class="text-center"><strong>{{ $mark->marks }}</strong></td>
                                        <td class="text-center">
                                            @php
                                                if ($mark->grade === 'A') {
                                                    $gradeClass = 'success';
                                                } elseif ($mark->grade === 'B') {
                                                    $gradeClass = 'primary';
                                                } elseif ($mark->grade === 'C') {
                                                    $gradeClass = 'warning';
                                                } elseif ($mark->grade === 'D') {
                                                    $gradeClass = 'danger';
                                                } else {
                                                    $gradeClass = 'secondary';
                                                }
                                            @endphp
                                            <span class="badge bg-{{ $gradeClass }}">{{ $mark->grade }}</span>
                                        </td>
                                        <td>{{ $mark->remarks ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @empty
                    <div class="p-4 text-center text-muted">
                        <p>No exam results available for this student</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const gradeData = @json($gradeDistribution);
        const gradeLabels = Object.keys(gradeData);
        const gradeValues = Object.values(gradeData);

        const gradeChartCanvas = document.getElementById('gradeChart');
        if (gradeChartCanvas) {
            const gradeCtx = gradeChartCanvas.getContext('2d');
            new Chart(gradeCtx, {
                type: 'doughnut',
                data: {
                    labels: gradeLabels.map(grade => `Grade ${grade}`),
                    datasets: [{
                        data: gradeValues,
                        backgroundColor: [
                            '#28a745', // A - Green
                            '#007bff', // B - Blue
                            '#ffc107', // C - Yellow
                            '#dc3545', // D - Red
                            '#6c757d'  // F - Gray
                        ],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
