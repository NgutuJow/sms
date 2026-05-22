@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-uppercase">Mchakato wa Promotion & Data Logging</h4>
        <a href="{{ route('promotions.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Rudi Nyuma
        </a>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-dark text-white fw-bold">
                    Snapshot ya Mwanafunzi
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="bg-light d-inline-block p-3 rounded-circle mb-2">
                            <i class="bi bi-person-fill fs-1 text-secondary"></i>
                        </div>
                        <h5 class="fw-bold mb-0">{{ $student->first_name }} {{ $student->last_name }}</h5>
                        <span class="badge bg-info text-dark">{{ $student->admission_no }}</span>
                    </div>

                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Darasa la Sasa:</span>
                            <span class="fw-bold">{{ $currentClass->class_name ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Academic Session:</span>
                            <span class="fw-bold">{{ $student->academic_session }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Gender:</span>
                            <span class="fw-bold">{{ ucfirst($student->gender) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Guardian:</span>
                            <span class="fw-bold text-truncate" style="max-width: 150px;">{{ $student->guardian_name }}</span>
                        </li>
                    </ul>

                    <div class="alert alert-warning mt-3 mb-0 py-2 small border-0">
                        <i class="bi bi-info-circle-fill"></i> Data hizi zitanakiliwa kwenda kwenye <strong>Student Logs</strong> kabla ya kubadilishwa.
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    Vigezo vya Promotion
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('promotions.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="student_id" value="{{ $student->id }}">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Pandisha Kwenda (To Class)</label>
                                <select name="to_class_id" class="form-select form-control-lg border-primary" required>
                                    <option value="">-- Chagua Darasa --</option>
                                    @foreach($classes as $c)
                                        @if($c->id != $student->classes)
                                            <option value="{{ $c->id }}">{{ $c->class_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="form-text text-primary">Chagua darasa ambalo mwanafunzi anahamia.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Academic Session Mpya</label>
                                <select name="academic_session_id" class="form-select form-control-lg border-primary" required>
                                    <option value="">-- Chagua Mwaka --</option>
                                    @foreach($sessions as $session)
                                        <option value="{{ $session->id }}" {{ $session->is_current ? 'selected' : '' }}>{{ $session->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Chagua mwaka wa masomo mwanafunzi anapohamia.</div>
                            </div>

                            <div class="col-12 mt-4">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-2 text-danger">Maelekezo Muhimu:</h6>
                                        <ul class="small text-muted mb-0">
                                            <li>Data zote za sasa (Mkoa, Wilaya, Mzazi, n.k.) zitahifadhiwa kwenye log table.</li>
                                            <li>Baada ya hapa, darasa la mwanafunzi litabadilika moja kwa moja.</li>
                                            <li>Huwezi kurudisha mabadiliko haya kirahisi (Action is irreversible).</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 text-end mt-4">
                                <button type="submit" class="btn btn-success btn-lg px-5 shadow-sm" onclick="return confirm('Je, una uhakika unataka kukamilisha mchakato huu?')">
                                    <i class="bi bi-rocket-takeoff-fill"></i> Kamilisha & Update Logs
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-label { font-size: 0.9rem; }
    .card { border-radius: 15px; overflow: hidden; }
    .btn-lg { border-radius: 10px; font-weight: 600; }
    .bg-primary { background: linear-gradient(45deg, #0d6efd, #0b5ed7) !important; }
</style>
@endsection