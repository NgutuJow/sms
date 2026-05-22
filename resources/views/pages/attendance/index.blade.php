@extends('layouts.app')

@section('content')
<style>
    .status-select {
        font-weight: 600;
        cursor: pointer;
        color: #000000 !important;
        background-color: #ffffff !important;
        -webkit-appearance: menulist !important;
        appearance: menulist !important;
    }
    .status-present { background-color: rgba(40, 167, 69, 0.15) !important; }
    .status-absent { background-color: rgba(220, 53, 69, 0.15) !important; }
    .status-late { background-color: rgba(255, 193, 7, 0.15) !important; }

    .summary-card {
        padding: 15px; border-radius: 12px; color: #fff; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .summary-card h3 { margin: 0; font-size: 1.5rem; }
    .present-card { background: linear-gradient(45deg, #28a745, #34ce57); }
    .absent-card { background: linear-gradient(45deg, #dc3545, #ff4757); }
    .late-card { background: linear-gradient(45deg, #ffc107, #ffdb6e); color: #000; }

    .action-bar {
        position: sticky; bottom: 0; background: #fff; padding: 15px 0; border-top: 1px solid #dee2e6; z-index: 100;
    }
    .student-checkbox { transform: scale(1.3); cursor: pointer; }
    .selected-row { background-color: #f0f7ff !important; border-left: 4px solid #0d6efd; }
</style>

<div class="container pb-5">
    
    {{-- ALERT MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <strong>✅ Hongera!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">📊 Attendance Management</h4>
            <small class="text-muted">Manage and review daily student records</small>
        </div>
        <div class="d-flex gap-2 align-items-center">
            {{-- BUTTON MPYA YA REVIEW --}}
            @if($selectedClass)
                <a href="{{ route('academic.attendance.review', ['class_id' => $selectedClass, 'date' => $date]) }}" class="btn btn-outline-primary shadow-sm">
                    <i class="fas fa-list-ul"></i> Review List
                </a>
                <a href="{{ route('academic.attendance.reports', ['class_id' => $selectedClass, 'date' => $date, 'period' => 'day']) }}" class="btn btn-outline-secondary shadow-sm">
                    <i class="fas fa-chart-line"></i> Reports
                </a>
            @endif
            <span class="badge bg-secondary p-2">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</span>
        </div>
    </div>

    {{-- FILTER SECTION --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body bg-light rounded">
            <form method="GET" action="{{ route('academic.attendance.index') }}" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-bold small text-uppercase">Class</label>
                    <select name="class_id" class="form-select border-primary" required>
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-uppercase">Date</label>
                    <input type="date" name="date" value="{{ $date }}" class="form-control border-primary">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">🔍 LOAD SHEET</button>
                </div>
            </form>
        </div>
    </div>

    @if(count($students) > 0)
        {{-- STATS SECTION (Dashboard Rate) --}}
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card bg-gradient-primary text-white shadow border-0" style="background: linear-gradient(45deg, #0d6efd, #00d2ff);">
                    <div class="card-body d-flex justify-content-between align-items-center p-4">
                        <div>
                            <h5 class="mb-0 text-white-50">Class Attendance Rate</h5>
                            <h1 class="display-4 fw-bold mb-0">{{ $stats['percent'] ?? 0 }}%</h1>
                            <p class="mb-0 fw-light">{{ $stats['present'] ?? 0 }} out of {{ $stats['total'] ?? 0 }} students present.</p>
                        </div>
                        <div class="text-end d-none d-md-block">
                            <i class="fas fa-chart-line fa-4x opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SUMMARY CARDS --}}
        <div class="row mb-4 text-center">
            <div class="col-md-4"><div class="summary-card present-card"><label class="small text-uppercase">Present</label><h3 id="presentCount">0</h3></div></div>
            <div class="col-md-4"><div class="summary-card absent-card"><label class="small text-uppercase">Absent</label><h3 id="absentCount">0</h3></div></div>
            <div class="col-md-4"><div class="summary-card late-card"><label class="small text-uppercase">Late</label><h3 id="lateCount">0</h3></div></div>
        </div>

        {{-- BULK ACTIONS --}}
        <div class="card border-primary mb-3 shadow-sm border-0 bg-white">
            <div class="card-body py-2 d-flex align-items-center justify-content-between">
                <div class="fw-bold text-primary">
                    <i class="fas fa-check-double"></i> <span id="selectedCounter">0</span> Selected
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success btn-sm px-3" onclick="markSelected('present')">Present</button>
                    <button type="button" class="btn btn-danger btn-sm px-3" onclick="markSelected('absent')">Absent</button>
                    <button type="button" class="btn btn-warning btn-sm px-3" onclick="markSelected('late')">Late</button>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('academic.attendance.store') }}">
            @csrf
            <input type="hidden" name="class_id" value="{{ $selectedClass }}">
            <input type="hidden" name="date" value="{{ $date }}">

            <div class="table-responsive shadow-sm rounded-3">
                <table class="table table-hover align-middle mb-0 bg-white" id="attendanceTable">
                    <thead class="table-dark">
                        <tr>
                            <th width="40"><input type="checkbox" id="selectAll" class="form-check-input student-checkbox"></th>
                            <th width="50">#</th>
                            <th>Student Name</th>
                            <th width="180">Status</th>
                            <th>Remarks</th>
                            <th width="80" class="text-center">Report</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                        @php $status = $attendances[$student->id]->status ?? 'present'; @endphp
                        <tr>
                            <td><input type="checkbox" class="form-check-input student-checkbox row-checkbox" onchange="toggleRowHighlight(this)"></td>
                            <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                            <td><strong>{{ $student->name }}</strong></td>
                            <td>
                                <select name="attendance[{{ $student->id }}]" 
                                        class="form-select status-select"
                                        onchange="updateRowColor(this)">
                                    <option value="present" {{ $status=='present'?'selected':'' }}>✅ Present</option>
                                    <option value="absent" {{ $status=='absent'?'selected':'' }}>❌ Absent</option>
                                    <option value="late" {{ $status=='late'?'selected':'' }}>⏰ Late</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="remarks[{{ $student->id }}]" class="form-control form-control-sm border-0 bg-light" placeholder="Note..." value="{{ $attendances[$student->id]->remarks ?? '' }}">
                            </td>
                            <td class="text-center">
                                <a href="{{ route('academic.attendance.report', $student->id) }}" target="_blank" class="btn btn-sm btn-outline-danger border-0">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="action-bar text-center mt-3 shadow-lg rounded-pill mx-auto mb-4" style="max-width: 400px;">
                <button type="submit" class="btn btn-primary btn-lg px-5 shadow fw-bold w-100 rounded-pill">
                    💾 SAVE ATTENDANCE
                </button>
            </div>
        </form>
    @else
        <div class="alert alert-info text-center shadow-sm py-5 border-0">
            <i class="fas fa-folder-open fa-3x mb-3 text-muted"></i><br>
            Please select a class and date to manage attendance sheet.
        </div>
    @endif
</div>

<script>
    // JS zote zinabaki vile vile kama mwanzo...
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const isChecked = this.checked;
        document.querySelectorAll('.row-checkbox').forEach(checkbox => {
            checkbox.checked = isChecked;
            toggleRowHighlight(checkbox);
        });
    });

    function toggleRowHighlight(checkbox) {
        const row = checkbox.closest('tr');
        if (checkbox.checked) row.classList.add('selected-row');
        else row.classList.remove('selected-row');
        
        const selectedCount = document.querySelectorAll('.row-checkbox:checked').length;
        document.getElementById('selectedCounter').innerText = selectedCount;
    }

    function markSelected(status) {
        const selected = document.querySelectorAll('.row-checkbox:checked');
        if (selected.length === 0) {
            alert('Please select at least one student first!');
            return;
        }
        selected.forEach(checkbox => {
            const row = checkbox.closest('tr');
            const select = row.querySelector('.status-select');
            select.value = status;
            updateRowColor(select);
        });
    }

    function updateRowColor(select) {
        let row = select.closest('tr');
        row.classList.remove('status-present', 'status-absent', 'status-late');
        row.classList.add('status-' + select.value);
        updateSummary();
    }

    function updateSummary() {
        let counts = { present: 0, absent: 0, late: 0 };
        document.querySelectorAll('.status-select').forEach(select => {
            counts[select.value]++;
        });
        document.getElementById('presentCount').innerText = counts.present;
        document.getElementById('absentCount').innerText = counts.absent;
        document.getElementById('lateCount').innerText = counts.late;
    }

    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.status-select').forEach(select => updateRowColor(select));
    });
</script>
@endsection