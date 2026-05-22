<div class="modal fade" id="viewTeacher{{$teacher->id}}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-tie me-2"></i> Teacher Detailed Profile</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0">
                    {{-- Left Side: Image & Basic Info --}}
                    <div class="col-md-4 bg-light text-center p-4 border-end">
                        <img src="{{ $teacher->image ? asset('uploads/teachers/'.$teacher->image) : asset('assets/img/default-user.png') }}" 
                             class="rounded shadow-sm mb-3 border border-white border-4" 
                             style="width: 150px; height: 150px; object-fit: cover;">
                        <h5 class="fw-bold text-dark mb-1">{{ $teacher->full_name }}</h5>
                        <p class="badge bg-soft-primary text-primary px-3">{{ $teacher->designation }}</p>
                        <hr>
                        <div class="text-start small">
                            <p class="mb-1"><strong>Status:</strong> 
                                <span class="{{ $teacher->status ? 'text-success' : 'text-danger' }}">
                                    {{ $teacher->status ? '● Active' : '● Inactive' }}
                                </span>
                            </p>
                            <p class="mb-1"><strong>Joined:</strong> {{ date('d M, Y', strtotime($teacher->joining_date)) }}</p>
                        </div>
                    </div>

                    {{-- Right Side: Full Migration Data --}}
                    <div class="col-md-8 p-4">
                        <h6 class="text-primary fw-bold text-uppercase small mb-3">Academic & Professional</h6>
                        <div class="row mb-4">
                            <div class="col-6 mb-3">
                                <label class="text-muted d-block small">Teacher ID</label>
                                <span class="fw-bold">{{ $teacher->teacher_id_number }}</span>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-muted d-block small">Qualification</label>
                                <span class="fw-bold">{{ $teacher->qualification }}</span>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-muted d-block small">Gender</label>
                                <span class="fw-bold">{{ $teacher->gender }}</span>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-muted d-block small">Date of Birth</label>
                                <span class="fw-bold">{{ $teacher->dob ? date('d M, Y', strtotime($teacher->dob)) : 'N/A' }}</span>
                            </div>
                        </div>

                        <h6 class="text-primary fw-bold text-uppercase small mb-3 border-top pt-3">Contact Details</h6>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="text-muted d-block small">Phone Number</label>
                                <span class="fw-bold">{{ $teacher->phone }}</span>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-muted d-block small">Email Address</label>
                                <span class="fw-bold text-lowercase">{{ $teacher->email ?? 'N/A' }}</span>
                            </div>
                            <div class="col-12">
                                <label class="text-muted d-block small">Residential Address</label>
                                <span class="fw-bold">{{ $teacher->address ?? 'No address recorded' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary shadow-sm" data-bs-dismiss="modal">Close Profile</button>
                <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-edit me-1"></i> Edit Details
                </a>
            </div>
        </div>
    </div>
</div>