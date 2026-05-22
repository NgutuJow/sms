@extends('pages.parent.layout.layout')

@section('content')
<div class="row g-4">
    <!-- Header -->
    <div class="col-12">
        <div class="card-custom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Children Exam Reports</h4>
                    <p class="text-muted mb-0">View detailed exam performance for your children</p>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <a href="{{ route('parent.exam-reports.download', ['exam_id' => request('exam_id')]) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-file-pdf me-1"></i>Download PDF
                    </a>
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
        </div>
    </div>

    @if($students->count() == 0)
    <!-- No Students Message -->
    <div class="col-12">
        <div class="card-custom">
            <div class="text-center py-5">
                <i class="fas fa-users fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No Children Found</h5>
                <p class="text-muted">You don't have any children registered in the system yet.</p>
                <a href="{{ route('parent.dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
            </div>
        </div>
    </div>
    @else
    <!-- Student Reports -->
    @foreach($examReports as $studentId => $data)
    <div class="col-12">
        <div class="card-custom">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; font-size: 20px;">
                        {{ substr($data['student']->first_name, 0, 1) }}{{ substr($data['student']->last_name, 0, 1) }}
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $data['student']->first_name }} {{ $data['student']->last_name }}</h5>
                        <p class="mb-0 text-muted">Admission: {{ $data['student']->admission_no }} | Class: {{ $data['student']->classData->name ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="text-end">
                    <div class="h6 mb-0">{{ $data['reports'] ? count($data['reports']) : 0 }} Exam{{ count($data['reports']) != 1 ? 's' : '' }}</div>
                    <small class="text-muted">Completed</small>
                </div>
            </div>

            @if($data['reports'] && count($data['reports']) > 0)
                @foreach($data['reports'] as $report)
                <div class="exam-section mb-4 p-3 bg-light rounded">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">
                            <i class="fas fa-file-alt me-2"></i>{{ $report['exam']->name ?? 'Exam' }}
                        </h6>
                        <div class="d-flex gap-2">
                            <span class="badge bg-info">Avg: {{ $report['average'] }}%</span>
                            <span class="badge bg-{{ $report['grade'] == 'A' ? 'success' : ($report['grade'] == 'B' || $report['grade'] == 'B+' ? 'info' : ($report['grade'] == 'C' || $report['grade'] == 'C+' ? 'warning' : 'danger')) }}">
                                {{ $report['grade'] }}
                            </span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-borderless">
                            <thead>
                                <tr class="border-bottom">
                                    <th>Subject</th>
                                    <th>Marks</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($report['marks'] as $mark)
                                <tr>
                                    <td>{{ $mark->subject->name ?? 'N/A' }}</td>
                                    <td><strong>{{ $mark->marks }}</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $mark->grade == 'A' ? 'success' : ($mark->grade == 'B' || $mark->grade == 'B+' ? 'info' : ($mark->grade == 'C' || $mark->grade == 'C+' ? 'warning' : 'danger')) }} badge-sm">
                                            {{ $mark->grade ?? 'N/A' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                                <tr class="border-top">
                                    <td><strong>Total</strong></td>
                                    <td><strong class="text-primary">{{ $report['total_marks'] }}</strong></td>
                                    <td><strong class="text-primary">{{ $report['grade'] }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach

                <!-- Performance Summary for this student -->
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="text-center p-2 bg-primary bg-opacity-10 rounded">
                            <div class="h6 mb-0 text-primary">{{ round(collect($data['reports'])->avg('average'), 1) }}%</div>
                            <small class="text-muted">Overall Avg</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-2 bg-success bg-opacity-10 rounded">
                            <div class="h6 mb-0 text-success">{{ collect($data['reports'])->where('grade', 'A')->count() }}</div>
                            <small class="text-muted">A Grades</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-2 bg-info bg-opacity-10 rounded">
                            <div class="h6 mb-0 text-info">{{ collect($data['reports'])->count() }}</div>
                            <small class="text-muted">Exams</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-2 bg-warning bg-opacity-10 rounded">
                            <div class="h6 mb-0 text-warning">{{ collect($data['reports'])->whereIn('grade', ['B', 'B+', 'C', 'C+'])->count() }}</div>
                            <small class="text-muted">B-C Grades</small>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">No exam reports available</p>
                    <small class="text-muted">Exam results will appear here once marks are entered</small>
                </div>
            @endif
        </div>
    </div>
    @endforeach
    @endif
</div>
@endsection

@push('scripts')
<script>
function applyFilters() {
    const examId = document.getElementById('examFilter').value;

    let url = '{{ route("parent.exam-reports") }}';
    const params = new URLSearchParams();

    if (examId) params.append('exam_id', examId);

    if (params.toString()) {
        url += '?' + params.toString();
    }

    window.location.href = url;
}
</script>
@endpush