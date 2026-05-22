@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>{{ $exam->name }} - {{ $class->name }} Results</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('results.exam', $exam->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Students</h6>
                    <h3>{{ $statistics['total_students'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Average Marks</h6>
                    <h3>{{ round($statistics['average_marks'] ?? 0, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Highest Marks</h6>
                    <h3>{{ $statistics['highest_marks'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Pass Rate</h6>
                    <h3>{{ round($statistics['pass_rate'] ?? 0, 2) }}%</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Position</th>
                    <th>Student</th>
                    <th>Admission No</th>
                    <th>Average Marks</th>
                    <th>Grade</th>
                    <th>Remarks</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $result)
                    <tr>
                        <td><strong>{{ $result->position }}</strong></td>
                        <td>{{ $result->student->first_name }} {{ $result->student->last_name }}</td>
                        <td>{{ $result->student->admission_no }}</td>
                        <td>{{ round($result->average_marks, 2) }}</td>
                        <td><span class="badge bg-info">{{ $result->grade }}</span></td>
                        <td>{{ $result->remarks ?? '-' }}</td>
                        <td>
                            @if($result->is_passed)
                                <span class="badge bg-success">PASS</span>
                            @else
                                <span class="badge bg-danger">FAIL</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No results found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($results->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $results->links() }}
        </div>
    @endif
</div>
@endsection
