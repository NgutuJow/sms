@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h4 class="fw-bold text-uppercase mb-4">Exam Setup</h4>

    <form action="{{ route('exams.store') }}" method="POST">
        @csrf

        <div class="row g-3">

            <div class="col-md-4">
                <label>Exam Name</label>
                <select name="name" class="form-control">
                    <option value="">Select Exam</option>
                    <option value="Test 1">Test 1</option>
                    <option value="Test 2">Test 2</option>
                    <option value="Midterm">Midterm</option>
                    <option value="Final Exam">Final Exam</option>
                </select>
            </div>

            <div class="col-md-4">
                <label>Academic Session</label>
                <select name="academic_session_id" class="form-control">
                    @foreach($sessions as $session)
                        <option value="{{ $session->id }}">{{ $session->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>Semester</label>
                <select name="semester_id" class="form-control">
                    @foreach($semesters as $semester)
                        <option value="{{ $semester->id }}">{{ $semester->semester_name }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <button class="btn btn-dark mt-4 rounded-0">
            SAVE EXAM
        </button>
    </form>
</div>
@endsection