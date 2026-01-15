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
                            <li class="breadcrumb-item"><a href="#">Currency Conversion</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.currency-conversion.index') }}">USD to PKR</a></li>
                            <li class="breadcrumb-item active">Edit Conversion Rate</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Edit USD to PKR Conversion Rate</h4>
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

                        <h4 class="mt-0 header-title">Edit USD to PKR Conversion Rate</h4>
                        <p class="text-muted mb-4 font-14">Update the custom price of 1 USD in Pakistani Rupees (PKR).</p>

                        <form action="{{ route('admin.currency-conversion.update', $currencyConversion->id) }}" method="POST" class="">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Conversion Rate (1 USD = X PKR) <span class="text-danger">*</span></label>
                                <input type="number" step="0.0001" class="form-control" name="rate" value="{{ old('rate', $currencyConversion->rate) }}" required
                                    placeholder="Enter rate (e.g., 278.5000)" min="0" />
                                <small class="form-text text-muted">Enter the amount of Pakistani Rupees (PKR) that equals 1 USD. For example, if 1 USD = 278.50 PKR, enter 278.50.</small>
                            </div>

                            <div class="form-group mb-0">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $currencyConversion->is_active) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Active</label>
                                </div>
                                <small class="form-text text-muted">Check to make this conversion rate active.</small>
                            </div>

                            <div class="form-group mb-0">
                                <div>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                                        Update
                                    </button>
                                    <a href="{{ route('admin.currency-conversion.index') }}" class="btn btn-secondary waves-effect m-l-5">
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
