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
                            <li class="breadcrumb-item"><a href="#">Investment Commission</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.investment-commission.index') }}">All Commission</a></li>
                            <li class="breadcrumb-item active">Add Commission</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Add Investment Commission Structure</h4>
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

                        <h4 class="mt-0 header-title">Add New Commission Structure</h4>
                        <p class="text-muted mb-4 font-14">Fill in the details to create a new investment commission structure.</p>

                        <form action="{{ route('admin.investment-commission.store') }}" method="POST" class="">
                            @csrf
                            
                            <div class="form-group mb-0">
                                <label class="mb-2 pb-1">Level <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="level" value="{{ old('level') }}" required
                                    placeholder="Enter level (e.g., 1, 2, 3...)" min="1" max="10" />
                                <small class="form-text text-muted">The commission level number (1-10).</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Level Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="level_name" value="{{ old('level_name') }}" required
                                    placeholder="Enter level name (e.g., Direct Referral, Second Level)" />
                                <small class="form-text text-muted">The display name for this commission level.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Mining Plan <span class="text-muted">(Optional)</span></label>
                                <select class="form-control" name="mining_plan_id">
                                    <option value="">Global (Applies to all plans)</option>
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}" {{ old('mining_plan_id') == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Select a specific plan or leave blank for global commission rate (applies to all plans).</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Commission Rate (%) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" name="commission_rate" value="{{ old('commission_rate') }}" required
                                    placeholder="Enter commission rate (e.g., 6.00)" min="0" max="100" />
                                <small class="form-text text-muted">The commission percentage rate (0-100).</small>
                            </div>

                            <div class="form-group mb-0">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : 'checked' }}>
                                    <label class="custom-control-label" for="is_active">Active</label>
                                </div>
                                <small class="form-text text-muted">Check to make this commission structure active.</small>
                            </div>

                            <div class="form-group mb-0">
                                <div>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                                        Submit
                                    </button>
                                    <a href="{{ route('admin.investment-commission.index') }}" class="btn btn-secondary waves-effect m-l-5">
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

