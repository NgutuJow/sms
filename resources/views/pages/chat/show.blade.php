@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Area -->
    <div class="row mb-4 align-items-center">
        <div class="col-auto">
            <a href="{{ route('chat.index') }}" class="btn btn-light border-0 shadow-sm rounded-circle p-2" style="width: 40px; height: 40px;">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>
        <div class="col">
            <h4 class="fw-bold mb-0">Conversation: {{ $student->first_name }} {{ $student->last_name }}</h4>
            <p class="text-muted small mb-0">
                <span class="me-3"><i class="fa-solid fa-user-shield me-1"></i> {{ $student->guardian_name ?? 'N/A' }}</span>
                <span><i class="fa-solid fa-phone me-1"></i> {{ $student->guardian_phone }}</span>
            </p>
        </div>
        <div class="col-auto">
            <div class="dropdown">
                <button class="btn btn-white border shadow-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li>
                        <form action="{{ route('chat.clear', $student->id) }}" method="POST" onsubmit="return confirm('Erase all history?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fa-solid fa-broom me-2"></i> Clear History
                            </button>
                        </form>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="https://api.whatsapp.com/send?phone={{ preg_replace('/[^0-9]/', '', $student->guardian_phone) }}" target="_blank">
                        <i class="fa-brands fa-whatsapp me-2 text-success"></i> Open in WhatsApp
                    </a></li>
                </ul>
            </div>
        </div>
    </div>

    @if(session('success') || session('error'))
        <div class="row mb-4">
            <div class="col-12">
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-0">
                        <i class="fa-solid fa-circle-check me-2"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-0">
                        <i class="fa-solid fa-circle-exclamation me-2"></i>
                        <div>
                            <strong>Error:</strong> {{ session('error') }}
                            @if(isset(session('error_details')['error']['message']))
                                <div class="small opacity-75 mt-1">{{ session('error_details')['error']['message'] }}</div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Chat Area -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-body p-0 d-flex flex-column" style="height: 600px;">
                    <!-- Message Feed -->
                    <div class="flex-grow-1 p-4 overflow-auto bg-chat" id="chatFeed">
                        <div class="d-flex flex-column gap-3">
                            @forelse($messages as $message)
                                <div class="d-flex {{ $message->sender_role == 'parent' ? 'justify-content-start' : 'justify-content-end' }} animate-in">
                                    <div class="message-wrapper {{ $message->sender_role == 'parent' ? 'parent' : 'staff' }}" style="max-width: 75%;">
                                        <div class="message-meta d-flex justify-content-between align-items-center mb-1 px-2">
                                            <span class="role-badge">{{ $message->sender_role == 'parent' ? 'Parent' : ucfirst($message->sender_role) }}</span>
                                            <form action="{{ route('chat.destroy', $message->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-delete border-0 bg-transparent text-muted opacity-50 hover-opacity-100">
                                                    <i class="fa-solid fa-xmark" style="font-size: 0.7rem;"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="message-bubble">
                                            <div class="content">{!! nl2br(e($message->message)) !!}</div>
                                            <div class="timestamp text-end mt-1">{{ $message->created_at->format('H:i') }}</div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="h-100 d-flex flex-column align-items-center justify-content-center text-muted py-5">
                                    <div class="bg-light rounded-circle p-4 mb-3">
                                        <i class="fa-solid fa-comments fs-1"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark">No Conversation Yet</h5>
                                    <p class="small">Messages sent via the form will appear here.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Input Area -->
                    <div class="p-3 bg-white border-top">
                        <form action="{{ route('chat.send', $student->id) }}" method="POST" id="chatForm">
                            @csrf
                            <div class="input-group gap-2">
                                <textarea name="message" class="form-control border-0 bg-light rounded-3 px-3 py-2" rows="1" placeholder="Type a message..." style="resize: none;" required></textarea>
                                <button type="submit" class="btn btn-primary rounded-3 px-4 shadow-sm d-flex align-items-center gap-2">
                                    <span>Send</span>
                                    <i class="fa-solid fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar / Info Area -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-4">Communication Details</h6>
                    
                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="bg-success-soft text-success rounded-3 p-3">
                            <i class="fa-brands fa-whatsapp fs-3"></i>
                        </div>
                        <div>
                            <div class="fw-bold">WhatsApp Cloud API</div>
                            <small class="text-muted d-block">Automatic delivery enabled</small>
                            <span class="badge bg-success-soft text-success mt-2">Active</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small fw-bold text-uppercase mb-2 d-block">Student Info</label>
                        <div class="p-3 bg-light rounded-3">
                            <div class="fw-bold">{{ $student->first_name }} {{ $student->last_name }}</div>
                            <div class="small text-muted">{{ $student->classData->class_name ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <div class="p-3 rounded-4 bg-primary bg-opacity-10 border border-primary border-opacity-25">
                        <h6 class="fw-bold text-primary mb-2"><i class="fa-solid fa-lightbulb me-2"></i>Quick Help</h6>
                        <p class="small text-muted mb-0">
                            Meta requires a customer reply within 24h for free-form text. If delivery fails, use an approved template.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f8fafc; }
    .bg-chat { background-color: #f1f5f9; background-image: radial-gradient(#cbd5e1 0.5px, transparent 0.5px); background-size: 20px 20px; }
    .bg-success-soft { background-color: rgba(16, 185, 129, 0.1); }
    .btn-white { background: #fff; }
    
    /* Message Bubbles */
    .message-bubble {
        padding: 10px 16px;
        border-radius: 18px;
        font-size: 0.95rem;
        line-height: 1.5;
        position: relative;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    
    .staff .message-bubble {
        background-color: #2563eb;
        color: #fff;
        border-bottom-right-radius: 4px;
    }
    
    .parent .message-bubble {
        background-color: #fff;
        color: #1e293b;
        border-bottom-left-radius: 4px;
    }
    
    .role-badge { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; }
    .timestamp { font-size: 0.7rem; opacity: 0.7; }
    
    /* Animations */
    .animate-in { animation: slideUp 0.3s ease-out forwards; }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Hover Effects */
    .hover-opacity-100:hover { opacity: 1 !important; }
    
    /* Custom Scrollbar */
    #chatFeed::-webkit-scrollbar { width: 5px; }
    #chatFeed::-webkit-scrollbar-track { background: transparent; }
    #chatFeed::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatFeed = document.getElementById('chatFeed');
        chatFeed.scrollTop = chatFeed.scrollHeight;

        // Auto-expand textarea
        const textarea = document.querySelector('textarea[name="message"]');
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
            if(this.scrollHeight > 150) {
                this.style.overflowY = 'scroll';
            } else {
                this.style.overflowY = 'hidden';
            }
        });
    });
</script>
@endsection
