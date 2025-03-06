@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Booking</h1>
            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-outline-secondary rounded-pill">
                <i class="fas fa-arrow-left me-1"></i> Back to Booking
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="card-title fw-bold mb-0">Edit Booking #{{ $booking->id }}</h5>
                <h2 class="card-title fw-bold mb-0">{{ $booking->booking_title }}</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Booking Information</h6>

                            <div class="mb-3">
                                <label for="service_type" class="form-label">Service Type</label>
                                <select class="form-select @error('service_type') is-invalid @enderror" id="service_type"
                                    name="service_type">
                                    <option value="Plumbing" {{ $booking->service_type == 'Plumbing' ? 'selected' : '' }}>
                                        Plumbing</option>
                                    <option value="Carpentry" {{ $booking->service_type == 'Carpentry' ? 'selected' : '' }}>
                                        Carpentry</option>
                                    <option value="Electrician"
                                        {{ $booking->service_type == 'Electrician' ? 'selected' : '' }}>Electrician</option>
                                    <option value="Home Cleaning"
                                        {{ $booking->service_type == 'Home Cleaning' ? 'selected' : '' }}>Home Cleaning
                                    </option>
                                    <option value="Other"
                                        {{ !in_array($booking->service_type, ['Plumbing', 'Carpentry', 'Electrician', 'Home Cleaning']) ? 'selected' : '' }}>
                                        Other</option>
                                </select>
                                @error('service_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status"
                                    name="status">
                                    <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="ongoing" {{ $booking->status == 'ongoing' ? 'selected' : '' }}>Ongoing
                                    </option>
                                    <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="4">{{ old('description', $booking->notes) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Schedule & Payment</h6>

                            <div class="mb-3">
                                <label for="scheduled_date" class="form-label">Scheduled Date</label>
                                <input type="date" class="form-control @error('scheduled_date') is-invalid @enderror"
                                    id="scheduled_date" name="scheduled_date"
                                    value="{{ old('scheduled_date', $booking->scheduled_date->format('Y-m-d')) }}">
                                @error('scheduled_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="scheduled_time" class="form-label">Scheduled Time</label>
                                <input type="time" class="form-control @error('scheduled_time') is-invalid @enderror"
                                    id="scheduled_time" name="scheduled_time"
                                    value="{{ old('scheduled_time', $booking->scheduled_date->format('h:i')) }}">
                                @error('scheduled_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="hours_worked" class="form-label">Hours Worked</label>
                                <input type="number" step="0.5" min="0"
                                    class="form-control @error('hours_worked') is-invalid @enderror" id="hours_worked"
                                    name="hours_worked" value="{{ old('hours_worked', $booking->hours_worked) }}">
                                @error('hours_worked')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Leave empty if the job is not completed yet</small>
                            </div>

                            <div class="mb-3">
                                <label for="total_amount" class="form-label">Total Amount (₱)</label>
                                <input type="number" step="0.01" min="0"
                                    class="form-control @error('total_amount') is-invalid @enderror" id="total_amount"
                                    name="total_amount" value="{{ old('total_amount', $booking->total_amount) }}">
                                @error('total_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Will be calculated automatically based on hourly rate and hours
                                    worked if left empty</small>
                            </div>

                            <div class="mb-3">
                                <label for="payment_status" class="form-label">Payment Status</label>
                                <select class="form-select @error('payment_status') is-invalid @enderror"
                                    id="payment_status" name="payment_status">
                                    <option value="pending"
                                        {{ ($booking->payment_status ?? 'pending') == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="paid"
                                        {{ ($booking->payment_status ?? '') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="refunded"
                                        {{ ($booking->payment_status ?? '') == 'refunded' ? 'selected' : '' }}>Refunded
                                    </option>
                                </select>
                                @error('payment_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Client & Worker</h6>

                            <div class="mb-3">
                                <label for="client_id" class="form-label">Client</label>
                                <select class="form-select @error('client_id') is-invalid @enderror" id="client_id"
                                    name="client_id">
                                    <!-- This would typically be populated from your database -->
                                    <option value="{{ $booking->client_id }}" selected>{{ $booking->client->full_name }}
                                        ({{ $booking->client->email }})</option>
                                </select>
                                @error('client_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="worker_id" class="form-label">Worker</label>
                                <select class="form-select @error('worker_id') is-invalid @enderror" id="worker_id"
                                    name="worker_id">
                                    <!-- This would typically be populated from your database -->
                                    <option value="{{ $booking->worker_id }}" selected>{{ $booking->worker->full_name }}
                                        ({{ $booking->worker->email }})</option>
                                </select>
                                @error('worker_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Location</h6>

                            <div class="mb-3">
                                <label for="client_address" class="form-label">Address</label>
                                <textarea class="form-control @error('client_address') is-invalid @enderror" id="address" name="address"
                                    rows="4">{{ old('address', $booking->client_address ?? ($booking->client->profile->address ?? '')) }}</textarea>
                                @error('client_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>

                        <div>
                            @if ($booking->status == 'pending')
                                <a href="{{ route('admin.bookings.approve', $booking->id) }}"
                                    onclick="event.preventDefault(); document.getElementById('approve-form').submit();"
                                    class="btn btn-success me-2">
                                    <i class="fas fa-check me-1"></i> Approve
                                </a>

                                <form id="approve-form" action="{{ route('admin.bookings.approve', $booking->id) }}"
                                    method="POST" style="display: none;">
                                    @csrf
                                    @method('PUT')
                                </form>
                            @endif

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Auto-calculate total amount based on hours worked and hourly rate
            document.addEventListener('DOMContentLoaded', function() {
                const hoursWorkedInput = document.getElementById('hours_worked');
                const totalAmountInput = document.getElementById('total_amount');
                const hourlyRate = {{ $booking->worker->hourly_rate ?? 0 }};

                hoursWorkedInput.addEventListener('input', function() {
                    if (this.value && hourlyRate) {
                        totalAmountInput.value = (parseFloat(this.value) * hourlyRate).toFixed(2);
                    }
                });
            });
        </script>
    @endpush
@endsection
