@extends('pages.parent.layout.layout')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card-custom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Messages & Notifications</h4>
                    <p class="text-muted mb-0">Messages sent to you via WhatsApp and System</p>
                </div>
                <div>
                    <span class="badge bg-primary">{{ $messages->count() }} Total Messages</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-custom h-100">
            <h5 class="mb-3">Your Children</h5>
            <div class="list-group list-group-flush">
                @foreach($students as $student)
                <div class="list-group-item px-0 border-0 mb-2">
                    <div class="d-flex align-items-center p-3 rounded-3 bg-light border">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                        </div>
                        <div>
                            <div class="fw-bold">{{ $student->first_name }} {{ $student->last_name }}</div>
                            <small class="text-muted">{{ $student->classData->name ?? '' }} - {{ $student->admission_no }}</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-4 p-3 bg-info bg-opacity-10 rounded-3 border border-info border-opacity-25">
                <h6><i class="fa-solid fa-circle-info me-2"></i>WhatsApp Sync</h6>
                <p class="small text-muted mb-0">These messages are also sent to your registered WhatsApp number: <strong>{{ auth()->user()->students->first()->guardian_phone ?? 'N/A' }}</strong></p>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card-custom">
            <h5 class="mb-3">Recent Messages</h5>
            
            @if($messages->count() > 0)
                <div class="message-timeline">
                    @foreach($messages as $message)
                    <div class="mb-4 position-relative ps-4">
                        <div class="position-absolute start-0 top-0 h-100 border-start border-2 border-light"></div>
                        <div class="position-absolute start-0 top-0 translate-middle-x bg-white p-1">
                            @if($message->sender_role == 'admin')
                                <i class="fa-solid fa-user-shield text-danger"></i>
                            @elseif($message->sender_role == 'teacher')
                                <i class="fa-solid fa-chalkboard-user text-primary"></i>
                            @elseif($message->sender_role == 'accountant')
                                <i class="fa-solid fa-file-invoice-dollar text-success"></i>
                            @else
                                <i class="fa-solid fa-robot text-secondary"></i>
                            @endif
                        </div>
                        
                        <div class="card border-0 bg-light rounded-3 p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="fw-bold">{{ ucfirst($message->sender_role) }}</span>
                                    <span class="text-muted mx-2">•</span>
                                    <span class="small text-muted">{{ $message->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                                @if($message->status == 'sent' || $message->status == 'delivered')
                                    <span class="badge bg-success-soft text-success"><i class="fa-solid fa-check-double me-1"></i> WhatsApp</span>
                                @else
                                    <span class="badge bg-secondary-soft text-secondary"><i class="fa-solid fa-clock me-1"></i> System Only</span>
                                @endif
                            </div>
                            
                            <div class="message-content">
                                {!! nl2br(e($message->message)) !!}
                            </div>
                            
                            <div class="mt-2 text-end">
                                <small class="text-muted">Regarding: {{ $message->student->first_name }}</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <div class="display-1 text-light mb-3"><i class="fa-solid fa-message"></i></div>
                    <h5>No messages yet</h5>
                    <p class="text-muted">When teachers or staff send you messages, they will appear here.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.bg-success-soft { background-color: rgba(16, 185, 129, 0.1); }
.bg-secondary-soft { background-color: rgba(107, 114, 128, 0.1); }
.message-timeline { position: relative; }
</style>
@endsection
