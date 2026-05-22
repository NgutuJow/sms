@extends('pages.parent.layout.layout')

@section('content')
<div class="row g-4">
    <!-- Header -->
    <div class="col-12">
        <div class="card-custom d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Children Attendance</h4>
                <p class="text-muted mb-0">Clear, exportable attendance records for your children</p>
            </div>

            <div class="d-flex align-items-center gap-2">
                <input id="attendanceSearch" class="form-control form-control-sm" placeholder="Search date or notes" style="width: 240px;" />
                <select class="form-select form-select-sm" id="periodSelect" style="width: 140px;">
                    <option value="monthly">This Month</option>
                    <option value="weekly">This Week</option>
                    <option value="yearly">This Year</option>
                </select>
                <a href="{{ route('parent.attendance.download') }}" class="btn btn-outline-primary btn-sm" id="downloadBtn">
                    <i class="fa-solid fa-file-pdf me-2"></i>PDF
                </a>
                <button id="csvBtn" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-file-csv me-2"></i>CSV</button>
                <button id="printBtn" class="btn btn-primary btn-sm"><i class="fa-solid fa-print me-2"></i>Print</button>
            </div>
        </div>
    </div>

    @if($students->count() == 0)
    <div class="col-12">
        <div class="card-custom text-center py-5">
            <i class="fa-solid fa-users fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">No Children Found</h5>
            <p class="text-muted">You don't have any children registered in the system yet.</p>
            <a href="{{ route('parent.dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
        </div>
    </div>
    @else
    @foreach($students as $student)
    <div class="col-12">
        <div class="card-custom">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-0">{{ $student->first_name }} {{ $student->last_name }}</h5>
                    <small class="text-muted">Class: {{ $student->classData->name ?? 'N/A' }} &middot; Stream: {{ $student->streamData->name ?? 'N/A' }}</small>
                </div>
                <div class="text-end">
                    <a href="{{ route('parent.student.details', $student->id) }}" class="btn btn-outline-primary btn-compact btn-sm me-2">View Profile</a>
                    <a href="#" class="btn btn-outline-secondary btn-compact btn-sm">Attendance Settings</a>
                </div>
            </div>

            @php
                $records = $attendanceRecords[$student->id] ?? collect();
                $totalDays = $records->count();
                $presentDays = $records->where('status', 'present')->count();
                $percentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;
            @endphp

            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="text-center p-3 rounded" style="background: linear-gradient(180deg, rgba(37,99,235,0.06), rgba(37,99,235,0.02));">
                        <div class="fw-semibold">Total Days</div>
                        <div class="h4 mt-1 text-primary">{{ $totalDays }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center p-3 rounded" style="background: rgba(16,185,129,0.06);">
                        <div class="fw-semibold">Present</div>
                        <div class="h4 mt-1 text-success">{{ $presentDays }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center p-3 rounded" style="background: rgba(239,68,68,0.04);">
                        <div class="fw-semibold">Absent</div>
                        <div class="h4 mt-1 text-danger">{{ $totalDays - $presentDays }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center p-3 rounded" style="background: rgba(14,165,233,0.04);">
                        <div class="fw-semibold">Rate</div>
                        <div class="h4 mt-1 text-info">{{ $percentage }}%</div>
                    </div>
                </div>
            </div>

            @if($records->count() > 0)
            <div class="table-responsive">
                <table class="table table-borderless align-middle" data-student-id="{{ $student->id }}">
                    <thead>
                        <tr class="text-muted small">
                            <th style="width:180px">Date</th>
                            <th style="width:120px">Status</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $record)
                        <tr>
                            <td class="record-date">{{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}</td>
                            <td>
                                @if($record->status === 'present')
                                    <span class="badge bg-success">Present</span>
                                @elseif($record->status === 'absent')
                                    <span class="badge bg-danger">Absent</span>
                                @else
                                    <span class="badge bg-warning">{{ ucfirst($record->status) }}</span>
                                @endif
                            </td>
                            <td class="record-notes">{{ $record->notes ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <i class="fa-solid fa-calendar-xmark fa-3x text-muted mb-3"></i>
                <p class="text-muted">No attendance records found</p>
            </div>
            @endif
        </div>
    </div>
    @endforeach
    @endif
</div>
@endsection

@push('scripts')
<script>
// Update PDF download link when period changes
document.getElementById('periodSelect').addEventListener('change', function() {
    const period = this.value;
    const downloadBtn = document.getElementById('downloadBtn');
    downloadBtn.href = '{{ route("parent.attendance.download") }}?period=' + period;
});

// Client-side search across all attendance tables
(function() {
    const searchInput = document.getElementById('attendanceSearch');
    searchInput.addEventListener('input', function() {
        const q = this.value.trim().toLowerCase();
        document.querySelectorAll('table[data-student-id] tbody').forEach(tbody => {
            tbody.querySelectorAll('tr').forEach(row => {
                const date = row.querySelector('.record-date')?.textContent.toLowerCase() || '';
                const notes = row.querySelector('.record-notes')?.textContent.toLowerCase() || '';
                const visible = q === '' || date.includes(q) || notes.includes(q);
                row.style.display = visible ? '' : 'none';
            });
        });
    });
})();

// CSV export for visible rows
document.getElementById('csvBtn').addEventListener('click', function() {
    const rows = [];
    document.querySelectorAll('table[data-student-id]').forEach(table => {
        const studentHeader = table.closest('.card-custom').querySelector('h5')?.textContent.trim() || '';
        table.querySelectorAll('tbody tr').forEach(tr => {
            if (tr.style.display === 'none') return; // skip hidden rows
            const date = tr.querySelector('.record-date')?.textContent.trim() || '';
            const status = tr.querySelector('td:nth-child(2)')?.textContent.trim() || '';
            const notes = tr.querySelector('.record-notes')?.textContent.trim() || '';
            rows.push([studentHeader, date, status, notes]);
        });
    });

    if (rows.length === 0) {
        alert('No records to export');
        return;
    }

    let csv = 'Student,Date,Status,Notes\n';
    rows.forEach(r => {
        csv += r.map(c => '"' + String(c).replace(/"/g, '""') + '"').join(',') + '\n';
    });

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'attendance_export.csv';
    document.body.appendChild(a);
    a.click();
    a.remove();
    URL.revokeObjectURL(url);
});

// Print current visible tables
document.getElementById('printBtn').addEventListener('click', function() {
    const printWindow = window.open('', '_blank');
    const style = document.createElement('style');
    style.innerHTML = 'body{font-family:Inter,sans-serif;padding:20px;color:#0f172a}table{width:100%;border-collapse:collapse;margin-bottom:20px}th,td{padding:8px;border-bottom:1px solid #e6edf3}th{background:#f8fafc;text-align:left}';

    let html = '<h2>Attendance Export</h2>';
    document.querySelectorAll('table[data-student-id]').forEach(table => {
        const studentHeader = table.closest('.card-custom').querySelector('h5')?.textContent.trim() || '';
        html += `<h3>${studentHeader}</h3>`;
        html += '<table><thead>' + table.querySelector('thead').innerHTML + '</thead><tbody>';
        table.querySelectorAll('tbody tr').forEach(tr => {
            if (tr.style.display === 'none') return;
            html += '<tr>' + tr.innerHTML + '</tr>';
        });
        html += '</tbody></table>';
    });

    printWindow.document.head.appendChild(style);
    printWindow.document.body.innerHTML = html;
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => printWindow.print(), 500);
});
</script>
@endpush