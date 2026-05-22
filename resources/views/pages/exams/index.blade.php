@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between mb-4">
        <h4 class="fw-bold">Exam Setup List</h4>
        <a href="{{ route('exams.create') }}" class="btn btn-dark rounded-0">
            Add Exam
        </a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Exam Name</th>
                <th>Academic Session</th>
                <th>Semester</th>
            </tr>
        </thead>
        <tbody>
            @foreach($exams as $exam)
            <tr>
                <td>{{ $exam->name }}</td>
                <td>{{ $exam->academicSession->name ?? '' }}</td>
                <td>{{ $exam->semester->semester_name ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection