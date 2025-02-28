@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-10">
        <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
            <!-- Profile Picture and Basic Info -->
            <div class="text-center mb-6">
                @if (Auth::user()->profile && Auth::user()->profile->profile_picture)
                    <img src="{{ asset('storage/' . Auth::user()->profile->profile_picture) }}" alt="Profile"
                        class="w-48 h-48 rounded-full border-4 border-gray-300 mx-auto mb-4 object-cover">
                @else
                    <div class="w-48 h-48 rounded-full bg-gray-200 flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                @endif

                <!-- Name & Rating -->
                <div class="mt-4 sm:mt-6 flex items-center justify-center space-x-2">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800 tracking-tight">
                        {{ Auth::user()->full_name }}
                    </h2>

                    @if (Auth::user()->role == 'worker' && isset($averageRating))
                        <span
                            class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-white px-3 py-1 text-sm font-semibold rounded-full shadow-sm flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                <path fill-rule="evenodd"
                                    d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ number_format($averageRating, 1) }}
                        </span>
                    @endif
                </div>

                <!-- Role & Service Type -->
                <div class="mt-2 text-gray-500 flex items-center justify-center space-x-2">
                    <span class="text-gray-600 font-medium">{{ ucfirst(Auth::user()->role) }}</span>

                    @if (Auth::user()->role == 'worker' && Auth::user()->workerVerification)
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ Auth::user()->workerVerification->service_type }}
                        </span>
                    @endif
                </div>

                <!-- Edit Profile Button -->
                <div class="mt-4">
                    <a href="{{ route('profile.edit') }}"
                        class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-md hover:bg-blue-700 transition duration-200 no-underline">
                        Edit Profile
                    </a>
                </div>
            </div>

            <!-- Role-specific Details -->
            @if (Auth::user()->role == 'worker')
                <div class="border-t pt-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Worker Details</h3>

                    <!-- Experience and Hourly Rate with improved icons -->
                    <div class="grid grid-cols-1 sm:flex sm:flex-wrap gap-3 sm:gap-6 text-sm mb-4">
                        <div class="flex items-center space-x-2 bg-gray-50 px-4 py-2 rounded-lg shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span
                                class="font-medium text-gray-700">{{ Auth::user()->workerVerification->experience ?? '0' }}
                                years
                                experience</span>
                        </div>
                        <div class="flex items-center space-x-2 bg-gray-50 px-4 py-2 rounded-lg shadow-sm mt-2 sm:mt-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span
                                class="font-medium text-gray-700">₱{{ number_format(Auth::user()->workerVerification->hourly_rate ?? 0, 2) }}/hour</span>
                        </div>
                        <div class="flex items-center space-x-2 bg-gray-50 px-4 py-2 rounded-lg shadow-sm mt-2 sm:mt-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span
                                class="font-medium text-gray-700">{{ Auth::user()->workerBookings ? Auth::user()->workerBookings->count() : 0 }}
                                total bookings</span>
                        </div>
                        <div class="flex items-center space-x-2 bg-gray-50 px-4 py-2 rounded-lg shadow-sm mt-2 sm:mt-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span
                                class="font-medium text-gray-700">{{ Auth::user()->workerBookings()->where('status', 'ongoing')->count() }}
                                active bookings</span>
                        </div>
                    </div>
                </div>

                <!-- Reviews Section for Workers -->
                <div class="mt-6 bg-gray-50 rounded-xl shadow-inner p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6 gap-3">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800">Client Reviews</h3>

                        <!-- Review stats summary -->
                        @if (isset($reviews) && !$reviews->isEmpty())
                            <div
                                class="bg-white px-3 sm:px-4 py-2 rounded-lg shadow-sm flex items-center gap-3 self-start sm:self-auto">
                                <span class="flex items-center gap-1 text-yellow-500 font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="w-5 h-5">
                                        <path fill-rule="evenodd"
                                            d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ number_format($averageRating, 1) }}
                                </span>
                                <div class="w-px h-6 bg-gray-200"></div>
                                <span class="text-gray-600">{{ $reviews->count() }}
                                    {{ Str::plural('review', $reviews->count()) }}</span>
                            </div>
                        @endif
                    </div>

                    @if (!isset($reviews) || $reviews->isEmpty())
                        <div class="bg-white rounded-xl p-6 sm:p-8 text-center shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 sm:h-16 sm:w-16 mx-auto text-gray-300"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <p class="text-gray-600 mt-4">You have no reviews yet.</p>
                            <p class="text-gray-500 text-sm mt-1">Reviews will appear here after clients rate your services.
                            </p>
                        </div>
                    @else
                        <div class="space-y-4 sm:space-y-6">
                            @foreach ($reviews as $review)
                                <div
                                    class="bg-white rounded-xl p-4 sm:p-5 shadow-sm transition-all duration-300 hover:shadow-md">
                                    <!-- Booking Title with icon -->
                                    <div class="flex items-center gap-2 mb-3 sm:mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <h4 class="text-base sm:text-lg font-semibold text-gray-800 truncate">
                                            {{ $review->booking_title ?? 'Service' }}
                                        </h4>
                                    </div>

                                    <div class="flex flex-col sm:flex-row sm:items-start gap-3 sm:gap-4">
                                        <!-- Client Profile Picture with fallback -->
                                        <div class="flex-shrink-0">
                                            @if (isset($clientProfiles[$review->client_id]) && $clientProfiles[$review->client_id]->profile_picture)
                                                <img src="{{ asset('storage/' . $clientProfiles[$review->client_id]->profile_picture) }}"
                                                    class="w-10 h-10 sm:w-12 sm:h-12 rounded-full shadow-sm object-cover border-2 border-gray-50">
                                            @else
                                                <div
                                                    class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex-1">
                                            <!-- Client Name and Date -->
                                            <div
                                                class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1">
                                                <span
                                                    class="text-gray-800 font-medium">{{ $review->client->full_name ?? 'Anonymous Client' }}</span>
                                                <span
                                                    class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>

                                            <!-- Star Rating -->
                                            <div class="mt-1 flex">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $review->rating)
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                            fill="currentColor" class="w-4 h-4 text-yellow-400">
                                                            <path fill-rule="evenodd"
                                                                d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="w-4 h-4 text-gray-300">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                                        </svg>
                                                    @endif
                                                @endfor
                                            </div>

                                            <!-- Review Comment -->
                                            <p class="text-gray-700 mt-2 leading-relaxed text-sm sm:text-base">
                                                {{ $review->comment }}</p>

                                            <!-- Review Photos with improved gallery -->
                                            @if (!empty($review->review_photos))
                                                @php
                                                    // Convert JSON or comma-separated string to an array
                                                    $photos = is_array($review->review_photos)
                                                        ? $review->review_photos
                                                        : json_decode($review->review_photos, true);
                                                    if (!is_array($photos)) {
                                                        $photos = explode(',', $review->review_photos);
                                                    }
                                                @endphp

                                                @if (!empty($photos) && is_array($photos))
                                                    <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 gap-2">
                                                        @foreach ($photos as $photo)
                                                            <a href="#"
                                                                onclick="openModal('{{ asset('storage/' . $photo) }}')"
                                                                class="block group relative">
                                                                <div
                                                                    class="aspect-square rounded-lg overflow-hidden shadow-sm">
                                                                    <img src="{{ asset('storage/' . trim($photo)) }}"
                                                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                                </div>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination controls -->
                        @if (method_exists($reviews, 'hasPages') && $reviews->hasPages())
                            <div class="mt-6 flex justify-center">
                                {{ $reviews->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            @elseif(Auth::user()->role == 'client')
                <div class="border-t pt-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Client Information</h3>
                    <div class="grid grid-cols-1 sm:flex sm:flex-wrap gap-3 sm:gap-6 text-sm">
                        <div class="flex items-center space-x-2 bg-gray-50 px-4 py-2 rounded-lg shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span
                                class="font-medium text-gray-700">{{ Auth::user()->clientBookings ? Auth::user()->clientBookings->count() : 0 }}
                                total bookings</span>
                        </div>
                        <div class="flex items-center space-x-2 bg-gray-50 px-4 py-2 rounded-lg shadow-sm mt-2 sm:mt-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span
                                class="font-medium text-gray-700">{{ Auth::user()->clientBookings()->where('status', 'ongoing')->count() }}
                                active bookings</span>
                        </div>
                    </div>
                </div>

                <!-- Client Reviews Section -->
                @if (isset($reviews) && $reviews->count() > 0)
                    <div class="mt-6 bg-gray-50 rounded-xl shadow-inner p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 sm:mb-6">My Past Booking Reviews</h3>

                        <div class="space-y-4 sm:space-y-6">
                            @foreach ($reviews as $review)
                                <div
                                    class="bg-white rounded-xl p-4 sm:p-5 shadow-sm transition-all duration-300 hover:shadow-md">
                                    <!-- Booking Title with icon -->
                                    <div class="flex items-center gap-2 mb-3 sm:mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <h4 class="text-base sm:text-lg font-semibold text-gray-800 truncate">
                                            {{ $review->booking_title ?? 'Service' }}
                                        </h4>
                                    </div>

                                    <div class="flex flex-col sm:flex-row sm:items-start gap-3 sm:gap-4">
                                        <!-- Worker Profile Picture with fallback -->
                                        <div class="flex-shrink-0">
                                            @if (isset($review->worker) && isset($review->worker->profile) && $review->worker->profile->profile_picture)
                                                <img src="{{ asset('storage/' . $review->worker->profile->profile_picture) }}"
                                                    class="w-10 h-10 sm:w-12 sm:h-12 rounded-full shadow-sm object-cover border-2 border-gray-50">
                                            @else
                                                <div
                                                    class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex-1">
                                            <!-- Worker Name and Date -->
                                            <div
                                                class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1">
                                                <span
                                                    class="text-gray-800 font-medium">{{ isset($review->worker) ? $review->worker->full_name : 'Worker' }}</span>
                                                <span
                                                    class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>

                                            <!-- Star Rating -->
                                            <div class="mt-1 flex">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $review->rating)
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                            fill="currentColor" class="w-4 h-4 text-yellow-400">
                                                            <path fill-rule="evenodd"
                                                                d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="w-4 h-4 text-gray-300">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                                        </svg>
                                                    @endif
                                                @endfor
                                            </div>

                                            <!-- Review Comment -->
                                            <p class="text-gray-700 mt-2 leading-relaxed text-sm sm:text-base">
                                                {{ $review->comment }}</p>

                                            <!-- Review Photos with improved gallery -->
                                            @if (!empty($review->review_photos))
                                                @php
                                                    // Convert JSON or comma-separated string to an array
                                                    $photos = is_array($review->review_photos)
                                                        ? $review->review_photos
                                                        : json_decode($review->review_photos, true);
                                                    if (!is_array($photos)) {
                                                        $photos = explode(',', $review->review_photos);
                                                    }
                                                @endphp

                                                @if (!empty($photos) && is_array($photos))
                                                    <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 gap-2">
                                                        @foreach ($photos as $photo)
                                                            <a href="#"
                                                                onclick="openModal('{{ asset('storage/' . $photo) }}')"
                                                                class="block group relative">
                                                                <div
                                                                    class="aspect-square rounded-lg overflow-hidden shadow-sm">
                                                                    <img src="{{ asset('storage/' . trim($photo)) }}"
                                                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                                </div>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination controls -->
                        @if (method_exists($reviews, 'hasPages') && $reviews->hasPages())
                            <div class="mt-6 flex justify-center">
                                {{ $reviews->links() }}
                            </div>
                        @endif
                    </div>
                @else
                    <div class="mt-6 bg-gray-50 rounded-xl shadow-inner p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">My Past Booking Reviews</h3>
                        <div class="bg-white rounded-xl p-6 sm:p-8 text-center shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-12 w-12 sm:h-16 sm:w-16 mx-auto text-gray-300" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <p class="text-gray-600 mt-4">You haven't left any reviews yet.</p>
                            <p class="text-gray-500 text-sm mt-1">Reviews you leave on completed bookings will appear here.
                            </p>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 hidden p-4">
        <div class="relative max-w-full max-h-full">
            <button onclick="closeModal()" class="absolute -top-10 right-0 bg-white rounded-full p-2 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <img id="modalImage" class="max-w-full max-h-[80vh] mx-auto rounded-lg" src="" alt="Review Image">
        </div>
    </div>

    <script>
        function openModal(imageSrc) {
            document.getElementById("modalImage").src = imageSrc;
            document.getElementById("imageModal").classList.remove("hidden");
            document.body.style.overflow = "hidden"; // Prevent background scrolling
        }

        function closeModal() {
            document.getElementById("imageModal").classList.add("hidden");
            document.body.style.overflow = "auto"; // Restore scrolling
        }

        // Close modal when clicking outside the image
        document.getElementById("imageModal").addEventListener("click", function(event) {
            if (event.target === this) {
                closeModal();
            }
        });

        // Close modal on escape key
        document.addEventListener("keydown", function(event) {
            if (event.key === "Escape") {
                closeModal();
            }
        });
    </script>
@endsection
