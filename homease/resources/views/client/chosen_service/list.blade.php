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
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                            <div class="mb-2 sm:mb-0">
                                <h3 class="text-lg sm:text-xl font-semibold text-gray-700">{{ $worker->full_name }}</h3>
                                <p class="text-gray-500 text-sm sm:text-base">
                                    Experience: {{ $worker->workerVerification->experience ?? 'N/A' }} years
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
                @endforeach
            </div>
        @endif
    </div>
@endsection
