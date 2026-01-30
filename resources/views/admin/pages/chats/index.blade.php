@extends('admin.layouts.main')

@push('styles')
<style>
    @media (max-width: 576px) {
        .chats-topbar {
            flex-wrap: wrap;
            align-items: flex-start !important;
            gap: 10px;
        }

        .chats-topbar .header-title {
            width: 100%;
            margin-bottom: 0;
        }

        .chats-filter-actions {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .chats-filter-actions .btn {
            flex: 1 1 calc(50% - 8px);
            width: 100%;
            padding-left: 10px;
            padding-right: 10px;
            white-space: nowrap;
        }
    }
</style>
@endpush

@section('content')
<div class="page-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">Chat Management</h4>
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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3 chats-topbar">
                            <h4 class="mt-0 header-title">All Chats</h4>
                            <div class="chats-filter-actions">
                                <a href="{{ route('admin.chats.index', ['status' => 'pending']) }}" class="btn btn-warning waves-effect waves-light">
                                    <i class="mdi mdi-clock"></i> Pending
                                </a>
                                <a href="{{ route('admin.chats.index', ['status' => 'active']) }}" class="btn btn-success waves-effect waves-light">
                                    <i class="mdi mdi-check"></i> Active
                                </a>
                                <a href="{{ route('admin.chats.index', ['status' => 'closed']) }}" class="btn btn-secondary waves-effect waves-light">
                                    <i class="mdi mdi-close"></i> Closed
                                </a>
                                <a href="{{ route('admin.chats.index') }}" class="btn btn-primary waves-effect waves-light">
                                    <i class="mdi mdi-view-list"></i> All
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Assigned To</th>
                                        <th>Last Message</th>
                                        <th>Started</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($chats as $chat)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>
                                            <strong>{{ $chat->user_name }}</strong>
                                        </td>
                                        <td>{{ $chat->user_email }}</td>
                                        <td>
                                            @if($chat->status === 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($chat->status === 'active')
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-secondary">Closed</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($chat->assignedAdmin)
                                                {{ $chat->assignedAdmin->name }}
                                            @else
                                                <span class="text-muted">Unassigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($chat->latestMessage)
                                                <small>{{ Str::limit($chat->latestMessage->message, 50) }}</small>
                                            @else
                                                <span class="text-muted">No messages</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $chat->created_at->format('M d, Y h:i A') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.chats.show', $chat->id) }}" class="btn btn-sm btn-primary waves-effect waves-light">
                                                <i class="mdi mdi-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No chats found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($chats->hasPages())
                        <div class="mt-3">
                            {{ $chats->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

