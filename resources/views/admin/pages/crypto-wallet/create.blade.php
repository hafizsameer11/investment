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
                            <li class="breadcrumb-item"><a href="#">Crypto Wallets</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.crypto-wallet.index') }}">All Crypto Wallets</a></li>
                            <li class="breadcrumb-item active">Add Crypto Wallet</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Add Crypto Wallet</h4>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Please fix the following errors:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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
                        <h4 class="mt-0 header-title">Add New Crypto Wallet</h4>
                        <p class="text-muted mb-4 font-14">Fill in the details to create a new crypto wallet.</p>

                        <form action="{{ route('admin.crypto-wallet.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="form-group mb-0">
                                <label class="mb-2 pb-1">Network <span class="text-danger">*</span></label>
                                <select class="form-control" name="network" required>
                                    <option value="">Select Network</option>
                                    <option value="bnb_smart_chain" {{ old('network') == 'bnb_smart_chain' ? 'selected' : '' }}>BNB Smart Chain (BEP20)</option>
                                    <option value="tron" {{ old('network') == 'tron' ? 'selected' : '' }}>Tron</option>
                                </select>
                                <small class="form-text text-muted">Select the blockchain network.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Network Display Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="network_display_name" value="{{ old('network_display_name') }}" required
                                    placeholder="e.g., BNB Smart Chain (BEP20)" />
                                <small class="form-text text-muted">The display name for this network.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Wallet Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="wallet_address" value="{{ old('wallet_address') }}" required
                                    placeholder="Enter wallet address" />
                                <small class="form-text text-muted">The wallet address for receiving/sending crypto.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">QR Code Image</label>
                                <input type="file" class="form-control" name="qr_code_image" accept="image/*">
                                <small class="form-text text-muted">Upload QR code image for the wallet address (Max: 2MB, Formats: jpeg, png, jpg, gif).</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Token <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="token" value="{{ old('token', 'USDT') }}" required
                                    placeholder="USDT" />
                                <small class="form-text text-muted">The token type (e.g., USDT).</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Minimum Deposit</label>
                                <input type="number" step="0.01" class="form-control" name="minimum_deposit" value="{{ old('minimum_deposit') }}"
                                    placeholder="Enter minimum deposit amount (e.g., 10.00)" min="0" />
                                <small class="form-text text-muted">The minimum deposit amount allowed.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Maximum Deposit</label>
                                <input type="number" step="0.01" class="form-control" name="maximum_deposit" value="{{ old('maximum_deposit') }}"
                                    placeholder="Enter maximum deposit amount (e.g., 50000.00)" min="0" />
                                <small class="form-text text-muted">The maximum deposit amount allowed.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Minimum Withdrawal</label>
                                <input type="number" step="0.01" class="form-control" name="minimum_withdrawal" value="{{ old('minimum_withdrawal') }}"
                                    placeholder="Enter minimum withdrawal amount (e.g., 10.00)" min="0" />
                                <small class="form-text text-muted">The minimum withdrawal amount allowed.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Maximum Withdrawal</label>
                                <input type="number" step="0.01" class="form-control" name="maximum_withdrawal" value="{{ old('maximum_withdrawal') }}"
                                    placeholder="Enter maximum withdrawal amount (e.g., 50000.00)" min="0" />
                                <small class="form-text text-muted">The maximum withdrawal amount allowed.</small>
                            </div>

                            <div class="form-group mb-0">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Active</label>
                                </div>
                                <small class="form-text text-muted">Check to make this crypto wallet active.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Usage Settings</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="allowed_for_deposit" name="allowed_for_deposit" value="1" {{ old('allowed_for_deposit', true) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="allowed_for_deposit">Allow for Deposit</label>
                                </div>
                                <small class="form-text text-muted">Check to allow this crypto wallet for deposits.</small>
                            </div>

                            <div class="form-group mb-0">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="allowed_for_withdrawal" name="allowed_for_withdrawal" value="1" {{ old('allowed_for_withdrawal', true) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="allowed_for_withdrawal">Allow for Withdrawal</label>
                                </div>
                                <small class="form-text text-muted">Check to allow this crypto wallet for withdrawals.</small>
                            </div>

                            <div class="form-group mb-0 mt-4">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    <i class="mdi mdi-content-save"></i> Create Crypto Wallet
                                </button>
                                <a href="{{ route('admin.crypto-wallet.index') }}" class="btn btn-secondary waves-effect waves-light ml-2">
                                    <i class="mdi mdi-close"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

