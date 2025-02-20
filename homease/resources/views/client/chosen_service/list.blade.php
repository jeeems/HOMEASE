@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 py-10">
        <!-- Navigation for services -->
        <nav class="mb-8 overflow-x-auto">
            <ul
                class="flex flex-wrap justify-center md:justify-center space-x-2 sm:space-x-4 bg-gray-100 p-3 rounded-lg shadow-md">
                @php
                    $services = ['Home Cleaning', 'Daycare', 'Carpentry', 'Plumbing', 'Electrician'];
                @endphp

                @foreach ($services as $service)
                    <li class="whitespace-nowrap">
                        <a href="{{ route('workers.list', ['service_type' => strtolower(str_replace(' ', '-', $service))]) }}"
                            class="block px-4 py-2 text-sm sm:text-base rounded-lg transition-colors duration-200 no-underline
                                {{ strtolower($serviceType) == strtolower(str_replace(' ', '-', $service)) ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-blue-200' }}">
                            {{ $service }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6 text-center">
            Available {{ ucwords(str_replace('-', ' ', $serviceType)) }} Workers
        </h2>

        @if ($workers->isEmpty())
            <div class="bg-white shadow-md rounded-lg p-6 text-center">
                <p class="text-gray-600 text-lg">No workers available for this service at the moment. Please check back
                    later.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($workers as $worker)
                    <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 transition-transform transform hover:scale-105">
                        <div class="flex items-center space-x-4">
                            <!-- Profile Picture -->
                            <div class="flex-shrink-0">
                                @if ($worker->profile && $worker->profile->profile_picture)
                                    <img src="{{ asset('storage/' . $worker->profile->profile_picture) }}"
                                        alt="{{ $worker->full_name }}"
                                        class="w-16 h-16 rounded-full border-2 border-gray-200 object-cover">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-user-circle text-3xl text-gray-400"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Worker Information -->
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-lg sm:text-xl font-semibold text-gray-700">{{ $worker->full_name }}
                                        </h3>
                                        <p class="text-gray-500 text-sm sm:text-base">
                                            Experience: {{ $worker->workerVerification->experience ?? 'N/A' }} years
                                        </p>
                                        <p class="text-gray-500 text-xs sm:text-sm flex items-center">
                                            <i class="fas fa-map-marker-alt text-gray-500 mr-1"></i>
                                            {{ $worker->barangay }}, {{ $worker->city }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-yellow-500 text-sm sm:text-lg font-semibold flex items-center">
                                            ⭐ {{ number_format($worker->averageRating(), 1) }}
                                        </p>
                                        <p class="text-blue-600 font-bold text-sm sm:text-base">
                                            ₱{{ number_format($worker->hourly_rate, 2) }}/hour
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
