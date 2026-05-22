@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>My Results</h2>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Exam</th>
                    <th>Class</th>
                    <th>Average Marks</th>
                    <th>Grade</th>
                    <th>Position</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $result)
                    <tr>
                        <td>
                            <strong>{{ $result->exam->name }}</strong>
                        </td>
                        <td>{{ $result->class->name }}</td>
                        <td>{{ round($result->average_marks, 2) }}/{{ $result->exam->total_marks }}</td>
                        <td>
                            <span class="badge bg-info">{{ $result->grade }}</span>
                        </td>
                        <td>{{ $result->position }}</td>
                        <td>
                            @if($result->is_passed)
                                <span class="badge bg-success">PASS</span>
                            @else
                                <span class="badge bg-danger">FAIL</span>
                            @endif
                        </td>
                        <td>{{ $result->remarks ?? '-' }}</td>
                        <td>
                            <a href="{{ route('student.result', $result->exam->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No results available</td>
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
