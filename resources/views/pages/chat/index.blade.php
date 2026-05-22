@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold">Parent WhatsApp Chat</h4>
            <p class="text-muted mb-0">Select a parent and start a WhatsApp conversation from admin, teacher, or accountant accounts.</p>
        </div>
    </div>

    <div class="row g-3">
        @forelse($students as $student)
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-1">{{ $student->first_name }} {{ $student->last_name }}</h5>
                        <p class="mb-1 text-muted">Class: {{ $student->classData->class_name ?? 'N/A' }}</p>
                        <p class="mb-1 text-muted">Parent: {{ $student->guardian_name ?? 'N/A' }}</p>
                        <p class="mb-3 text-muted">
                            Phone: {{ $student->guardian_phone }}
                            @if($student->whatsapp_status === true)
                                <span class="badge bg-success-soft text-success"><i class="fa-brands fa-whatsapp"></i> Verified</span>
                            @elseif($student->whatsapp_status === false)
                                <span class="badge bg-danger-soft text-danger"><i class="fa-brands fa-whatsapp"></i> Not Found</span>
                            @endif
                        </p>
                        <a href="{{ route('chat.show', $student->id) }}" class="btn btn-primary btn-sm w-100">
                            <i class="fa-solid fa-comments me-2"></i>
                            Chat on WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No parents with valid phone numbers found.</div>
            </div>
        @endforelse
    </div>
</div>
@endsection
