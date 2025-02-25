@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 relative">
        <!-- Back Button -->
        <a href="{{ url()->previous() }}" class="absolute top-4 left-4 text-gray-600 hover:text-gray-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>

        <!-- Centered Content -->
        <div class="flex flex-col items-center text-center px-4 md:px-6 lg:px-8">
            <!-- Profile Picture -->
            @if ($profile->profile_picture)
                <img src="{{ asset('storage/' . $profile->profile_picture) }}"
                    class="w-32 h-32 md:w-40 md:h-40 rounded-full shadow-md">
            @endif

            <!-- Worker Name and Rating -->
            <div class="mt-4 flex flex-col sm:flex-row items-center space-x-0 sm:space-x-3">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-700">{{ $user->full_name }}</h2>
                @if (isset($user->average_rating))
                    <span class="mt-1 sm:mt-0 bg-yellow-400 text-white px-3 py-1 text-sm font-semibold rounded-lg">
                        ⭐ {{ number_format($user->average_rating, 1) }}
                    </span>
                @endif
            </div>

            <!-- Service Type -->
            <p class="text-gray-500 text-sm mt-1">{{ $user->workerVerification->service_type ?? 'N/A' }}</p>

            <!-- Experience and Hourly Rate (On the Same Row) -->
            <div class="mt-2 flex flex-row items-center space-x-6 text-sm text-gray-600">
                <!-- Experience -->
                <div class="relative group flex items-center space-x-2">
                    <i class="fas fa-briefcase text-gray-700"></i>
                    <span>{{ $user->workerVerification->experience ?? 'N/A' }} years</span>
                    <div
                        class="absolute bottom-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">
                        Experience
                    </div>
                </div>

                <!-- Price Rate -->
                <div class="relative group flex items-center space-x-2">
                    <i class="fas fa-coins text-gray-700"></i>
                    <span>₱{{ number_format($user->workerVerification->hourly_rate, 2) }}/hour</span>
                    <div
                        class="absolute bottom-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">
                        Price Rate
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="mt-6 p-6 bg-gray-100 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-800">Reviews from Clients</h3>

            @if ($reviews->isEmpty())
                <p class="text-gray-600 mt-2">There are no reviews yet for this worker.</p>
            @else
                @foreach ($reviews as $review)
                    <div class="mt-4 border-b pb-4">
                        <div class="flex items-center space-x-2">
                            <span class="text-yellow-400 text-lg">⭐ {{ $review->rating }}</span>
                            <span
                                class="text-gray-700 font-semibold">{{ $review->client->full_name ?? 'Anonymous Client' }}</span>
                        </div>
                        <p class="text-gray-600 mt-1">{{ $review->comment }}</p>
                        <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
