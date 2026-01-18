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
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Dashboard</h4>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <!-- end page title end breadcrumb -->
        <div class="row">
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="col-3 align-self-center">
                                        <div class="round">
                                            <i class="mdi mdi-account-multiple"></i>
                                        </div>
                                    </div>
                                    <div class="col-9 align-self-center text-right">
                                        <div class="m-l-10">
                                            <h5 class="mt-0">{{ number_format($totalUsers) }}</h5>
                                            <p class="mb-0 text-muted">Total Users</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress mt-3" style="height:3px;">
                                    <div class="progress-bar  bg-success" role="progressbar" style="width: 100%;"
                                        aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div><!--end col-->

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="col-3 align-self-center">
                                        <div class="round">
                                            <i class="mdi mdi-account-plus"></i>
                                        </div>
                                    </div>
                                    <div class="col-9 text-right align-self-center">
                                        <div class="m-l-10 ">
                                            <h5 class="mt-0">{{ number_format($thisMonthUsers) }}</h5>
                                            <p class="mb-0 text-muted">This Month Users</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress mt-3" style="height:3px;">
                                    @php
                                        $thisMonthProgress = $totalUsers > 0 ? ($thisMonthUsers / $totalUsers) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ min($thisMonthProgress, 100) }}%;"
                                        aria-valuenow="{{ $thisMonthProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div><!--end col-->

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="search-type-arrow"></div>
                                <div class="d-flex flex-row">
                                    <div class="col-3 align-self-center">
                                        <div class="round ">
                                            <i class="mdi mdi-account-multiple-outline"></i>
                                        </div>
                                    </div>
                                    <div class="col-9 align-self-center text-right">
                                        <div class="m-l-10 ">
                                            <h5 class="mt-0">{{ number_format($lastMonthUsers) }}</h5>
                                            <p class="mb-0 text-muted">Last Month Users</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress mt-3" style="height:3px;">
                                    @php
                                        $lastMonthProgress = $totalUsers > 0 ? ($lastMonthUsers / $totalUsers) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ min($lastMonthProgress, 100) }}%;"
                                        aria-valuenow="{{ $lastMonthProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div><!--end col-->
                </div><!--end row-->

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mt-0 header-title mb-0">Financial Overview</h4>
                            <div class="btn-group" role="group" aria-label="Time period">
                                <button type="button" class="btn btn-sm btn-outline-primary period-btn" data-period="weekly">Weekly</button>
                                <button type="button" class="btn btn-sm btn-primary period-btn active" data-period="monthly">Monthly</button>
                                <button type="button" class="btn btn-sm btn-outline-primary period-btn" data-period="yearly">Yearly</button>
                            </div>
                        </div>
                        <p class="text-muted mb-4 font-14"></p>
                        <div id="financial-chart" class="morris-chart" style="height: 300px;"></div>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->

           
        </div><!--end row-->

        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body new-user">
                        <h5 class="header-title mb-4 mt-0">New Users</h5>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th class="border-top-0">Users</th>
                                        <th class="border-top-0">Name</th>
                                        <th class="border-top-0">Country</th>
                                        <th class="border-top-0">Reviews</th>
                                        <th class="border-top-0">Socials</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <img class="rounded-circle" src="assets/images/users/avatar-2.jpg"
                                                alt="user" width="30">
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);">Ruby T. Curd</a>
                                        </td>
                                        <td>
                                            <img src="assets/images/flags/us_flag.jpg" alt=""
                                                class="img-flag">
                                        </td>
                                        <td>
                                            <i class="mdi mdi-star text-warning"></i>
                                            <i class="mdi mdi-star text-warning"></i>
                                            <i class="mdi mdi-star text-warning"></i>
                                            <i class="mdi mdi-star-half text-warning"></i>
                                            <i class="mdi mdi-star-outline text-warning"></i>
                                        </td>
                                        <td>
                                            <ul class="list-unstyled list-inline mb-0">
                                                <li class="list-inline-item"><a href="#"><i
                                                            class="ti-facebook text-primary"></i></a></li>
                                                <li class="list-inline-item"><a href="#"><i
                                                            class="ti-linkedin text-danger"></i></a></li>
                                                <li class="list-inline-item"><a href="#"><i
                                                            class="ti-twitter-alt text-info"></i></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img class="rounded-circle" src="assets/images/users/avatar-3.jpg"
                                                alt="user" width="30">
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);">William A. Johnson</a>
                                        </td>
                                        <td>
                                            <img src="assets/images/flags/french_flag.jpg" alt=""
                                                class="img-flag">
                                        </td>
                                        <td>
                                            <i class="mdi mdi-star text-warning"></i>
                                            <i class="mdi mdi-star text-warning"></i>
                                            <i class="mdi mdi-star text-warning"></i>
                                            <i class="mdi mdi-star-half text-warning"></i>
                                            <i class="mdi mdi-star-outline text-warning"></i>
                                        </td>
                                        <td>
                                            <ul class="list-unstyled list-inline mb-0">
                                                <li class="list-inline-item"><a href="#"><i
                                                            class="ti-facebook text-primary"></i></a></li>
                                                <li class="list-inline-item"><a href="#"><i
                                                            class="ti-linkedin text-danger"></i></a></li>
                                                <li class="list-inline-item"><a href="#"><i
                                                            class="ti-twitter-alt text-info"></i></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img class="rounded-circle" src="assets/images/users/avatar-4.jpg"
                                                alt="user" width="30">
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);">Bobby M. Gray</a>
                                        </td>
                                        <td>
                                            <img src="assets/images/flags/spain_flag.jpg" alt=""
                                                class="img-flag">
                                        </td>
                                        <td>
                                            <i class="mdi mdi-star text-warning"></i>
                                            <i class="mdi mdi-star text-warning"></i>
                                            <i class="mdi mdi-star text-warning"></i>
                                            <i class="mdi mdi-star-half text-warning"></i>
                                            <i class="mdi mdi-star-outline text-warning"></i>
                                        </td>
                                        <td>
                                            <ul class="list-unstyled list-inline mb-0">
                                                <li class="list-inline-item"><a href="#"><i
                                                            class="ti-facebook text-primary"></i></a></li>
                                                <li class="list-inline-item"><a href="#"><i
                                                            class="ti-linkedin text-danger"></i></a></li>
                                                <li class="list-inline-item"><a href="#"><i
                                                            class="ti-twitter-alt text-info"></i></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img class="rounded-circle" src="assets/images/users/avatar-5.jpg"
                                                alt="user" width="30">
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);">Robert N. Carlile</a>
                                        </td>
                                        <td>
                                            <img src="assets/images/flags/russia_flag.jpg" alt=""
                                                class="img-flag">
                                        </td>
                                        <td>
                                            <i class="mdi mdi-star text-warning"></i>
                                            <i class="mdi mdi-star text-warning"></i>
                                            <i class="mdi mdi-star text-warning"></i>
                                            <i class="mdi mdi-star-half text-warning"></i>
                                            <i class="mdi mdi-star-outline text-warning"></i>
                                        </td>
                                        <td>
                                            <ul class="list-unstyled list-inline mb-0">
                                                <li class="list-inline-item"><a href="#"><i
                                                            class="ti-facebook text-primary"></i></a></li>
                                                <li class="list-inline-item"><a href="#"><i
                                                            class="ti-linkedin text-danger"></i></a></li>
                                                <li class="list-inline-item"><a href="#"><i
                                                            class="ti-twitter-alt text-info"></i></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img class="rounded-circle" src="assets/images/users/avatar-6.jpg"
                                                alt="user" width="30">
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);">Ruby T. Curd</a>
                                        </td>
                                        <td>
                                            <img src="assets/images/flags/italy_flag.jpg" alt=""
                                                class="img-flag">
                                        </td>
                                        <td>
                                            <i class="mdi mdi-star text-warning"></i>
                                            <i class="mdi mdi-star text-warning"></i>
                                            <i class="mdi mdi-star text-warning"></i>
                                            <i class="mdi mdi-star-half text-warning"></i>
                                            <i class="mdi mdi-star-outline text-warning"></i>
                                        </td>
                                        <td>
                                            <ul class="list-unstyled list-inline mb-0">
                                                <li class="list-inline-item"><a href="#"><i
                                                            class="ti-facebook text-primary"></i></a></li>
                                                <li class="list-inline-item"><a href="#"><i
                                                            class="ti-linkedin text-danger"></i></a></li>
                                                <li class="list-inline-item"><a href="#"><i
                                                            class="ti-twitter-alt text-info"></i></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img class="rounded-circle" src="assets/images/users/avatar-2.jpg"
                                                alt="user" width="30">
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);">Ruby T. Curd</a>
                                        </td>
                                        <td>
                                            <img src="assets/images/flags/us_flag.jpg" alt=""
                                                class="img-flag">
                                        </td>
                                        <td>
                                            <i class="mdi mdi-star text-warning"></i>
                                            <i class="mdi mdi-star text-warning"></i>
                                            <i class="mdi mdi-star text-warning"></i>
                                            <i class="mdi mdi-star-half text-warning"></i>
                                            <i class="mdi mdi-star-outline text-warning"></i>
                                        </td>
                                        <td>
                                            <ul class="list-unstyled list-inline mb-0">
                                                <li class="list-inline-item"><a href="#"><i
                                                            class="ti-facebook text-primary"></i></a></li>
                                                <li class="list-inline-item"><a href="#"><i
                                                            class="ti-linkedin text-danger"></i></a></li>
                                                <li class="list-inline-item"><a href="#"><i
                                                            class="ti-twitter-alt text-info"></i></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body new-user">
                        <h5 class="header-title mb-4 mt-0">Top 5 Referrers</h5>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        {{-- <th class="border-top-0">#</th> --}}
                                        <th class="border-top-0">User</th>
                                        <th class="border-top-0">Username</th>
                                        <th class="border-top-0">Referrals</th>
                                        <th class="border-top-0">Referral Earnings</th>
                                        <th class="border-top-0">Join Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topReferrers as $index => $user)
                                    <tr>
                                        {{-- <td>
                                            <span class="badge badge-boxed badge-primary">{{ $index + 1 }}</span>
                                        </td> --}}
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="mr-2">
                                                    <div class="rounded-circle bg-soft-primary text-primary d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold;">
                                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $user->name ?? 'N/A' }}</h6>
                                                    <small class="text-muted">{{ $user->email ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $user->username ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-boxed badge-success">
                                                <i class="mdi mdi-account-multiple"></i> {{ $user->direct_referrals_count ?? 0 }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong>${{ number_format($user->referral_earning ?? 0, 2) }}</strong>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <p class="text-muted mb-0">No users with referrals found.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->
        </div> <!--end row-->

    </div><!-- container -->

</div> <!-- Page content Wrapper -->
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let financialChart = null;
    let currentPeriod = 'monthly';

    // Function to load and render chart
    function loadFinancialChart(period) {
        currentPeriod = period;
        
        // Update button states
        $('.period-btn').removeClass('active btn-primary').addClass('btn-outline-primary');
        $(`.period-btn[data-period="${period}"]`).removeClass('btn-outline-primary').addClass('active btn-primary');

        // Show loading
        $('#financial-chart').html('<div class="text-center p-5"><i class="mdi mdi-loading mdi-spin"></i> Loading...</div>');

        // Fetch data
        $.ajax({
            url: '{{ route("admin.financial-data") }}',
            method: 'GET',
            data: { period: period },
            success: function(data) {
                // Destroy existing chart if any
                if (financialChart) {
                    financialChart.setData(formatChartData(data));
                } else {
                    // Create new chart
                    financialChart = Morris.Line({
                        element: 'financial-chart',
                        data: formatChartData(data),
                        xkey: 'period',
                        ykeys: ['deposit', 'withdrawal', 'revenue'],
                        labels: ['Deposit', 'Withdrawal', 'Revenue'],
                        lineColors: ['#FF8A1D', '#29b348', '#888888'],
                        pointSize: 4,
                        lineWidth: 2,
                        hideHover: 'auto',
                        resize: true,
                        gridLineColor: '#eef0f2',
                        gridTextColor: '#666',
                        yLabelFormat: function(y) {
                            return '$' + y.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        },
                        xLabelAngle: period === 'yearly' ? 0 : 45
                    });
                }
            },
            error: function(xhr, status, error) {
                $('#financial-chart').html('<div class="text-center p-5 text-danger">Error loading chart data. Please try again.</div>');
                console.error('Error:', error);
            }
        });
    }

    // Format data for Morris chart
    function formatChartData(data) {
        return data.map(function(item) {
            return {
                period: item.period,
                deposit: parseFloat(item.deposit) || 0,
                withdrawal: parseFloat(item.withdrawal) || 0,
                revenue: parseFloat(item.revenue) || 0
            };
        });
    }

    // Handle period button clicks
    $('.period-btn').on('click', function() {
        const period = $(this).data('period');
        loadFinancialChart(period);
    });

    // Load initial chart with monthly data
    loadFinancialChart('monthly');
});
</script>
@endpush