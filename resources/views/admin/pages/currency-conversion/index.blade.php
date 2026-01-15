@extends('admin.layouts.main')

@section('content')
<div class="page-content-wrapper ">

    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                   
                    <h4 class="page-title">USD to PKR Conversion</h4>
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
                            <h4 class="mt-0 header-title">USD to PKR Conversion Rate</h4>
                            @if($currencyConversion)
                                <a href="{{ route('admin.currency-conversion.edit', $currencyConversion->id) }}" class="btn btn-primary waves-effect waves-light">
                                    <i class="mdi mdi-pencil"></i> Edit Rate
                                </a>
                            @else
                                <a href="{{ route('admin.currency-conversion.create') }}" class="btn btn-primary waves-effect waves-light">
                                    <i class="mdi mdi-plus"></i> Set Conversion Rate
                                </a>
                            @endif
                        </div>

                        @if($currencyConversion)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-body">
                                        <h5 class="card-title">Current Conversion Rate</h5>
                                        <div class="mb-3">
                                            <h2 class="text-primary mb-0">1 USD = {{ number_format($currencyConversion->rate, 4) }} PKR</h2>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Status:</strong> 
                                            @if($currencyConversion->is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </div>
                                        <div class="mb-2">
                                            <strong>Last Updated:</strong> {{ $currencyConversion->updated_at->format('M d, Y h:i A') }}
                                        </div>
                                        <div class="mt-3">
                                            <a href="{{ route('admin.currency-conversion.edit', $currencyConversion->id) }}" class="btn btn-primary waves-effect waves-light mr-2">
                                                <i class="mdi mdi-pencil"></i> Edit Rate
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-info">
                            <h5>No Conversion Rate Set</h5>
                            <p>Please set the USD to PKR conversion rate to continue.</p>
                            <a href="{{ route('admin.currency-conversion.create') }}" class="btn btn-primary waves-effect waves-light">
                                <i class="mdi mdi-plus"></i> Set Conversion Rate
                            </a>
                        </div>
                        @endif

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div><!-- container -->

</div> <!-- Page content Wrapper -->
@endsection
