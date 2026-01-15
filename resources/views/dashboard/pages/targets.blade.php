@extends('dashboard.layouts.main')

@section('title', 'Core Mining - Targets')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/targets.css') }}">
@endpush

@section('content')
<div class="targets-page">
    <!-- Page Header -->
    <div class="targets-header">
        <h1 class="targets-title">Targets</h1>
    </div>

    <!-- Main Target Card -->
    <div class="targets-section">
        <div class="target-card">
            <div class="target-card-header">
                <div class="target-current-header">
                    <div class="target-star-icon">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="currentColor"/>
                        </svg>
                    </div>
                    <span class="target-current-label">Current Target</span>
                </div>
            </div>

            <div class="target-card-body">
                <div class="target-status">
                    <h2 class="target-status-text">No Active Target</h2>
                </div>

                <div class="target-progress-section">
                    <div class="target-progress-header">
                        <span class="target-progress-label">Overall Progress</span>
                        <span class="target-progress-percent" id="targetProgressPercent">0%</span>
                    </div>
                    <div class="target-progress-bar">
                        <div class="target-progress-fill" id="targetProgressFill" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/dashboard/js/targets.js') }}"></script>
@endpush
@endsection

