@extends('pages.teacher.layout.layout')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        --surface-card: #ffffff;
        --text-main: #1e293b;
    }

    .main-wrapper {
        background-color: #f8fafc;
        min-height: 100vh;
        padding: 2rem;
        font-family: 'Inter', system-ui, sans-serif;
    }

    /* Modern Header Card */
    .glass-header {
        background: var(--primary-gradient);
        border-radius: 24px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 20px 25px -5px rgba(79, 70, 229, 0.2);
        position: relative;
        overflow: hidden;
    }

    .glass-header::after {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    /* Search & Filter Bar */
    .action-bar {
        background: white;
        border-radius: 16px;
        padding: 1rem 1.5rem;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    /* Custom Table Styling */
    .custom-table-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .table thead th {
        background: #f1f5f9;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: #64748b;
        border: none;
        padding: 1.25rem 1rem;
    }

    .student-row {
        transition: all 0.2s ease;
    }

    .student-row:hover {
        background-color: #f8fafc;
        transform: scale(1.002);
    }

    /* Inputs */
    .marks-input-modern {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.5rem;
        transition: 0.3s;
        font-weight: 700;
        width: 80px;
        text-align: center;
    }

    .marks-input-modern:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        outline: none;
    }

    /* Grade Badge */
    .grade-pill {
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 800;
        background: #f1f5f9;
        color: #475569;
    }

    /* Upload Sidebar */
    .upload-card-modern {
        background: #1e293b;
        color: white;
        border-radius: 24px;
        padding: 2rem;
        height: 100%;
    }

    .file-input-wrapper {
        border: 2px dashed #475569;
        border-radius: 16px;
        padding: 2rem 1rem;
        text-align: center;
        margin-bottom: 1.5rem;
    }
</style>

<div class="main-wrapper">
    {{-- Dynamic Header --}}
    <div class="glass-header">
        <div class="row align-items-center">
            <div class="col-md-7">
                <span class="badge bg-white text-primary mb-2 px-3 py-2 rounded-pill fw-bold">EXAM PORTAL</span>
                <h1 class="display-6 fw-bold mb-1">{{ $exam->name }}</h1>
                <p class="opacity-75 mb-0">Recording results for <span class="fw-bold">{{ $subject->subject_name }}</span> — {{ $subject->schoolClass->class_name }}</p>
            </div>
            <div class="col-md-5 text-md-end mt-4 mt-md-0">
                <a href="{{ route('teacher-exams.results.template', [$exam->id, $subject->id]) }}?stream_id={{ request('stream_id') }}" 
                   class="btn btn-light btn-lg rounded-pill px-4 fw-bold shadow-sm">
                    <i class="fa-solid fa-file-export me-2"></i>Get Template
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            {{-- Action Bar --}}
            <div class="action-bar shadow-sm">
                <div class="d-flex align-items-center flex-grow-1">
                    <i class="fa-solid fa-filter text-muted me-2"></i>
                    <form method="GET" class="d-flex gap-2">
                        <select name="stream_id" class="form-select border-0 fw-bold" onchange="this.form.submit()" style="cursor: pointer; width: 200px;">
                            <option value="">All Streams</option>
                            @foreach($streams as $stream)
                                <option value="{{ $stream->id }}" {{ request('stream_id') == $stream->id ? 'selected' : '' }}>
                                    Stream: {{ $stream->stream_name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="text-muted small fw-medium">
                    {{ $students->count() }} Students Enrolled
                </div>
            </div>

            {{-- Table Area --}}
            <div class="custom-table-card shadow-sm">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="ps-4">Student Information</th>
                                <th class="text-center">ID Number</th>
                                <th class="text-center">Score (100)</th>
                                <th class="text-center">Grade</th>
                                <th class="pe-4 text-end">Save</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr class="student-row">
                                <td class="ps-4 py-4">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center fw-bold text-primary" style="width: 42px; height: 42px; border: 2px solid #eef2ff;">
                                            {{ substr($student->first_name, 0, 1) }}
                                        </div>
                                        <div class="ms-3">
                                            <div class="fw-bold text-dark mb-0">{{ $student->first_name }} {{ $student->last_name }}</div>
                                            <span class="text-muted" style="font-size: 0.75rem;">{{ $student->streamData->stream_name ?? 'Regular' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center fw-medium text-secondary">{{ $student->admission_no }}</td>
                                <td class="text-center">
                                    <input type="number" class="marks-input-modern marks-input" 
                                           data-student-id="{{ $student->id }}"
                                           value="{{ $existingMarks[$student->id] ?? '' }}"
                                           placeholder="--">
                                </td>
                                <td class="text-center">
                                    <span class="grade-pill grade-display" data-student-id="{{ $student->id }}">
                                        {{ isset($existingMarks[$student->id]) ? \App\Http\Controllers\TeacherExamController::calculateGradeStatic($existingMarks[$student->id]) : '-' }}
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <button class="btn btn-primary rounded-pill btn-sm px-3 py-2 save-result" data-student-id="{{ $student->id }}">
                                        <i class="fa-solid fa-check-circle"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sidebar Upload --}}
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="upload-card-modern shadow-lg">
                <h4 class="fw-bold mb-3">Bulk Import</h4>
                <p class="text-white-50 small mb-4">Upload your results via CSV file to save time. Make sure the headers match the template.</p>
                
                <form action="{{ route('teacher-exams.results.bulk') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="exam_id" value="{{ $exam->id }}">
                    <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                    
                    <div class="file-input-wrapper">
                        <i class="fa-solid fa-cloud-arrow-up fs-2 mb-2 text-primary"></i>
                        <input type="file" name="file" class="form-control form-control-sm bg-transparent text-white border-0" accept=".csv" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold">
                        <i class="fa-solid fa-bolt me-2"></i>Sync Results
                    </button>
                </form>

                <div class="mt-5 p-3 rounded-4" style="background: rgba(255,255,255,0.05);">
                    <h6 class="small fw-bold text-uppercase opacity-50 mb-3">Quick Guidelines</h6>
                    <div class="d-flex gap-3 mb-3 small">
                        <i class="fa-solid fa-circle-check text-success"></i>
                        <span>Max score is 100.0</span>
                    </div>
                    <div class="d-flex gap-3 small">
                        <i class="fa-solid fa-circle-check text-success"></i>
                        <span>System calculates grades automatically.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script logic remain similar but with updated UI feedback --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const getGrade = (marks) => {
        if (marks === '' || marks === null) return '-';
        marks = parseFloat(marks);
        if (marks >= 81) return 'A';
        if (marks >= 61) return 'B';
        if (marks >= 41) return 'C';
        if (marks >= 21) return 'D';
        return 'F';
    };

    document.querySelectorAll('.marks-input').forEach(input => {
        input.addEventListener('input', function() {
            const studentId = this.dataset.studentId;
            const gradeSpan = document.querySelector(`.grade-display[data-student-id="${studentId}"]`);
            if (this.value > 100) this.value = 100;
            gradeSpan.textContent = getGrade(this.value);
            gradeSpan.style.background = '#eef2ff';
            gradeSpan.style.color = '#4f46e5';
        });
    });

    document.querySelectorAll('.save-result').forEach(btn => {
        btn.addEventListener('click', function() {
            const studentId = this.dataset.studentId;
            const marks = document.querySelector(`.marks-input[data-student-id="${studentId}"]`).value;
            const icon = this.querySelector('i');

            if (marks === "") return;

            this.classList.replace('btn-primary', 'btn-warning');
            icon.className = 'fa-solid fa-spinner fa-spin';

            fetch('{{ route("teacher-exams.results.single") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({
                    exam_id: '{{ $exam->id }}',
                    subject_id: '{{ $subject->id }}',
                    student_id: studentId,
                    marks_obtained: marks
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    this.classList.replace('btn-warning', 'btn-success');
                    icon.className = 'fa-solid fa-check';
                    setTimeout(() => {
                        this.classList.replace('btn-success', 'btn-primary');
                        icon.className = 'fa-solid fa-check-circle';
                    }, 2000);
                }
            });
        });
    });
});
</script>
@endsection