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
                            <li class="breadcrumb-item"><a href="#">Users</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">All Users</a></li>
                            <li class="breadcrumb-item active">User Details</li>
                        </ol>
                    </div>
                    <h4 class="page-title">User Details</h4>
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

        <div class="row">
            <!-- User Information Card -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <div class="avatar-lg mx-auto mb-4">
                                <div class="avatar-title rounded-circle bg-soft-primary text-primary font-24" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                    <i class="mdi mdi-account"></i>
                                </div>
                            </div>
                            <h4 class="mb-1">{{ $user->name ?? 'N/A' }}</h4>
                            <p class="text-muted mb-0">
                                @if($user->role === 'admin')
                                    <span class="badge badge-danger">Admin</span>
                                @else
                                    <span class="badge badge-primary">User</span>
                                @endif
                            </p>
                        </div>

                        <div class="text-left mt-4">
                            <div class="table-responsive">
                                <table class="table table-borderless table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="pl-0" width="40%">ID:</th>
                                            <td class="text-right">#{{ $user->id }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">Username:</th>
                                            <td class="text-right">{{ $user->username ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">Email:</th>
                                            <td class="text-right">{{ $user->email ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">Phone:</th>
                                            <td class="text-right">{{ $user->phone ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">Referral Code:</th>
                                            <td class="text-right"><code>{{ $user->refer_code ?? 'N/A' }}</code></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary waves-effect waves-light">
                                <i class="mdi mdi-pencil"></i> Edit User
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary waves-effect waves-light">
                                <i class="mdi mdi-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Account Status Card -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Account Status</h5>
                        <div class="mb-3">
                            <p class="mb-1"><strong>Email Verified:</strong></p>
                            @if($user->email_verified_at)
                                <span class="badge badge-success">Verified</span>
                                <br><small class="text-muted">{{ $user->email_verified_at->format('Y-m-d H:i:s') }}</small>
                            @else
                                <span class="badge badge-warning">Not Verified</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <p class="mb-1"><strong>Member Since:</strong></p>
                            <p class="text-muted mb-0">{{ $user->created_at ? $user->created_at->format('F d, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="mb-1"><strong>Last Updated:</strong></p>
                            <p class="text-muted mb-0">{{ $user->updated_at ? $user->updated_at->format('F d, Y H:i') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Referral Information -->
            <div class="col-lg-8">
                <!-- Referrer Information (Who referred this user) -->
                @if($referrer)
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">
                                <i class="mdi mdi-account-arrow-left text-primary"></i> Referred By
                            </h5>
                            <a href="{{ route('admin.users.show', $referrer->id) }}" class="btn btn-sm btn-info waves-effect waves-light">
                                <i class="mdi mdi-eye"></i> View Referrer
                            </a>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <th width="40%">Name:</th>
                                            <td><strong>{{ $referrer->name ?? 'N/A' }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Username:</th>
                                            <td>{{ $referrer->username ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ $referrer->email ?? 'N/A' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <th width="40%">Phone:</th>
                                            <td>{{ $referrer->phone ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Role:</th>
                                            <td>
                                                @if($referrer->role === 'admin')
                                                    <span class="badge badge-danger">Admin</span>
                                                @else
                                                    <span class="badge badge-primary">User</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Referral Code:</th>
                                            <td><code>{{ $referrer->refer_code ?? 'N/A' }}</code></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="card">
                    <div class="card-body text-center">
                        <i class="mdi mdi-information-outline text-muted" style="font-size: 48px;"></i>
                        <h5 class="mt-3">No Referrer</h5>
                        <p class="text-muted">This user was not referred by anyone.</p>
                    </div>
                </div>
                @endif

                <!-- Users Referred by This User -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">
                                <i class="mdi mdi-account-multiple text-success"></i> Referred Users
                                <span class="badge badge-primary ml-2">{{ $referrals->count() }}</span>
                            </h5>
                        </div>

                        @if($referrals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Joined</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($referrals as $referral)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $referral->name ?? 'N/A' }}</td>
                                        <td>{{ $referral->username ?? 'N/A' }}</td>
                                        <td>{{ $referral->email ?? 'N/A' }}</td>
                                        <td>
                                            @if($referral->role === 'admin')
                                                <span class="badge badge-danger">Admin</span>
                                            @else
                                                <span class="badge badge-primary">User</span>
                                            @endif
                                        </td>
                                        <td>{{ $referral->created_at ? $referral->created_at->format('Y-m-d') : 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $referral->id) }}" class="btn btn-sm btn-info waves-effect waves-light" title="View">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $referral->id) }}" class="btn btn-sm btn-primary waves-effect waves-light" title="Edit">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="mdi mdi-account-off text-muted" style="font-size: 48px;"></i>
                            <p class="text-muted mt-3 mb-0">This user has not referred anyone yet.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div> <!-- end row -->

    </div><!-- container -->

</div> <!-- Page content Wrapper -->
@endsection

@push('styles')
<style>
    .avatar-lg {
        width: 80px;
        height: 80px;
    }
    .avatar-title {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
    }
    .card {
        margin-bottom: 20px;
    }
</style>
@endpush
