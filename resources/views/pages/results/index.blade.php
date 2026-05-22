@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h4 class="fw-bold mb-3">Results Summary</h4>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Position</th>
                <th>Student</th>
                <th>Total Marks</th>
                <th>Average</th>
                <th>Grade</th>
            </tr>
        </thead>

        <tbody>
            @foreach($results as $index => $result)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $result['student'] }}</td>
                    <td>{{ $result['total'] }}</td>
                    <td>{{ $result['average'] }}</td>
                    <td>
                        <span class="badge bg-dark">
                            {{ $result['grade'] }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection