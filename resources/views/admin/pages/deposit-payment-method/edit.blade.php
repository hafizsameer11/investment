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
                            <li class="breadcrumb-item"><a href="#">Deposit Payment Methods</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.deposit-payment-method.index') }}">All Payment Methods</a></li>
                            <li class="breadcrumb-item active">Edit Payment Method</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Edit Deposit Payment Method</h4>
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

                        <h4 class="mt-0 header-title">Edit Deposit Payment Method</h4>
                        <p class="text-muted mb-4 font-14">Update the details of the deposit payment method.</p>

                        <form action="{{ route('admin.deposit-payment-method.update', $paymentMethod->id) }}" method="POST" enctype="multipart/form-data" class="">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group mb-0">
                                <label class="mb-2 pb-1">Image</label>
                                @if($paymentMethod->image)
                                    <div class="mb-2">
                                        <img src="{{ asset($paymentMethod->image) }}" alt="Current Image" style="max-width: 200px; max-height: 200px; object-fit: cover; border: 1px solid #ddd; padding: 5px;">
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="image" accept="image/*">
                                <small class="form-text text-muted">Upload a new image to replace the current one (Max: 2MB, Formats: jpeg, png, jpg, gif, svg). Leave empty to keep current image.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Type <span class="text-danger">*</span></label>
                                <select class="form-control" name="type" id="payment_type" required>
                                    <option value="rast" {{ old('type', $paymentMethod->type) == 'rast' ? 'selected' : '' }}>Rast</option>
                                    <option value="bank" {{ old('type', $paymentMethod->type) == 'bank' ? 'selected' : '' }}>Bank</option>
                                    <option value="crypto" {{ old('type', $paymentMethod->type) == 'crypto' ? 'selected' : '' }}>Crypto</option>
                                    <option value="onepay" {{ old('type', $paymentMethod->type) == 'onepay' ? 'selected' : '' }}>OnePay</option>
                                </select>
                                <small class="form-text text-muted">Select the category of the payment method.</small>
                            </div>

                            <div class="form-group mb-0" id="account_type_group">
                                <label class="my-2 py-1">Service Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="account_type" value="{{ old('account_type', $paymentMethod->account_type) }}" required
                                    placeholder="Enter service name (e.g., Easypaisa, Jazzcash, Bank Transfer)" />
                                <small class="form-text text-muted">The name of the payment service.</small>
                            </div>

                            <div class="form-group mb-0" id="bank_name_group">
                                <label class="my-2 py-1">Bank Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="bank_name" value="{{ old('bank_name', $paymentMethod->bank_name) }}"
                                    placeholder="Enter bank name" />
                                <small class="form-text text-muted">The name of the bank.</small>
                            </div>

                            <div class="form-group mb-0" id="account_name_group">
                                <label class="my-2 py-1">Account Holder Name</label>
                                <input type="text" class="form-control" name="account_name" value="{{ old('account_name', $paymentMethod->account_name ?? '') }}"
                                    placeholder="Enter account holder name" />
                                <small class="form-text text-muted">The name of the account holder.</small>
                            </div>

                            <div class="form-group mb-0" id="account_number_group">
                                <label class="my-2 py-1">Account Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="account_number" value="{{ old('account_number', $paymentMethod->account_number) }}"
                                    placeholder="Enter account number" />
                                <small class="form-text text-muted">The account number for this payment method.</small>
                            </div>

                            <div class="form-group mb-0" id="till_id_group">
                                <label class="my-2 py-1">Till ID <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="till_id" value="{{ old('till_id', $paymentMethod->till_id) }}"
                                    placeholder="Enter till ID" />
                                <small class="form-text text-muted">The Till ID for Raast payments.</small>
                            </div>

                            <div class="form-group mb-0" id="qr_scanner_group">
                                <label class="my-2 py-1">QR Scanner</label>
                                @if($paymentMethod->qr_scanner)
                                    <div class="mb-2">
                                        <img src="{{ asset($paymentMethod->qr_scanner) }}" alt="QR Scanner" style="max-width: 200px; max-height: 200px; object-fit: cover; border: 1px solid #ddd; padding: 5px;">
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="qr_scanner" accept="image/*">
                                <small class="form-text text-muted">Upload a new QR scanner image to replace the current one. Leave empty to keep current image.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Minimum Deposit</label>
                                <input type="number" step="0.01" class="form-control" name="minimum_deposit" value="{{ old('minimum_deposit', $paymentMethod->minimum_deposit) }}"
                                    placeholder="Enter minimum deposit amount (e.g., 10.00)" min="0" />
                                <small class="form-text text-muted">The minimum deposit amount allowed for this payment method.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Maximum Deposit</label>
                                <input type="number" step="0.01" class="form-control" name="maximum_deposit" value="{{ old('maximum_deposit', $paymentMethod->maximum_deposit) }}"
                                    placeholder="Enter maximum deposit amount (e.g., 10000.00)" min="0" />
                                <small class="form-text text-muted">The maximum deposit amount allowed for this payment method.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Minimum Withdrawal</label>
                                <input type="number" step="0.01" class="form-control" name="minimum_withdrawal_amount" value="{{ old('minimum_withdrawal_amount', $paymentMethod->minimum_withdrawal_amount) }}"
                                    placeholder="Enter minimum withdrawal amount (e.g., 10.00)" min="0" />
                                <small class="form-text text-muted">The minimum withdrawal amount allowed for this payment method.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Maximum Withdrawal</label>
                                <input type="number" step="0.01" class="form-control" name="maximum_withdrawal_amount" value="{{ old('maximum_withdrawal_amount', $paymentMethod->maximum_withdrawal_amount) }}"
                                    placeholder="Enter maximum withdrawal amount (e.g., 10000.00)" min="0" />
                                <small class="form-text text-muted">The maximum withdrawal amount allowed for this payment method.</small>
                            </div>

                            <div class="form-group mb-0">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Active</label>
                                </div>
                                <small class="form-text text-muted">Check to make this payment method active.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="my-2 py-1">Usage Settings</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="allowed_for_deposit" name="allowed_for_deposit" value="1" {{ old('allowed_for_deposit', $paymentMethod->allowed_for_deposit ?? true) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="allowed_for_deposit">Allow for Deposit</label>
                                </div>
                                <small class="form-text text-muted">Check to allow this payment method for deposits/recharges.</small>
                            </div>

                            <div class="form-group mb-0">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="allowed_for_withdrawal" name="allowed_for_withdrawal" value="1" {{ old('allowed_for_withdrawal', $paymentMethod->allowed_for_withdrawal ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="allowed_for_withdrawal">Allow for Withdrawal</label>
                                </div>
                                <small class="form-text text-muted">Check to allow this payment method for withdrawals.</small>
                            </div>

                            <div class="form-group mb-0">
                                <div>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                                        Update
                                    </button>
                                    <a href="{{ route('admin.deposit-payment-method.index') }}" class="btn btn-secondary waves-effect m-l-5">
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

@push('scripts')
<script>
    $(document).ready(function() {
        function toggleFields() {
            var type = $('#payment_type').val();
            
            // Service Name (account_type) is now always shown and required
            $('#account_type_group').show();
            $('#account_type_group input').prop('required', true);

            if (type === 'rast') {
                $('#bank_name_group').hide();
                $('#bank_name_group input').prop('required', false);
                $('#account_name_group').show();

                $('#account_number_group').hide();
                $('#account_number_group input').prop('required', false);
                $('#till_id_group').show();
                $('#till_id_group input').prop('required', true);
            } else if (type === 'bank') {
                $('#bank_name_group').show();
                $('#bank_name_group input').prop('required', true);
                $('#account_name_group').show();

                $('#account_number_group').show();
                $('#account_number_group input').prop('required', true);
                $('#till_id_group').hide();
                $('#till_id_group input').prop('required', false);
            } else if (type === 'onepay') {
                $('#bank_name_group').hide();
                $('#bank_name_group input').prop('required', false);
                $('#account_name_group').show();
                $('#account_name_group input').prop('required', true);

                $('#account_number_group').show();
                $('#account_number_group input').prop('required', true);
                $('#till_id_group').hide();
                $('#till_id_group input').prop('required', false);
            } else {
                $('#bank_name_group').hide();
                $('#bank_name_group input').prop('required', false);
                $('#account_name_group').hide();

                $('#account_number_group').show();
                $('#account_number_group input').prop('required', true);
                $('#till_id_group').hide();
                $('#till_id_group input').prop('required', false);
            }

            $('#qr_scanner_group').show();
        }

        $('#payment_type').change(toggleFields);
        toggleFields(); // Run on page load
    });
</script>
@endpush
