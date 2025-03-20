@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Booking Details</h1>
            <div>
                <a href="{{ route('admin.bookings') }}" class="btn btn-outline-secondary rounded-pill">
                    <i class="fas fa-arrow-left me-1"></i> Back to Bookings
                </a>

                @if ($booking->status == 'pending')
                    <form action="{{ route('admin.bookings.approve', $booking->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success rounded-pill ms-2">
                            <i class="fas fa-check me-1"></i> Approve Booking
                        </button>
                    </form>
                @endif

                <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="btn btn-primary rounded-pill ms-2">
                    <i class="fas fa-edit me-1"></i> Edit Booking
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="card-title mb-0 fw-bold">Booking #{{ $booking->id }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <h6 class="text-muted mb-2">Service Details</h6>
                                <div class="d-flex flex-column">
                                    <div class="mb-3">
                                        <span class="text-muted">Service Type:</span>
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
                                        px-2 py-1 rounded-pill ms-2">
                                            {{ $booking->service_type }}
                                        </span>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-muted">Status:</span>
                                        <span
                                            class="ms-2 badge
                                        @if ($booking->status == 'completed') bg-success bg-opacity-10 text-success
                                        @elseif($booking->status == 'pending') bg-warning bg-opacity-10 text-warning
                                        @elseif($booking->status == 'ongoing') bg-info bg-opacity-10 text-info
                                        @elseif($booking->status == 'cancelled') bg-danger bg-opacity-10 text-danger
                                        @else bg-secondary bg-opacity-10 text-secondary @endif
                                        px-3 py-2 rounded-pill">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-muted">Description:</span>
                                        <p class="mb-0 mt-1">{{ $booking->notes ?? 'No description provided' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">Schedule Information</h6>
                                <div class="d-flex flex-column">
                                    <div class="mb-3">
                                        <span class="text-muted">Scheduled Date:</span>
                                        <span
                                            class="ms-2 fw-semibold">{{ $booking->scheduled_date->format('F d, Y') }}</span>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-muted">Scheduled Time:</span>
                                        <span
                                            class="ms-2 fw-semibold">{{ $booking->scheduled_date->format('h:i A') ?? 'Flexible' }}</span>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-muted">Duration:</span>
                                        <span class="ms-2 fw-semibold">{{ $booking->hours_worked ?? 'To be determined' }}
                                            hours</span>
                                    </div>
                                    <div>
                                        <span class="text-muted">Created At:</span>
                                        <span class="ms-2">{{ $booking->created_at->format('M d, Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <h6 class="text-muted mb-2">Payment Details</h6>
                                <div class="d-flex flex-column">
                                    <div class="mb-3">
                                        <span class="text-muted">Total Amount:</span>
                                        <span
                                            class="ms-2 fs-5 fw-bold">₱{{ number_format($booking->total_amount ?? ($booking->hours_worked * $booking->worker->hourly_rate ?? 0), 2) }}</span>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-muted">Hourly Rate:</span>
                                        <span
                                            class="ms-2">₱{{ number_format($booking->worker->hourly_rate ?? 0, 2) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-muted">Payment Status:</span>
                                        <span
                                            class="ms-2 badge
    @if ($booking->completion_date) bg-success
    @else bg-warning @endif
    px-2 py-1 rounded-pill">
                                            {{ $booking->completion_date ? 'Paid' : 'Pending' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">Location</h6>
                                <div class="d-flex flex-column">
                                    <div class="mb-3">
                                        <span class="text-muted">Address:</span>
                                        <p class="mb-0 mt-1">
                                            {{ $booking->client_address ?? ($booking->client->profile->address ?? 'No address provided') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($booking->status == 'completed' && $booking->review)
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-muted mb-2">Client Review</h6>
                                    <div class="card border bg-light">
                                        <div class="card-body">
                                            <div class="d-flex mb-2">
                                                <div class="text-warning">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $booking->review->rating)
                                                            <i class="fas fa-star"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <div class="ms-2">
                                                    <span class="fw-semibold">{{ $booking->review->rating }}.0</span>
                                                </div>
                                            </div>
                                            <p class="card-text">{{ $booking->review->comment }}</p>
                                            <small
                                                class="text-muted">{{ $booking->review->created_at->format('M d, Y') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="card-title mb-0 fw-bold">Client</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px; font-size: 16px;">
                                @if ($booking->worker->profile && $booking->worker->profile->profile_picture)
                                    <img src="{{ asset('storage/' . $booking->client->profile->profile_picture) }}"
                                        alt="{{ $booking->client->full_name }}"
                                        class="w-100 h-100 rounded-circle object-fit-cover">
                                @else
                                    <span>
                                        {{ substr($booking->client->full_name, 0, 1) }}
                                    </span>
                                @endif
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $booking->client->full_name }}</h6>
                                <small class="text-muted">{{ $booking->client->email }}</small>
                            </div>
                        </div>

                        <div class="list-group list-group-flush mb-3">
                            <div class="list-group-item px-0 py-2 d-flex justify-content-between border-0">
                                <span class="text-muted">Phone</span>
                                <span>{{ $booking->client->phone ?? 'N/A' }}</span>
                            </div>
                            <div class="list-group-item px-0 py-2 d-flex justify-content-between border-0">
                                <span class="text-muted">Member Since</span>
                                <span>{{ $booking->client->created_at->format('M Y') }}</span>
                            </div>
                            <div class="list-group-item px-0 py-2 d-flex justify-content-between border-0">
                                <span class="text-muted">Total Bookings</span>
                                <span>{{ $booking->client->clientBookings->count() }}</span>
                            </div>
                        </div>

                        <a href="{{ route('admin.users.show', $booking->client_id) }}"
                            class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-user me-2"></i> View Profile
                        </a>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="card-title mb-0 fw-bold">Worker</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px; font-size: 16px;">
                                @if ($booking->worker->profile->profile_picture)
                                    <img src="{{ asset('storage/' . $booking->worker->profile->profile_picture) }}"
                                        alt="{{ $booking->worker->full_name }}"
                                        class="w-100 h-100 rounded-circle object-fit-cover">
                                @else
                                    <span>
                                        {{ substr($booking->worker->full_name, 0, 1) }}
                                    </span>
                                @endif
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $booking->worker->full_name }}</h6>
                                <small class="text-muted">{{ $booking->worker->email }}</small>
                            </div>
                        </div>

                        <div class="list-group list-group-flush mb-3">
                            <div class="list-group-item px-0 py-2 d-flex justify-content-between border-0">
                                <span class="text-muted">Specialization</span>
                                <span>{{ $booking->worker->workerVerification->service_type ?? 'Multiple' }}</span>
                            </div>
                            <div class="list-group-item px-0 py-2 d-flex justify-content-between border-0">
                                <span class="text-muted">Hourly Rate</span>
                                <span>₱{{ number_format($booking->worker->hourly_rate ?? 0, 2) }}</span>
                            </div>
                            <div class="list-group-item px-0 py-2 d-flex justify-content-between border-0">
                                <span class="text-muted">Rating</span>
                                <span>
                                    <i class="fas fa-star text-warning me-1"></i>
                                    {{ number_format($booking->worker->ratings->avg('rating') ?? 0, 1) }}
                                    <small class="text-muted">({{ $booking->worker->ratings->count() }})</small>
                                </span>
                            </div>
                        </div>

                        <a href="{{ route('admin.workers.show', $booking->worker_id) }}"
                            class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-user-tie me-2"></i> View Profile
                        </a>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="card-title mb-0 fw-bold">Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i> Edit Booking
                            </a>

                            @if ($booking->status == 'pending')
                                <form action="{{ route('admin.bookings.approve', $booking->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-check me-2"></i> Approve Booking
                                    </button>
                                </form>
                            @endif

                            @if ($booking->status != 'cancelled' && $booking->status != 'completed')
                                <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="fas fa-times me-2"></i> Cancel Booking
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
