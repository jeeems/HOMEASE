@extends('layouts.admin')

@section('content')
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
        <div class="row mb-5">
            <!-- Revenue Trend Chart -->
            <div class="col-xl-8 col-md-12 mb-5">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="card-title mb-0 fw-bold">Revenue Trend</h5>
                    </div>
                    <div class="card-body">
                        @if (!empty($revenueTrend) && count($revenueTrend) > 0)
                            <div class="revenue-trend-chart">
                                <div class="chart-container position-relative" style="height: 350px; padding-bottom: 20px;">
                                    <div class="line-chart d-flex align-items-end justify-content-between h-100 pt-4 pb-5">
                                        @php
                                            $maxRevenue = collect($revenueTrend)->max('amount');
                                            $points = [];
                                            $totalWidth = 100;
                                            $stepWidth = $totalWidth / (count($revenueTrend) - 1);

                                            foreach ($revenueTrend as $index => $item) {
                                                $x = $index * $stepWidth;
                                                $y = $maxRevenue > 0 ? (1 - $item['amount'] / $maxRevenue) * 90 : 0;
                                                $points[] = "$x,$y";
                                            }

                                            $pointsString = implode(' ', $points);
                                        @endphp

                                        <svg class="line-graph" viewBox="0 0 100 100" preserveAspectRatio="none"
                                            style="width: 100%; height: 100%;">
                                            <!-- Grid lines -->
                                            <line x1="0" y1="0" x2="100" y2="0"
                                                stroke="#e9ecef" stroke-width="0.2" />
                                            <line x1="0" y1="22.5" x2="100" y2="22.5"
                                                stroke="#e9ecef" stroke-width="0.2" />
                                            <line x1="0" y1="45" x2="100" y2="45"
                                                stroke="#e9ecef" stroke-width="0.2" />
                                            <line x1="0" y1="67.5" x2="100" y2="67.5"
                                                stroke="#e9ecef" stroke-width="0.2" />
                                            <line x1="0" y1="90" x2="100" y2="90"
                                                stroke="#e9ecef" stroke-width="0.2" />

                                            <!-- Revenue line -->
                                            <polyline points="{{ $pointsString }}" fill="none" stroke="#3b82f6"
                                                stroke-width="0.5" vector-effect="non-scaling-stroke" />

                                            <!-- Data points -->
                                            @foreach ($revenueTrend as $index => $item)
                                                @php
                                                    $x = $index * $stepWidth;
                                                    $y = $maxRevenue > 0 ? (1 - $item['amount'] / $maxRevenue) * 90 : 0;
                                                @endphp
                                                <circle cx="{{ $x }}" cy="{{ $y }}" r="0.8"
                                                    fill="#3b82f6" stroke="#fff" stroke-width="0.2"
                                                    data-amount="{{ $item['amount'] }}" data-date="{{ $item['date'] }}"
                                                    class="data-point" />
                                            @endforeach
                                        </svg>

                                        <!-- X-axis labels -->
                                        <div
                                            class="x-axis-labels position-absolute bottom-0 w-100 d-flex justify-content-between px-3">
                                            @foreach ($revenueTrend as $index => $item)
                                                @if ($index % 3 === 0 || count($revenueTrend) < 10)
                                                    <div class="text-muted small" style="transform: translateX(-50%);">
                                                        {{ \Carbon\Carbon::parse($item['date'])->format('M d') }}
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>

                                        <!-- Y-axis labels -->
                                        <div class="y-axis-labels position-absolute top-0 start-0 h-100 d-flex flex-column justify-content-between"
                                            style="padding-top: 4px; padding-bottom: 35px;">
                                            <div class="text-muted small">₱{{ number_format($maxRevenue) }}</div>
                                            <div class="text-muted small">₱{{ number_format($maxRevenue * 0.75) }}</div>
                                            <div class="text-muted small">₱{{ number_format($maxRevenue * 0.5) }}</div>
                                            <div class="text-muted small">₱{{ number_format($maxRevenue * 0.25) }}</div>
                                            <div class="text-muted small">₱0</div>
                                        </div>
                                    </div>

                                    <div class="mt-4 pt-4 border-top">
                                        <div class="row text-center">
                                            <div class="col-md-3 col-6 mb-3">
                                                <h6 class="fw-bold">Total Revenue</h6>
                                                <p class="text-primary mb-0">
                                                    ₱{{ number_format(collect($revenueTrend ?? [])->sum('amount'), 2) }}
                                                </p>
                                            </div>
                                            <div class="col-md-3 col-6 mb-3">
                                                <h6 class="fw-bold">Average Daily</h6>
                                                <p class="text-primary mb-0">
                                                    ₱{{ number_format(collect($revenueTrend ?? [])->avg('amount') ?? 0, 2) }}
                                                </p>
                                            </div>
                                            <div class="col-md-3 col-6 mb-3">
                                                <h6 class="fw-bold">Highest Day</h6>
                                                <p class="text-primary mb-0">
                                                    ₱{{ number_format(collect($revenueTrend ?? [])->max('amount') ?? 0, 2) }}
                                                </p>
                                            </div>
                                            <div class="col-md-3 col-6 mb-3">
                                                <h6 class="fw-bold">Lowest Day</h6>
                                                <p class="text-primary mb-0">
                                                    ₱{{ number_format(collect($revenueTrend ?? [])->min('amount') ?? 0, 2) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p>No revenue trend data available.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Service Distribution Chart -->
            <div class="col-xl-4 col-md-12 mb-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="card-title mb-0 fw-bold">Service Distribution</h5>
                    </div>
                    <div class="card-body">
                        @if (!empty($serviceDistribution) && count($serviceDistribution) > 0)
                            <div class="service-distribution-chart mb-3">
                                @php
                                    $colors = [
                                        'Plumbing' => '#3b82f6',
                                        'Carpentry' => '#10b981',
                                        'Home Cleaning' => '#06b6d4',
                                        'Electrician' => '#f59e0b',
                                        'Painting' => '#8b5cf6',
                                        'Other' => '#6b7280',
                                    ];
                                    $totalCount = collect($serviceDistribution)->sum('count');
                                @endphp

                                <!-- Horizontal Bar Chart -->
                                <div class="horizontal-bar-chart mb-4">
                                    @foreach ($serviceDistribution as $service)
                                        @php
                                            $percentage = $totalCount > 0 ? ($service['count'] / $totalCount) * 100 : 0;
                                            $color = $colors[$service['type']] ?? $colors['Other'];
                                        @endphp
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="bar-label"
                                                style="width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                {{ $service['type'] }}
                                            </div>
                                            <div class="progress flex-fill" style="height: 20px;">
                                                <div class="progress-bar"
                                                    style="width: {{ $percentage }}%; background-color: {{ $color }};"
                                                    data-bs-toggle="tooltip"
                                                    title="{{ $service['type'] }}: {{ number_format($percentage, 1) }}% ({{ $service['count'] }})">
                                                    @if ($percentage > 15)
                                                        <span class="px-2">{{ number_format($percentage, 1) }}%</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="count-label ms-2" style="width: 60px; text-align: right;">
                                                {{ $service['count'] }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Legend -->
                                <div class="row mt-2">
                                    @foreach ($serviceDistribution as $service)
                                        @php
                                            $color = $colors[$service['type']] ?? $colors['Other'];
                                        @endphp
                                        <div class="col-6 mb-2">
                                            <div class="d-flex align-items-center">
                                                <div
                                                    style="width: 12px; height: 12px; background-color: {{ $color }}; margin-right: 8px; border-radius: 2px;">
                                                </div>
                                                <div class="small text-muted">{{ $service['type'] }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <p>No service distribution data available.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Status Chart -->
        <div class="row mt-5 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="card-title mb-0 fw-bold">Booking Status</h5>
                    </div>
                    <div class="card-body">
                        @if (!empty($bookingStatus) && is_array($bookingStatus) && count($bookingStatus) > 0)
                            <div class="booking-status-chart">
                                @php
                                    $statusColors = [
                                        'completed' => '#10B981',
                                        'ongoing' => '#3B82F6',
                                        'pending' => '#F59E0B',
                                        'cancelled' => '#EF4444',
                                    ];
                                    $totalBookingStatus = array_sum($bookingStatus);
                                @endphp

                                <div class="row">
                                    <div class="col-md-6 col-lg-4 mx-auto mb-4">
                                        <div class="position-relative d-flex justify-content-center">
                                            <!-- Donut Chart with Rays -->
                                            <div class="ray-chart-container position-relative"
                                                style="width: 220px; height: 220px;">
                                                <div class="ray-slices"></div>

                                                <!-- Center circle for donut effect -->
                                                <div
                                                    class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                                                    <div class="donut-hole bg-white rounded-circle d-flex align-items-center justify-content-center"
                                                        style="width: 60%; height: 60%; z-index: 5; box-shadow: inset 0 0 8px rgba(0,0,0,0.1);">
                                                        <div class="text-center">
                                                            <div class="text-muted">Total</div>
                                                            <div class="fw-bold fs-4">
                                                                {{ number_format($totalBookingStatus) }}
                                                            </div>
                                                            <div class="text-muted">Bookings</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-8">
                                        <div class="row mt-md-0 mt-4">
                                            @foreach ($bookingStatus as $status => $count)
                                                @php
                                                    $percentage =
                                                        $totalBookingStatus > 0
                                                            ? ($count / $totalBookingStatus) * 100
                                                            : 0;
                                                    $color = $statusColors[$status] ?? '#888';
                                                    $statusLabel = ucfirst($status);
                                                @endphp
                                                <div class="col-md-6 col-lg-3 mb-3">
                                                    <div class="d-flex flex-column">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <div
                                                                style="width: 15px; height: 15px; background-color: {{ $color }}; margin-right: 8px; border-radius: 2px;">
                                                            </div>
                                                            <div class="fw-semibold fs-6">{{ $statusLabel }}</div>
                                                        </div>
                                                        <div class="progress" style="height: 12px;">
                                                            <div class="progress-bar status-bar" role="progressbar"
                                                                data-status="{{ $status }}"
                                                                data-percentage="{{ $percentage }}"
                                                                data-color="{{ $color }}"
                                                                data-count="{{ $count }}"
                                                                style="width: {{ $percentage }}%; background-color: {{ $color }};"
                                                                aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                                aria-valuemax="100"></div>
                                                        </div>
                                                        <div class="small mt-1 text-muted fw-semibold">
                                                            {{ number_format($percentage, 1) }}% ({{ $count }})
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p>No booking status data available.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Performing Workers -->
        <div class="row mb-4">
            <div class="col-12">
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
                                <li><a class="dropdown-item active" href="#" data-metric="revenue">By
                                        Revenue</a>
                                </li>
                                <li><a class="dropdown-item" href="#" data-metric="bookings">By Bookings</a>
                                </li>
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
                                            <td class="text-end pe-4">₱{{ number_format($worker->total_revenue, 2) }}
                                            </td>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Render the ray chart for booking status
            renderRayChart();

            // Animate bar chart on load
            animateBarChart();

            // Handle time range dropdown
            const timeRangeDropdown = document.querySelectorAll('#timeRangeDropdown + .dropdown-menu a');
            timeRangeDropdown.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const range = this.getAttribute('data-range');
                    const text = this.textContent.trim();
                    document.getElementById('timeRangeDropdown').innerHTML =
                        `<i class="fas fa-calendar-alt me-1"></i> ${text}`;

                    // Remove active class from all items
                    timeRangeDropdown.forEach(el => el.classList.remove('active'));
                    // Add active class to clicked item
                    this.classList.add('active');

                    // Here you would typically reload the data based on the range
                    // window.location.href = '?range=' + range;
                });
            });

            // Handle worker metrics dropdown
            const workerMetricDropdown = document.querySelectorAll('#workerMetricDropdown + .dropdown-menu a');
            workerMetricDropdown.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const metric = this.getAttribute('data-metric');
                    const text = this.textContent.trim();
                    document.getElementById('workerMetricDropdown').innerHTML = text;

                    // Remove active class from all items
                    workerMetricDropdown.forEach(el => el.classList.remove('active'));
                    // Add active class to clicked item
                    this.classList.add('active');

                    // Here you would typically reload or resort the data based on the metric
                    // window.location.href = '?metric=' + metric;
                });
            });
        });

        function animateBarChart() {
            const bars = document.querySelectorAll('.revenue-trend-chart .bar');

            bars.forEach((bar, index) => {
                // Store the original height
                const targetHeight = bar.style.height;
                // Start with zero height
                bar.style.height = '0%';

                // Animate to the target height with a staggered delay
                setTimeout(() => {
                    bar.style.transition = 'height 0.8s ease-out';
                    bar.style.height = targetHeight;
                }, index * 50); // Staggered delay of 50ms per bar
            });
        }

        function renderRayChart() {
            const rayContainer = document.querySelector('.ray-slices');
            if (!rayContainer) return;

            // Clear the container
            rayContainer.innerHTML = '';

            // Get all status bars to match the data
            const statusBars = document.querySelectorAll('.status-bar');
            const statusData = [];

            statusBars.forEach(bar => {
                statusData.push({
                    status: bar.getAttribute('data-status'),
                    percentage: parseFloat(bar.getAttribute('data-percentage')),
                    color: bar.getAttribute('data-color'),
                    count: bar.getAttribute('data-count')
                });
            });

            // Sort by percentage descending to ensure larger sectors are drawn first
            statusData.sort((a, b) => b.percentage - a.percentage);

            // Create SVG for ray chart
            const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            svg.setAttribute('viewBox', '0 0 100 100');
            svg.setAttribute('width', '100%');
            svg.setAttribute('height', '100%');
            svg.style.position = 'absolute';
            svg.style.top = '0';
            svg.style.left = '0';

            // Calculate total percentage (should be 100 but just in case)
            let totalPercentage = statusData.reduce((sum, item) => sum + item.percentage, 0);

            // Create sectors
            let startAngle = 0;
            statusData.forEach(item => {
                const sliceAngle = (item.percentage / totalPercentage) * 360;
                const endAngle = startAngle + sliceAngle;

                // Create sector path
                const sector = createSectorPath(50, 50, 30, 50, startAngle, endAngle);

                // Set attributes
                sector.setAttribute('fill', item.color);
                sector.setAttribute('stroke', '#fff');
                sector.setAttribute('stroke-width', '0.5');
                sector.setAttribute('data-bs-toggle', 'tooltip');
                sector.setAttribute('data-bs-title',
                    `${item.status.charAt(0).toUpperCase() + item.status.slice(1)}: ${item.count} (${item.percentage.toFixed(1)}%)`
                );

                // Add hover effects
                sector.style.transition = 'transform 0.2s ease';
                sector.addEventListener('mouseover', function() {
                    this.style.transform = 'scale(1.05)';
                    this.style.transformOrigin = '50px 50px';
                    this.setAttribute('stroke-width', '1');
                });

                sector.addEventListener('mouseout', function() {
                    this.style.transform = 'scale(1)';
                    this.setAttribute('stroke-width', '0.5');
                });

                svg.appendChild(sector);

                // Update start angle for next sector
                startAngle = endAngle;
            });

            rayContainer.appendChild(svg);

            // Initialize tooltips for the new elements
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(tooltipTriggerEl => {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        function createSectorPath(cx, cy, innerRadius, outerRadius, startAngle, endAngle) {
            // Convert angles from degrees to radians
            const startRad = (startAngle - 90) * Math.PI / 180;
            const endRad = (endAngle - 90) * Math.PI / 180;

            // Calculate coordinates
            const startOuterX = cx + outerRadius * Math.cos(startRad);
            const startOuterY = cy + outerRadius * Math.sin(startRad);
            const endOuterX = cx + outerRadius * Math.cos(endRad);
            const endOuterY = cy + outerRadius * Math.sin(endRad);

            const startInnerX = cx + innerRadius * Math.cos(startRad);
            const startInnerY = cy + innerRadius * Math.sin(startRad);
            const endInnerX = cx + innerRadius * Math.cos(endRad);
            const endInnerY = cy + innerRadius * Math.sin(endRad);

            // Determine which arc to take (large or small)
            const largeArcFlag = endAngle - startAngle > 180 ? 1 : 0;

            // Create path
            const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');

            // Move to start of outer arc
            let d = `M ${startOuterX} ${startOuterY}`;

            // Draw outer arc
            d += ` A ${outerRadius} ${outerRadius} 0 ${largeArcFlag} 1 ${endOuterX} ${endOuterY}`;

            // Draw line to inner point
            d += ` L ${endInnerX} ${endInnerY}`;

            // Draw inner arc
            d += ` A ${innerRadius} ${innerRadius} 0 ${largeArcFlag} 0 ${startInnerX} ${startInnerY}`;

            // Close path
            d += ' Z';

            path.setAttribute('d', d);
            return path;
        }
    </script>

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
            overflow: hidden;
        }

        .progress-bar {
            border-radius: 1rem;
            transition: width 0.6s ease;
        }

        .avatar img {
            object-fit: cover;
        }

        /* Revenue Chart Styling */
        .revenue-trend-chart .chart-container {
            padding-left: 40px;
        }

        .revenue-trend-chart .bar {
            transition: height 0.8s ease-out, opacity 0.3s, transform 0.3s;
        }

        .revenue-trend-chart .bar:hover {
            opacity: 0.8;
            transform: scaleY(1.05);
            transform-origin: bottom;
        }

        .revenue-trend-chart .y-axis-labels {
            width: 60px;
            left: -10px;
        }

        .service-distribution-chart .progress-bar {
            transition: width 0.8s ease-out;
        }

        .ray-chart-container {
            position: relative;
            border-radius: 50%;
            overflow: visible;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-color: #f8f9fa;
        }

        .ray-slices {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .ray-slices svg path {
            transition: transform 0.3s ease, stroke-width 0.2s ease;
            transform-origin: center;
        }

        .ray-chart-container:hover .donut-hole {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .donut-hole {
                width: 55% !important;
                height: 55% !important;
            }

            .donut-hole .text-muted.small {
                font-size: 10px;
            }

            .donut-hole .fw-bold {
                font-size: 14px;
            }
        }

        /* Print Styling */
        @media print {

            .btn,
            .dropdown {
                display: none !important;
            }

            .card {
                break-inside: avoid;
                box-shadow: none !important;
                transform: none !important;
            }

            .container-fluid {
                width: 100% !important;
                padding: 0 !important;
            }

            /* Ensure charts print properly */
            .chart-container,
            .pie-chart-container {
                page-break-inside: avoid;
            }
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .revenue-trend-chart .bar-container {
                min-width: 5px !important;
            }

            .revenue-trend-chart .chart-container {
                padding-left: 30px;
            }

            .revenue-trend-chart .y-axis-labels {
                width: 40px;
                font-size: 10px;
            }

            .bar-label,
            .count-label {
                font-size: 10px;
            }
        }
    </style>
@endsection
