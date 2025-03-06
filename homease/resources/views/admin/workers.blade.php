@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Top Rated Workers</h5>
                        <form method="GET" action="{{ route('admin.workers') }}">
                            <select name="sort" class="form-select" onchange="this.form.submit()">
                                <option value="rating_desc" {{ request('sort') == 'rating_desc' ? 'selected' : '' }}>Highest
                                    Rating</option>
                                <option value="rating_asc" {{ request('sort') == 'rating_asc' ? 'selected' : '' }}>Lowest
                                    Rating</option>
                                <option value="reviews_desc" {{ request('sort') == 'reviews_desc' ? 'selected' : '' }}>Most
                                    Reviews</option>
                                <option value="reviews_asc" {{ request('sort') == 'reviews_asc' ? 'selected' : '' }}>Least
                                    Reviews</option>
                            </select>
                        </form>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach ($topRatedWorkers as $worker)
                                <li class="list-group-item px-4 py-3 border-0 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3 bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 46px; height: 46px; font-size: 18px;">
                                                @if ($worker->profile->profile_picture)
                                                    <img src="{{ asset('storage/' . $worker->profile->profile_picture) }}"
                                                        alt="{{ $worker->full_name }}"
                                                        class="w-100 h-100 rounded-circle object-fit-cover">
                                                @else
                                                    <span
                                                        class="text-white bg-primary d-flex align-items-center justify-content-center w-100 h-100 rounded-circle">
                                                        {{ substr($worker->full_name, 0, 1) }}
                                                    </span>
                                                @endif
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
                                                    <span class="badge bg-secondary text-white px-2 py-1 rounded-pill">
                                                        {{ $worker->workerVerification->service_type }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
