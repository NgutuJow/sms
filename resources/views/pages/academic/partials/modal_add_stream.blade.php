<div class="modal fade" id="addStream{{$class->id}}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('academic.stream.store') }}" method="POST" class="modal-content border-0 shadow">
            @csrf
            <input type="hidden" name="school_class_id" value="{{ $class->id }}">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add Stream to {{ $class->class_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Stream Name</label>
                    <input type="text" name="stream_name" class="form-control" placeholder="e.g. A, Blue, or Commerce" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Assign Class Teacher (Optional)</label>
                    <select name="teacher_id" class="form-select">
                        <option value="">Select Teacher...</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->full_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary shadow-sm">Save Stream</button>
            </div>
        </form>
    </div>
</div>