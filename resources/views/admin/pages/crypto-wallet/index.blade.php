@extends('admin.layouts.main')

@push('styles')
<style>
    #crypto-wallet-table ~ .btn-toolbar,
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
                    <h4 class="page-title">Crypto Wallets</h4>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>

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
                            <h4 class="mt-0 header-title">Crypto Wallets</h4>
                            <a href="{{ route('admin.crypto-wallet.create') }}" class="btn btn-primary waves-effect waves-light">
                                <i class="mdi mdi-plus"></i> Add New Crypto Wallet
                            </a>
                        </div>

                        <div class="table-rep-plugin">
                            <div class="table-responsive b-0" data-pattern="priority-columns">
                                <table id="crypto-wallet-table" class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th data-priority="1">Network</th>
                                        <th data-priority="2">Display Name</th>
                                        <th data-priority="3">Wallet Address</th>
                                        <th data-priority="4">Token</th>
                                        <th data-priority="5">QR Code</th>
                                        <th data-priority="6">Min Deposit</th>
                                        <th data-priority="6">Max Deposit</th>
                                        <th data-priority="6">Min Withdrawal</th>
                                        <th data-priority="6">Max Withdrawal</th>
                                        <th data-priority="3">Status</th>
                                        <th data-priority="2">Usage</th>
                                        <th data-priority="1">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($cryptoWallets as $wallet)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>
                                            <span class="badge badge-soft-primary">
                                                {{ strtoupper(str_replace('_', ' ', $wallet->network)) }}
                                            </span>
                                        </td>
                                        <td>{{ $wallet->network_display_name }}</td>
                                        <td>
                                            <code style="font-size: 0.75rem;">{{ Str::limit($wallet->wallet_address, 20) }}</code>
                                        </td>
                                        <td>{{ $wallet->token }}</td>
                                        <td>
                                            @if($wallet->qr_code_image)
                                                <img src="{{ asset($wallet->qr_code_image) }}" alt="QR Code" style="max-width: 60px; max-height: 60px;">
                                            @else
                                                <span class="text-muted">No QR Code</span>
                                            @endif
                                        </td>
                                        <td>${{ number_format($wallet->minimum_deposit ?? 0, 2) }}</td>
                                        <td>${{ number_format($wallet->maximum_deposit ?? 0, 2) }}</td>
                                        <td>${{ number_format($wallet->minimum_withdrawal ?? 0, 2) }}</td>
                                        <td>${{ number_format($wallet->maximum_withdrawal ?? 0, 2) }}</td>
                                        <td>
                                            @if($wallet->is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                @if($wallet->allowed_for_deposit)
                                                    <span class="badge badge-soft-info mb-1">Deposit</span>
                                                @endif
                                                @if($wallet->allowed_for_withdrawal)
                                                    <span class="badge badge-soft-warning">Withdrawal</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.crypto-wallet.edit', $wallet->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.crypto-wallet.destroy', $wallet->id) }}" method="POST" style="display: inline;" data-confirm="Are you sure you want to delete this crypto wallet?">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="13" class="text-center">No crypto wallets found.</td>
                                    </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

