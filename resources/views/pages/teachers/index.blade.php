@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Teacher Management</h3>
            <p class="text-muted small">Manage all staff details and academic qualifications</p>
        </div>
        <a href="{{ route('teachers.create') }}" class="btn btn-primary shadow-sm px-4">
            <i class="fas fa-plus me-1"></i> Add New Teacher
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="teachersTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Teacher</th>
                            <th class="border-0">ID Number</th>
                            <th class="border-0">Branch</th>
                            <th class="border-0">Contact</th>
                            <th class="border-0">Designation</th>
                            <th class="border-0">Status</th>
                            <th class="border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teachers as $teacher)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $teacher->image ? asset('uploads/teachers/'.$teacher->image) : asset('assets/img/default-user.png') }}" 
                                         class="rounded-circle me-3" style="width: 45px; height: 45px; object-fit: cover; border: 2px solid #eee;">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $teacher->full_name }}</div>
                                        <small class="text-muted">{{ $teacher->gender }} • {{ $teacher->qualification }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-primary border">{{ $teacher->teacher_id_number }}</span></td>
                            <td><i class="fas fa-map-marker-alt text-muted me-1 small"></i> {{ $teacher->branch->branch_name }}</td>
                            <td>
                                <div class="small fw-bold">{{ $teacher->phone }}</div>
                                <div class="small text-muted">{{ $teacher->email ?? 'No Email' }}</div>
                            </td>
                            <td>{{ $teacher->designation }}</td>
                            <td>
                                <form action="{{ route('teachers.toggle', $teacher->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm rounded-pill px-3 {{ $teacher->status ? 'btn-success' : 'btn-secondary' }}" style="font-size: 0.75rem;">
                                        {{ $teacher->status ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#viewTeacher{{$teacher->id}}" title="View Profile">
                                        <i class="fas fa-eye text-info"></i>
                                    </button>
                                    <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-sm btn-light border" title="Edit">
                                        <i class="fas fa-edit text-primary"></i>
                                    </a>
                                    <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-light border" onclick="return confirm('Are you sure you want to delete this teacher?')" title="Delete">
                                            <i class="fas fa-trash text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- MODAL VIEW FULL PROFILE --}}
                        @include('pages.teachers.partials.view_modal')

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#teachersTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "search": "Search Teacher:",
                "paginate": {
                    "next": "Next",
                    "previous": "Back"
                }
            },
            "columnDefs": [
                { "orderable": false, "targets": 6 } // Disable ordering on "Actions" column
            ]
        });
    });
</script>
@endpush