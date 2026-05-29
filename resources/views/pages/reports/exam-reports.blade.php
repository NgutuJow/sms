@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Exam Reports & Rankings
                    </h4>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" id="classFilter" style="width: auto;">
                            <option value="">All Classes</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                        <select class="form-select form-select-sm" id="examFilter" style="width: auto;">
                            <option value="">All Exams</option>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                                    {{ $exam->name }}
                                </option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary btn-sm" onclick="applyFilters()">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    @if(count($studentReports) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Rank</th>
                                        <th>Student Name</th>
                                        <th>Admission No</th>
                                        <th>Class</th>
                                        <th>Exam</th>
                                        <th>Total Marks</th>
                                        <th>Average</th>
                                        <th>Grade</th>
                                        <th>Subjects</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($studentReports as $report)
                                    <tr class="{{ $report['rank'] <= 3 ? 'table-warning' : '' }}">
                                        <td>
                                            <span class="badge bg-{{ $report['rank'] <= 3 ? 'warning' : 'secondary' }} fs-6">
                                                #{{ $report['rank'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-3">
                                                    <div class="avatar-initial bg-primary rounded-circle">
                                                        {{ substr($report['student']->first_name ?? 'S', 0, 1) }}{{ substr($report['student']->last_name ?? 'U', 0, 1) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $report['student']->first_name ?? 'Unknown' }} {{ $report['student']->last_name ?? 'Student' }}</h6>
                                                    <small class="text-muted">{{ $report['student']->admission_no ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $report['student']->admission_no ?? 'N/A' }}</td>
                                        <td>{{ $report['student']->classData->name ?? 'N/A' }}</td>
                                        <td>{{ $report['exam']->name ?? 'N/A' }}</td>
                                        <td><strong>{{ $report['total_marks'] }}</strong></td>
                                        <td>
                                            <span class="badge bg-info">{{ $report['average'] }}%</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $report['grade'] == 'A' ? 'success' : ($report['grade'] == 'B' || $report['grade'] == 'B+' ? 'info' : ($report['grade'] == 'C' || $report['grade'] == 'C+' ? 'warning' : 'danger')) }}">
                                                {{ $report['grade'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    {{ $report['marks']->count() }} Subjects
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @foreach($report['marks'] as $mark)
                                                    <li class="dropdown-item">
                                                        <small>
                                                            <strong>{{ $mark->subject->name ?? 'N/A' }}:</strong>
                                                            {{ $mark->marks }} marks
                                                            <span class="badge bg-secondary ms-1">{{ $mark->grade ?? 'N/A' }}</span>
                                                        </small>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('exam-reports.student', $report['student']->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye me-1"></i>View Details
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Statistics -->
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <h5 class="text-primary">{{ count($studentReports) }}</h5>
                                        <p class="mb-0">Total Students</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <h5 class="text-success">{{ collect($studentReports)->where('grade', 'A')->count() }}</h5>
                                        <p class="mb-0">Grade A Students</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-warning">
                                    <div class="card-body text-center">
                                        <h5 class="text-warning">{{ collect($studentReports)->whereIn('grade', ['C', 'C+', 'D', 'E'])->count() }}</h5>
                                        <p class="mb-0">At Risk Students</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-info">
                                    <div class="card-body text-center">
                                        <h5 class="text-info">{{ round(collect($studentReports)->avg('average'), 1) }}%</h5>
                                        <p class="mb-0">Class Average</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-line fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No Exam Reports Found</h5>
                            <p class="text-muted">Select a class and exam to view student performance reports.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function applyFilters() {
    const classId = document.getElementById('classFilter').value;
    const examId = document.getElementById('examFilter').value;

    let url = '{{ route("exam-reports.index") }}';
    const params = new URLSearchParams();

    if (classId) params.append('class_id', classId);
    if (examId) params.append('exam_id', examId);

    if (params.toString()) {
        url += '?' + params.toString();
    }

    window.location.href = url;
}
</script>
@endpush