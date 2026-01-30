@extends('admin.layouts.main')

@section('content')
<div class="page-content-wrapper ">

    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">Users Management</h4>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <!-- end page title end breadcrumb -->

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mt-0 header-title">All Users</h4>
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary waves-effect waves-light">
                                <i class="mdi mdi-plus"></i> Add New User
                            </a>
                        </div>

                        <form method="GET" action="{{ route('admin.users.index') }}" class="mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" name="q" value="{{ request()->query('q') }}" class="form-control" placeholder="Search by email or phone">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">Search</button>
                                            <a href="{{ route('admin.users.index') }}" class="btn btn-light">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="table-rep-plugin">
                            <div class="table-responsive b-0" data-pattern="priority-columns">
                                <table id="users-table" class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th data-priority="1">Name</th>
                                        <th data-priority="2">Username</th>
                                        <th data-priority="1">Email</th>
                                        <th data-priority="3">Phone</th>
                                        <th data-priority="2">Role</th>
                                        <th data-priority="1">Referral Code</th>
                                        <th data-priority="3">Created At</th>
                                        <th data-priority="1">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($users as $user)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $user->name ?? 'N/A' }}</td>
                                        <td>{{ $user->username ?? 'N/A' }}</td>
                                        <td>{{ $user->email ?? 'N/A' }}</td>
                                        <td>{{ $user->phone ?? 'N/A' }}</td>
                                        <td>
                                            @if($user->role === 'admin')
                                                <span class="badge badge-danger">Admin</span>
                                            @else
                                                <span class="badge badge-primary">User</span>
                                            @endif
                                        </td>
                                        <td><code>{{ $user->refer_code ?? 'N/A' }}</code></td>
                                        <td>{{ $user->created_at ? $user->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info waves-effect waves-light" title="View">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary waves-effect waves-light" title="Edit">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger waves-effect waves-light delete-user-btn" 
                                                    data-user-id="{{ $user->id }}" 
                                                    data-user-name="{{ $user->name }}"
                                                    title="Delete">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No users found. <a href="{{ route('admin.users.create') }}">Create one now</a>.</td>
                                    </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div><!-- container -->

</div> <!-- Page content Wrapper -->
@endsection

@push('scripts')
<script>
$(function() {
    $('#users-table').closest('.table-responsive').responsiveTable({
        addDisplayAllBtn: false,
        addFocusBtn: false
    });
    
    // Hide the dropdown button toolbar after initialization
    setTimeout(function() {
        $('.table-wrapper .btn-toolbar').hide();
    }, 100);
    
    // Handle delete user button clicks
    $(document).on('click', '.delete-user-btn', function(e) {
        e.preventDefault();
        
        const button = $(this);
        const userId = button.data('user-id');
        const userName = button.data('user-name');

        // Confirm deletion (no browser popup)
        if (typeof window.showConfirmDialog === 'function') {
            window.showConfirmDialog('Are you sure you want to delete user "' + userName + '"?\n\nThis action cannot be undone!', function() {
                doDelete();
            });
        }

        function doDelete() {
            // Disable button and show loading state
            button.prop('disabled', true);
            const originalHtml = button.html();
            button.html('<i class="mdi mdi-loading mdi-spin"></i>');
            
            // Make AJAX DELETE request
            $.ajax({
                url: '{{ url("/admin/users") }}/' + userId,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Reload page to show success message and updated table
                    window.location.reload();
                },
                error: function(xhr) {
                    // Re-enable button
                    button.prop('disabled', false);
                    button.html(originalHtml);
                    
                    // Show error message
                    let errorMsg = 'Failed to delete user. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    if (typeof window.showErrorMessage === 'function') {
                        window.showErrorMessage(errorMsg);
                    }
                }
            });
        }
    });
});
</script>
@endpush
