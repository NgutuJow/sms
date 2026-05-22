@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-3 text-uppercase">Ingiza Alama za Mitihani</h4>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <label class="fw-bold mb-2">Darasa</label>
                <select id="class_id" class="form-select">
                    <option value="">-- Chagua Darasa --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <label class="fw-bold mb-2">Mtihani</label>
                <select id="exam_id_select" class="form-select">
                    <option value="">-- Chagua Mtihani --</option>
                    @foreach($exams as $exam)
                        <option value="{{ $exam->id }}">{{ $exam->exam_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('marks.store') }}" id="marks-form">
        @csrf
        <input type="hidden" name="class_id" id="hidden_class">
        <input type="hidden" name="exam_id" id="hidden_exam">

        <div id="marks_container">
            </div>

        <div id="save-button-container" style="display: none;" class="mt-4 mb-5">
            <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                <i class="bi bi-check-all"></i> Hifadhi Alama
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('class_id').addEventListener('change', loadStudents);
document.getElementById('exam_id_select').addEventListener('change', function() {
    document.getElementById('hidden_exam').value = this.value;
});

function loadStudents() {
    let classId = document.getElementById('class_id').value;
    let container = document.getElementById('marks_container');
    let btnContainer = document.getElementById('save-button-container');
    
    document.getElementById('hidden_class').value = classId;

    if (!classId) {
        container.innerHTML = "";
        btnContainer.style.display = 'none';
        return;
    }

    container.innerHTML = '<div class="text-center p-5">Inapakia wanafunzi na masomo...</div>';

    fetch(`/marks/load-data?class_id=${classId}`)
        .then(res => res.json())
        .then(data => {
            if (data.students.length === 0) {
                container.innerHTML = '<div class="alert alert-warning">Hakuna wanafunzi kwenye darasa hili.</div>';
                btnContainer.style.display = 'none';
                return;
            }

            let html = "";
            data.students.forEach(stu => {
                html += `
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-light fw-bold">
                        ${stu.first_name} ${stu.last_name} (${stu.admission_no})
                    </div>
                    <div class="card-body">
                        <div class="row">
                `;

                // Kama hakuna masomo, weka ujumbe
                if (data.subjects.length === 0) {
                    html += '<div class="col-12 text-danger small">Hakuna masomo yaliyopangwa kwa darasa hili!</div>';
                }

                data.subjects.forEach(sub => {
                    let sName = sub.subject ? sub.subject.subject_name : "Somo";
                    let sId = sub.subject_id;

                    html += `
                        <div class="col-md-3 mb-3">
                            <label class="small fw-bold text-muted d-block">${sName}</label>
                            <input type="number" 
                                   name="marks[${stu.id}][${sId}]" 
                                   class="form-control" 
                                   min="0" max="100" 
                                   placeholder="0-100">
                        </div>
                    `;
                });

                html += `</div></div></div>`;
            });

            container.innerHTML = html;
            
            // Onyesha button kama kuna wanafunzi na masomo
            if (data.subjects.length > 0) {
                btnContainer.style.display = 'block';
            } else {
                btnContainer.style.display = 'none';
            }
        })
        .catch(err => {
            container.innerHTML = '<div class="alert alert-danger font-weight-bold text-center">Imefeli kupakia data!</div>';
            console.error(err);
        });
}
</script>
@endsection