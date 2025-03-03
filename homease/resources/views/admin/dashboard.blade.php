@extends('layouts.admin')

@section('content')
    <div class="container-fluid p-4">
        <!-- Dashboard Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <h2 class="fw-bold text-primary m-0 mb-3 mb-md-0">
                <i class="fas fa-tachometer-alt me-2"></i>Homease Dashboard
            </h2>
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center">
                <span class="text-muted me-md-3 mb-2 mb-md-0">
                    <i class="far fa-calendar-alt me-1"></i>{{ now()->format('l, F d, Y') }}
                </span>
                <div>
                    <button class="btn btn-outline-primary me-2 mb-2 mb-md-0 rounded-pill px-3">
                        <i class="fas fa-sync-alt me-1"></i> Refresh
                    </button>
                    <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary rounded-pill px-3 shadow-sm">
                        <i class="fas fa-plus me-1"></i> New Booking
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar Navigation - Hidden on mobile -->
            <div class="col-lg-3 col-md-4 mb-4 d-none d-md-block">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('admin.dashboard') }}"
                                class="list-group-item list-group-item-action active border-0 d-flex align-items-center py-3 px-4">
                                <i class="fas fa-tachometer-alt me-3"></i> Dashboard
                            </a>
                            <a href="{{ route('admin.users') }}"
                                class="list-group-item list-group-item-action border-0 d-flex align-items-center py-3 px-4">
                                <i class="fas fa-users me-3"></i> Users
                            </a>
                            <a href="{{ route('admin.bookings') }}"
                                class="list-group-item list-group-item-action border-0 d-flex align-items-center py-3 px-4">
                                <i class="fas fa-calendar-check me-3"></i> Bookings
                            </a>
                            <a href="{{ route('admin.services') }}"
                                class="list-group-item list-group-item-action border-0 d-flex align-items-center py-3 px-4">
                                <i class="fas fa-tools me-3"></i> Services
                            </a>
                            <a href="{{ route('admin.ratings') }}"
                                class="list-group-item list-group-item-action border-0 d-flex align-items-center py-3 px-4">
                                <i class="fas fa-star me-3"></i> Ratings
                            </a>
                            <a href="{{ route('admin.reports') }}"
                                class="list-group-item list-group-item-action border-0 d-flex align-items-center py-3 px-4">
                                <i class="fas fa-chart-bar me-3"></i> Reports
                            </a>
                            <a href="{{ route('admin.settings') }}"
                                class="list-group-item list-group-item-action border-0 d-flex align-items-center py-3 px-4">
                                <i class="fas fa-cog me-3"></i> Settings
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-4 rounded-3 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="mb-0 fw-bold">Worker Status</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <div
                                class="list-group-item border-0 d-flex justify-content-between align-items-center py-3 px-4">
                                <span><i class="fas fa-wrench text-primary me-2"></i>Plumbing</span>
                                <span
                                    class="badge bg-primary rounded-pill">{{ App\Models\User::whereHas('workerVerification', function ($q) {$q->where('service_type', 'Plumbing');})->count() }}</span>
                            </div>
                            <div
                                class="list-group-item border-0 d-flex justify-content-between align-items-center py-3 px-4">
                                <span><i class="fas fa-hammer text-success me-2"></i>Carpentry</span>
                                <span
                                    class="badge bg-success rounded-pill">{{ App\Models\User::whereHas('workerVerification', function ($q) {$q->where('service_type', 'Carpentry');})->count() }}</span>
                            </div>
                            <div
                                class="list-group-item border-0 d-flex justify-content-between align-items-center py-3 px-4">
                                <span><i class="fas fa-broom text-info me-2"></i>Home Cleaning</span>
                                <span
                                    class="badge bg-info rounded-pill">{{ App\Models\User::whereHas('workerVerification', function ($q) {$q->where('service_type', 'Home Cleaning');})->count() }}</span>
                            </div>
                            <div
                                class="list-group-item border-0 d-flex justify-content-between align-items-center py-3 px-4">
                                <span><i class="fas fa-bolt text-warning me-2"></i>Electrician</span>
                                <span
                                    class="badge bg-warning rounded-pill">{{ App\Models\User::whereHas('workerVerification', function ($q) {$q->where('service_type', 'Electrician');})->count() }}</span>
                            </div>
                            <div
                                class="list-group-item border-0 d-flex justify-content-between align-items-center py-3 px-4">
                                <span><i class="fas fa-child text-danger me-2"></i>Daycare</span>
                                <span
                                    class="badge bg-danger rounded-pill">{{ App\Models\User::whereHas('workerVerification', function ($q) {$q->where('service_type', 'Daycare');})->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-8 col-12">
                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                        <div class="card border-0 shadow-sm h-100 rounded-3 hover-shadow transition-all">
                            <div class="card-body d-flex flex-column p-4">
                                <div class="d-flex justify-content-between mb-3">
                                    <h6 class="text-muted fw-normal">Total Users</h6>
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        <i class="fas fa-users text-primary"></i>
                                    </div>
                                </div>
                                <h3 class="fw-bold mb-0 display-6">{{ $totalUsers }}</h3>
                                <div class="mt-3">
                                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill">
                                        <i class="fas fa-arrow-up me-1"></i>
                                        {{ number_format((($totalUsers - $previousMonthUsers) / max(1, $previousMonthUsers)) * 100, 1) }}%
                                    </span>
                                    <span class="text-muted ms-2 small">this month</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                        <div class="card border-0 shadow-sm h-100 rounded-3 hover-shadow transition-all">
                            <div class="card-body d-flex flex-column p-4">
                                <div class="d-flex justify-content-between mb-3">
                                    <h6 class="text-muted fw-normal">Active Bookings</h6>
                                    <div class="rounded-circle bg-success bg-opacity-10 p-2 d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        <i class="fas fa-calendar-check text-success"></i>
                                    </div>
                                </div>
                                <h3 class="fw-bold mb-0 display-6">{{ $activeBookings }}</h3>
                                <div class="mt-3">
                                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill">
                                        <i class="fas fa-arrow-up me-1"></i>
                                        {{ number_format((($activeBookings - $previousWeekActiveBookings) / max(1, $previousWeekActiveBookings)) * 100, 1) }}%
                                    </span>
                                    <span class="text-muted ms-2 small">this week</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                        <div class="card border-0 shadow-sm h-100 rounded-3 hover-shadow transition-all">
                            <div class="card-body d-flex flex-column p-4">
                                <div class="d-flex justify-content-between mb-3">
                                    <h6 class="text-muted fw-normal">Pending</h6>
                                    <div class="rounded-circle bg-warning bg-opacity-10 p-2 d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        <i class="fas fa-clock text-warning"></i>
                                    </div>
                                </div>
                                <h3 class="fw-bold mb-0 display-6">{{ $pendingRequests }}</h3>
                                <div class="mt-3">
                                    <span
                                        class="badge {{ $pendingRequests - $yesterdayPendingRequests > 0 ? 'bg-danger bg-opacity-10 text-danger' : 'bg-success bg-opacity-10 text-success' }} px-2 py-1 rounded-pill">
                                        <i
                                            class="fas fa-{{ $pendingRequests - $yesterdayPendingRequests > 0 ? 'arrow-up' : 'arrow-down' }} me-1"></i>
                                        {{ abs(number_format((($pendingRequests - $yesterdayPendingRequests) / max(1, $yesterdayPendingRequests)) * 100, 1)) }}%
                                    </span>
                                    <span class="text-muted ms-2 small">since yesterday</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                        <div class="card border-0 shadow-sm h-100 rounded-3 hover-shadow transition-all">
                            <div class="card-body d-flex flex-column p-4">
                                <div class="d-flex justify-content-between mb-3">
                                    <h6 class="text-muted fw-normal">Revenue</h6>
                                    <div class="rounded-circle bg-info bg-opacity-10 p-2 d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        <i class="fas fa-peso-sign text-info"></i>
                                    </div>
                                </div>
                                <h3 class="fw-bold mb-0 display-6">₱{{ number_format($totalRevenue, 0) }}</h3>
                                <div class="mt-3">
                                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill">
                                        <i class="fas fa-arrow-up me-1"></i>
                                        {{ number_format((($totalRevenue - $lastMonthRevenue) / max(1, $lastMonthRevenue)) * 100, 1) }}%
                                    </span>
                                    <span class="text-muted ms-2 small">this month</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bookings Table -->
                <div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
                    <div
                        class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center py-3 border-0">
                        <h5 class="mb-3 mb-md-0 fw-bold">Recent Bookings</h5>
                        <div class="d-flex flex-column flex-sm-row w-100 w-md-auto">
                            <div class="input-group me-sm-3 mb-2 mb-sm-0">
                                <form action="{{ route('admin.dashboard') }}" method="GET"
                                    class="input-group me-sm-3 mb-2 mb-sm-0">
                                    <!-- Preserve any existing query parameters except 'search' -->
                                    @foreach (request()->except('search', 'page') as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach

                                    <input type="text" class="form-control border-end-0"
                                        placeholder="Search bookings..." id="searchInput" name="search"
                                        value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary border-start-0 bg-white" type="submit"
                                        id="searchButton">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle rounded-pill" type="button"
                                    id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter me-1"></i>
                                    @if (request('status') == 'pending')
                                        Pending
                                    @elseif(request('status') == 'ongoing')
                                        Ongoing
                                    @elseif(request('status') == 'completed')
                                        Completed
                                    @elseif(request('status') == 'cancelled')
                                        Cancelled
                                    @else
                                        Filter
                                    @endif
                                </button>
                                <ul class="dropdown-menu shadow-sm border-0" aria-labelledby="filterDropdown">
                                    <li><a class="dropdown-item"
                                            href="{{ route('admin.dashboard', array_merge(request()->except('status'), ['status' => 'all'])) }}"><i
                                                class="fas fa-list-ul me-2 text-muted"></i>All</a></li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('admin.dashboard', array_merge(request()->except('status'), ['status' => 'pending'])) }}"><i
                                                class="fas fa-clock me-2 text-warning"></i>Pending</a></li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('admin.dashboard', array_merge(request()->except('status'), ['status' => 'ongoing'])) }}"><i
                                                class="fas fa-sync-alt me-2 text-info"></i>Ongoing</a></li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('admin.dashboard', array_merge(request()->except('status'), ['status' => 'completed'])) }}"><i
                                                class="fas fa-check-circle me-2 text-success"></i>Completed</a></li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('admin.dashboard', array_merge(request()->except('status'), ['status' => 'cancelled'])) }}"><i
                                                class="fas fa-times-circle me-2 text-danger"></i>Cancelled</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3">#</th>
                                        <th class="py-3">Customer</th>
                                        <th class="py-3">Service</th>
                                        <th class="py-3">Worker</th>
                                        <th class="py-3">Date</th>
                                        <th class="py-3">Amount</th>
                                        <th class="py-3">Status</th>
                                        <th class="text-end pe-4 py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentBookings as $booking)
                                        <tr>
                                            <td class="ps-4">{{ $booking->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-2 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                        style="width: 38px; height: 38px; font-size: 14px;">
                                                        @if ($booking->client->profile->profile_picture)
                                                            <img src="{{ asset('storage/' . $booking->client->profile->profile_picture) }}"
                                                                alt="{{ $booking->client->full_name }}"
                                                                class="w-100 h-100 rounded-circle object-fit-cover">
                                                        @else
                                                            <span
                                                                class="text-white bg-primary d-flex align-items-center justify-content-center w-100 h-100 rounded-circle">
                                                                {{ substr($booking->client->full_name, 0, 1) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">{{ $booking->client->full_name }}</div>
                                                        <div class="small text-muted">{{ $booking->client->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge
                                            @if ($booking->service_type == 'Plumbing') bg-primary
                                            @elseif($booking->service_type == 'Carpentry') bg-success
                                            @elseif($booking->service_type == 'Home Cleaning') bg-info
                                            @elseif($booking->service_type == 'Electrician') bg-warning
                                            @else bg-secondary @endif
                                            bg-opacity-10
                                            @if ($booking->service_type == 'Plumbing') text-primary
                                            @elseif($booking->service_type == 'Carpentry') text-success
                                            @elseif($booking->service_type == 'Home Cleaning') text-info
                                            @elseif($booking->service_type == 'Electrician') text-warning
                                            @else text-secondary @endif
                                            px-2 py-1 rounded-pill">
                                                    {{ $booking->service_type }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-2 bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                        style="width: 32px; height: 32px; font-size: 14px;">
                                                        @if ($booking->worker->profile->profile_picture)
                                                            <img src="{{ asset('storage/' . $booking->worker->profile->profile_picture) }}"
                                                                alt="{{ $booking->worker->full_name }}"
                                                                class="w-100 h-100 rounded-circle object-fit-cover">
                                                        @else
                                                            <span
                                                                class="text-white bg-primary d-flex align-items-center justify-content-center w-100 h-100 rounded-circle">
                                                                {{ substr($booking->worker->full_name, 0, 1) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="small">{{ $booking->worker->full_name }}</div>
                                                </div>
                                            </td>
                                            <td>{{ $booking->scheduled_date->format('M d, Y') }}</td>
                                            <td>₱{{ number_format($booking->total_amount ?? ($booking->hours_worked * $booking->worker->hourly_rate ?? 0), 2) }}
                                            </td>
                                            <td>
                                                @if ($booking->status == 'completed')
                                                    <span
                                                        class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Completed</span>
                                                @elseif($booking->status == 'pending')
                                                    <span
                                                        class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">Pending</span>
                                                @elseif($booking->status == 'ongoing')
                                                    <span
                                                        class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">Ongoing</span>
                                                @elseif($booking->status == 'cancelled')
                                                    <span
                                                        class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Cancelled</span>
                                                @else
                                                    <span
                                                        class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">{{ ucfirst($booking->status) }}</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                                                        type="button" id="actionDropdown{{ $booking->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-h"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                                                        aria-labelledby="actionDropdown{{ $booking->id }}">
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('admin.bookings.show', $booking->id) }}"><i
                                                                    class="fas fa-eye me-2 text-primary"></i> View</a></li>
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('admin.bookings.edit', $booking->id) }}"><i
                                                                    class="fas fa-edit me-2 text-info"></i> Edit</a></li>
                                                        @if ($booking->status == 'pending')
                                                            <li><a class="dropdown-item text-success"
                                                                    href="{{ route('admin.bookings.approve', $booking->id) }}"><i
                                                                        class="fas fa-check me-2"></i> Approve</a></li>
                                                        @endif
                                                        @if ($booking->status != 'cancelled' && $booking->status != 'completed')
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li>
                                                                <form
                                                                    action="{{ route('admin.bookings.cancel', $booking->id) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit"
                                                                        class="dropdown-item text-danger"><i
                                                                            class="fas fa-times me-2"></i> Cancel</button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white py-3 border-0">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                            <div class="text-muted small mb-3 mb-md-0">
                                Showing <span
                                    class="fw-semibold">{{ $recentBookings->firstItem() ?: 0 }}-{{ $recentBookings->lastItem() ?: 0 }}</span>
                                of <span
                                    class="fw-semibold">{{ isset($filteredTotal) ? $filteredTotal : $totalBookings }}</span>
                                bookings
                                @if (request('status') && request('status') != 'all')
                                    <span class="text-muted">(filtered by {{ request('status') }})</span>
                                @endif
                                @if (request('search'))
                                    <span class="text-muted">(search: "{{ request('search') }}")</span>
                                @endif
                            </div>
                            {{ $recentBookings->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>

                <!-- Bottom Stats Row -->
                <div class="row">
                    <!-- Recent Reviews -->
                    <div class="col-lg-8 mb-4">
                        <div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
                            <div
                                class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-0">
                                <h5 class="mb-0 fw-bold">Recent Reviews</h5>
                                <a href="{{ route('admin.ratings') }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fas fa-external-link-alt me-1"></i> View All
                                </a>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    @foreach ($recentRatings as $rating)
                                        <div class="list-group-item p-4 border-0 border-bottom">
                                            <div class="d-flex flex-column flex-md-row justify-content-between mb-3">
                                                <div class="d-flex align-items-center mb-2 mb-md-0">
                                                    <div class="avatar me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                        style="width: 42px; height: 42px; font-size: 16px;">
                                                        {{ substr($rating->client->full_name ?? 'U', 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">
                                                            {{ $rating->client->full_name ?? 'Unknown User' }}</div>
                                                        <div class="small text-muted">
                                                            <i
                                                                class="far fa-calendar-alt me-1"></i>{{ $rating->created_at->format('M d, Y') }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-warning">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $rating->rating)
                                                            <i class="fas fa-star"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                    <span
                                                        class="ms-1 text-dark fw-semibold">{{ $rating->rating }}.0</span>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <span
                                                    class="badge
                                    @if ($rating->worker->workerVerification->service_type == 'Plumbing') bg-primary
                                    @elseif($rating->worker->workerVerification->service_type == 'Carpentry') bg-success
                                    @elseif($rating->worker->workerVerification->service_type == 'Home Cleaning') bg-info
                                    @elseif($rating->worker->workerVerification->service_type == 'Electrician') bg-warning
                                    @else bg-secondary @endif
                                    bg-opacity-10
                                    @if ($rating->worker->workerVerification->service_type == 'Plumbing') text-primary
                                    @elseif($rating->worker->workerVerification->service_type == 'Carpentry') text-success
                                    @elseif($rating->worker->workerVerification->service_type == 'Home Cleaning') text-info
                                    @elseif($rating->worker->workerVerification->service_type == 'Electrician') text-warning
                                    @else text-secondary @endif
                                    px-2 py-1 rounded-pill">
                                                    {{ $rating->worker->workerVerification->service_type ?? 'Service' }}
                                                </span>
                                                <span class="ms-2 fw-semibold">{{ $rating->booking_title }}</span>
                                            </div>
                                            <p class="mb-3 text-muted">{{ $rating->comment }}</p>
                                            @if ($rating->review_photos && json_decode($rating->review_photos) && count(json_decode($rating->review_photos)) > 0)
                                                <div class="d-flex mt-2 flex-wrap">
                                                    @foreach (json_decode($rating->review_photos) as $photo)
                                                        <div class="me-2 mb-2"
                                                            style="width: 70px; height: 70px; overflow: hidden; border-radius: 8px;">
                                                            <img src="{{ asset('storage/' . $photo) }}"
                                                                alt="Review photo" class="img-fluid"
                                                                style="object-fit: cover; width: 100%; height: 100%;">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            <div class="mt-2">
                                                <div class="small text-muted">Worker: <a
                                                        href="{{ route('admin.workers.show', $rating->worker_id) }}"
                                                        class="text-decoration-none fw-semibold">{{ $rating->worker->full_name }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Rated Workers -->
                    <div class="col-lg-4 mb-4">
                        <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
                            <div class="card-header bg-white py-3 border-0">
                                <h5 class="mb-0 fw-bold">Top Rated Workers</h5>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    @foreach ($topRatedWorkers as $worker)
                                        <li class="list-group-item px-4 py-3 border-0 border-bottom">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-3 bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                        style="width: 46px; height: 46px; font-size: 18px;">
                                                        {{ substr($worker->full_name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1 fw-semibold">{{ $worker->full_name }}</h6>
                                                        <div class="d-flex align-items-center">
                                                            <div class="text-warning me-2">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($i <= $worker->average_rating)
                                                                        <i class="fas fa-star"></i>
                                                                    @elseif($i - 0.5 <= $worker->average_rating)
                                                                        <i class="fas fa-star-half-alt"></i>
                                                                    @else
                                                                        <i class="far fa-star"></i>
                                                                    @endif
                                                                @endfor
                                                            </div>
                                                            <span
                                                                class="small text-muted">{{ number_format($worker->average_rating, 1) }}
                                                                ({{ $worker->ratings_count }})
                                                            </span>
                                                        </div>
                                                        <div class="small mt-1">
                                                            <span
                                                                class="badge
                                                @if ($worker->service_type == 'Plumbing') bg-primary
                                                @elseif($worker->service_type == 'Carpentry') bg-success
                                                @elseif($worker->service_type == 'Home Cleaning') bg-info
                                                @elseif($worker->service_type == 'Electrician') bg-warning
                                                @else bg-secondary @endif
                                                bg-opacity-10
                                                @if ($worker->service_type == 'Plumbing') text-primary
                                                @elseif($worker->service_type == 'Carpentry') text-success
                                                @elseif($worker->service_type == 'Home Cleaning') text-info
                                                @elseif($worker->service_type == 'Electrician') text-warning
                                                @else text-secondary @endif
                                                px-2 py-1 rounded-pill">
                                                                {{ $worker->service_type }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="card-footer bg-white text-center py-3 border-0">
                                <a href="{{ route('admin.workers') }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-4">
                                    View all workers <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Add hover effect for cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.hover-shadow');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                    this.style.boxShadow = '0 10px 20px rgba(0,0,0,0.1)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 .125rem .25rem rgba(0,0,0,.075)';
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, setting up search handlers');

            // Get the search input and button
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');

            if (!searchInput || !searchButton) {
                console.error('Search input or button not found in the DOM');
                return;
            }

            console.log('Search elements found, adding event listeners');

            // Function to handle search
            function handleSearch() {
                console.log('Search triggered with value:', searchInput.value);

                // Create a form dynamically
                const form = document.createElement('form');
                form.method = 'GET';
                form.action = window.location.pathname; // Current URL path

                // Add the search parameter
                const searchParam = document.createElement('input');
                searchParam.type = 'hidden';
                searchParam.name = 'search';
                searchParam.value = searchInput.value.trim();
                form.appendChild(searchParam);

                // Add all other existing query parameters (except search and page)
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.forEach((value, key) => {
                    if (key !== 'search' && key !== 'page') {
                        const param = document.createElement('input');
                        param.type = 'hidden';
                        param.name = key;
                        param.value = value;
                        form.appendChild(param);
                    }
                });

                // Append the form to the body and submit it
                document.body.appendChild(form);
                form.submit();
            }

            // Add click event listener to the search button
            searchButton.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Search button clicked');
                handleSearch();
            });

            // Add keypress event listener to the search input for Enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    console.log('Enter key pressed in search input');
                    handleSearch();
                }
            });

            console.log('Event listeners added successfully');
        });
    </script>
    <style>
        .transition-all {
            transition: all 0.3s ease;
        }

        .hover-shadow {
            transition: all 0.3s ease;
        }

        .avatar {
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
