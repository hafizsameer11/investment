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
                            <li class="breadcrumb-item active">Edit User</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Edit User</h4>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <!-- end page title end breadcrumb -->

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="mt-0 header-title">Edit User Information</h4>
                        <p class="text-muted mb-4 font-14">Update the user details below. Leave password blank to keep the current password.</p>

                        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group mb-0">
                                <label class="mb-2 pb-1">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required
                                    placeholder="Enter full name" />
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">The user's full name.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username', $user->username) }}" required
                                    placeholder="Enter username" />
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">A unique username for the user.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required
                                    placeholder="Enter email address" />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">The user's email address.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Phone</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $user->phone) }}"
                                    placeholder="Enter phone number" />
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">The user's phone number (optional).</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                                    placeholder="Enter new password (leave blank to keep current)" minlength="8" />
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Leave blank to keep the current password. Minimum 8 characters if changing.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Role <span class="text-danger">*</span></label>
                                <select class="form-control @error('role') is-invalid @enderror" name="role" required>
                                    <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Select the user's role.</small>
                            </div>

                            <div class="form-group mb-0">
                                <div class="alert alert-info">
                                    <strong>Referral Code:</strong> <code>{{ $user->refer_code ?? 'N/A' }}</code><br>
                                    <small>The referral code cannot be changed. It was auto-generated when the user was created.</small>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <div>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                                        Update User
                                    </button>
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info waves-effect m-l-5">
                                        <i class="mdi mdi-eye"></i> View Details
                                    </a>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary waves-effect m-l-5">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div><!-- container -->

</div> <!-- Page content Wrapper -->
@endsection

