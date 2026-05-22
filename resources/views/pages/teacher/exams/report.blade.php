@extends('pages.teacher.layout.layout')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Marking Report: {{ $exam->name }}</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('teacher.exams.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Classes Marked</h6>
                    <h3>{{ $report['classes_marked'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Marks Entered</h6>
                    <h3>{{ $report['total_marks_entered'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Class-wise Marking Report</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Class</th>
                        <th>Total Marked</th>
                        <th>Average Marks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report['marks_by_class'] as $classReport)
                        <tr>
                            <td>{{ $classReport['class_id'] }}</td>
                            <td>{{ $classReport['total_marked'] }}</td>
                            <td>{{ round($classReport['average_marks'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
