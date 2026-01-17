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
                            <li class="breadcrumb-item"><a href="#">Mining Plan</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.mining-plan.index') }}">All Plans</a></li>
                            <li class="breadcrumb-item active">Add Plan</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Add Mining Plan</h4>
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

                        <h4 class="mt-0 header-title">Add New Mining Plan</h4>
                        <p class="text-muted mb-4 font-14">Fill in the details to create a new mining plan.</p>

                        <form action="{{ route('admin.mining-plan.store') }}" method="POST" class="">
                            @csrf
                            
                            <div class="form-group mb-0">
                                <label class="mb-2 pb-1">Plan Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required
                                    placeholder="Enter plan name (e.g., Lithium)" />
                                <small class="form-text text-muted">The name of the mining plan.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Tagline <span class="text-muted">(Optional)</span></label>
                                <input type="text" class="form-control" name="tagline" value="{{ old('tagline') }}"
                                    placeholder="Enter tagline (e.g., Advanced Mining Plan for Maximum Returns)" />
                                <small class="form-text text-muted">A short tagline describing the plan.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Subtitle <span class="text-muted">(Optional)</span></label>
                                <input type="text" class="form-control" name="subtitle" value="{{ old('subtitle') }}"
                                    placeholder="Enter subtitle (e.g., Earn through lithium mining)" />
                                <small class="form-text text-muted">A subtitle for mobile view.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Icon Class <span class="text-muted">(Optional)</span></label>
                                <input type="text" class="form-control" name="icon_class" value="{{ old('icon_class') }}"
                                    placeholder="Enter icon class (e.g., fas fa-gem, fas fa-star)" />
                                <small class="form-text text-muted">FontAwesome icon class (e.g., fas fa-gem). <a href="https://fontawesome.com/icons" target="_blank">Browse icons</a></small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label class="my-2 py-1">Minimum Investment ($) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" class="form-control" name="min_investment" value="{{ old('min_investment') }}" required
                                            placeholder="Enter minimum investment" min="0" />
                                        <small class="form-text text-muted">Minimum investment amount.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label class="my-2 py-1">Maximum Investment ($) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" class="form-control" name="max_investment" value="{{ old('max_investment') }}" required
                                            placeholder="Enter maximum investment" min="0" />
                                        <small class="form-text text-muted">Maximum investment amount (must be >= minimum).</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label class="my-2 py-1">Daily ROI Min (%) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" class="form-control" name="daily_roi_min" value="{{ old('daily_roi_min') }}" required
                                            placeholder="Enter minimum daily ROI" min="0" max="100" />
                                        <small class="form-text text-muted">Minimum daily return on investment percentage.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label class="my-2 py-1">Daily ROI Max (%) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" class="form-control" name="daily_roi_max" value="{{ old('daily_roi_max') }}" required
                                            placeholder="Enter maximum daily ROI" min="0" max="100" />
                                        <small class="form-text text-muted">Maximum daily return on investment percentage (must be >= min).</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Hourly Rate (%) <span class="text-muted">(Optional)</span></label>
                                <input type="number" step="0.01" class="form-control" name="hourly_rate" value="{{ old('hourly_rate', 0) }}"
                                    placeholder="Enter hourly rate" min="0" max="100" />
                                <small class="form-text text-muted">Hourly return rate percentage (default: 0).</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Sort Order <span class="text-muted">(Optional)</span></label>
                                <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', 0) }}"
                                    placeholder="Enter sort order" />
                                <small class="form-text text-muted">Lower numbers appear first (default: 0).</small>
                            </div>

                            <div class="form-group mb-0">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : 'checked' }}>
                                    <label class="custom-control-label" for="is_active">Active</label>
                                </div>
                                <small class="form-text text-muted">Check to make this plan active and visible.</small>
                            </div>

                            <div class="form-group mb-0">
                                <div>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                                        Submit
                                    </button>
                                    <a href="{{ route('admin.mining-plan.index') }}" class="btn btn-secondary waves-effect m-l-5">
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










