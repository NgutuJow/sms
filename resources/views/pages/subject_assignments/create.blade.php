@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h4 class="fw-bold text-uppercase mb-4">Assign Subject to Class</h4>

    <form action="{{ route('subject-assignments.store') }}" method="POST">
        @csrf

        <div class="row g-3">

            <div class="col-md-6">
                <label>Class</label>
                <select name="class_id" class="form-control">
                    <option value="">Select Class</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label>Subject</label>
                <select name="subject_id" class="form-control">
                    <option value="">Select Subject</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <button class="btn btn-dark mt-4 rounded-0">
            ASSIGN SUBJECT
        </button>
    </form>

</div>
@endsection