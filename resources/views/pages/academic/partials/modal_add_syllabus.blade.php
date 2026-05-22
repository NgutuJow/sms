<div class="modal fade" id="addSyllabusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog border-0">
        <form action="{{ route('academic.syllabus.store') }}" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow">
            @csrf
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-cloud-upload-alt me-2"></i> Upload Syllabus PDF</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4">
                    <label class="form-label fw-bold small">Select Subject</label>
                    <select name="subject_id" class="form-select border-0 bg-light" required>
                        <option value="">-- Choose Subject --</option>
                        @foreach($classes as $class)
                            <optgroup label="{{ $class->class_name }}">
                                @foreach($class->subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold small">Syllabus Title (e.g. 2026 Curriculum)</label>
                    <input type="text" name="topic_name" class="form-control border-0 bg-light" placeholder="Enter title" required>
                </div>

                <div class="mb-2">
                    <label class="form-label fw-bold small">PDF File</label>
                    <div class="upload-zone p-4 border-dashed rounded-3 text-center bg-light position-relative" style="border: 2px dashed #cbd5e1;">
                        <input type="file" name="file_path" class="stretched-link opacity-0" accept=".pdf" required id="pdfInput">
                        <i class="fas fa-file-pdf text-danger fs-1 mb-2"></i>
                        <div class="fw-bold text-dark" id="fileName">Click to upload Syllabus</div>
                        <div class="text-muted x-small">Maximum size: 10MB (PDF only)</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Start Upload</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('pdfInput').onchange = function() {
        document.getElementById('fileName').innerHTML = this.files[0].name;
        document.getElementById('fileName').classList.add('text-primary');
    };
</script>