<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('academic.subject.store') }}" method="POST" class="modal-content border-0 shadow">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add New Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Select Class (Grade)</label>
                    <select name="school_class_id" class="form-select" required>
                        <option value="">Which class is this subject for?</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->class_name }} ({{ $class->branch->branch_name }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label fw-bold">Subject Name</label>
                        <input type="text" name="subject_name" class="form-control" placeholder="e.g. Mathematics" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Code</label>
                        <input type="text" name="subject_code" class="form-control" placeholder="MATH-01">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Subject Type</label>
                    <select name="type" class="form-select" required>
                        <option value="Theory">Theory Only</option>
                        <option value="Practical">Practical Only</option>
                        <option value="Both">Both (Theory & Practical)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary shadow-sm">Save Subject</button>
            </div>
        </form>
    </div>
</div>