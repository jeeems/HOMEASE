@extends('layouts.admin')

@section('content')
    <div class="container-fluid p-4">
        <!-- Bookings Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <h2 class="fw-bold text-primary m-0 mb-3 mb-md-0">
                <i class="fas fa-calendar-check me-2"></i>Bookings Management
            </h2>
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center">
                <span class="text-muted me-md-3 mb-2 mb-md-0">
                    <i class="far fa-calendar-alt me-1"></i>{{ now()->format('l, F d, Y') }}
                </span>
                <div>
                    <a href="{{ route('admin.bookings') }}"
                        class="btn btn-outline-primary me-2 mb-2 mb-md-0 rounded-pill px-3">
                        <i class="fas fa-sync-alt me-1"></i> Refresh
                    </a>
                    <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary rounded-pill px-3 shadow-sm">
                        <i class="fas fa-plus me-1"></i> New Booking
                    </a>
                </div>
            </div>
        </div>

        <!-- Bookings Table Card -->
        <div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
            <div
                class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center py-3 border-0">
                <h5 class="mb-3 mb-md-0 fw-bold">All Bookings</h5>
                <div class="d-flex flex-column flex-sm-row w-100 w-md-auto">
                    <!-- Search Form -->
                    <div class="input-group me-sm-3 mb-2 mb-sm-0">
                        <form action="{{ route('admin.bookings') }}" method="GET"
                            class="input-group me-sm-3 mb-2 mb-sm-0">
                            @foreach (request()->except('search', 'page') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach

                            <input type="text" class="form-control border-end-0" placeholder="Search bookings..."
                                id="searchInput" name="search" value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary border-start-0 bg-white" type="submit"
                                id="searchButton">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Status Filter Dropdown -->
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
                                    href="{{ route('admin.bookings', array_merge(request()->except('status'), ['status' => 'all'])) }}">
                                    <i class="fas fa-list-ul me-2 text-muted"></i>All</a>
                            </li>
                            <li><a class="dropdown-item"
                                    href="{{ route('admin.bookings', array_merge(request()->except('status'), ['status' => 'pending'])) }}">
                                    <i class="fas fa-clock me-2 text-warning"></i>Pending</a>
                            </li>
                            <li><a class="dropdown-item"
                                    href="{{ route('admin.bookings', array_merge(request()->except('status'), ['status' => 'ongoing'])) }}">
                                    <i class="fas fa-sync-alt me-2 text-info"></i>Ongoing</a>
                            </li>
                            <li><a class="dropdown-item"
                                    href="{{ route('admin.bookings', array_merge(request()->except('status'), ['status' => 'completed'])) }}">
                                    <i class="fas fa-check-circle me-2 text-success"></i>Completed</a>
                            </li>
                            <li><a class="dropdown-item"
                                    href="{{ route('admin.bookings', array_merge(request()->except('status'), ['status' => 'cancelled'])) }}">
                                    <i class="fas fa-times-circle me-2 text-danger"></i>Cancelled</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Bookings Table -->
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
                            @foreach ($bookings as $booking)
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
                                            <div class="avatar me-2 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 38px; height: 38px; font-size: 14px;">
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
                                                        href="{{ route('admin.bookings.show', $booking->id) }}">
                                                        <i class="fas fa-eye me-2 text-primary"></i> View</a></li>
                                                <li><a class="dropdown-item"
                                                        href="{{ route('admin.bookings.edit', $booking->id) }}">
                                                        <i class="fas fa-edit me-2 text-info"></i> Edit</a></li>
                                                @if ($booking->status == 'pending')
                                                    <li><a class="dropdown-item text-success"
                                                            href="{{ route('admin.bookings.approve', $booking->id) }}">
                                                            <i class="fas fa-check me-2"></i> Approve</a></li>
                                                @endif
                                                @if ($booking->status != 'cancelled' && $booking->status != 'completed')
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.bookings.cancel', $booking->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-times me-2"></i> Cancel
                                                            </button>
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

            <!-- Pagination and Showing Results -->
            <div class="card-footer bg-white py-3 border-0">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="text-muted small mb-3 mb-md-0">
                        Showing <span
                            class="fw-semibold">{{ $bookings->firstItem() ?: 0 }}-{{ $bookings->lastItem() ?: 0 }}</span>
                        of <span class="fw-semibold">{{ $bookings->total() }}</span> bookings
                        @if (request('status') && request('status') != 'all')
                            <span class="text-muted">(filtered by {{ request('status') }})</span>
                        @endif
                        @if (request('search'))
                            <span class="text-muted">(search: "{{ request('search') }}")</span>
                        @endif
                    </div>
                    {{ $bookings->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');

            function handleSearch() {
                const searchForm = document.querySelector('form');
                searchForm.submit();
            }

            searchButton.addEventListener('click', function(e) {
                e.preventDefault();
                handleSearch();
            });

            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    handleSearch();
                }
            });
        });
    </script>
@endsection
