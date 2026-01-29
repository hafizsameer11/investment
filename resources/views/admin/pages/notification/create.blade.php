@extends('admin.layouts.main')
@section('content')
<div class="page-content-wrapper ">

    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="btn-group float-right">
                        <ol class="breadcrumb hide-phone p-0 m-0">
                            <li class="breadcrumb-item"><a href="#">Zoter</a></li>
                            <li class="breadcrumb-item"><a href="#">Notifications</a></li>
                            <li class="breadcrumb-item active">Send Notification</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Send Notification</h4>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <!-- end page title end breadcrumb -->

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Please fix the following errors:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

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

                        <h4 class="mt-0 header-title">Send Notification to Users</h4>
                        <p class="text-muted mb-4 font-14">Send a notification to all users or select specific users.</p>

                        <form action="{{ route('admin.notifications.store') }}" method="POST" id="notificationForm">
                            @csrf
                            
                            <div class="form-group mb-3">
                                <label class="mb-2 pb-1">Notification Type <span class="text-danger">*</span></label>
                                <div class="mt-2">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="all_users" name="notification_type" value="all_users" class="custom-control-input" {{ old('notification_type', 'all_users') === 'all_users' ? 'checked' : '' }} onchange="toggleUserSelection()">
                                        <label class="custom-control-label" for="all_users">All Users</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="specific_users" name="notification_type" value="specific_users" class="custom-control-input" {{ old('notification_type') === 'specific_users' ? 'checked' : '' }} onchange="toggleUserSelection()">
                                        <label class="custom-control-label" for="specific_users">Specific Users</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Choose whether to send to all users or select specific users.</small>
                            </div>

                            <div class="form-group mb-3" id="userSelectionGroup" style="display: {{ old('notification_type') === 'specific_users' ? 'block' : 'none' }};">
                                <label class="mb-2 pb-1">Select Users <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="user_ids[]" id="user_ids" multiple style="width: 100%;">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ in_array($user->id, old('user_ids', [])) ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Select one or more users to send the notification to.</small>
                            </div>

                            <div class="form-group mb-3">
                                <label class="mb-2 pb-1">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" value="{{ old('title') }}" required
                                    placeholder="Enter notification title" maxlength="255" />
                                <small class="form-text text-muted">The title of the notification (max 255 characters).</small>
                            </div>

                            <div class="form-group mb-3">
                                <label class="mb-2 pb-1">Message <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="message" rows="6" required
                                    placeholder="Enter notification message" maxlength="5000">{{ old('message') }}</textarea>
                                <small class="form-text text-muted">The message content of the notification (max 5000 characters).</small>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    <i class="mdi mdi-send"></i> Send Notification
                                </button>
                                <a href="{{ route('admin.index') }}" class="btn btn-secondary waves-effect waves-light ml-2">
                                    <i class="mdi mdi-close"></i> Cancel
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

    </div>
    <!-- end container-fluid -->

</div>
<!-- end page content-->

@push('scripts')
<script>
    // Initialize Select2 for user selection
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: 'Select users...',
            allowClear: true
        });
    });

    function toggleUserSelection() {
        const specificUsersRadio = document.getElementById('specific_users');
        const userSelectionGroup = document.getElementById('userSelectionGroup');
        const userIdsSelect = document.getElementById('user_ids');
        
        if (specificUsersRadio.checked) {
            userSelectionGroup.style.display = 'block';
            userIdsSelect.setAttribute('required', 'required');
        } else {
            userSelectionGroup.style.display = 'none';
            userIdsSelect.removeAttribute('required');
        }
    }

    // Form validation
    document.getElementById('notificationForm').addEventListener('submit', function(e) {
        const notificationType = document.querySelector('input[name="notification_type"]:checked').value;
        const userIds = document.getElementById('user_ids');
        
        if (notificationType === 'specific_users') {
            if (!userIds.value || userIds.value.length === 0) {
                e.preventDefault();
                if (typeof window.showErrorMessage === 'function') {
                    window.showErrorMessage('Please select at least one user.');
                }
                return false;
            }
        }
    });
</script>
@endpush
@endsection

