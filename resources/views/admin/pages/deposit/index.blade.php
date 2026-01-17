@extends('admin.layouts.main')

@push('styles')
<!-- Table css -->
<style>
    /* Hide all responsive table buttons */
    #deposits-table ~ .btn-toolbar,
    .table-responsive[data-pattern="priority-columns"] ~ .btn-toolbar,
    .table-wrapper .btn-toolbar {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="page-content-wrapper ">

    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">Deposits Management</h4>
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

        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
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
                            <h4 class="mt-0 header-title">All Deposits</h4>
                            <div>
                                <a href="{{ route('admin.deposits.index', ['status' => 'pending']) }}" class="btn btn-warning waves-effect waves-light">
                                    <i class="mdi mdi-clock"></i> Pending
                                </a>
                                <a href="{{ route('admin.deposits.index', ['status' => 'approved']) }}" class="btn btn-success waves-effect waves-light">
                                    <i class="mdi mdi-check"></i> Approved
                                </a>
                                <a href="{{ route('admin.deposits.index', ['status' => 'rejected']) }}" class="btn btn-danger waves-effect waves-light">
                                    <i class="mdi mdi-close"></i> Rejected
                                </a>
                                <a href="{{ route('admin.deposits.index') }}" class="btn btn-primary waves-effect waves-light">
                                    <i class="mdi mdi-view-list"></i> All
                                </a>
                            </div>
                        </div>

                        <div class="table-rep-plugin">
                            <div class="table-responsive b-0" data-pattern="priority-columns">
                                <table id="deposits-table" class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th data-priority="1">User</th>
                                        <th data-priority="2">Payment Method</th>
                                        <th data-priority="3">Amount (USD)</th>
                                        <th data-priority="3">Amount (PKR)</th>
                                        <th data-priority="4">Transaction ID</th>
                                        <th data-priority="4">Phone</th>
                                        <th data-priority="2">Status</th>
                                        <th data-priority="1">Date</th>
                                        <th data-priority="1">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($deposits as $deposit)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>
                                            <strong>{{ $deposit->user->name ?? 'N/A' }}</strong><br>
                                            <small class="text-muted">{{ $deposit->user->email ?? 'N/A' }}</small>
                                        </td>
                                        <td>{{ $deposit->paymentMethod->account_type ?? 'N/A' }}</td>
                                        <td>${{ number_format($deposit->amount, 2) }}</td>
                                        <td>Rs. {{ number_format($deposit->pkr_amount, 2) }}</td>
                                        <td><code>{{ $deposit->transaction_id }}</code></td>
                                        <td>{{ $deposit->account_number }}</td>
                                        <td>
                                            @if($deposit->status === 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($deposit->status === 'approved')
                                                <span class="badge badge-success">Approved</span>
                                            @else
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $deposit->created_at->format('M d, Y') }}<br>
                                            <small class="text-muted">{{ $deposit->created_at->format('h:i A') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.deposits.show', $deposit->id) }}" class="btn btn-sm btn-info waves-effect waves-light" title="View Details">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No deposits found.</td>
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
    $('#deposits-table').closest('.table-responsive').responsiveTable({
        addDisplayAllBtn: false,
        addFocusBtn: false
    });
    
    // Hide the dropdown button toolbar after initialization
    setTimeout(function() {
        $('.table-wrapper .btn-toolbar').hide();
    }, 100);
});
</script>
@endpush

