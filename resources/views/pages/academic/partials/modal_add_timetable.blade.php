<div class="modal fade" id="addTimetableModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog border-0">
        <form action="{{ route('academic.timetable.store') }}" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow">
            @csrf
            <div class="modal-header bg-info text-white border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-upload me-2"></i> Upload Class Timetable</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">SELECT CLASS STREAM</label>
                    <select name="stream_id" class="form-select border-0 bg-light" required>
                        @foreach($classes as $class)
                            @foreach($class->streams as $stream)
                                <option value="{{ $stream->id }}">{{ $class->class_name }} - {{ $stream->stream_name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">TIMETABLE TITLE</label>
                    <input type="text" name="timetable_name" class="form-control border-0 bg-light" placeholder="e.g., Term 1 Schedule" required>
                </div>

                <div class="mb-0">
                    <label class="form-label fw-bold small text-muted">ATTACH PDF FILE</label>
                    <div class="border rounded-3 p-4 text-center bg-light shadow-sm" style="border: 2px dashed #dee2e6 !important;">
                        <i class="fas fa-file-pdf text-danger fa-3x mb-2"></i>
                        <input type="file" name="timetable_pdf" class="form-control mt-2" accept=".pdf" required>
                        <div class="x-small text-muted mt-2">Only PDF files are accepted (Max 10MB)</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-info text-white px-4 fw-bold shadow-sm">Start Upload</button>
            </div>
        </form>
    </div>
</div>