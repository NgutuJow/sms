@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Student Enrollment</h4>
            <p class="text-muted mb-0">Class: {{ $class->class_name }} · Student management and enrollment overview.</p>
        </div>
        <a href="{{ route('students.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold">
            <i class="bi bi-plus-lg me-2"></i> Add Student
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <small class="text-uppercase text-muted fw-semibold">Total Students</small>
                    <h3 class="mt-3 fw-bold">{{ $studentsCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 rounded-4 border-start border-4 border-info">
                <div class="card-body">
                    <small class="text-uppercase text-muted fw-semibold">Paid Students</small>
                    <h3 class="mt-3 fw-bold text-info">{{ $paidCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 rounded-4 border-start border-4 border-warning">
                <div class="card-body">
                    <small class="text-uppercase text-muted fw-semibold">Partially Paid</small>
                    <h3 class="mt-3 fw-bold text-warning">{{ $partiallyPaidCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 rounded-4 border-start border-4 border-secondary">
                <div class="card-body">
                    <small class="text-uppercase text-muted fw-semibold">Total Due</small>
                    <h3 class="mt-3 fw-bold text-secondary">{{ number_format($totalDue, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm rounded-4 border-0">
        <div class="card-header bg-white border-0 py-3 px-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h6 class="mb-1 fw-bold">Registered Students</h6>
                    <p class="text-muted mb-0">Review and manage students currently enrolled in this class.</p>
                </div>
                <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-sm-auto">
                    <input type="search" class="form-control form-control-sm rounded-pill border-light bg-light" placeholder="Search students...">
                    <button class="btn btn-outline-secondary btn-sm rounded-pill px-4">Export</button>
                </div>
            </div>
        </div>

        <div class="table-responsive px-4 pb-4">
            <table class="table table-striped table-hover align-middle mb-0" style="font-size: 0.9rem;">
                <thead class="bg-white border-bottom">
                    <tr class="text-uppercase text-dark fw-semibold small">
                        <th class="ps-3 py-3" width="120">Admission No</th>
                        <th class="py-3">Student Name</th>
                        <th class="py-3">Gender</th>
                        <th class="py-3">Fee Status</th>
                        <th class="py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    @php
                        $invoiceBalance = $student->invoices->sum('balance');
                        $totalPaid = $student->payments->sum('amount');
                        $hasInvoices = $student->invoices->isNotEmpty();

                        if (! $hasInvoices) {
                            $statusLabel = 'No Invoice';
                            $statusClass = 'secondary';
                        } elseif ($invoiceBalance <= 0) {
                            $statusLabel = 'Paid';
                            $statusClass = 'success';
                        } elseif ($totalPaid > 0) {
                            $statusLabel = 'Partially Paid';
                            $statusClass = 'warning';
                        } else {
                            $statusLabel = 'Unpaid';
                            $statusClass = 'danger';
                        }
                    @endphp
                    <tr>
                        <td class="ps-3 text-secondary">#{{ $student->admission_no }}</td>
                        <td class="fw-semibold text-dark text-uppercase">{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</td>
                        <td class="text-secondary">{{ $student->gender }}</td>
                        <td>
                            <span class="badge bg-{{ $statusClass }} bg-opacity-10 text-{{ $statusClass }} rounded-pill px-3 py-1 fw-semibold text-uppercase" style="font-size: 0.75rem;">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-sm btn-outline-primary rounded-circle p-2" data-bs-toggle="modal" data-bs-target="#studentModal{{ $student->id }}" title="View Profile">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-outline-secondary rounded-circle p-2" title="Edit Profile">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="m-0" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" title="Delete Student">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted bg-white">
                            <i class="bi bi-folder-x d-block mb-3 fs-2 text-secondary"></i>
                            No students are currently registered in this class.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL SECTION --}}
@foreach($students as $student)
    <div class="modal fade" id="studentModal{{ $student->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-0 shadow-lg modal-details">
                <div class="modal-header bg-dark text-white rounded-0 py-2">
                    <h6 class="modal-title fw-bold small text-uppercase">Student Details</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 bg-white">
                    <div class="border-bottom border-dark border-2 pb-3 mb-4 text-center">
                        <h4 class="fw-bold mb-0 text-dark text-uppercase">
                            {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}
                        </h4>
                        <p class="text-muted small mb-0">
                            ID: #{{ $student->admission_no }} | Class: <strong>{{ $class->class_name }}</strong>
                        </p>
                    </div>

                    <div class="row g-3 text-dark">
                        <!-- Personal Info -->
                        <div class="col-12 mt-2 border-bottom border-light pb-2">
                            <h6 class="fw-bold text-uppercase small mb-0 text-secondary">Personal Information</h6>
                        </div>
                        <div class="col-md-3 col-6"><small class="text-muted d-block">Gender</small> <strong>{{ $student->gender }}</strong></div>
                        <div class="col-md-3 col-6"><small class="text-muted d-block">DOB</small> <strong>{{ $student->dob }}</strong></div>
                        <div class="col-md-3 col-6"><small class="text-muted d-block">Region</small> <strong>{{ $student->region }}</strong></div>
                        <div class="col-md-3 col-6"><small class="text-muted d-block">District</small> <strong>{{ $student->district }}</strong></div>
                        <div class="col-md-3 col-6"><small class="text-muted d-block">Street</small> <strong>{{ $student->street }}</strong></div>
                        <div class="col-md-9 col-12"><small class="text-muted d-block">Address</small> <strong>{{ $student->address }}</strong></div>

                        <!-- Guardian Info -->
                        <div class="col-12 mt-4 border-bottom border-light pb-2">
                            <h6 class="fw-bold text-uppercase small mb-0 text-secondary">Guardian Information</h6>
                        </div>
                        <div class="col-md-4 col-12"><small class="text-muted d-block">Name</small> <strong>{{ $student->guardian_name }}</strong></div>
                        <div class="col-md-4 col-6"><small class="text-muted d-block">Email</small> <strong>{{ $student->guardian_email }}</strong></div>
                        <div class="col-md-4 col-6"><small class="text-muted d-block">Phone</small> <strong>{{ $student->guardian_phone }}</strong></div>
                        <div class="col-md-4 col-6"><small class="text-muted d-block">Occupation</small> <strong>{{ $student->guardian_occupation }}</strong></div>
                        <div class="col-md-4 col-6"><small class="text-muted d-block">Guardian Type</small> <strong>{{ $student->guardian_type }}</strong></div>
                        <div class="col-md-4 col-6"><small class="text-muted d-block">Region</small> <strong>{{ $student->guardian_region }}</strong></div>
                        <div class="col-md-4 col-6"><small class="text-muted d-block">District</small> <strong>{{ $student->guardian_district }}</strong></div>
                        <div class="col-md-4 col-6"><small class="text-muted d-block">Street</small> <strong>{{ $student->guardian_street }}</strong></div>
                        <div class="col-12"><small class="text-muted d-block">Address</small> <strong>{{ $student->guardian_address }}</strong></div>

                        <!-- Academic Info -->
                        <div class="col-12 mt-4 border-bottom border-light pb-2">
                            <h6 class="fw-bold text-uppercase small mb-0 text-secondary">Academic Information</h6>
                        </div>
                        <div class="col-md-3 col-6"><small class="text-muted d-block">Level</small> <strong>{{ $student->education_level }}</strong></div>
                        <div class="col-md-3 col-6"><small class="text-muted d-block">Class</small> <strong>{{ $student->classData->class_name ?? '' }}</strong></div>
                        <div class="col-md-3 col-6"><small class="text-muted d-block">Stream</small> <strong>{{ $student->streamData->stream_name ?? '' }}</strong></div>
                        <div class="col-md-3 col-6"><small class="text-muted d-block">Branch</small> <strong>{{ $student->branchData->branch_name ?? '' }}</strong></div>
                        <div class="col-md-4 col-6"><small class="text-muted d-block">Session</small> <strong>{{ $student->academicSessionData->name ?? '' }}</strong></div>
                        <div class="col-md-4 col-6"><small class="text-muted d-block">Semester</small> <strong>{{ $student->semesterData->semester_name ?? '' }}</strong></div>
                        <div class="col-md-4 col-12"><small class="text-muted d-block">Previous School</small> <strong>{{ $student->school_attended }}</strong></div>
                        <div class="col-md-3 col-6"><small class="text-muted d-block">Grade Completed</small> <strong>{{ $student->grade_completed }}</strong></div>
                        <div class="col-md-9 col-6"><small class="text-muted d-block">Previously Suspended</small> <strong>{{ $student->suspended_before }}</strong></div>
                        @if($student->suspension_reason)
                            <div class="col-12"><small class="text-muted d-block">Suspension Reason</small> <strong>{{ $student->suspension_reason }}</strong></div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer border-top-0 rounded-0 bg-light">
                    <button type="button" class="btn btn-outline-dark btn-sm rounded-0 px-4" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('students.edit', $student->id) }}" class="btn btn-dark btn-sm rounded-0 px-4">Edit Details</a>
                </div>
            </div>
        </div>
    </div>
@endforeach

<style>
    .table th {
        border-bottom: 2px solid #dee2e6 !important;
        background-color: #fff !important;
    }
    .table-striped>tbody>tr:nth-of-type(odd)>* {
        --bs-table-accent-bg: #f9f9f9;
    }
    .modal-content { border: 2px solid #000 !important; }
    .custom-action-group .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-weight: bold;
    }
    .custom-action-group .btn-outline-dark i {
        color: #212529 !important;
    }
    .custom-action-group .btn-outline-danger i {
        color: #dc3545 !important;
    }
    .custom-action-group .btn-dark i {
        color: #fff !important;
    }
    .custom-action-group form.d-inline { display: inline-flex; }
    .modal-details { font-size: 0.9rem; }
    .modal-details strong { color: #212529; }
</style>
@endsection