@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h4 class="fw-bold text-uppercase mb-4">Edit Student</h4>

    <form action="{{ route('students.update', $student->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">

            <!-- STUDENT INFO -->
            <div class="col-12">
                <h6 class="fw-bold text-uppercase">Student Info</h6>
            </div>

            <div class="col-md-4">
                <label>Admission No</label>
                <input type="text" name="admission_no" value="{{ $student->admission_no }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label>First Name</label>
                <input type="text" name="first_name" value="{{ $student->first_name }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label>Middle Name</label>
                <input type="text" name="middle_name" value="{{ $student->middle_name }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label>Last Name</label>
                <input type="text" name="last_name" value="{{ $student->last_name }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label>DOB</label>
                <input type="date" name="dob" value="{{ $student->dob }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label>Gender</label>
                <select name="gender" class="form-control">
                    <option value="Male" {{ $student->gender == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ $student->gender == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>

            <div class="col-md-4">
                <label>Region</label>
                <input type="text" name="region" value="{{ $student->region }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label>District</label>
                <input type="text" name="district" value="{{ $student->district }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label>Street</label>
                <input type="text" name="street" value="{{ $student->street }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label>Address</label>
                <input type="text" name="address" value="{{ $student->address }}" class="form-control">
            </div>

            <!-- GUARDIAN -->
            <div class="col-12 mt-4">
                <h6 class="fw-bold text-uppercase">Guardian Info</h6>
            </div>

            <div class="col-md-4">
                <label>Guardian Name</label>
                <input type="text" name="guardian_name" value="{{ $student->guardian_name }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label>Guardian Email</label>
                <input type="email" name="guardian_email" value="{{ $student->guardian_email }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label>Guardian Phone</label>
                <input type="text" name="guardian_phone" value="{{ $student->guardian_phone }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label>Occupation</label>
                <input type="text" name="guardian_occupation" value="{{ $student->guardian_occupation }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label>Guardian Type</label>
                <input type="text" name="guardian_type" value="{{ $student->guardian_type }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label>Guardian Region</label>
                <input type="text" name="guardian_region" value="{{ $student->guardian_region }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label>Guardian District</label>
                <input type="text" name="guardian_district" value="{{ $student->guardian_district }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label>Guardian Street</label>
                <input type="text" name="guardian_street" value="{{ $student->guardian_street }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label>Guardian Address</label>
                <input type="text" name="guardian_address" value="{{ $student->guardian_address }}" class="form-control">
            </div>

            <!-- ACADEMIC -->
            <div class="col-12 mt-4">
                <h6 class="fw-bold text-uppercase">Academic Info</h6>
            </div>

            <div class="col-md-4">
                <label>Education Level</label>
                <input type="text" name="education_level" value="{{ $student->education_level }}" class="form-control">
            </div>

           <!-- ACADEMIC INFO -->
<div class="col-12 mt-4">
    <h6 class="fw-bold text-uppercase">Academic Info</h6>
</div>

<!-- CLASS -->
<div class="col-md-4">
    <label>Class</label>
    <input type="text" class="form-control" value="{{ $student->classData->class_name ?? '' }}" readonly>
</div>

<!-- STREAM -->
<div class="col-md-4">
    <label>Stream</label>
    <input type="text" class="form-control" value="{{ $student->streamData->stream_name ?? '' }}" readonly>
</div>

<!-- BRANCH -->
<div class="col-md-4">
    <label>Branch</label>
    <input type="text" class="form-control" value="{{ $student->branchData->branch_name ?? '' }}" readonly>
</div>

<!-- SEMESTER -->
<div class="col-md-4">
    <label>Semester</label>
    <input type="text" class="form-control" value="{{ $student->semesterData->semester_name ?? '' }}" readonly>
</div>

<!-- ACADEMIC SESSION -->
<div class="col-md-4">
    <label>Academic Session</label>
    <input type="text" class="form-control" value="{{ $student->academicSessionData->name ?? '' }}" readonly>
</div>

            <div class="col-md-6">
                <label>School Attended</label>
                <input type="text" name="school_attended" value="{{ $student->school_attended }}" class="form-control">
            </div>

            <div class="col-md-6">
                <label>Grade Completed</label>
                <input type="text" name="grade_completed" value="{{ $student->grade_completed }}" class="form-control">
            </div>

            <div class="col-md-6">
                <label>Suspended Before</label>
                <input type="text" name="suspended_before" value="{{ $student->suspended_before }}" class="form-control">
            </div>

            <div class="col-md-6">
                <label>Suspension Reason</label>
                <input type="text" name="suspension_reason" value="{{ $student->suspension_reason }}" class="form-control">
            </div>

        </div>

        <button class="btn btn-dark mt-4 rounded-0">
            UPDATE STUDENT
        </button>

    </form>

</div>
@endsection