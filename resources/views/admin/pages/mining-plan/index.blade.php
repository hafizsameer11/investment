@extends('admin.layouts.main')

@push('styles')
<!-- Table css -->
<style>
    /* Hide all responsive table buttons */
    #mining-plan-table ~ .btn-toolbar,
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
                   
                    <h4 class="page-title">Mining Plans</h4>
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
                            <h4 class="mt-0 header-title">Mining Plans</h4>
                            <a href="{{ route('admin.mining-plan.create') }}" class="btn btn-primary waves-effect waves-light">
                                <i class="mdi mdi-plus"></i> Add New Plan
                            </a>
                        </div>

                        <div class="table-rep-plugin">
                            <div class="table-responsive b-0" data-pattern="priority-columns">
                                <table id="mining-plan-table" class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th data-priority="1">Name</th>
                                        <th data-priority="2">Icon</th>
                                        <th data-priority="3">Tagline</th>
                                        <th data-priority="4">Investment Range</th>
                                        <th data-priority="5">Daily ROI</th>
                                        <th data-priority="6">Hourly Rate</th>
                                        <th data-priority="7">Sort Order</th>
                                        <th data-priority="8">Status</th>
                                        <th data-priority="1">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($plans as $plan)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>
                                            <strong>{{ $plan->name }}</strong>
                                            @if($plan->subtitle)
                                                <br><small class="text-muted">{{ $plan->subtitle }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($plan->icon_class)
                                                <i class="{{ $plan->icon_class }}"></i>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $plan->tagline ?? '-' }}</td>
                                        <td>
                                            ${{ number_format($plan->min_investment, 2) }} - 
                                            ${{ number_format($plan->max_investment, 2) }}
                                        </td>
                                        <td>
                                            {{ number_format($plan->daily_roi_min, 2) }}% - 
                                            {{ number_format($plan->daily_roi_max, 2) }}%
                                        </td>
                                        <td>{{ number_format($plan->hourly_rate, 2) }}%</td>
                                        <td>{{ $plan->sort_order }}</td>
                                        <td>
                                            @if($plan->is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.mining-plan.edit', $plan->id) }}" class="btn btn-sm btn-primary waves-effect waves-light" title="Edit">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.mining-plan.destroy', $plan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this mining plan?');">
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
                                        <td colspan="10" class="text-center">No mining plans found. <a href="{{ route('admin.mining-plan.create') }}">Create one now</a>.</td>
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
    $('#mining-plan-table').closest('.table-responsive').responsiveTable({
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





