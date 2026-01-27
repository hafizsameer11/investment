@extends('admin.layouts.main')

@push('styles')
<style>
    .chat-container {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 200px);
        min-height: 600px;
    }

    .chat-header-info {
        background: #f8f9fa;
        padding: 1rem;
        border-bottom: 1px solid #dee2e6;
    }

    .chat-messages-container {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
        background: #f5f5f5;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .chat-message {
        display: flex;
        flex-direction: column;
        max-width: 75%;
    }

    .chat-message.user {
        align-self: flex-end;
    }

    .chat-message.admin {
        align-self: flex-start;
    }

    .chat-message-bubble {
        padding: 0.75rem 1rem;
        border-radius: 12px;
        word-wrap: break-word;
    }

    .chat-message.user .chat-message-bubble {
        background: linear-gradient(135deg, #9333ea 0%, #7c3aed 100%);
        color: white;
    }

    .chat-message.admin .chat-message-bubble {
        background: #e5e5e5;
        color: #1a1a1a;
    }

    .chat-message-time {
        font-size: 0.75rem;
        color: #666;
        margin-top: 0.25rem;
    }

    .chat-input-container {
        display: flex;
        gap: 0.5rem;
        padding: 1rem;
        background: white;
        border-top: 1px solid #dee2e6;
    }

    .chat-input {
        flex: 1;
        padding: 0.75rem;
        border: 1px solid #dee2e6;
        border-radius: 20px;
        font-size: 0.9375rem;
    }

    .chat-send-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #9333ea 0%, #7c3aed 100%);
        border: none;
        color: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@section('content')
<div class="page-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="btn-group float-right">
                        <ol class="breadcrumb hide-phone p-0 m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.chats.index') }}">Chats</a></li>
                            <li class="breadcrumb-item active">Chat Details</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Chat Details</h4>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="chat-container">
                            <div class="chat-header-info">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1">{{ $chat->user_name }}</h5>
                                        <small class="text-muted">{{ $chat->user_email }}</small>
                                    </div>
                                    <div>
                                        @if($chat->status === 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($chat->status === 'active')
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Closed</span>
                                        @endif
                                        @if(!$chat->assigned_to)
                                            <button class="btn btn-sm btn-primary ml-2" id="assignChatBtn">
                                                <i class="mdi mdi-account-plus"></i> Assign to Me
                                            </button>
                                        @elseif($chat->assigned_to === auth()->id())
                                            <span class="badge badge-info ml-2">Assigned to You</span>
                                        @else
                                            <span class="badge badge-secondary ml-2">Assigned to {{ $chat->assignedAdmin->name ?? 'Another Admin' }}</span>
                                        @endif
                                        @if($chat->status !== 'closed')
                                            <button class="btn btn-sm btn-danger ml-2" id="closeChatBtn">
                                                <i class="mdi mdi-close"></i> Close Chat
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="chat-messages-container" id="chatMessagesContainer">
                                @foreach($chat->messages as $message)
                                <div class="chat-message {{ $message->sender_type }}">
                                    <div class="chat-message-bubble">
                                        {{ $message->message }}
                                    </div>
                                    <div class="chat-message-time">
                                        {{ $message->created_at->format('h:i A') }}
                                        @if($message->sender_type === 'admin')
                                            - {{ $message->sender->name ?? 'Admin' }}
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            @if($chat->status !== 'closed')
                            <div class="chat-input-container">
                                <input type="text" id="adminChatInput" class="chat-input" placeholder="Type your message...">
                                <button class="chat-send-btn" id="adminSendMessageBtn">
                                    <i class="mdi mdi-send"></i>
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Chat Information</h5>
                        <table class="table table-borderless table-sm">
                            <tbody>
                                <tr>
                                    <th>Chat ID:</th>
                                    <td>#{{ $chat->id }}</td>
                                </tr>
                                <tr>
                                    <th>User:</th>
                                    <td>{{ $chat->user_name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $chat->user_email }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($chat->status === 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($chat->status === 'active')
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Closed</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Assigned To:</th>
                                    <td>
                                        @if($chat->assignedAdmin)
                                            {{ $chat->assignedAdmin->name }}
                                        @else
                                            <span class="text-muted">Unassigned</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Started:</th>
                                    <td>{{ $chat->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                                @if($chat->closed_at)
                                <tr>
                                    <th>Closed:</th>
                                    <td>{{ $chat->closed_at->format('M d, Y h:i A') }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Messages:</th>
                                    <td>{{ $chat->messages->count() }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const chatId = {{ $chat->id }};
    const assignChatUrl = '{{ route("admin.chats.assign", $chat->id) }}';
    const sendMessageUrl = '{{ route("admin.chats.send-message", $chat->id) }}';
    const closeChatUrl = '{{ route("admin.chats.close", $chat->id) }}';

    // Assign chat
    document.getElementById('assignChatBtn')?.addEventListener('click', function() {
        fetch(assignChatUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    });

    // Send message
    document.getElementById('adminSendMessageBtn')?.addEventListener('click', function() {
        const input = document.getElementById('adminChatInput');
        const message = input.value.trim();
        
        if (!message) return;

        fetch(sendMessageUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                input.value = '';
                // Add message to UI (will be handled by Echo in full implementation)
                location.reload();
            }
        });
    });

    // Close chat
    document.getElementById('closeChatBtn')?.addEventListener('click', function() {
        if (confirm('Are you sure you want to close this chat?')) {
            fetch(closeChatUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    });

    // Enter key to send
    document.getElementById('adminChatInput')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('adminSendMessageBtn')?.click();
        }
    });
</script>
@endpush
@endsection

