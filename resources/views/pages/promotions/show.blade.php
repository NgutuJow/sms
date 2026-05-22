@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-uppercase">Orodha ya Wanafunzi: {{ $class->class_name }}</h4>
        <span class="badge bg-primary fs-6 px-3">Mwaka: {{ date('Y') }}</span>
    </div>

    <div class="card shadow-sm border-0 mb-4 bg-light">
        <div class="card-body">
            <form action="{{ route('promotions.show', $class->id) }}" method="GET" class="row align-items-end g-2">
                <div class="col-md-3">
                    <label class="small fw-bold mb-1">Chagua Mitihani ya Mwaka:</label>
                    <select name="exam_id" class="form-select">
                        <option value="">-- Select Exam --</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>{{ $exam->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold mb-1">Chuja kwa Wastani (Min Average):</label>
                    <input type="number" name="min_marks" class="form-control" value="{{ request('min_marks', 0) }}" placeholder="Mfano: 50">
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold mb-1">Ada Zote Zimekulibishwa:</label>
                    <div class="form-check">
                        <input type="hidden" name="fee_paid_only" value="0">
                        <input type="checkbox" class="form-check-input" id="fee_paid_only" name="fee_paid_only" value="1" {{ $feePaidOnly ? 'checked' : '' }}>
                        <label class="form-check-label" for="fee_paid_only">Only fully paid</label>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-dark w-100">Apply</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('promotions.show', $class->id) }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <form action="{{ route('promotions.bulkStore') }}" method="POST">
        @csrf
        <input type="hidden" name="exam_id" value="{{ request('exam_id') }}">
        <input type="hidden" name="min_marks" value="{{ request('min_marks', 0) }}">
        <input type="hidden" name="fee_paid_only" value="{{ $feePaidOnly ? 1 : 0 }}">
        
        <div class="card shadow-sm border-0 mb-3" style="border-left: 5px solid #198754 !important;">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex gap-3 align-items-center">
                    <div style="min-width: 200px">
                        <select name="to_class_id" class="form-select border-success" required>
                            <option value="">-- Promote to Class --</option>
                            @foreach($classes as $c)
                                @if($c->id != $class->id)
                                    <option value="{{ $c->id }}">{{ $c->class_name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div style="min-width: 150px">
                        <select name="academic_session_id" class="form-select border-success" required>
                            <option value="">-- Academic Year --</option>
                            @foreach($sessions as $session)
                                <option value="{{ $session->id }}" {{ $session->is_current ? 'selected' : '' }}>{{ $session->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-success px-4 fw-bold shadow-sm" onclick="return confirm('Je, una uhakika wa kupandisha darasa wanafunzi wote waliochaguliwa?')">
                    <i class="bi bi-people-fill"></i> PROMOTE SELECTED
                </button>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th width="50" class="ps-3">
                                    <input type="checkbox" id="select-all" class="form-check-input">
                                </th>
                                <th>Jina Kamili</th>
                                <th>Darasa</th>
                                <th>Aina (Level)</th>
                                <th>Average</th>
                                <th>Fee Status</th>
                                <th>Academic Year</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $minMarks = request('min_marks', 0);
                            @endphp
                            @forelse($students as $student)
                                @php
                                    $studentMarks = $marks->where('student_id', $student->id);
                                    $average = $studentMarks->count() > 0 ? $studentMarks->avg('marks') : 0;
                                    $sessionName = $student->academicSessionData->name ?? '';
                                    $studentInvoices = $student->invoices->where('academic_year', $sessionName);
                                    $balance = $studentInvoices->sum('balance');
                                    $hasInvoices = $studentInvoices->count() > 0;
                                    $hasPaidFees = !$hasInvoices || $balance <= 0;
                                    $isEligible = $average >= $minMarks && (! $feePaidOnly || $hasPaidFees);
                                @endphp

                                <tr class="{{ $isEligible ? '' : 'table-warning' }}">
                                    <td class="ps-3">
                                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="student-checkbox form-check-input" {{ $isEligible ? '' : 'disabled' }}>
                                    </td>
                                    <td class="fw-bold">{{ $student->first_name }} {{ $student->last_name }}</td>
                                    <td>{{ $class->class_name }}</td>
                                    <td><span class="badge bg-info text-dark">{{ $class->level_type ?? 'N/A' }}</span></td>
                                    <td>
                                        <strong class="{{ $average >= 50 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($average, 1) }}%
                                        </strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $hasPaidFees ? 'success' : 'danger' }}">
                                            {{ $hasPaidFees ? 'Paid' : 'Unpaid' }}
                                        </span>
                                    </td>
                                    <td>{{ $student->academic_session ?? date('Y') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('promotions.create', ['student_id' => $student->id]) }}" class="btn btn-sm btn-outline-success">
                                            Individual
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">Hakuna wanafunzi waliopatikana.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
</div>

<script>
    // Script ya kuselect checkboxes zote
    document.getElementById('select-all').onclick = function() {
        var checkboxes = document.getElementsByClassName('student-checkbox');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    }
</script>

<style>
    .table th { font-weight: 600; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px; }
    .card { border-radius: 12px; overflow: hidden; }
    .form-check-input:checked { background-color: #198754; border-color: #198754; }
</style>
@endsection