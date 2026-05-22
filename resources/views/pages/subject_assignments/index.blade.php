@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between mb-4">
        <h4 class="fw-bold">Assigned Subjects</h4>
        <a href="{{ route('subject-assignments.create') }}" class="btn btn-dark rounded-0">
            Assign Subject
        </a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Class</th>
                <th>Subject</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignments as $assign)
            <tr>
                <td>{{ $assign->classData->class_name ?? '' }}</td>
                <td>{{ $assign->subject->subject_name ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection