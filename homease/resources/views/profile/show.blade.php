@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-10">
        <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
            <!-- Profile Picture and Basic Info -->
            <div class="text-center mb-6">
                @if (Auth::user()->profile && Auth::user()->profile->profile_picture)
                    <img src="{{ asset('storage/' . Auth::user()->profile->profile_picture) }}" alt="Profile"
                        alt="Profile Picture" class="w-48 h-48 rounded-full border-4 border-gray-300 mx-auto mb-4">
                @else
                    <i class="fas fa-user-circle text-8xl text-gray-600"></i>
                @endif
                <h3 class="text-2xl font-semibold text-gray-800 mt-2">{{ Auth::user()->full_name }}</h3>
                <span class="text-gray-500">
                    {{ ucfirst(Auth::user()->role) }}
                    @if (Auth::user()->role == 'worker' && Auth::user()->workerVerification)
                        | {{ Auth::user()->workerVerification->service_type }}
                    @endif
                </span>
                <!-- Enhanced Edit Profile Button -->
                <div class="mt-2">
                    <a href="{{ route('profile.edit') }}"
                        class="inline-block bg-blue-600 text-white px-3 py-1 rounded-lg text-sm font-semibold shadow-md hover:bg-blue-700 transition duration-200 no-underline">
                        Edit Profile
                    </a>
                </div>
            </div>

            <!-- Role-specific Details -->
            @if (Auth::user()->role == 'worker')
                <div class="border-t pt-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Worker Details</h3>
                    <p class="text-gray-600">Experience: {{ Auth::user()->workerVerification->experience ?? 'N/A' }} years
                    </p>
                    <p class="text-gray-600">Hourly Rate:
                        ₱{{ number_format(Auth::user()->workerVerification->hourly_rate ?? 0, 2) }}/hour</p>
                    <p class="text-gray-600 mt-2">Total Bookings:
                        {{ Auth::user()->workerBookings ? Auth::user()->workerBookings->count() : 0 }}
                    </p>
                    <p class="text-gray-600">Active Bookings:
                        {{ Auth::user()->workerBookings()->where('status', 'ongoing')->count() }}
                    </p>
                </div>
            @elseif(Auth::user()->role == 'client')
                <div class="border-t pt-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Client Information</h3>
                    <p class="text-gray-600">Total Bookings:
                        {{ Auth::user()->clientBookings ? Auth::user()->clientBookings->count() : 0 }}
                    </p>
                    <p class="text-gray-600">Active Bookings:
                        {{ Auth::user()->clientBookings()->where('status', 'ongoing')->count() }}
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection
