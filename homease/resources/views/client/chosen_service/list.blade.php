<!-- booking worker's page -->
@extends('layouts.app')

@section('content')
    <!-- Wrapper for entire content -->
    <div id="page-wrapper" class="min-h-screen transform transition-transform duration-300">
        <!-- Pull to Refresh Indicator -->
        <div id="pull-to-refresh"
            class="fixed left-0 right-0 flex items-center justify-center h-16 -translate-y-full z-50 bg-transparent"
            style="top: -20px; cursor: grab;">
            <span class="text-gray-600 flex items-center">
                <i class="fas fa-arrow-down mr-2"></i>Pull to refresh
            </span>
        </div>

        <!-- Main Content Container -->
        <div class="container mx-auto px-4 sm:px-6 py-6">
            <h2 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6 flex items-center">
                <i class="fas fa-tools mr-2 sm:mr-3 text-blue-600"></i>
                Available Workers
            </h2>

            <!-- list.blade.php nav -->
            <!-- Navigation for services with smooth scrolling -->
            <div class="container mx-auto px-4">
                <!-- For larger screens - all items visible, no scroll -->
                <ul
                    class="hidden sm:flex justify-center space-x-4 py-3 px-4
            bg-transparent transition-all duration-300 mx-auto max-w-4xl">

                    @php
                        $services = ['Home Cleaning', 'Daycare', 'Carpentry', 'Plumbing', 'Electrician'];
                    @endphp
                    @foreach ($services as $service)
                        @php
                            $isActive = strtolower($serviceType) == strtolower(str_replace(' ', '-', $service));
                        @endphp
                        <li class="min-w-[160px]">
                            <a href="{{ route('workers.list', ['service_type' => strtolower(str_replace(' ', '-', $service))]) }}"
                                class="flex items-center space-x-2 px-5 py-2.5 text-sm font-medium rounded-full transition-all duration-300 no-underline
                        {{ $isActive
                            ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-md transform scale-105'
                            : 'bg-gray-50 text-gray-700 hover:bg-blue-50 hover:text-blue-600 hover:shadow-sm' }}">
                                <i
                                    class="fas fa-{{ $service == 'Home Cleaning'
                                        ? 'broom'
                                        : ($service == 'Daycare'
                                            ? 'baby'
                                            : ($service == 'Carpentry'
                                                ? 'hammer'
                                                : ($service == 'Plumbing'
                                                    ? 'faucet'
                                                    : 'bolt'))) }} text-lg"></i>
                                <span class="whitespace-nowrap">{{ $service }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                <!-- Mobile Version -->
                <div class="sm:hidden w-full mb-4">
                    <div class="flex flex-col items-center">
                        @php
                            $activeService = '';
                            foreach ($services as $service) {
                                if (strtolower($serviceType) == strtolower(str_replace(' ', '-', $service))) {
                                    $activeService = $service;
                                    break;
                                }
                            }
                        @endphp

                        <a href="#"
                            class="flex items-center space-x-2 px-8 py-3 mb-3 text-base font-medium rounded-full
                    bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-md w-auto no-underline">
                            <i
                                class="fas fa-{{ $activeService == 'Home Cleaning'
                                    ? 'broom'
                                    : ($activeService == 'Daycare'
                                        ? 'baby'
                                        : ($activeService == 'Carpentry'
                                            ? 'hammer'
                                            : ($activeService == 'Plumbing'
                                                ? 'faucet'
                                                : 'bolt'))) }} text-lg"></i>
                            <span>{{ $activeService }}</span>
                        </a>

                        <div class="grid grid-cols-2 gap-2 w-full max-w-xs">
                            @foreach ($services as $service)
                                @php
                                    $isActive = strtolower($serviceType) == strtolower(str_replace(' ', '-', $service));
                                @endphp
                                @if (!$isActive)
                                    <a href="{{ route('workers.list', ['service_type' => strtolower(str_replace(' ', '-', $service))]) }}"
                                        class="flex items-center space-x-2 justify-center px-4 py-2 text-xs font-medium rounded-full
                                bg-gray-50 text-gray-700 hover:bg-blue-50 hover:text-blue-600 shadow-sm transition-all duration-300 no-underline">
                                        <i
                                            class="fas fa-{{ $service == 'Home Cleaning'
                                                ? 'broom'
                                                : ($service == 'Daycare'
                                                    ? 'baby'
                                                    : ($service == 'Carpentry'
                                                        ? 'hammer'
                                                        : ($service == 'Plumbing'
                                                            ? 'faucet'
                                                            : 'bolt'))) }} text-lg"></i>
                                        <span>{{ $service }}</span>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sorting Options -->
            <div class="flex justify-end mb-6">
                <div class="relative w-52">
                    <!-- Sort Button -->
                    <button id="sort-button"
                        class="flex items-center justify-between w-full bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer shadow-sm">
                        <div class="flex items-center">
                            <i class="fas fa-sort-amount-down w-5 text-blue-500"></i>
                            <span id="selected-sort" class="ml-2 truncate">Highest Rated</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs text-gray-600 ml-2"></i>
                    </button>

                    <!-- Sort Dropdown -->
                    <div id="sort-dropdown"
                        class="absolute left-0 right-0 mt-1 bg-white rounded-lg shadow-lg z-10 hidden border border-gray-100">
                        <ul class="py-1 m-0 p-0">
                            <li data-value="highest"
                                class="sort-option flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 cursor-pointer">
                                <i class="fas fa-sort-amount-down w-5 text-center text-blue-500"></i>
                                <span class="ml-2 truncate">Highest Rated</span>
                            </li>
                            <li data-value="lowest"
                                class="sort-option flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 cursor-pointer">
                                <i class="fas fa-sort-amount-up w-5 text-center text-blue-500"></i>
                                <span class="ml-2 truncate">Lowest Rated</span>
                            </li>
                            <li data-value="price-low"
                                class="sort-option flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 cursor-pointer">
                                <i class="fas fa-dollar-sign w-5 text-center text-blue-500"></i>
                                <span class="ml-2 truncate">Price: Low to High</span>
                            </li>
                            <li data-value="price-high"
                                class="sort-option flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 cursor-pointer">
                                <i class="fas fa-dollar-sign w-5 text-center text-blue-500"></i>
                                <span class="ml-2 truncate">Price: High to Low</span>
                            </li>
                            <li data-value="experience"
                                class="sort-option flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 cursor-pointer">
                                <i class="fas fa-briefcase w-5 text-center text-blue-500"></i>
                                <span class="ml-2 truncate">Most Experienced</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex items-center mb-6">
                <div class="flex-1 h-px bg-gray-200"></div>
                <h3 class="px-4 text-lg sm:text-xl font-semibold text-gray-700">
                    {{ ucwords(str_replace('-', ' ', $serviceType)) }} Experts
                </h3>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            <!-- Refreshable Content Wrapper with animation -->
            <div id="refreshable-content" class="relative transition-all duration-300">
                @if ($workers->isEmpty())
                    <div class="bg-white shadow-md rounded-lg p-8 text-center">
                        <i class="fas fa-user-slash text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-600 text-lg">No workers available for this service at the moment.</p>
                        <p class="text-gray-500 mt-2">Please check back later or try another service.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6">
                        @foreach ($workers as $worker)
                            <div
                                class="bg-white shadow-lg rounded-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                                <!-- Header with gradient background -->
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-3 text-white">
                                    <div class="flex justify-between items-center">
                                        <span class="font-semibold">
                                            <i class="fas fa-star text-yellow-300 mr-1"></i>
                                            {{ number_format($worker->averageRating(), 1) }}
                                        </span>
                                        <span class="font-bold">
                                            ₱{{ number_format($worker->hourly_rate, 2) }}/hour
                                        </span>
                                    </div>
                                </div>

                                <!-- Worker Main Content -->
                                <div class="p-4 sm:p-5">
                                    <div class="flex space-x-4">
                                        <!-- Profile Picture -->
                                        <div class="flex-shrink-0">
                                            @if ($worker->profile && $worker->profile->profile_picture)
                                                <img src="{{ asset('storage/' . $worker->profile->profile_picture) }}"
                                                    alt="{{ $worker->full_name }}"
                                                    class="w-20 h-20 rounded-full border-2 border-white shadow-md object-cover">
                                            @else
                                                <div
                                                    class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center shadow-md">
                                                    <i class="fas fa-user text-3xl text-blue-400"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Worker Information -->
                                        <div class="flex-1">
                                            <h3 class="text-xl font-semibold text-gray-800 mb-1">
                                                {{ $worker->full_name }}
                                            </h3>

                                            <div class="flex items-center mb-1 text-gray-600">
                                                <i class="fas fa-briefcase text-blue-500 mr-1.5 w-5 text-center"></i>
                                                <span>{{ $worker->workerVerification->experience ?? 'N/A' }} years
                                                    experience</span>
                                            </div>

                                            <div class="flex items-center text-gray-600">
                                                <i class="fas fa-map-marker-alt text-blue-500 mr-1.5 w-5 text-center"></i>
                                                <span>{{ $worker->barangay }}, {{ $worker->city }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="px-4 pb-4 flex gap-2">
                                    <a href="{{ route('worker.profile', $worker->id) }}"
                                        class="flex-1 bg-gray-100 text-gray-700 py-2.5 px-4 rounded-lg text-center font-medium transition-colors hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 no-underline">
                                        <i class="fas fa-user mr-1.5"></i>View Profile
                                    </a>
                                    <button
                                        onclick="openBookingModal('{{ $worker->id }}', '{{ $worker->full_name }}', '{{ $serviceType }}')"
                                        class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white py-2.5 px-4 rounded-lg text-center font-medium transition-all hover:from-blue-600 hover:to-blue-700 shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                        <i class="fas fa-calendar-check mr-1.5"></i>Book Now
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}

    <!-- list.blade.php -->
    <!-- Enhanced Booking Modal with Modern Design -->
    <div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md overflow-hidden m-4 max-h-[90vh] md:max-h-[80vh]">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-t-lg px-6 py-3 sticky top-0 z-10">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-calendar-plus mr-2"></i>Book a Service
                    </h2>
                    <button onclick="closeBookingModal()" class="text-white hover:text-gray-200 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-4 md:p-6 overflow-y-auto" style="max-height: calc(90vh - 57px);">
                <form id="bookingForm" method="POST" action="{{ route('book.service') }}">
                    @csrf
                    <input type="hidden" name="worker_id" id="workerId">
                    <input type="hidden" name="service_type" id="serviceType">

                    <div class="space-y-4">
                        <!-- Worker Info -->
                        <div class="bg-blue-50 p-3 rounded-lg mb-2">
                            <label class="block text-sm font-semibold text-gray-600 mb-1">
                                <i class="fas fa-user mr-1.5"></i>Selected Worker
                            </label>
                            <input type="text" id="workerName"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white" readonly>
                        </div>

                        <!-- Address Input with Enhanced UI -->
                        <div class="relative z-50"> <!-- Added z-50 to bring this to the front -->
                            <label class="block text-sm font-semibold text-gray-600 mb-1">
                                <i class="fas fa-map-marker-alt mr-1.5"></i>Your Address
                            </label>
                            <div class="relative">
                                <input type="text" id="addressInput" name="address"
                                    class="w-full px-3 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter your address" required>
                            </div>
                            <!-- Suggestion Box (Ensures it stays on top) -->
                            <div id="suggestionsBox"
                                class="absolute top-full left-0 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto hidden z-50">
                            </div>
                        </div>

                        <!-- Map Container (Send it to the back) -->
                        <div class="relative mt-4 z-10"> <!-- Lower z-index to ensure it's behind -->
                            <label class="block text-sm font-semibold text-gray-600 mb-1">
                                <i class="fas fa-map mr-1.5"></i>Pin Your Location
                            </label>
                            <div id="map"
                                class="w-full h-48 rounded-lg border border-gray-300 shadow-inner bg-gray-100 relative z-0">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>Drag the marker to adjust your exact location
                            </p>
                            <input type="hidden" name="latitude" id="latitude" required>
                            <input type="hidden" name="longitude" id="longitude" required>
                        </div>

                        <!-- Booking Details -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">
                                <i class="fas fa-heading mr-1.5"></i>Service Title
                            </label>
                            <input type="text" name="title" id="title"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                placeholder="e.g. Broken Outlet" required>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">
                                <i class="fas fa-align-left mr-1.5"></i>Service Description
                            </label>
                            <textarea name="description" id="description" class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                rows="2" placeholder="Describe what you need help with..." required></textarea>
                        </div>

                        <!-- Date Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">
                                <i class="fas fa-calendar-alt mr-1.5"></i>Service Date
                            </label>
                            <div class="relative">
                                <input type="text" id="bookingDate" name="booking_date"
                                    class="w-full px-3 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Select Date" required>
                                {{-- <i
                                    class="fas fa-calendar absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i> --}}
                            </div>
                        </div>

                        <!-- Time Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">
                                <i class="fas fa-clock mr-1.5"></i>Service Time
                            </label>
                            <div class="flex items-center space-x-1">
                                <div class="relative flex-1">
                                    <input type="number" id="hourInput" name="hour"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center"
                                        placeholder="HH" min="1" max="12" required>
                                </div>
                                <span class="text-lg font-semibold">:</span>
                                <div class="relative flex-1">
                                    <input type="number" id="minuteInput" name="minute"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center"
                                        placeholder="MM" min="0" max="59" required>
                                </div>
                                <select id="ampmInput" name="ampm"
                                    class="pl-2 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white">
                                    <option value="AM">AM</option>
                                    <option value="PM">PM</option>
                                </select>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3 pt-2">
                            <button type="button" onclick="closeBookingModal()"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 font-medium transition-colors">
                                <i class="fas fa-times mr-1.5"></i>Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm hover:shadow font-medium transition-all">
                                <i class="fas fa-calendar-check mr-1.5"></i>Book Now
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add this right after your body tag or at the top of your content -->
    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]">
        <div class="bg-white p-6 rounded-lg shadow-xl text-center">
            <div
                class="animate-spin inline-block w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full mb-4">
            </div>
            <p class="text-gray-700 font-medium text-lg">Submitting your booking...</p>
            <p class="text-gray-500 text-sm mt-2">Please wait while we process your request</p>
        </div>
    </div>

    <!-- Conflict Modal -->
    <div id="conflictModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md overflow-hidden m-4">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-t-lg px-6 py-3">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Scheduling Conflict
                    </h2>
                    <button onclick="closeConflictModal()" class="text-white hover:text-gray-200 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <div class="mb-4 text-center">
                    <div
                        class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-100 text-red-500 mb-4">
                        <i class="fas fa-calendar-times text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Time Slot Unavailable</h3>
                    <p id="conflictMessage" class="text-gray-600"></p>
                </div>

                <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-lightbulb text-amber-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-amber-700">
                                Try selecting a different time or date for your booking.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center">
                    <button onclick="closeConflictModal()"
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium transition-colors">
                        <i class="fas fa-check mr-1.5"></i>Okay, I'll Try Another Time
                    </button>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        body {
            overscroll-behavior-y: contain;
            -webkit-overflow-scrolling: touch;
            overflow-x: hidden;
            background-color: #f8fafc;
        }

        #page-wrapper {
            will-change: transform;
            touch-action: pan-y;
        }

        #pull-to-refresh.grabbing {
            cursor: grabbing;
        }

        @keyframes rotating {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .refresh-spinner {
            animation: rotating 1s linear infinite;
        }

        /* Style for suggestion dropdown */
        .autocomplete-suggestions {
            position: absolute;
            background: white;
            border: 1px solid #ddd;
            max-height: 150px;
            overflow-y: auto;
            width: 100%;
            z-index: 1000;
            border-radius: 0.5rem;
            margin-top: 4px;
        }

        .autocomplete-suggestions div {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
        }

        .autocomplete-suggestions div:last-child {
            border-bottom: none;
        }

        .autocomplete-suggestions div:hover {
            background: #f0f7ff;
        }

        /* Hide scrollbar but keep functionality */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Smooth transitions for interactive elements */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }

        .pulse-effect {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 0, 0, 0.2);
            animation: pulse 1.5s infinite;
            position: relative;
        }

        @keyframes pulse {
            0% {
                transform: scale(0.5);
                opacity: 1;
            }

            100% {
                transform: scale(2);
                opacity: 0;
            }
        }

        .custom-div-icon i {
            position: relative;
            display: block;
            text-align: center;
        }

        /* Reset list styles */
        #sort-dropdown ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        /* Ensure icons are centered in their fixed width */
        #sort-dropdown i,
        #sort-button i.fas:not(.fa-chevron-down) {
            display: inline-block;
            text-align: center;
        }

        /* Ensure consistent icon alignment in button and dropdown */
        #sort-button .flex.items-center,
        #sort-dropdown .sort-option {
            display: flex;
            align-items: center;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            let startY = 0;
            let currentY = 0;
            let isDragging = false;
            const refreshThreshold = 80;
            const pageWrapper = document.getElementById("page-wrapper");
            const refreshIndicator = document.getElementById("pull-to-refresh");
            let isRefreshing = false;
            let isMouseDown = false;

            function updatePagePosition(distance) {
                if (distance >= 0 && !isRefreshing) {
                    const translateY = Math.min(distance * 0.4, refreshThreshold); // Add resistance
                    pageWrapper.style.transform = `translateY(${translateY}px)`;

                    // Update indicator text based on distance
                    if (translateY >= refreshThreshold * 0.4) { // Adjust for the 0.4 resistance factor
                        refreshIndicator.querySelector('span').innerHTML =
                            '<i class="fas fa-arrow-up mr-2"></i>Release to refresh';
                        refreshIndicator.classList.add('grabbing');
                    } else {
                        refreshIndicator.querySelector('span').innerHTML =
                            '<i class="fas fa-arrow-down mr-2"></i>Pull to refresh';
                        refreshIndicator.classList.remove('grabbing');
                    }
                }
            }

            function startRefresh() {
                isRefreshing = true;
                refreshIndicator.querySelector('span').innerHTML =
                    '<i class="fas fa-sync-alt mr-2"></i>Refreshing...';
                refreshIndicator.classList.remove('grabbing');
                refreshIndicator.classList.remove('cursor-grab');
                refreshIndicator.style.cursor = 'default';

                // Add loading animation
                const spinner = document.createElement('span');
                spinner.innerHTML = '↻';
                spinner.className = 'refresh-spinner inline-block ml-2';
                refreshIndicator.querySelector('span').appendChild(spinner);

                // Animate to loading state
                pageWrapper.style.transform = `translateY(48px)`;

                // Add animation to content
                const content = document.getElementById('refreshable-content');
                content.style.opacity = '0.6';

                // Perform the refresh
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            }

            function resetPosition() {
                if (!isRefreshing) {
                    pageWrapper.style.transform = 'translateY(0)';
                    refreshIndicator.classList.remove('grabbing');
                }
            }

            // Touch events for mobile
            document.addEventListener("touchstart", (e) => {
                if (window.scrollY === 0 && !isRefreshing) {
                    startY = e.touches[0].pageY;
                    currentY = startY;
                    isDragging = true;

                    // Reset transition during drag
                    pageWrapper.style.transition = 'none';
                }
            }, {
                passive: true
            });

            document.addEventListener("touchmove", (e) => {
                if (!isDragging || isRefreshing) return;

                currentY = e.touches[0].pageY;
                const distance = currentY - startY;

                if (window.scrollY === 0 && distance > 0) {
                    updatePagePosition(distance);
                    if (distance > 5) { // Small threshold to prevent accidental triggers
                        e.preventDefault();
                    }
                }
            }, {
                passive: false
            });

            document.addEventListener("touchend", () => {
                if (!isDragging || isRefreshing) return;

                // Restore transition for smooth animation
                pageWrapper.style.transition = 'transform 0.3s ease-out';

                const distance = currentY - startY;
                isDragging = false;

                if (distance * 0.4 >= refreshThreshold) { // Adjust for the resistance factor
                    startRefresh();
                } else {
                    resetPosition();
                }
            });

            // Mouse events for desktop
            refreshIndicator.addEventListener("mousedown", (e) => {
                if (window.scrollY === 0 && !isRefreshing) {
                    startY = e.pageY;
                    currentY = startY;
                    isDragging = true;
                    isMouseDown = true;

                    // Change cursor to grabbing
                    refreshIndicator.classList.add('grabbing');

                    // Reset transition during drag
                    pageWrapper.style.transition = 'none';

                    e.preventDefault(); // Prevent text selection
                }
            });

            document.addEventListener("mousemove", (e) => {
                if (!isDragging || !isMouseDown || isRefreshing) return;

                currentY = e.pageY;
                const distance = currentY - startY;

                if (window.scrollY === 0 && distance > 0) {
                    updatePagePosition(distance);
                }
            });

            document.addEventListener("mouseup", () => {
                if (!isDragging || !isMouseDown || isRefreshing) return;

                // Restore transition for smooth animation
                pageWrapper.style.transition = 'transform 0.3s ease-out';

                const distance = currentY - startY;
                isDragging = false;
                isMouseDown = false;

                if (distance * 0.4 >= refreshThreshold) { // Adjust for the resistance factor
                    startRefresh();
                } else {
                    resetPosition();
                }
            });

            // Reset on scroll
            window.addEventListener('scroll', () => {
                if (window.scrollY > 0 && !isRefreshing) {
                    isDragging = false;
                    isMouseDown = false;
                    resetPosition();
                }
            }, {
                passive: true
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Get elements
            const sortButton = document.getElementById('sort-button');
            const sortDropdown = document.getElementById('sort-dropdown');
            const selectedSort = document.getElementById('selected-sort');
            const sortOptions = document.querySelectorAll('.sort-option');
            const workersContainer = document.getElementById('refreshable-content').querySelector('.grid');

            // Toggle dropdown
            sortButton.addEventListener('click', function() {
                sortDropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!sortButton.contains(e.target) && !sortDropdown.contains(e.target)) {
                    sortDropdown.classList.add('hidden');
                }
            });

            // Handle option selection
            sortOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const value = this.dataset.value;
                    const text = this.querySelector('span').textContent;
                    const icon = this.querySelector('i').cloneNode(true);

                    // Update button text and icon
                    selectedSort.textContent = text;
                    sortButton.querySelector('.flex.items-center i').className = icon.className;

                    // Hide dropdown
                    sortDropdown.classList.add('hidden');

                    // Sort workers
                    sortWorkers(value);
                });
            });

            // Function to sort workers
            function sortWorkers(sortType) {
                if (!workersContainer) return;

                const workerCards = Array.from(workersContainer.querySelectorAll('.bg-white.shadow-lg'));

                workerCards.sort((a, b) => {
                    if (sortType === 'highest') {
                        // Sort by rating (highest first)
                        const ratingA = parseFloat(a.querySelector('.font-semibold i.fas.fa-star')
                            .nextSibling.textContent.trim());
                        const ratingB = parseFloat(b.querySelector('.font-semibold i.fas.fa-star')
                            .nextSibling.textContent.trim());
                        return ratingB - ratingA;
                    } else if (sortType === 'lowest') {
                        // Sort by rating (lowest first)
                        const ratingA = parseFloat(a.querySelector('.font-semibold i.fas.fa-star')
                            .nextSibling.textContent.trim());
                        const ratingB = parseFloat(b.querySelector('.font-semibold i.fas.fa-star')
                            .nextSibling.textContent.trim());
                        return ratingA - ratingB;
                    } else if (sortType === 'price-low') {
                        // Sort by price (low to high)
                        const priceA = parseFloat(a.querySelector('.font-bold').textContent.replace('₱', '')
                            .replace(',', ''));
                        const priceB = parseFloat(b.querySelector('.font-bold').textContent.replace('₱', '')
                            .replace(',', ''));
                        return priceA - priceB;
                    } else if (sortType === 'price-high') {
                        // Sort by price (high to low)
                        const priceA = parseFloat(a.querySelector('.font-bold').textContent.replace('₱', '')
                            .replace(',', ''));
                        const priceB = parseFloat(b.querySelector('.font-bold').textContent.replace('₱', '')
                            .replace(',', ''));
                        return priceB - priceA;
                    } else if (sortType === 'experience') {
                        // Sort by experience
                        const expTextA = a.querySelector('.fas.fa-briefcase').nextElementSibling.textContent
                            .trim();
                        const expTextB = b.querySelector('.fas.fa-briefcase').nextElementSibling.textContent
                            .trim();

                        // Extract numbers from text using regex
                        const expA = parseInt(expTextA.match(/(\d+)/) ? expTextA.match(/(\d+)/)[0] : 0);
                        const expB = parseInt(expTextB.match(/(\d+)/) ? expTextB.match(/(\d+)/)[0] : 0);

                        return expB - expA;
                    }
                    return 0;
                });

                // Remove all workers from the container
                while (workersContainer.firstChild) {
                    workersContainer.removeChild(workersContainer.firstChild);
                }

                // Append the sorted workers back to the container
                workerCards.forEach(card => {
                    workersContainer.appendChild(card);
                });
            }

            // Initial sort (highest rated)
            if (workersContainer && workersContainer.childElementCount > 0) {
                sortWorkers('highest');
            }
        });


        let map, marker, pulseMarker;

        function initMap() {
            map = L.map("map").setView([14.5995, 120.9842], 15); // Default: Manila, PH

            // Load OpenStreetMap tiles with custom styling
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                attribution: "© OpenStreetMap contributors"
            }).addTo(map);

            // Custom marker icon
            let markerIcon = L.divIcon({
                html: '<i class="fas fa-map-marker-alt text-red-600 text-3xl"></i>',
                className: 'custom-div-icon',
                iconSize: [30, 42],
                iconAnchor: [15, 42]
            });

            // Draggable marker
            marker = L.marker([14.5995, 120.9842], {
                draggable: true,
                icon: markerIcon
            }).addTo(map);

            // Add a pulse effect around the marker
            let pulseIcon = L.divIcon({
                html: '<div class="pulse-effect"></div>',
                className: 'pulse-div-icon',
                iconSize: [40, 40],
                iconAnchor: [20, 20]
            });

            pulseMarker = L.marker([14.5995, 120.9842], {
                icon: pulseIcon
            }).addTo(map);

            // Ensure pulse follows the main marker
            marker.on('drag', function(e) {
                pulseMarker.setLatLng(e.target.getLatLng());
            });

            // Change cursor when hovering over marker
            marker.on("mouseover", function() {
                this._icon.style.cursor = "grab";
            });

            marker.on("mousedown", function() {
                this._icon.style.cursor = "grabbing";
            });

            marker.on("mouseup", function() {
                this._icon.style.cursor = "grab";
            });

            // Update hidden inputs & address on marker drag end
            marker.on("dragend", function(event) {
                let latLng = marker.getLatLng();
                pulseMarker.setLatLng(latLng);
                document.getElementById("latitude").value = latLng.lat;
                document.getElementById("longitude").value = latLng.lng;

                // Show loading indicator in address field
                const addressInput = document.getElementById("addressInput");
                addressInput.value = "Loading address...";

                // Fetch address based on lat/lng
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latLng.lat}&lon=${latLng.lng}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.display_name) {
                            addressInput.value = data.display_name;
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching address:", error);
                        addressInput.value = "Error loading address, please try again";
                    });
            });

            // Get user's location with improved UX
            if (navigator.geolocation) {
                // Show loading message in address field
                document.getElementById("addressInput").value = "Detecting your location...";

                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        let userLat = position.coords.latitude;
                        let userLng = position.coords.longitude;
                        map.setView([userLat, userLng], 15);
                        marker.setLatLng([userLat, userLng]);
                        pulseMarker.setLatLng([userLat, userLng]);
                        document.getElementById("latitude").value = userLat;
                        document.getElementById("longitude").value = userLng;

                        // Fetch address based on user's location
                        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${userLat}&lon=${userLng}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data && data.display_name) {
                                    document.getElementById("addressInput").value = data.display_name;
                                }
                            })
                            .catch(error => {
                                console.error("Error fetching address:", error);
                                document.getElementById("addressInput").value = "";
                            });
                    },
                    function(error) {
                        console.error("Geolocation error:", error);
                        document.getElementById("addressInput").value = "";
                        // Show a friendly error toast instead of an alert
                        showToast("Could not access your location. Please enter your address manually.", "warning");
                    }
                );
            } else {
                showToast("Geolocation is not supported by your browser.", "warning");
            }

            // Enhanced address search with suggestions
            const addressInput = document.getElementById("addressInput");
            const suggestionsBox = document.getElementById("suggestionsBox");

            let searchTimeout;

            addressInput.addEventListener("input", function() {
                clearTimeout(searchTimeout);
                let query = this.value;

                if (query.length < 3) {
                    suggestionsBox.innerHTML = "";
                    suggestionsBox.classList.add("hidden");
                    return;
                }

                // Show loading indicator
                suggestionsBox.innerHTML =
                    "<div class='p-3 text-gray-500'><i class='fas fa-spinner fa-spin mr-2'></i>Searching...</div>";
                suggestionsBox.classList.remove("hidden");

                // Debounce the search to avoid too many requests
                searchTimeout = setTimeout(() => {
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}`)
                        .then(response => response.json())
                        .then(data => {
                            suggestionsBox.innerHTML = "";

                            if (data.length === 0) {
                                suggestionsBox.innerHTML =
                                    "<div class='p-3 text-gray-500'><i class='fas fa-info-circle mr-2'></i>No results found</div>";
                                return;
                            }

                            data.forEach(place => {
                                let suggestion = document.createElement("div");
                                suggestion.innerHTML =
                                    `<i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>${place.display_name}`;
                                suggestion.classList.add("cursor-pointer", "p-3",
                                    "hover:bg-blue-50", "text-sm");
                                suggestion.addEventListener("click", function() {
                                    addressInput.value = place.display_name;
                                    map.setView([place.lat, place.lon], 15);
                                    marker.setLatLng([place.lat, place.lon]);
                                    pulseMarker.setLatLng([place.lat, place.lon]);
                                    document.getElementById("latitude").value = place
                                        .lat;
                                    document.getElementById("longitude").value = place
                                        .lon;
                                    suggestionsBox.innerHTML = "";
                                    suggestionsBox.classList.add("hidden");
                                });
                                suggestionsBox.appendChild(suggestion);
                            });

                            suggestionsBox.classList.remove("hidden");
                        })
                        .catch(error => {
                            console.error("Error fetching location:", error);
                            suggestionsBox.innerHTML =
                                "<div class='p-3 text-gray-500'><i class='fas fa-exclamation-triangle mr-2'></i>Error loading suggestions</div>";
                        });
                }, 300);
            });

            // Hide suggestions when clicking outside
            document.addEventListener("click", function(event) {
                if (!addressInput.contains(event.target) && !suggestionsBox.contains(event.target)) {
                    suggestionsBox.classList.add("hidden");
                }
            });

            document.getElementById('bookingForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                // Clear previous error messages
                document.querySelectorAll('.error-message').forEach(el => el.remove());

                // Validate fields
                if (!validateForm()) return;

                // Show loading overlay
                const loadingOverlay = document.getElementById('loadingOverlay');
                loadingOverlay.style.display = 'flex';

                // Get the modal container
                const modalBody = document.querySelector('.p-4.md\\:p-6.overflow-y-auto');

                // Add visual "disabled" state to the entire modal
                modalBody.classList.add('opacity-50', 'pointer-events-none');

                // Disable form buttons to prevent multiple submissions
                const submitButton = this.querySelector('button[type="submit"]');
                const cancelButton = this.querySelector('button[type="button"]');
                submitButton.disabled = true;
                cancelButton.disabled = true;

                // Add "disabled" visual state
                submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                cancelButton.classList.add('opacity-50', 'cursor-not-allowed');

                // Create FormData object
                const formData = new FormData(this);

                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    // Hide loading overlay
                    loadingOverlay.style.display = 'none';

                    // Show submitting state
                    await showToast("Submitting your booking request...", "submitting");

                    if (data.success) {
                        // Show success toast
                        await showToast("Booking submitted successfully!", "success");

                        // Close modal after success
                        closeBookingModal();

                        // Redirect if needed
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        }
                    } else {
                        // Re-enable modal
                        modalBody.classList.remove('opacity-50', 'pointer-events-none');

                        // Re-enable buttons
                        submitButton.disabled = false;
                        cancelButton.disabled = false;
                        submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                        cancelButton.classList.remove('opacity-50', 'cursor-not-allowed');

                        // Check if this is a scheduling conflict error
                        if (data.errors && data.errors.booking_date &&
                            data.errors.booking_date[0].includes('conflicts with an existing booking')) {
                            // Show conflict modal with the message
                            showConflictModal(data.message);

                            // Close the booking modal
                            closeBookingModal();
                        } else {
                            // Handle validation errors
                            if (data.errors) {
                                Object.entries(data.errors).forEach(([field, messages]) => {
                                    const element = document.getElementById(field);
                                    if (element) {
                                        element.classList.add('border-red-500');
                                        const errorMessage = document.createElement('div');
                                        errorMessage.className =
                                            'error-message text-red-500 text-sm mt-1';
                                        errorMessage.innerHTML =
                                            `<i class="fas fa-exclamation-circle mr-1"></i>${messages[0]}`;
                                        element.parentNode.appendChild(errorMessage);
                                    }
                                });
                            }
                            await showToast(data.message || "Error submitting booking", "error");
                        }
                    }
                } catch (error) {
                    console.error('Booking error:', error);

                    // Hide loading overlay
                    loadingOverlay.style.display = 'none';

                    // Re-enable modal
                    modalBody.classList.remove('opacity-50', 'pointer-events-none');

                    // Re-enable buttons
                    submitButton.disabled = false;
                    cancelButton.disabled = false;
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    cancelButton.classList.remove('opacity-50', 'cursor-not-allowed');

                    await showToast("Error submitting booking. Please try again.", "error");
                }
            });

            function validateForm() {
                const requiredFields = {
                    'title': 'Service Title',
                    'description': 'Service Description',
                    'addressInput': 'Address',
                    'latitude': 'Location',
                    'longitude': 'Location',
                    'bookingDate': 'Booking Date',
                    'hourInput': 'Hour',
                    'minuteInput': 'Minute',
                    'ampmInput': 'AM/PM'
                };

                let missingFields = [];
                let isValid = true;

                // Clear previous error styles
                document.querySelectorAll('.error-message').forEach(el => el.remove());
                document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));

                for (let [fieldId, label] of Object.entries(requiredFields)) {
                    const element = document.getElementById(fieldId);
                    if (!element || !element.value.trim()) {
                        isValid = false;
                        if (element) {
                            element.classList.add('border-red-500');

                            // Add error message below the field
                            const errorMessage = document.createElement('div');
                            errorMessage.className = 'error-message text-red-500 text-sm mt-1';
                            errorMessage.innerHTML =
                                `<i class="fas fa-exclamation-circle mr-1"></i>${label} is required`;
                            element.parentNode.appendChild(errorMessage);
                        }
                        missingFields.push(label);
                    }
                }

                if (!isValid) {
                    // Show comprehensive error message
                    showToast(`Please fill in all required fields: ${missingFields.join(', ')}`, "error");

                    // Scroll to first error
                    const firstError = document.querySelector('.border-red-500');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                }

                return isValid;
            }
        }

        document.addEventListener("DOMContentLoaded", initMap);

        window.addEventListener("load", function() {
            setTimeout(() => {
                map.invalidateSize();
            }, 500); // Delay ensures the map is fully rendered
        });

        document.getElementById("addressInput").addEventListener("focus", function() {
            setTimeout(() => {
                map.invalidateSize();
            }, 300);
        });

        const toastStyles = `
<style>
    .toast-enter {
        transform: translateX(100%);
        opacity: 0;
    }
    .toast-enter-active {
        transform: translateX(0);
        opacity: 1;
        transition: all 0.3s ease-out;
    }
    .toast-exit {
        transform: translateX(0);
        opacity: 1;
    }
    .toast-exit-active {
        transform: translateX(100%);
        opacity: 0;
        transition: all 0.3s ease-in;
    }
</style>
`;
        document.head.insertAdjacentHTML('beforeend', toastStyles);

        // Updated toast function with proper animation handling
        let activeToast = null;

        function showToast(message, type = "info") {
            return new Promise((resolve) => {
                // If there's an active toast, remove it first
                if (activeToast) {
                    activeToast.classList.add('toast-exit');
                    activeToast.classList.add('toast-exit-active');
                    setTimeout(() => {
                        if (activeToast && activeToast.parentNode) {
                            document.body.removeChild(activeToast);
                        }
                        createNewToast();
                    }, 300);
                } else {
                    createNewToast();
                }

                function createNewToast() {
                    const toast = document.createElement("div");
                    toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50 toast-enter ${
                type === "warning" ? "bg-yellow-100 text-yellow-800 border-l-4 border-yellow-500" :
                type === "error" ? "bg-red-100 text-red-800 border-l-4 border-red-500" :
                type === "success" ? "bg-green-100 text-green-800 border-l-4 border-green-500" :
                type === "submitting" ? "bg-blue-100 text-blue-800 border-l-4 border-blue-500" :
                "bg-gray-100 text-gray-800 border-l-4 border-gray-500"
            }`;

                    toast.innerHTML = `<div class="flex items-center">
                <i class="fas fa-${
                    type === "warning" ? "exclamation-triangle" :
                    type === "error" ? "times-circle" :
                    type === "success" ? "check-circle" :
                    type === "submitting" ? "spinner fa-spin" :
                    "info-circle"
                } mr-2"></i>
                <span>${message}</span>
            </div>`;

                    document.body.appendChild(toast);
                    activeToast = toast;

                    // Start enter animation
                    requestAnimationFrame(() => {
                        toast.classList.add('toast-enter-active');
                        toast.classList.remove('toast-enter');
                    });

                    // Remove after delay
                    setTimeout(() => {
                        toast.classList.add('toast-exit');
                        toast.classList.add('toast-exit-active');
                        setTimeout(() => {
                            if (toast.parentNode) {
                                document.body.removeChild(toast);
                            }
                            if (activeToast === toast) {
                                activeToast = null;
                            }
                            resolve();
                        }, 300);
                    }, 3000);
                }
            });
        }

        // Initialize the map when the modal is opened
        function openBookingModal(workerId, workerName, serviceType) {

            if (!document.querySelector('input[name="_token"]')) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                document.getElementById('bookingForm').appendChild(csrfInput);
            }

            console.log('Opening modal with:', {
                workerId,
                workerName,
                serviceType
            });

            document.getElementById("workerId").value = workerId;
            document.getElementById("workerName").value = workerName;
            document.getElementById("serviceType").value = serviceType;

            // Reset form fields
            document.getElementById("bookingForm").reset();
            // Re-set the worker ID and service type after reset
            document.getElementById("workerId").value = workerId;
            document.getElementById("serviceType").value = serviceType;
            document.getElementById("workerName").value = workerName;

            document.getElementById("bookingModal").classList.remove("hidden");
            document.body.style.overflow = "hidden";
            setTimeout(initMap, 300);
        }

        function closeBookingModal() {
            document.getElementById("bookingModal").classList.add("hidden");
            document.getElementById("loadingOverlay").style.display = 'none';
            document.body.style.overflow = "";

            // Reset button states in case modal is closed during processing
            const submitButton = document.querySelector('#bookingForm button[type="submit"]');
            const cancelButton = document.querySelector('#bookingForm button[type="button"]');
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
            }
            if (cancelButton) {
                cancelButton.disabled = false;
                cancelButton.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Initialize Flatpickr for Date Picker
            flatpickr("#bookingDate", {
                dateFormat: "F j, Y", // Example: September 5, 2025
                disableMobile: true,
                defaultDate: new Date(),
                theme: "light"
            });

            // Prevent invalid input for Hour and Minute fields
            document.getElementById("hourInput").addEventListener("input", function() {
                let value = parseInt(this.value, 10);
                if (value < 1 || value > 12) {
                    this.value = "";
                }
            });

            document.getElementById("minuteInput").addEventListener("input", function() {
                let value = parseInt(this.value, 10);
                if (value < 0 || value > 59) {
                    this.value = "";
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const activeItem = document.querySelector(".active-item");
            if (activeItem) {
                activeItem.scrollIntoView({
                    behavior: "smooth",
                    inline: "center",
                    block: "nearest"
                });
            }
        });

        // Function to show the conflict modal
        function showConflictModal(message) {
            // Set the message
            document.getElementById('conflictMessage').textContent = message;

            // Show the modal
            const conflictModal = document.getElementById('conflictModal');
            conflictModal.classList.remove('hidden');
            conflictModal.classList.add('flex');

            // Disable scrolling on the body
            document.body.style.overflow = 'hidden';
        }

        // Function to close the conflict modal
        function closeConflictModal() {
            const conflictModal = document.getElementById('conflictModal');
            conflictModal.classList.add('hidden');
            conflictModal.classList.remove('flex');

            // Re-enable scrolling on the body
            document.body.style.overflow = 'auto';
        }
    </script>
@endsection
