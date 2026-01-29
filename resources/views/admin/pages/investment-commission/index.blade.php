@extends('admin.layouts.main')

@push('styles')
<!-- Table css -->
<style>
    /* Hide all responsive table buttons */
    #investment-commission-table ~ .btn-toolbar,
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
                   
                    <h4 class="page-title">Investment Commission Structure</h4>
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
                            <h4 class="mt-0 header-title">Investment Commission Structure</h4>
                            <a href="{{ route('admin.investment-commission.create') }}" class="btn btn-primary waves-effect waves-light">
                                <i class="mdi mdi-plus"></i> Add New Commission
                            </a>
                        </div>

                        <div class="table-rep-plugin">
                            <div class="table-responsive b-0" data-pattern="priority-columns">
                                <table id="investment-commission-table" class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th data-priority="1">Level</th>
                                        <th data-priority="2">Level Name</th>
                                        <th data-priority="2">Plan</th>
                                        <th data-priority="1">Commission Rate (%)</th>
                                        <th data-priority="3">Status</th>
                                        <th data-priority="1">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($commissions as $commission)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $commission->level }}</td>
                                        <td>{{ $commission->level_name }}</td>
                                        <td>
                                            @if($commission->miningPlan)
                                                <span class="badge badge-info">{{ $commission->miningPlan->name }}</span>
                                            @else
                                                <span class="badge badge-secondary">Global</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($commission->commission_rate, 2) }}%</td>
                                        <td>
                                            @if($commission->is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.investment-commission.edit', $commission->id) }}" class="btn btn-sm btn-primary waves-effect waves-light" title="Edit">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.investment-commission.destroy', $commission->id) }}" method="POST" class="d-inline" data-confirm="Are you sure you want to delete this commission structure?">
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
                                        <td colspan="7" class="text-center">No commission structures found. <a href="{{ route('admin.investment-commission.create') }}">Create one now</a>.</td>
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
    $('#investment-commission-table').closest('.table-responsive').responsiveTable({
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

