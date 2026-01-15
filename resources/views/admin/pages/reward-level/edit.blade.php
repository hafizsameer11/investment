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
                            <li class="breadcrumb-item"><a href="#">Reward Levels</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.reward-level.index') }}">All Reward Levels</a></li>
                            <li class="breadcrumb-item active">Edit Reward Level</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Edit Reward Level</h4>
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

                        <h4 class="mt-0 header-title">Edit Reward Level</h4>
                        <p class="text-muted mb-4 font-14">Update the details of the reward level.</p>

                        <form action="{{ route('admin.reward-level.update', $rewardLevel->id) }}" method="POST" class="">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group mb-0">
                                <label class="mb-2 pb-1">Level <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="level" value="{{ old('level', $rewardLevel->level) }}" required
                                    placeholder="Enter level (e.g., 1, 2, 3...)" min="1" max="10" />
                                <small class="form-text text-muted">The reward level number (1-10).</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Level Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="level_name" value="{{ old('level_name', $rewardLevel->level_name) }}" required
                                    placeholder="Enter level name (e.g., Team Builder, Team Leader)" />
                                <small class="form-text text-muted">The display name for this reward level.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Icon Class</label>
                                <input type="text" class="form-control" name="icon_class" value="{{ old('icon_class', $rewardLevel->icon_class) }}"
                                    placeholder="Enter icon class (e.g., fas fa-user-tie, fas fa-medal)" />
                                <small class="form-text text-muted">Font Awesome icon class (e.g., fas fa-user-tie).</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Icon Color</label>
                                <select class="form-control" name="icon_color">
                                    <option value="">Select Color</option>
                                    <option value="gold" {{ old('icon_color', $rewardLevel->icon_color) == 'gold' ? 'selected' : '' }}>Gold</option>
                                    <option value="silver" {{ old('icon_color', $rewardLevel->icon_color) == 'silver' ? 'selected' : '' }}>Silver</option>
                                    <option value="purple" {{ old('icon_color', $rewardLevel->icon_color) == 'purple' ? 'selected' : '' }}>Purple</option>
                                    <option value="red" {{ old('icon_color', $rewardLevel->icon_color) == 'red' ? 'selected' : '' }}>Red</option>
                                    <option value="orange" {{ old('icon_color', $rewardLevel->icon_color) == 'orange' ? 'selected' : '' }}>Orange</option>
                                    <option value="blue" {{ old('icon_color', $rewardLevel->icon_color) == 'blue' ? 'selected' : '' }}>Blue</option>
                                </select>
                                <small class="form-text text-muted">Icon color theme for this level.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Investment Required ($) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" name="investment_required" value="{{ old('investment_required', $rewardLevel->investment_required) }}" required
                                    placeholder="Enter investment required (e.g., 10.00)" min="0" />
                                <small class="form-text text-muted">The total referral investment required to unlock this level.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Reward Amount ($) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" name="reward_amount" value="{{ old('reward_amount', $rewardLevel->reward_amount) }}" required
                                    placeholder="Enter reward amount (e.g., 2.00)" min="0" />
                                <small class="form-text text-muted">The reward amount for achieving this level.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Sort Order</label>
                                <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', $rewardLevel->sort_order) }}"
                                    placeholder="Enter sort order (e.g., 0, 1, 2...)" min="0" />
                                <small class="form-text text-muted">Display order (lower numbers appear first).</small>
                            </div>

                            <div class="form-group mb-0">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="is_premium" name="is_premium" value="1" {{ old('is_premium', $rewardLevel->is_premium) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_premium">Premium Level</label>
                                </div>
                                <small class="form-text text-muted">Check to mark this as a premium reward level.</small>
                            </div>

                            <div class="form-group mb-0">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $rewardLevel->is_active) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Active</label>
                                </div>
                                <small class="form-text text-muted">Check to make this reward level active.</small>
                            </div>

                            <div class="form-group mb-0">
                                <div>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                                        Update
                                    </button>
                                    <a href="{{ route('admin.reward-level.index') }}" class="btn btn-secondary waves-effect m-l-5">
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

