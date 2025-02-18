@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        @if (Auth::user()->role == 'worker')
            <div class="text-center mb-4">
                <h3 class="fw-bold">Welcome, {{ Auth::user()->first_name }}! 👋</h3>
                <p class="text-muted">Here are your pending or ongoing bookings.</p>
            </div>

            @php
                // Get worker's service type
$workerServiceType = Auth::user()->workerVerification->service_type ?? null;
// Filter bookings that match worker's service type
                $filteredBookings = $bookings->filter(function ($booking) use ($workerServiceType) {
                    return $booking->service_type == $workerServiceType;
                });
            @endphp

            @if ($filteredBookings->count() > 0)
                <div class="row">
                    @foreach ($filteredBookings as $booking)
                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold">
                                        <i class="fas fa-tools me-2"></i> {{ $booking->service_type }}
                                    </h5>
                                    <p class="card-text mb-1">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        Scheduled on:
                                        <strong>{{ \Carbon\Carbon::parse($booking->scheduled_date)->format('F d, Y h:i A') }}</strong>
                                    </p>
                                    <p class="card-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Status:
                                        <span
                                            class="badge
                                            @if ($booking->status == 'pending') bg-warning
                                            @elseif($booking->status == 'ongoing') bg-primary
                                            @elseif($booking->status == 'completed') bg-success @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </p>

                                    @if ($booking->status == 'pending')
                                        <div class="mt-3">
                                            <form action="{{ route('bookings.update', $booking->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="ongoing">
                                                <button type="submit" class="btn btn-success btn-sm">✔ Accept</button>
                                            </form>

                                            <form action="{{ route('bookings.update', $booking->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="btn btn-danger btn-sm">✖ Reject</button>
                                            </form>
                                        </div>
                                    @elseif ($booking->status == 'ongoing')
                                        <form action="{{ route('bookings.update', $booking->id) }}" method="POST"
                                            class="mt-3">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="btn btn-primary btn-sm w-100">✅ Mark as
                                                Completed</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center mt-5">
                    <i class="fas fa-calendar-times fa-4x text-muted"></i>
                    <h5 class="mt-3 text-muted">There's no booking for you now!</h5>
                    <p class="text-muted">Please check back later for new opportunities.</p>
                </div>
            @endif
        @else
            <div class="alert alert-danger text-center">
                <i class="fas fa-exclamation-triangle"></i> You are not authorized to view this page.
            </div>
        @endif
    </div>
@endsection
