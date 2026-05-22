<div class="modal fade" id="addClassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('academic.class.store') }}" method="POST" class="modal-content border-0 shadow">
            @csrf
            <div class="modal-header text-white" style="background: #0d6efd;">
                <h5 class="modal-title fw-bold">Create New Class (Grade)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Select Branch</label>
                    <select name="branch_id" class="form-select" required>
                        <option value="">Choose Branch...</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Class/Grade Name</label>
                    <input type="text" name="class_name" class="form-control" placeholder="e.g. Form One" required>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary shadow-sm">Create Class</button>
            </div>
        </form>
    </div>
</div>