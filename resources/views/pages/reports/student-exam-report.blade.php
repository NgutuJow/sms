@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">
                                <i class="fas fa-user-graduate me-2"></i>Student Exam Report
                            </h4>
                            <p class="mb-0 text-muted">{{ $student->first_name }} {{ $student->last_name }} ({{ $student->admission_no }})</p>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <div class="text-end">
                                <p class="mb-0"><strong>Class:</strong> {{ $student->classData->name ?? 'N/A' }}</p>
                                <p class="mb-0"><strong>Stream:</strong> {{ $student->streamData->name ?? 'N/A' }}</p>
                            </div>
                            <a href="{{ route('exam-reports.student.pdf', $student->id) }}" class="btn btn-info btn-sm" title="Download PDF Report">
                                <i class="fas fa-file-pdf me-1"></i>Download PDF
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if($examReports)
                        @foreach($examReports as $report)
                        <div class="exam-report-section mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-file-alt me-2"></i>{{ $report['exam']->name ?? 'Exam' }}
                                </h5>
                                <div class="d-flex gap-2">
                                    <span class="badge bg-info fs-6">Average: {{ $report['average'] }}%</span>
                                    <span class="badge bg-{{ $report['grade'] == 'A' ? 'success' : ($report['grade'] == 'B' || $report['grade'] == 'B+' ? 'info' : ($report['grade'] == 'C' || $report['grade'] == 'C+' ? 'warning' : 'danger')) }} fs-6">
                                        Grade: {{ $report['grade'] }}
                                    </span>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Subject</th>
                                            <th>Marks Obtained</th>
                                            <th>Grade</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($report['marks'] as $mark)
                                        <tr>
                                            <td>
                                                <strong>{{ $mark->subject->name ?? 'N/A' }}</strong>
                                            </td>
                                            <td>
                                                <span class="h5 text-primary">{{ $mark->marks }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $mark->grade == 'A' ? 'success' : ($mark->grade == 'B' || $mark->grade == 'B+' ? 'info' : ($mark->grade == 'C' || $mark->grade == 'C+' ? 'warning' : 'danger')) }}">
                                                    {{ $mark->grade ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $mark->remarks ?? '-' }}
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr class="table-primary">
                                            <td colspan="1"><strong>Total/Overall Performance</strong></td>
                                            <td><strong>{{ $report['total_marks'] }} marks</strong></td>
                                            <td><strong>{{ $report['grade'] }}</strong></td>
                                            <td><strong>{{ $report['average'] }}% Average</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endforeach

                        <!-- Performance Summary -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Performance Summary</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h4 class="text-primary">{{ number_format(collect($examReports)->avg('average'), 1) }}%</h4>
                                                    <p class="mb-0">Overall Average</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h4 class="text-success">{{ collect($examReports)->where('grade', 'A')->count() }}</h4>
                                                    <p class="mb-0">A Grades</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h4 class="text-info">{{ collect($examReports)->count() }}</h4>
                                                    <p class="mb-0">Total Exams</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h4 class="text-warning">{{ collect($examReports)->whereIn('grade', ['B', 'B+', 'C', 'C+'])->count() }}</h4>
                                                    <p class="mb-0">B-C Grades</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No Exam Reports Found</h5>
                            <p class="text-muted">This student has no exam records yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection