@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4 py-5">
        <div class="row g-5">
            <div class="col-12">
                <div class="d-flex align-items-center mb-5">
                    <a href="{{ route('admin.users') }}"
                        class="btn btn-outline-secondary btn-lg me-4 rounded-circle shadow-sm">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="display-6 fw-bold text-dark m-0">User Profile</h1>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-lg h-100 overflow-hidden">
                    <div class="card-body text-center py-5 position-relative">
                        <div class="position-absolute top-0 start-0 w-100 h-25 bg-primary" style="opacity: 0.1;"></div>

                        <div class="mb-4 position-relative">
                            @if ($details->profile && $details->profile->profile_picture)
                                <img src="{{ asset('storage/' . $details->profile->profile_picture) }}"
                                    class="rounded-circle shadow-lg"
                                    style="width: 200px; height: 200px; object-fit: cover; border: 6px solid white;">
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto shadow-lg"
                                    style="width: 200px; height: 200px; font-size: 5rem; border: 6px solid white;">
                                    {{ strtoupper(substr($details->first_name, 0, 1)) }}
                                </div>
                            @endif
                            <span
                                class="badge
                                @if ($details->role == 'client') bg-primary
                                @elseif($details->role == 'worker') bg-success
                                @else bg-info @endif
                                position-absolute bottom-0 end-0 rounded-pill px-4 py-2 shadow-sm">
                                {{ ucfirst($details->role) }}
                            </span>
                        </div>

                        <h3 class="fw-bold mb-2">{{ $details->full_name }}</h3>
                        <p class="text-muted mb-4">{{ $details->email }}</p>

                        {{-- <div class="d-flex justify-content-center gap-3">
                            <a href="#" class="btn btn-outline-primary btn-lg rounded-circle shadow-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="btn btn-outline-danger btn-lg rounded-circle shadow-sm">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div> --}}
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-header bg-white py-4 border-0">
                        <h4 class="mb-0 fw-bold text-dark">User Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="bg-light p-4 rounded-3 h-100">
                                    <small class="text-muted d-block mb-2">First Name</small>
                                    <h5 class="mb-0 text-dark">{{ $details->first_name }}</h5>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-4 rounded-3 h-100">
                                    <small class="text-muted d-block mb-2">Last Name</small>
                                    <h5 class="mb-0 text-dark">{{ $details->last_name }}</h5>
                                </div>
                            </div>
                        </div>

                        @if ($details->role == 'worker')
                            <hr class="my-5 border-2">
                            <h5 class="fw-bold text-primary mb-4">Worker Information</h5>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="bg-light p-4 rounded-3 h-100">
                                        <small class="text-muted d-block mb-2">Service Type</small>
                                        <h5 class="mb-0 text-dark">
                                            {{ $details->workerVerification->service_type ?? 'Not Specified' }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="bg-light p-4 rounded-3 h-100">
                                        <small class="text-muted d-block mb-2">Hourly Rate</small>
                                        <h5 class="mb-0 text-dark">
                                            ₱{{ $details->workerVerification->hourly_rate ?? 'Not Set' }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
