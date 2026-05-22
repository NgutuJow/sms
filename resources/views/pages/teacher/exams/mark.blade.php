@extends('pages.teacher.layout.layout')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">Mark Exam: {{ $exam->name }}</h2>
            <p class="text-muted">
                <i class="fas fa-book-reader"></i> Somo: <strong>{{ $subject->name }}</strong> | 
                <i class="fas fa-school"></i> Darasa: <strong>{{ $className }}</strong> | 
                <i class="fas fa-users"></i> Mkondo: <strong>{{ $streamName }}</strong>
            </p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('teacher.exams.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-light border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted">Wanafunzi wa {{ $streamName }}:</span>
                        <span class="h4 fw-bold ms-2">{{ $totalStudents }}</span>
                    </div>
                    <div>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                            <i class="fas fa-upload"></i> Bulk Upload
                        </button>
                        <!-- Tumeongeza subject_id hapa -->
                        <a href="{{ route('teacher.exams.download-template', $exam->id) }}?subject_id={{ $subject->id }}" class="btn btn-info text-white">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Orodha ya Wanafunzi - {{ $subject->name }}</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 250px;">Student Name</th>
                        <th>Admission No</th>
                        <th class="text-center">Marks (Max: {{ $exam->total_marks }})</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        @php
                            // Tunatafuta mark kwa kutumia student_id kwenye collection ya marks
                            $mark = $marks->get($student->id);
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $student->first_name }} {{ $student->last_name }}</div>
                            </td>
                            <td><span class="badge bg-secondary">{{ $student->admission_no }}</span></td>
                            
                            <td>
                                <input type="number" 
                                       class="form-control form-control-sm mx-auto text-center fw-bold" 
                                       style="width: 100px;"
                                       value="{{ $mark->marks ?? '' }}" 
                                       min="0" 
                                       max="{{ $exam->total_marks }}"
                                       data-student="{{ $student->id }}"
                                       data-subject="{{ $subject->id }}"
                                       data-exam="{{ $exam->id }}"
                                       onchange="saveMark(this)">
                            </td>
                            <td>
                                <small class="text-muted">{{ $mark->remarks ?? '-' }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                No students found in {{ $className }} {{ $streamName }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bulk Upload Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Marks: {{ $subject->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('teacher.exams.bulk-upload', $exam->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Hidden subject_id ili controller ijue ni somo gani -->
                <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="csv_file" class="form-label">CSV File *</label>
                        <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
                        <small class="text-muted d-block mt-1">Hakikisha format ni: <strong>student_id, marks, remarks</strong></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload Marks</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function saveMark(element) {
    // Onyesha loading state (Yellow background)
    element.style.backgroundColor = '#fff3cd';

    const data = {
        exam_id: element.dataset.exam,
        student_id: element.dataset.student,
        subject_id: element.dataset.subject,
        marks: element.value,
        _token: '{{ csrf_token() }}'
    };

    fetch('{{ route("teacher.exams.save-single-mark") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            element.style.backgroundColor = '#d4edda'; // Success (Green)
            element.style.borderColor = '#28a745';
            setTimeout(() => {
                element.style.backgroundColor = '';
                element.style.borderColor = '';
            }, 1000);
        } else {
            alert('Error: ' + data.message);
            element.style.backgroundColor = '#f8d7da'; // Error (Red)
        }
    })
    .catch(error => {
        console.error('Error:', error);
        element.style.backgroundColor = '#f8d7da';
    });
}
</script>
@endsection