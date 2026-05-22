<div class="modal fade" id="addSemester{{$session->id}}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('academic.semester.store') }}" method="POST" class="modal-content border-0 shadow">
            @csrf
            <input type="hidden" name="academic_session_id" value="{{ $session->id }}">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">New Semester for {{ $session->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Semester Name</label>
                    <input type="text" name="semester_name" class="form-control" placeholder="e.g. Semester 1" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Start Date</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">End Date</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary shadow-sm">Save Semester</button>
            </div>
        </form>
    </div>
</div>