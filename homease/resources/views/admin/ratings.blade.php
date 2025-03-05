@extends('layouts.admin')

@section('content')
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white py-4 d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 fw-bold text-primary">
                            <i class="fas fa-star-half-alt me-2"></i>Ratings Overview
                        </h4>

                        <div class="d-flex gap-2">
                            <!-- Service Filter -->
                            <form action="{{ route('admin.ratings') }}" method="GET">
                                <select name="service" class="form-select form-select-sm rounded-pill"
                                    onchange="this.form.submit()">
                                    <option value="all" {{ $selectedService == 'all' ? 'selected' : '' }}>All Services
                                    </option>
                                    @foreach ($serviceTypes as $service)
                                        <option value="{{ $service }}"
                                            {{ $selectedService == $service ? 'selected' : '' }}>
                                            {{ $service }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>

                            <!-- Sort Filter -->
                            <form action="{{ route('admin.ratings') }}" method="GET">
                                <input type="hidden" name="service" value="{{ $selectedService }}">
                                <select name="sort" class="form-select form-select-sm rounded-pill"
                                    onchange="this.form.submit()">
                                    <option value="latest" {{ $selectedSort == 'latest' ? 'selected' : '' }}>Latest</option>
                                    <option value="highest_rated" {{ $selectedSort == 'highest_rated' ? 'selected' : '' }}>
                                        Highest Rated</option>
                                    <option value="lowest_rated" {{ $selectedSort == 'lowest_rated' ? 'selected' : '' }}>
                                        Lowest Rated</option>
                                    <option value="oldest" {{ $selectedSort == 'oldest' ? 'selected' : '' }}>Oldest
                                    </option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        @if ($ratings->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-comment-slash fa-4x text-muted mb-3"></i>
                                <p class="text-muted">No ratings found at the moment.</p>
                            </div>
                        @else
                            <div class="list-group list-group-flush">
                                @foreach ($ratings as $rating)
                                    <div class="list-group-item p-4 border-0 border-bottom hover-shadow transition">
                                        <div class="d-flex flex-column flex-md-row justify-content-between mb-3">
                                            <div class="d-flex align-items-center mb-2 mb-md-0">
                                                <div class="avatar me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 50px; height: 50px; font-size: 18px;">
                                                    @if ($rating->client->profile->profile_picture)
                                                        <img src="{{ asset('storage/' . $rating->client->profile->profile_picture) }}"
                                                            alt="{{ $rating->client->full_name }}"
                                                            class="w-100 h-100 rounded-circle object-fit-cover">
                                                    @else
                                                        <span
                                                            class="text-white bg-primary d-flex align-items-center justify-content-center w-100 h-100 rounded-circle">
                                                            {{ substr($rating->client->full_name, 0, 1) }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark">
                                                        {{ $rating->client->full_name ?? 'Anonymous User' }}
                                                    </div>
                                                    <div class="small text-muted">
                                                        <i
                                                            class="far fa-calendar-alt me-1"></i>{{ $rating->created_at->format('M d, Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-warning d-flex align-items-center">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $rating->rating)
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                                <span class="ms-2 text-dark fw-bold">{{ $rating->rating }}.0</span>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <span
                                                class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                                {{ $rating->worker->workerVerification->service_type ?? 'Service' }}
                                            </span>
                                            <span class="ms-2 fw-semibold text-dark">{{ $rating->booking_title }}</span>
                                        </div>
                                        <p class="mb-3 text-muted">{{ $rating->comment }}</p>
                                        @if ($rating->review_photos && json_decode($rating->review_photos) && count(json_decode($rating->review_photos)) > 0)
                                            <div class="d-flex mt-3 flex-wrap gap-2">
                                                @foreach (json_decode($rating->review_photos) as $photo)
                                                    <div class="position-relative"
                                                        style="width: 80px; height: 80px; overflow: hidden; border-radius: 10px;">
                                                        <img src="{{ asset('storage/' . $photo) }}" alt="Review photo"
                                                            class="img-fluid position-absolute top-0 start-0 w-100 h-100 object-fit-cover">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        <div class="mt-3">
                                            <div class="small text-muted">
                                                Worker:
                                                <a href="{{ route('admin.workers.show', $rating->worker_id) }}"
                                                    class="text-decoration-none fw-semibold text-primary">
                                                    {{ $rating->worker->full_name }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="card-footer bg-white py-4 border-0">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="text-muted small mb-2 mb-md-0">
                                Showing {{ $ratings->firstItem() }} to {{ $ratings->lastItem() }} of
                                {{ $ratings->total() }} ratings
                            </div>
                            {{ $ratings->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .hover-shadow {
            transition: box-shadow 0.3s ease-in-out;
        }

        .hover-shadow:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .transition {
            transition: all 0.3s ease-in-out;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Optional: Add any additional interactivity for the ratings page
        });
    </script>
@endsection
