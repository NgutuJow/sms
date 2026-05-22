@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Exam Report: {{ $report['exam']->name }}</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.exams.show', $report['exam']->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Students</h6>
                    <h3>{{ $report['total_students_marked'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Average Marks</h6>
                    <h3>{{ round($report['overall_statistics']['average_marks'] ?? 0, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Highest Marks</h6>
                    <h3>{{ $report['overall_statistics']['highest_marks'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Pass Rate</h6>
                    <h3>{{ round($report['overall_statistics']['pass_rate'] ?? 0, 2) }}%</h3>
                </div>
            </div>
        </div>
    </div>

    @foreach($report['class_reports'] as $classReport)
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">{{ $classReport['class']->name }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Average Marks:</strong>
                        {{ round($classReport['report']['statistics']['average_marks'] ?? 0, 2) }}
                    </div>
                    <div class="col-md-3">
                        <strong>Highest Marks:</strong>
                        {{ $classReport['report']['statistics']['highest_marks'] ?? 0 }}
                    </div>
                    <div class="col-md-3">
                        <strong>Lowest Marks:</strong>
                        {{ $classReport['report']['statistics']['lowest_marks'] ?? 0 }}
                    </div>
                    <div class="col-md-3">
                        <strong>Marked Students:</strong>
                        {{ $classReport['report']['statistics']['marked_students'] ?? 0 }}
                    </div>
                </div>

                @if($classReport['report']['student_reports'])
                    <div class="table-responsive mt-3">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Position</th>
                                    <th>Student</th>
                                    <th>Average Marks</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($classReport['report']['student_reports'] as $i => $studentReport)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $studentReport['student']->first_name }} {{ $studentReport['student']->last_name }}</td>
                                        <td>{{ round($studentReport['average_marks'], 2) }}</td>
                                        <td><span class="badge bg-info">{{ $studentReport['subjects'][0]->grade ?? 'N/A' }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
