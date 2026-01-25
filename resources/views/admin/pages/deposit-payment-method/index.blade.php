@extends('admin.layouts.main')

@push('styles')
<!-- Table css -->
<style>
    /* Hide all responsive table buttons */
    #deposit-payment-method-table ~ .btn-toolbar,
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
                   
                    <h4 class="page-title">Deposit Payment Methods</h4>
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

        @if(session('danger'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('danger') }}
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
                            <h4 class="mt-0 header-title">Deposit Payment Methods</h4>
                            <a href="{{ route('admin.deposit-payment-method.create') }}" class="btn btn-primary waves-effect waves-light">
                                <i class="mdi mdi-plus"></i> Add New Payment Method
                            </a>
                        </div>

                        <div class="table-rep-plugin">
                            <div class="table-responsive b-0" data-pattern="priority-columns">
                                <table id="deposit-payment-method-table" class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th data-priority="1">Image</th>
                                        <th data-priority="2">Type</th>
                                        <th data-priority="2">Service/Bank</th>
                                        <th data-priority="2">Account Holder</th>
                                        <th data-priority="3">Number</th>
                                        <th data-priority="4">Min Deposit</th>
                                        <th data-priority="4">Max Deposit</th>
                                        <th data-priority="3">Status</th>
                                        <th data-priority="2">Usage</th>
                                        <th data-priority="1">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($paymentMethods as $paymentMethod)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>
                                            @if($paymentMethod->image)
                                                <img src="{{ asset($paymentMethod->image) }}" alt="Payment Method" style="max-width: 80px; max-height: 80px; object-fit: cover;">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td><span class="badge badge-soft-primary">{{ ucfirst($paymentMethod->type) }}</span></td>
                                        <td>
                                            @if($paymentMethod->type == 'rast')
                                                {{ $paymentMethod->account_type }}
                                            @elseif($paymentMethod->type == 'bank')
                                                {{ $paymentMethod->bank_name }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $paymentMethod->account_name }}</td>
                                        <td>{{ $paymentMethod->account_number }}</td>
                                        <td>
                                            @if($paymentMethod->minimum_deposit)
                                                ${{ number_format($paymentMethod->minimum_deposit, 2) }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($paymentMethod->maximum_deposit)
                                                ${{ number_format($paymentMethod->maximum_deposit, 2) }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($paymentMethod->is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($paymentMethod->allowed_for_deposit ?? true)
                                                <span class="badge badge-info">Deposit</span>
                                            @endif
                                            @if($paymentMethod->allowed_for_withdrawal ?? false)
                                                <span class="badge badge-warning">Withdrawal</span>
                                            @endif
                                            @if(!($paymentMethod->allowed_for_deposit ?? true) && !($paymentMethod->allowed_for_withdrawal ?? false))
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.deposit-payment-method.edit', $paymentMethod->id) }}" class="btn btn-sm btn-primary waves-effect waves-light" title="Edit">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.deposit-payment-method.destroy', $paymentMethod->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this payment method?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger waves-effect waves-light" title="Delete">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No payment methods found. <a href="{{ route('admin.deposit-payment-method.create') }}">Create one now</a>.</td>
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
    $('#deposit-payment-method-table').closest('.table-responsive').responsiveTable({
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

