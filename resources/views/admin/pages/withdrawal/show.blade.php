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
                            <li class="breadcrumb-item"><a href="#">Withdrawals</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.withdrawals.index') }}">All Withdrawals</a></li>
                            <li class="breadcrumb-item active">Withdrawal Details</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Withdrawal Details</h4>
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
            <!-- Withdrawal Information Card -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Withdrawal Information</h5>
                            <div>
                                @if($withdrawal->status === 'pending')
                                    <span class="badge badge-warning badge-lg">Pending</span>
                                @elseif($withdrawal->status === 'approved')
                                    <span class="badge badge-success badge-lg">Approved</span>
                                @else
                                    <span class="badge badge-danger badge-lg">Rejected</span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <th width="40%">Withdrawal ID:</th>
                                            <td><strong>#{{ $withdrawal->id }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>User:</th>
                                            <td>
                                                <strong>{{ $withdrawal->user->name ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">{{ $withdrawal->user->email ?? 'N/A' }}</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Payment Method:</th>
                                            <td>{{ $withdrawal->paymentMethod->account_type ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Amount (USD):</th>
                                            <td><strong class="text-primary">${{ number_format($withdrawal->amount, 2) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <th width="40%">Account Holder:</th>
                                            <td><strong>{{ $withdrawal->account_holder_name }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Account Number:</th>
                                            <td><code>{{ $withdrawal->account_number }}</code></td>
                                        </tr>
                                        <tr>
                                            <th>Submitted:</th>
                                            <td>
                                                {{ $withdrawal->created_at->format('M d, Y') }}<br>
                                                <small class="text-muted">{{ $withdrawal->created_at->format('h:i A') }}</small>
                                            </td>
                                        </tr>
                                        @if($withdrawal->approved_at)
                                        <tr>
                                            <th>Processed:</th>
                                            <td>
                                                {{ $withdrawal->approved_at->format('M d, Y') }}<br>
                                                <small class="text-muted">{{ $withdrawal->approved_at->format('h:i A') }}</small>
                                            </td>
                                        </tr>
                                        @if($withdrawal->approver)
                                        <tr>
                                            <th>Processed By:</th>
                                            <td>{{ $withdrawal->approver->name ?? 'N/A' }}</td>
                                        </tr>
                                        @endif
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if($withdrawal->admin_notes)
                        <div class="mt-3">
                            <h6>Admin Notes:</h6>
                            <div class="alert alert-info">
                                {{ $withdrawal->admin_notes }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Admin Proof Image Card -->
                @if($withdrawal->admin_proof_image)
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Admin Proof Image</h5>
                        <div class="text-center">
                            <img src="{{ asset($withdrawal->admin_proof_image) }}" alt="Admin Proof" class="img-fluid" style="max-height: 500px; border: 1px solid #ddd; border-radius: 8px;">
                            <div class="mt-3">
                                <a href="{{ asset($withdrawal->admin_proof_image) }}" target="_blank" class="btn btn-sm btn-primary waves-effect waves-light">
                                    <i class="mdi mdi-download"></i> View Full Size
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Action Card -->
            <div class="col-lg-4">
                @if($withdrawal->status === 'pending')
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Actions</h5>

                        <!-- Approve Form -->
                        <form action="{{ route('admin.withdrawals.approve', $withdrawal->id) }}" method="POST" enctype="multipart/form-data" class="mb-3" onsubmit="return confirm('Are you sure you want to approve this withdrawal? You must upload a proof image.');">
                            @csrf
                            <div class="form-group">
                                <label for="approve_proof">Proof Image <span class="text-danger">*</span>:</label>
                                <input type="file" name="admin_proof_image" id="approve_proof" class="form-control" accept="image/*" required>
                                <small class="form-text text-muted">Upload proof of transfer (Max: 5MB)</small>
                            </div>
                            <div class="form-group">
                                <label for="approve_notes">Admin Notes (Optional):</label>
                                <textarea name="admin_notes" id="approve_notes" class="form-control" rows="3" placeholder="Add any notes about this approval..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-block waves-effect waves-light">
                                <i class="mdi mdi-check"></i> Approve Withdrawal
                            </button>
                        </form>

                        <!-- Reject Form -->
                        <form action="{{ route('admin.withdrawals.reject', $withdrawal->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this withdrawal? The amount will be refunded to the user.');">
                            @csrf
                            <div class="form-group">
                                <label for="reject_notes">Rejection Reason <span class="text-danger">*</span>:</label>
                                <textarea name="admin_notes" id="reject_notes" class="form-control" rows="3" placeholder="Please provide a reason for rejection..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger btn-block waves-effect waves-light">
                                <i class="mdi mdi-close"></i> Reject Withdrawal
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Status</h5>
                        <div class="alert alert-{{ $withdrawal->status === 'approved' ? 'success' : 'danger' }}">
                            <strong>This withdrawal has been {{ $withdrawal->status }}.</strong>
                            @if($withdrawal->approved_at)
                                <br><small>Processed on {{ $withdrawal->approved_at->format('M d, Y h:i A') }}</small>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- User Information Card -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">User Information</h5>
                        <table class="table table-sm table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th width="40%">Name:</th>
                                    <td>{{ $withdrawal->user->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $withdrawal->user->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Username:</th>
                                    <td>{{ $withdrawal->user->username ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $withdrawal->user->phone ?? 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-3">
                            <a href="{{ route('admin.users.show', $withdrawal->user_id) }}" class="btn btn-sm btn-info btn-block waves-effect waves-light">
                                <i class="mdi mdi-account"></i> View User Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Back Button -->
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.withdrawals.index') }}" class="btn btn-secondary btn-block waves-effect waves-light">
                            <i class="mdi mdi-arrow-left"></i> Back to Withdrawals
                        </a>
                    </div>
                </div>
            </div>
        </div> <!-- end row -->

    </div><!-- container -->

</div> <!-- Page content Wrapper -->
@endsection

@push('styles')
<style>
    .badge-lg {
        font-size: 1rem;
        padding: 0.5rem 1rem;
    }
    .card {
        margin-bottom: 20px;
    }
</style>
@endpush

