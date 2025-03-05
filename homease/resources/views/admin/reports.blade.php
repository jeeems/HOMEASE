@extends('layouts.admin')

@section('content')
    <!-- Load ECharts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.2.2/echarts.min.js"></script>

    <div class="container-fluid p-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary m-0">
                <i class="fas fa-chart-line me-2"></i>Analytics & Reports
            </h2>
            <div class="d-flex align-items-center">
                <div class="dropdown me-2">
                    <button class="btn btn-outline-secondary dropdown-toggle rounded-pill px-3" type="button"
                        id="timeRangeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-calendar-alt me-1"></i>
                        Last 30 Days
                    </button>
                    <ul class="dropdown-menu shadow-sm border-0" aria-labelledby="timeRangeDropdown">
                        <li><a class="dropdown-item" href="#" data-range="7"><i
                                    class="fas fa-calendar-week me-2"></i>Last 7 Days</a></li>
                        <li><a class="dropdown-item active" href="#" data-range="30"><i
                                    class="fas fa-calendar-alt me-2"></i>Last 30 Days</a></li>
                        <li><a class="dropdown-item" href="#" data-range="90"><i class="fas fa-calendar me-2"></i>Last
                                90 Days</a></li>
                        <li><a class="dropdown-item" href="#" data-range="365"><i
                                    class="far fa-calendar me-2"></i>Last Year</a></li>
                    </ul>
                </div>
                <button class="btn btn-primary rounded-pill px-3" onclick="window.print()">
                    <i class="fas fa-download me-1"></i> Export Report
                </button>
            </div>
        </div>

        <!-- Summary Cards Row -->
        <div class="row mb-4">
            <!-- Total Revenue Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="text-muted">Total Revenue</div>
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                                <i class="fas fa-peso-sign text-primary"></i>
                            </div>
                        </div>
                        <h3 class="mb-1">₱{{ number_format($totalRevenue, 2) }}</h3>
                        <div class="d-flex align-items-center">
                            @if ($revenueGrowth >= 0)
                                <span class="badge bg-success bg-opacity-10 text-success me-2">
                                    <i class="fas fa-arrow-up me-1"></i>{{ number_format($revenueGrowth, 1) }}%
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger me-2">
                                    <i class="fas fa-arrow-down me-1"></i>{{ number_format(abs($revenueGrowth), 1) }}%
                                </span>
                            @endif
                            <span class="text-muted small">vs last period</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Bookings Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="text-muted">Total Bookings</div>
                            <div class="rounded-circle bg-success bg-opacity-10 p-2">
                                <i class="fas fa-calendar-check text-success"></i>
                            </div>
                        </div>
                        <h3 class="mb-1">{{ number_format($totalBookings) }}</h3>
                        <div class="d-flex align-items-center">
                            @if ($bookingsGrowth >= 0)
                                <span class="badge bg-success bg-opacity-10 text-success me-2">
                                    <i class="fas fa-arrow-up me-1"></i>{{ number_format($bookingsGrowth, 1) }}%
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger me-2">
                                    <i class="fas fa-arrow-down me-1"></i>{{ number_format(abs($bookingsGrowth), 1) }}%
                                </span>
                            @endif
                            <span class="text-muted small">vs last period</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Average Rating Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="text-muted">Average Rating</div>
                            <div class="rounded-circle bg-warning bg-opacity-10 p-2">
                                <i class="fas fa-star text-warning"></i>
                            </div>
                        </div>
                        <h3 class="mb-1">{{ number_format($averageRating, 1) }}</h3>
                        <div class="text-warning">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $averageRating)
                                    <i class="fas fa-star"></i>
                                @elseif($i - 0.5 <= $averageRating)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                            <span class="text-muted small ms-2">({{ number_format($totalRatings) }} reviews)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Workers Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="text-muted">Active Workers</div>
                            <div class="rounded-circle bg-info bg-opacity-10 p-2">
                                <i class="fas fa-users text-info"></i>
                            </div>
                        </div>
                        <h3 class="mb-1">{{ number_format($activeWorkers) }}</h3>
                        <div class="d-flex align-items-center">
                            @if ($workersGrowth >= 0)
                                <span class="badge bg-success bg-opacity-10 text-success me-2">
                                    <i class="fas fa-arrow-up me-1"></i>{{ number_format($workersGrowth, 1) }}%
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger me-2">
                                    <i class="fas fa-arrow-down me-1"></i>{{ number_format(abs($workersGrowth), 1) }}%
                                </span>
                            @endif
                            <span class="text-muted small">vs last period</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($validationMessages)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($validationMessages as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- <pre>{{ print_r($revenueTrendData) }}</pre>
        <pre>{{ print_r($serviceDistributionData) }}</pre>
        <pre>{{ print_r($bookingStatus) }}</pre> --}}

        <!-- Charts Row -->
        <div class="row mb-4">
            <!-- Revenue Trend Chart -->
            <div class="col-xl-8 mb-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="card-title mb-0 fw-bold">Revenue Trend</h5>
                    </div>
                    <div class="card-body">
                        @if (!empty($revenueTrend))
                            <div id="revenueTrendChart" style="width:100%; height:400px;"></div>
                        @else
                            <p>No revenue trend data available.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Service Distribution Chart -->
            <div class="col-xl-4 mb-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="card-title mb-0 fw-bold">Service Distribution</h5>
                    </div>
                    <div class="card-body">
                        @if (!empty($serviceDistribution))
                            <div id="serviceDistributionChart" style="width:100%; height:400px;"></div>
                        @else
                            <p>No service distribution data available.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Status Chart -->
        <div class="row mb-4">
            <div class="col-xl-4 mb-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="card-title mb-0 fw-bold">Booking Status</h5>
                    </div>
                    <div class="card-body">
                        @if (!empty($bookingStatus))
                            <div id="bookingStatusChart" style="width:100%; height:400px;"></div>
                        @else
                            <p>No booking status data available.</p>
                        @endif
                    </div>
                </div>
            </div>


            <!-- Top Performing Workers -->
            <div class="col-xl-8 mb-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold">Top Performing Workers</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle rounded-pill px-3"
                                type="button" id="workerMetricDropdown" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                By Revenue
                            </button>
                            <ul class="dropdown-menu shadow-sm border-0" aria-labelledby="workerMetricDropdown">
                                <li><a class="dropdown-item active" href="#" data-metric="revenue">By Revenue</a>
                                </li>
                                <li><a class="dropdown-item" href="#" data-metric="bookings">By Bookings</a></li>
                                <li><a class="dropdown-item" href="#" data-metric="rating">By Rating</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 ps-4">Worker</th>
                                        <th class="border-0">Service</th>
                                        <th class="border-0">Bookings</th>
                                        <th class="border-0">Rating</th>
                                        <th class="border-0 text-end pe-4">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topWorkers as $worker)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-3">
                                                        @if ($worker->profile->profile_picture)
                                                            <img src="{{ asset('storage/' . $worker->profile->profile_picture) }}"
                                                                alt="{{ $worker->full_name }}" class="rounded-circle"
                                                                width="40" height="40">
                                                        @else
                                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                                style="width: 40px; height: 40px;">
                                                                {{ substr($worker->full_name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">{{ $worker->full_name }}</div>
                                                        <div class="small text-muted">{{ $worker->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $worker->workerVerification->service_type == 'Plumbing' ? 'primary' : ($worker->workerVerification->service_type == 'Carpentry' ? 'success' : ($worker->workerVerification->service_type == 'Home Cleaning' ? 'info' : 'warning')) }} bg-opacity-10 text-{{ $worker->workerVerification->service_type == 'Plumbing' ? 'primary' : ($worker->workerVerification->service_type == 'Carpentry' ? 'success' : ($worker->workerVerification->service_type == 'Home Cleaning' ? 'info' : 'warning')) }} px-2 py-1 rounded-pill">
                                                    {{ $worker->workerVerification->service_type }}
                                                </span>
                                            </td>
                                            <td>{{ $worker->completed_bookings_count }}</td>
                                            <td>
                                                <div class="text-warning">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $worker->average_rating)
                                                            <i class="fas fa-star"></i>
                                                        @elseif($i - 0.5 <= $worker->average_rating)
                                                            <i class="fas fa-star-half-alt"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                    <span
                                                        class="text-dark ms-1">{{ number_format($worker->average_rating, 1) }}</span>
                                                </div>
                                            </td>
                                            <td class="text-end pe-4">₱{{ number_format($worker->total_revenue, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Satisfaction and Service Analysis -->
        <div class="row">
            <!-- Customer Satisfaction Metrics -->
            <div class="col-xl-6 mb-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="card-title mb-0 fw-bold">Customer Satisfaction Metrics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="text-center">
                                    <div class="display-4 fw-bold text-primary mb-2">
                                        {{ number_format($satisfactionRate, 1) }}%</div>
                                    <div class="text-muted">Overall Satisfaction Rate</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="text-center">
                                    <div class="display-4 fw-bold text-success mb-2">
                                        {{ number_format($completionRate, 1) }}%</div>
                                    <div class="text-muted">Service Completion Rate</div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h6 class="fw-bold mb-3">Rating Distribution</h6>
                            @foreach (range(5, 1) as $rating)
                                <div class="d-flex align-items-center mb-2">
                                    <div class="text-warning me-2" style="width: 70px;">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= $rating ? '' : '-o' }}"></i>
                                        @endfor
                                    </div>
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar bg-warning" role="progressbar"
                                            style="width: {{ ($ratingDistribution[$rating] / $totalRatings) * 100 }}%">
                                        </div>
                                    </div>
                                    <div class="ms-2" style="width: 60px;">
                                        {{ number_format(($ratingDistribution[$rating] / $totalRatings) * 100, 1) }}%
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Analysis -->
            <div class="col-xl-6 mb-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="card-title mb-0 fw-bold">Service Analysis</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Service Type</th>
                                        <th class="text-center">Bookings</th>
                                        <th class="text-center">Avg. Rating</th>
                                        <th class="text-end">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($serviceAnalysis as $service)
                                        <tr>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $service['type'] == 'Plumbing' ? 'primary' : ($service['type'] == 'Carpentry' ? 'success' : ($service['type'] == 'Home Cleaning' ? 'info' : 'warning')) }} bg-opacity-10 text-{{ $service['type'] == 'Plumbing' ? 'primary' : ($service['type'] == 'Carpentry' ? 'success' : ($service['type'] == 'Home Cleaning' ? 'info' : 'warning')) }} px-2 py-1 rounded-pill">
                                                    {{ $service['type'] }}
                                                </span>
                                            </td>
                                            <td class="text-center">{{ number_format($service['bookings']) }}</td>
                                            <td class="text-center">
                                                <div class="text-warning d-inline-block">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $service['average_rating'])
                                                            <i class="fas fa-star"></i>
                                                        @elseif($i - 0.5 <= $service['average_rating'])
                                                            <i class="fas fa-star-half-alt"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span
                                                    class="ms-1">{{ number_format($service['average_rating'], 1) }}</span>
                                            </td>
                                            <td class="text-end">₱{{ number_format($service['revenue'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var revenueTrendData = {!! json_encode($revenueTrend) !!} || [];
        var serviceDistributionData = {!! json_encode($serviceDistribution) !!} || [];
        var bookingStatus = {!! json_encode($bookingStatus) !!} || {};

        // Convert data properly in JavaScript
        revenueTrendData = revenueTrendData.map(item => ({
            date: item.date,
            amount: item.amount ? parseFloat(item.amount) : 0
        }));

        serviceDistributionData = serviceDistributionData.map(item => ({
            name: item.type,
            value: item.count ? parseInt(item.count) : 0
        }));

        console.log("Revenue Trend Data:", revenueTrendData);
        console.log("Service Distribution Data:", serviceDistributionData);
        console.log("Booking Status Data:", bookingStatus);

        // Revenue Trend Chart (Line Chart)
        if (revenueTrendData.length > 0) {
            var revenueTrendChart = echarts.init(document.getElementById('revenueTrendChart'));
            revenueTrendChart.setOption({
                title: {
                    text: 'Revenue Trend'
                },
                tooltip: {
                    trigger: 'axis'
                },
                xAxis: {
                    type: 'category',
                    data: revenueTrendData.map(item => item.date)
                },
                yAxis: {
                    type: 'value'
                },
                series: [{
                    name: 'Revenue',
                    type: 'line',
                    data: revenueTrendData.map(item => item.amount)
                }]
            });
        }

        // Service Distribution Chart (Pie Chart)
        if (serviceDistributionData.length > 0) {
            var serviceDistributionChart = echarts.init(document.getElementById('serviceDistributionChart'));
            serviceDistributionChart.setOption({
                title: {
                    text: 'Service Distribution'
                },
                tooltip: {
                    trigger: 'item'
                },
                series: [{
                    name: 'Services',
                    type: 'pie',
                    radius: '55%',
                    data: serviceDistributionData
                }]
            });
        }

        // Booking Status Chart (Pie Chart)
        if (Object.keys(bookingStatus).length > 0) {
            var bookingStatusChart = echarts.init(document.getElementById('bookingStatusChart'));
            bookingStatusChart.setOption({
                title: {
                    text: 'Booking Status'
                },
                tooltip: {
                    trigger: 'item'
                },
                series: [{
                    name: 'Bookings',
                    type: 'pie',
                    radius: '55%',
                    data: Object.keys(bookingStatus).map(status => ({
                        name: status,
                        value: bookingStatus[status]
                    }))
                }]
            });
        }
    </script>


    <!-- Error Container for Visible Errors -->
    {{-- <div id="chartErrorContainer" class="container mt-3"></div> --}}

    <style>
        .card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .progress {
            background-color: #f8f9fa;
            border-radius: 1rem;
        }

        .progress-bar {
            border-radius: 1rem;
        }

        .avatar img {
            object-fit: cover;
        }

        @media print {

            .btn,
            .dropdown {
                display: none !important;
            }

            .card {
                break-inside: avoid;
            }

            .container-fluid {
                width: 100% !important;
                padding: 0 !important;
            }
        }

        #revenueChart,
        #serviceDistributionChart,
        #bookingStatusChart {
            width: 100%;
            height: 400px;
            border: 1px solid #ddd;
            min-height: 400px;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }
    </style>
@endsection
