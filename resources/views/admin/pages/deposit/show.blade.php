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
                            <li class="breadcrumb-item"><a href="#">Deposits</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.deposits.index') }}">All Deposits</a></li>
                            <li class="breadcrumb-item active">Deposit Details</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Deposit Details</h4>
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
            <!-- Deposit Information Card -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Deposit Information</h5>
                            <div>
                                @if($deposit->status === 'pending')
                                    <span class="badge badge-warning badge-lg">Pending</span>
                                @elseif($deposit->status === 'approved')
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
                                            <th width="40%">Deposit ID:</th>
                                            <td><strong>#{{ $deposit->id }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>User:</th>
                                            <td>
                                                <strong>{{ $deposit->user->name ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">{{ $deposit->user->email ?? 'N/A' }}</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Payment Method:</th>
                                            <td>{{ $deposit->paymentMethod->account_type ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Amount (USD):</th>
                                            <td><strong class="text-primary">${{ number_format($deposit->amount, 2) }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Amount (PKR):</th>
                                            <td><strong class="text-primary">Rs. {{ number_format($deposit->pkr_amount, 2) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <th width="40%">Transaction ID:</th>
                                            <td><code>{{ $deposit->transaction_id }}</code></td>
                                        </tr>
                                        <tr>
                                            <th>Phone:</th>
                                            <td>{{ $deposit->phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Submitted:</th>
                                            <td>
                                                {{ $deposit->created_at->format('M d, Y') }}<br>
                                                <small class="text-muted">{{ $deposit->created_at->format('h:i A') }}</small>
                                            </td>
                                        </tr>
                                        @if($deposit->approved_at)
                                        <tr>
                                            <th>Processed:</th>
                                            <td>
                                                {{ $deposit->approved_at->format('M d, Y') }}<br>
                                                <small class="text-muted">{{ $deposit->approved_at->format('h:i A') }}</small>
                                            </td>
                                        </tr>
                                        @if($deposit->approver)
                                        <tr>
                                            <th>Processed By:</th>
                                            <td>{{ $deposit->approver->name ?? 'N/A' }}</td>
                                        </tr>
                                        @endif
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if($deposit->admin_notes)
                        <div class="mt-3">
                            <h6>Admin Notes:</h6>
                            <div class="alert alert-info">
                                {{ $deposit->admin_notes }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Proof Card -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Payment Proof</h5>
                        @if($deposit->payment_proof)
                            <div class="text-center">
                                <img src="{{ asset($deposit->payment_proof) }}" alt="Payment Proof" class="img-fluid" style="max-height: 500px; border: 1px solid #ddd; border-radius: 8px;">
                                <div class="mt-3">
                                    <a href="{{ asset($deposit->payment_proof) }}" target="_blank" class="btn btn-sm btn-primary waves-effect waves-light">
                                        <i class="mdi mdi-download"></i> View Full Size
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                No payment proof available.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Card -->
            <div class="col-lg-4">
                @if($deposit->status === 'pending')
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Actions</h5>

                        <!-- Approve Form -->
                        <form action="{{ route('admin.deposits.approve', $deposit->id) }}" method="POST" class="mb-3" onsubmit="return confirm('Are you sure you want to approve this deposit? This will add ${{ number_format($deposit->amount, 2) }} to the user\'s Fund Wallet.');">
                            @csrf
                            <div class="form-group">
                                <label for="approve_notes">Admin Notes (Optional):</label>
                                <textarea name="admin_notes" id="approve_notes" class="form-control" rows="3" placeholder="Add any notes about this approval..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-block waves-effect waves-light">
                                <i class="mdi mdi-check"></i> Approve Deposit
                            </button>
                        </form>

                        <!-- Reject Form -->
                        <form action="{{ route('admin.deposits.reject', $deposit->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this deposit?');">
                            @csrf
                            <div class="form-group">
                                <label for="reject_notes">Rejection Reason <span class="text-danger">*</span>:</label>
                                <textarea name="admin_notes" id="reject_notes" class="form-control" rows="3" placeholder="Please provide a reason for rejection..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger btn-block waves-effect waves-light">
                                <i class="mdi mdi-close"></i> Reject Deposit
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Status</h5>
                        <div class="alert alert-{{ $deposit->status === 'approved' ? 'success' : 'danger' }}">
                            <strong>This deposit has been {{ $deposit->status }}.</strong>
                            @if($deposit->approved_at)
                                <br><small>Processed on {{ $deposit->approved_at->format('M d, Y h:i A') }}</small>
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
                                    <td>{{ $deposit->user->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $deposit->user->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Username:</th>
                                    <td>{{ $deposit->user->username ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $deposit->user->phone ?? 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-3">
                            <a href="{{ route('admin.users.show', $deposit->user_id) }}" class="btn btn-sm btn-info btn-block waves-effect waves-light">
                                <i class="mdi mdi-account"></i> View User Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Back Button -->
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.deposits.index') }}" class="btn btn-secondary btn-block waves-effect waves-light">
                            <i class="mdi mdi-arrow-left"></i> Back to Deposits
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

