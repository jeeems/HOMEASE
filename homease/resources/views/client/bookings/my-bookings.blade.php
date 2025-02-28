@extends('layouts.app')

@section('content')

    <div class="container mx-auto px-4 sm:px-6 py-6">
        <h2 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6 flex items-center">
            <i class="fas fa-calendar-check mr-2 sm:mr-3 text-indigo-600"></i>My Bookings
        </h2>

        @if ($bookings->isEmpty())
            <div class="bg-white rounded-lg shadow-md p-6 sm:p-8 text-center">
                <i class="fas fa-calendar-times text-gray-400 text-4xl sm:text-5xl mb-3 sm:mb-4"></i>
                <p class="text-gray-500 text-base sm:text-lg">You have no bookings yet.</p>
            </div>
        @else
            {{-- Responsive Sliding Navbar --}}
            <div class="mb-4 sm:mb-6">
                <div class="flex overflow-x-auto pb-2 scrollbar-hide" id="booking-tabs">
                    <button
                        class="tab-link flex items-center whitespace-nowrap px-3 sm:px-5 py-2 sm:py-3 mr-2 rounded-lg shadow-sm bg-white border-l-4 border-yellow-400 font-medium transition-all duration-200 hover:shadow-md active text-sm sm:text-base"
                        onclick="showTab('pending')">
                        <i class="fas fa-hourglass-half mr-1 sm:mr-2 text-yellow-500"></i>
                        <span>Pending</span>
                        <span class="ml-1 sm:ml-2 bg-yellow-200 text-yellow-800 rounded-full px-1.5 sm:px-2 py-0.5 text-xs">
                            {{ $bookings->where('status', 'pending')->count() }}
                        </span>
                    </button>
                    <button
                        class="tab-link flex items-center whitespace-nowrap px-3 sm:px-5 py-2 sm:py-3 mr-2 rounded-lg shadow-sm bg-white border-l-4 border-blue-400 font-medium transition-all duration-200 hover:shadow-md text-sm sm:text-base"
                        onclick="showTab('ongoing')">
                        <i class="fas fa-spinner fa-spin mr-1 sm:mr-2 text-blue-500"></i>
                        <span>Ongoing</span>
                        <span class="ml-1 sm:ml-2 bg-blue-200 text-blue-800 rounded-full px-1.5 sm:px-2 py-0.5 text-xs">
                            {{ $bookings->where('status', 'ongoing')->count() }}
                        </span>
                    </button>
                    <button
                        class="tab-link flex items-center whitespace-nowrap px-3 sm:px-5 py-2 sm:py-3 mr-2 rounded-lg shadow-sm bg-white border-l-4 border-green-400 font-medium transition-all duration-200 hover:shadow-md text-sm sm:text-base"
                        onclick="showTab('completed')">
                        <i class="fas fa-check-circle mr-1 sm:mr-2 text-green-500"></i>
                        <span>Completed</span>
                        <span class="ml-1 sm:ml-2 bg-green-200 text-green-800 rounded-full px-1.5 sm:px-2 py-0.5 text-xs">
                            {{ $bookings->where('status', 'completed')->count() }}
                        </span>
                    </button>
                    <button
                        class="tab-link flex items-center whitespace-nowrap px-3 sm:px-5 py-2 sm:py-3 mr-2 rounded-lg shadow-sm bg-white border-l-4 border-red-400 font-medium transition-all duration-200 hover:shadow-md text-sm sm:text-base"
                        onclick="showTab('cancelled')">
                        <i class="fas fa-times-circle mr-1 sm:mr-2 text-red-500"></i>
                        <span>Cancelled</span>
                        <span class="ml-1 sm:ml-2 bg-red-200 text-red-800 rounded-full px-1.5 sm:px-2 py-0.5 text-xs">
                            {{ $bookings->where('status', 'cancelled')->count() }}
                        </span>
                    </button>
                </div>
            </div>

            {{-- Responsive Booking Content --}}
            <div class="booking-content">
                @foreach (['pending', 'ongoing', 'completed', 'cancelled'] as $status)
                    <div id="{{ $status }}-tab"
                        class="booking-tab {{ $status !== 'pending' ? 'hidden' : '' }} transition-all duration-300">
                        <div class="flex items-center mb-3 sm:mb-4">
                            <h3 class="text-lg sm:text-xl font-semibold capitalize flex items-center">
                                <i
                                    class="fas fa-{{ $status == 'pending' ? 'hourglass-half' : ($status == 'ongoing' ? 'spinner fa-spin' : ($status == 'completed' ? 'check-circle' : 'times-circle')) }}
                                mr-1.5 sm:mr-2 text-{{ $status == 'pending' ? 'yellow' : ($status == 'ongoing' ? 'blue' : ($status == 'completed' ? 'green' : 'red')) }}-500"></i>
                                {{ $status }} Bookings
                            </h3>
                        </div>

                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            @if ($bookings->where('status', $status)->isEmpty())
                                <div class="p-4 sm:p-6 text-center">
                                    <i class="fas fa-calendar-alt text-gray-300 text-3xl sm:text-4xl mb-2 sm:mb-3"></i>
                                    <p class="text-gray-500">No {{ $status }} bookings found</p>
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    {{-- Mobile Card View (visible on small screens) --}}
                                    <div class="sm:hidden">
                                        @foreach ($bookings->where('status', $status) as $booking)
                                            <div class="p-4 border-b last:border-b-0">
                                                <div class="font-medium text-gray-900 mb-2">{{ $booking->booking_title }}
                                                </div>

                                                <!-- Worker Profile and Name -->
                                                <div class="flex items-center gap-2 mb-3">
                                                    <img src="{{ asset('storage/' . $booking->worker->profile->profile_picture) }}"
                                                        alt="Worker Profile"
                                                        class="w-10 h-10 rounded-full border border-gray-300">
                                                    <span
                                                        class="text-sm font-medium text-gray-900">{{ $booking->worker->full_name }}</span>
                                                </div>

                                                <!-- Service Type -->
                                                <div class="flex items-center text-sm text-gray-600 mb-1">
                                                    <i class="fas fa-tools mr-2 w-4 text-center"></i>
                                                    <span>{{ $booking->service_type }}</span>
                                                </div>

                                                <!-- Scheduled Date -->
                                                <div class="flex items-center text-sm text-gray-600 mb-1">
                                                    <i class="far fa-calendar-alt mr-2 w-4 text-center"></i>
                                                    <span>{{ \Carbon\Carbon::parse($booking->scheduled_date)->format('M d, Y') }}</span>
                                                </div>

                                                <!-- Scheduled Time -->
                                                <div class="flex items-center text-sm text-gray-600 mb-3">
                                                    <i class="far fa-clock mr-2 w-4 text-center"></i>
                                                    <span>{{ \Carbon\Carbon::parse($booking->scheduled_date)->format('h:i A') }}</span>
                                                </div>

                                                <!-- Status and Action Buttons -->
                                                <div class="flex items-center justify-between">
                                                    <span
                                                        class="px-2.5 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full
                                                        {{ $status == 'pending'
                                                            ? 'bg-yellow-100 text-yellow-800'
                                                            : ($status == 'ongoing'
                                                                ? 'bg-blue-100 text-blue-800'
                                                                : ($status == 'cancelled'
                                                                    ? 'bg-red-100 text-red-800'
                                                                    : 'bg-green-100 text-green-800')) }}">
                                                        <i
                                                            class="fas fa-{{ $status == 'pending'
                                                                ? 'hourglass-half'
                                                                : ($status == 'ongoing'
                                                                    ? 'spinner fa-spin'
                                                                    : ($status == 'completed'
                                                                        ? 'check-circle'
                                                                        : 'times-circle')) }} text-sm mr-1"></i>
                                                        {{ ucfirst($status) }}
                                                    </span>


                                                    <!-- Cancel Button (Only for Pending Bookings) -->
                                                    @if ($status == 'pending')
                                                        <form action="{{ route('bookings.cancel', $booking->id) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="button"
                                                                class="bg-red-500 text-white px-3 py-1.5 rounded-md inline-flex items-center text-sm transition-all duration-200 hover:bg-red-600 shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50"
                                                                onclick="showCancelModal('{{ route('bookings.cancel', $booking->id) }}')">
                                                                <i class="fas fa-times-circle mr-1.5"></i> Cancel
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if ($status == 'completed')
                                                        <div class="flex items-center gap-2">
                                                            @php
                                                                // Check if rating exists for this booking
                                                                $rating = \App\Models\Rating::where(
                                                                    'booking_id',
                                                                    $booking->id,
                                                                )
                                                                    ->where('client_id', Auth::id())
                                                                    ->first();
                                                            @endphp

                                                            @if ($rating)
                                                                <div
                                                                    class="flex items-center bg-gray-100 px-3 py-1.5 rounded-md">
                                                                    <!-- Display the star rating -->
                                                                    <i class="fas fa-star text-yellow-500"></i>
                                                                    <span
                                                                        class="ml-1.5 text-sm text-gray-700">{{ $rating->rating }}</span>
                                                                </div>
                                                            @else
                                                                <!-- Show Rate Worker button if no rating yet -->
                                                                <button type="button"
                                                                    onclick="showRatingModal('{{ asset('storage/' . $booking->worker->profile->profile_picture) }}', '{{ $booking->worker->name }}', '{{ $booking->service_type }}', '{{ $booking->id }}', '{{ $booking->booking_title }}', '{{ $booking->worker_id }}')"
                                                                    class="bg-green-500 text-white px-3 py-1.5 rounded-md inline-flex items-center text-sm transition-all duration-200 hover:bg-green-600 shadow-sm">
                                                                    <i class="fas fa-star text-sm mr-1.5"></i> Rate Worker
                                                                </button>
                                                            @endif

                                                            <button type="button"
                                                                onclick="viewReceipt('{{ $booking->id }}', '{{ $booking->booking_title }}', '{{ $booking->worker->full_name }}', '{{ $booking->service_type }}', '{{ \Carbon\Carbon::parse($booking->scheduled_date)->format('F d, Y h:i A') }}', '{{ isset($booking->completion_date) ? \Carbon\Carbon::parse($booking->completion_date)->format('F d, Y h:i A') : 'Not recorded' }}', '{{ $booking->hours_worked ?? 'N/A' }}', '{{ number_format($booking->total_amount ?? 0, 2) }}')"
                                                                class="bg-white border border-gray-300 text-gray-700 px-3 py-1.5 rounded-md inline-flex items-center text-sm transition-all duration-200 hover:bg-gray-50 shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-opacity-50">
                                                                <i class="fas fa-receipt text-sm mr-1.5"></i> Receipt
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- Table View (visible on larger screens) --}}
                                    <table class="min-w-full divide-y divide-gray-200 hidden sm:table">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col"
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    <i class="fas fa-tag mr-1"></i> Title
                                                </th>
                                                <th scope="col"
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    <i class="fas fa-user-circle mr-1"></i> Worker
                                                </th>
                                                <th scope="col"
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    <i class="fas fa-tools mr-1"></i> Service Type
                                                </th>
                                                <th scope="col"
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    <i class="fas fa-clock mr-1"></i> Scheduled Date
                                                </th>
                                                <th scope="col"
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    <i class="fas fa-info-circle mr-1"></i> Status
                                                </th>
                                                @if ($status == 'pending')
                                                    <th scope="col"
                                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        <i class="fas fa-star mr-1"></i> Action
                                                    </th>
                                                @endif
                                                @if ($status == 'completed')
                                                    <th scope="col"
                                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        <i class="fas fa-star mr-1"></i> Action
                                                    </th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($bookings->where('status', $status) as $booking)
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $booking->booking_title }}</div>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <img src="{{ asset('storage/' . $booking->worker->profile->profile_picture) }}"
                                                                alt="Worker Profile"
                                                                class="w-8 h-8 rounded-full border border-gray-300">
                                                            <div class="ml-3">
                                                                <div class="text-sm text-gray-500">
                                                                    {{ $booking->worker->full_name }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-500">{{ $booking->service_type }}
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-500">
                                                            <div><i class="far fa-calendar-alt mr-1"></i>
                                                                {{ \Carbon\Carbon::parse($booking->scheduled_date)->format('M d, Y') }}
                                                            </div>
                                                            <div><i class="far fa-clock mr-1"></i>
                                                                {{ \Carbon\Carbon::parse($booking->scheduled_date)->format('h:i A') }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        <span
                                                            class="px-2.5 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full
                                                            {{ $status == 'pending'
                                                                ? 'bg-yellow-100 text-yellow-800'
                                                                : ($status == 'ongoing'
                                                                    ? 'bg-blue-100 text-blue-800'
                                                                    : ($status == 'cancelled'
                                                                        ? 'bg-red-100 text-red-800'
                                                                        : 'bg-green-100 text-green-800')) }}">
                                                            <i
                                                                class="fas fa-{{ $status == 'pending'
                                                                    ? 'hourglass-half'
                                                                    : ($status == 'ongoing'
                                                                        ? 'spinner fa-spin'
                                                                        : ($status == 'completed'
                                                                            ? 'check-circle'
                                                                            : 'times-circle')) }}
                                                                text-sm mr-1"></i>
                                                            {{ ucfirst($status) }}
                                                        </span>
                                                    </td>
                                                    @if ($status == 'pending')
                                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                                            <form action="{{ route('bookings.cancel', $booking->id) }}"
                                                                method="POST" class="inline">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="button"
                                                                    class="bg-red-500 text-white px-3 py-1.5 rounded-md inline-flex items-center transition-all duration-200 hover:bg-red-600 shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50"
                                                                    onclick="showCancelModal('{{ route('bookings.cancel', $booking->id) }}')">
                                                                    <i class="fas fa-times-circle mr-1.5"></i> Cancel
                                                                </button>
                                                            </form>
                                                        </td>
                                                    @endif
                                                    @if ($status == 'completed')
                                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                                            <div class="flex items-center gap-2">

                                                                @php
                                                                    // Check if rating exists for this booking
                                                                    $rating = \App\Models\Rating::where(
                                                                        'booking_id',
                                                                        $booking->id,
                                                                    )
                                                                        ->where('client_id', Auth::id())
                                                                        ->first();
                                                                @endphp
                                                                @if ($rating)
                                                                    <div
                                                                        class="flex items-center bg-gray-100 px-3 py-1.5 rounded-md">
                                                                        <!-- Display the star rating -->
                                                                        <i class="fas fa-star text-yellow-500"></i>
                                                                        <span
                                                                            class="ml-1.5 text-sm text-gray-700">{{ $rating->rating }}</span>
                                                                    </div>
                                                                @else
                                                                    <button type="button"
                                                                        onclick="showRatingModal('{{ asset('storage/' . $booking->worker->profile->profile_picture) }}', '{{ $booking->worker->name }}', '{{ $booking->service_type }}', '{{ $booking->id }}', '{{ $booking->booking_title }}', '{{ $booking->worker_id }}')"
                                                                        class="bg-green-500 text-white px-3 py-1.5 rounded-md inline-flex items-center text-sm transition-all duration-200 hover:bg-green-600 shadow-sm">
                                                                        <i class="fas fa-star text-sm mr-1.5"></i> Rate
                                                                        Worker
                                                                    </button>
                                                                @endif

                                                                <button type="button"
                                                                    onclick="viewReceipt('{{ $booking->id }}', '{{ $booking->booking_title }}', '{{ $booking->worker->full_name }}', '{{ $booking->service_type }}', '{{ \Carbon\Carbon::parse($booking->scheduled_date)->format('F d, Y h:i A') }}', '{{ isset($booking->completion_date) ? \Carbon\Carbon::parse($booking->completion_date)->format('F d, Y h:i A') : 'Not recorded' }}', '{{ $booking->hours_worked ?? 'N/A' }}', '{{ number_format($booking->total_amount ?? 0, 2) }}')"
                                                                    class="bg-white border border-gray-300 text-gray-700 px-3 py-1.5 rounded-md inline-flex items-center transition-all duration-200 hover:bg-gray-50 shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-opacity-50">
                                                                    <i class="fas fa-receipt mr-1.5"></i> View Receipt
                                                                </button>
                                                            </div>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Confirmation Modal -->
    <div id="cancelConfirmationModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-80">
            <h2 class="text-lg font-semibold mb-3">Cancel Booking</h2>
            <p class="text-gray-600 mb-4">Are you sure you want to cancel this booking?</p>
            <div class="flex justify-end">
                <button id="closeModal" class="px-4 py-2 bg-gray-300 rounded-md mr-2">No</button>
                <form id="cancelForm" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Yes,
                        Cancel</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Receipt Modal -->
    <div id="receiptModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 sm:mx-0 max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900">
                        <i class="fas fa-receipt mr-2 text-indigo-600"></i> Booking Receipt
                    </h3>
                    <button onclick="closeReceiptModal()" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <div class="mb-6 text-center">
                        <h4 id="receipt-booking-title" class="text-xl font-bold text-indigo-700 mb-1"></h4>
                        <p id="receipt-service-type" class="text-gray-600"></p>
                    </div>

                    <div class="mb-6">
                        <div class="flex items-start space-x-3 mb-6"> <!-- Changed items-center to items-start -->
                            <img src="{{ asset('storage/' . $booking->worker->profile->profile_picture) }}"
                                alt="Worker Profile" class="w-10 h-10 rounded-full border border-gray-300 mt-1">
                            <!-- Added mt-1 -->
                            <div>
                                <div class="text-sm text-gray-500">Worker</div>
                                <div id="receipt-worker-name" class="font-medium"></div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-hashtag w-5 text-center mt-1 text-gray-500 mr-3"></i>
                            <div>
                                <div class="text-sm text-gray-500">Booking ID</div>
                                <div id="receipt-booking-id" class="font-medium"></div>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <i class="far fa-calendar-alt w-5 text-center mt-1 text-gray-500 mr-3"></i>
                            <div>
                                <div class="text-sm text-gray-500">Start Time</div>
                                <div id="receipt-start-time" class="font-medium"></div>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <i class="far fa-clock w-5 text-center mt-1 text-gray-500 mr-3"></i>
                            <div>
                                <div class="text-sm text-gray-500">End Time</div>
                                <div id="receipt-end-time" class="font-medium"></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Hours Rendered:</span>
                            <span id="receipt-hours-rendered" class="font-semibold"></span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                            <span class="text-lg font-bold text-gray-700">Total Amount:</span>
                            <span id="receipt-total-amount" class="text-lg font-bold text-indigo-700"></span>
                        </div>
                    </div>

                    <div class="text-center">
                        <button onclick="closeReceiptModal()"
                            class="bg-indigo-600 text-white py-2 px-6 rounded-lg hover:bg-indigo-700 transition-colors duration-200 font-medium">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Modal -->
    <div id="ratingModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden"
        data-worker-id="" data-booking-id="">
        <div class="bg-white rounded-lg p-6 w-11/12 max-w-md shadow-lg">
            <div class="flex flex-col items-center">
                <!-- Worker Profile -->
                <img id="workerProfilePic" class="w-25 h-25 rounded-full border border-gray-300" alt="Worker Profile">
                <h2 id="workerName" class="mt-2 text-lg font-medium text-gray-900"></h2>
                <p id="workerServiceType" class="text-sm text-gray-600"></p>
            </div>

            <!-- Booking Info -->
            <div class="mt-4">
                <p class="text-sm text-gray-700"><strong>Booking ID:</strong> <span id="bookingId"></span></p>
                <p class="text-sm text-gray-700"><strong>Booking Title:</strong> <span id="bookingTitle"></span></p>
            </div>

            <!-- Rating Value Display -->
            <div id="ratingValue" class="text-yellow-500 text-xl font-bold text-center mt-2"></div>

            <!-- Star Rating -->
            <div class="mt-4 flex justify-center">
                <div class="flex space-x-2 text-3xl" id="starRating">
                    <i class="fas fa-star text-gray-400 cursor-pointer" data-value="1"></i>
                    <i class="fas fa-star text-gray-400 cursor-pointer" data-value="2"></i>
                    <i class="fas fa-star text-gray-400 cursor-pointer" data-value="3"></i>
                    <i class="fas fa-star text-gray-400 cursor-pointer" data-value="4"></i>
                    <i class="fas fa-star text-gray-400 cursor-pointer" data-value="5"></i>
                </div>
            </div>

            <!-- Review Textarea -->
            <textarea id="reviewComment" class="mt-4 w-full p-2 border rounded" placeholder="Write your review here..."></textarea>

            <!-- Photo Upload (Optional) -->
            <div class="mt-4">
                <label for="reviewPhotos" class="block text-sm font-medium text-gray-700">Upload Photos (Optional)</label>
                <div class="relative border-dashed border-2 border-gray-300 rounded-lg p-4 text-center cursor-pointer">
                    <input type="file" id="reviewPhotos" class="absolute inset-0 opacity-0 cursor-pointer" multiple
                        accept="image/*" onchange="previewImages(event)">
                    <p class="text-gray-500">Click to upload</p>
                </div>
                <div id="photoPreview" class="flex flex-wrap gap-2 mt-2"></div>
            </div>

            <!-- Buttons -->
            <div class="mt-4 flex justify-between">
                <button onclick="closeRatingModal()" class="px-4 py-2 bg-gray-400 text-white rounded">Cancel</button>
                <button onclick="submitRating()" class="px-4 py-2 bg-green-500 text-white rounded">Submit</button>
            </div>
        </div>
    </div>

    {{-- JavaScript for Tabs with Sliding Effect --}}
    <script>
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.booking-tab').forEach(tab => {
                tab.classList.add('hidden');
            });

            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-link').forEach(button => {
                button.classList.remove('active', 'shadow-md');
                button.classList.add('shadow-sm');
            });

            // Show selected tab with animation
            const selectedTab = document.getElementById(tabName + '-tab');
            selectedTab.classList.remove('hidden');

            // Add active class to selected button
            const activeButton = document.querySelector(`[onclick="showTab('${tabName}')"]`);
            activeButton.classList.add('active', 'shadow-md');

            // Scroll the tab button into view if needed
            activeButton.scrollIntoView({
                behavior: 'smooth',
                inline: 'center',
                block: 'nearest'
            });
        }

        // Add active class styling and responsive utilities
        document.head.insertAdjacentHTML('beforeend', `
        <style>
            .tab-link.active {
                background-color: #f8fafc;
                transform: translateY(-2px);
            }

            /* Hide scrollbar */
            .scrollbar-hide::-webkit-scrollbar {
                display: none;
            }

            .scrollbar-hide {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            /* Animation for tab transitions */
            @keyframes slideIn {
                from { opacity: 0; transform: translateX(20px); }
                to { opacity: 1; transform: translateX(0); }
            }

            .booking-tab {
                animation: slideIn 0.3s ease-out;
            }

            /* Auto-scroll tabs into view on smaller screens */
            @media (max-width: 640px) {
                #booking-tabs {
                    -webkit-overflow-scrolling: touch;
                    scroll-snap-type: x mandatory;
                }

                .tab-link {
                    scroll-snap-align: start;
                }
            }
        </style>
    `);

        // Initialize touch swipe support for mobile
        let startX, startY;
        const tabContent = document.querySelector('.booking-content');

        tabContent.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        }, false);

        tabContent.addEventListener('touchend', (e) => {
            if (!startX || !startY) return;

            let endX = e.changedTouches[0].clientX;
            let endY = e.changedTouches[0].clientY;

            let diffX = startX - endX;
            let diffY = startY - endY;

            // Only register as horizontal swipe if horizontal movement is greater than vertical
            if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                // Get current tab
                const currentTab = document.querySelector('.booking-tab:not(.hidden)');
                const tabId = currentTab.id.replace('-tab', '');

                // Get tab order
                const tabOrder = ['pending', 'ongoing', 'completed', 'cancelled'];
                const currentIndex = tabOrder.indexOf(tabId);

                // Swipe left (next tab)
                if (diffX > 0 && currentIndex < tabOrder.length - 1) {
                    showTab(tabOrder[currentIndex + 1]);
                }
                // Swipe right (previous tab)
                else if (diffX < 0 && currentIndex > 0) {
                    showTab(tabOrder[currentIndex - 1]);
                }
            }

            startX = null;
            startY = null;
        }, false);

        // Function to format booking ID based on service type
        function formatBookingId(bookingId, service_type) {
            // Pad the booking ID to 7 digits
            const paddedId = String(bookingId).padStart(7, '0');

            // Format based on service type
            switch (service_type) {
                case 'Electrician':
                    return `ELECT-${paddedId}`;
                case 'Daycare':
                    return `DC-${paddedId}`;
                case 'Home Cleaning':
                    return `HC-${paddedId}`;
                case 'Plumbing':
                    return `PLUMB-${paddedId}`; // Note: Fixed prefix from DC to PLUMB
                case 'Carpentry':
                    return `CARP-${paddedId}`;
                default:
                    return paddedId;
            }
        }

        // Function to show the receipt modal
        function viewReceipt(bookingId, bookingTitle, workerName, serviceType, startTime, endTime, hoursRendered,
            totalAmount) {

            // Format the booking ID
            const formattedBookingId = formatBookingId(bookingId, serviceType);

            // Set the modal content
            document.getElementById('receipt-booking-title').textContent = bookingTitle;
            document.getElementById('receipt-service-type').textContent = serviceType;
            document.getElementById('receipt-worker-name').textContent = workerName;
            document.getElementById('receipt-booking-id').textContent = formattedBookingId; // Updated to use formatted ID
            document.getElementById('receipt-start-time').textContent = startTime;
            document.getElementById('receipt-end-time').textContent = endTime;
            document.getElementById('receipt-hours-rendered').textContent = hoursRendered + ' hrs';
            document.getElementById('receipt-total-amount').textContent = '₱' + totalAmount;

            // Show the modal
            document.getElementById('receiptModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden'); // Prevent scrolling
        }

        // Function to close the receipt modal
        function closeReceiptModal() {
            document.getElementById('receiptModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden'); // Re-enable scrolling
        }

        // Close the modal when clicking outside of it
        document.getElementById('receiptModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeReceiptModal();
            }
        });

        function showCancelModal(route) {
            document.getElementById('cancelForm').action = route;
            document.getElementById('cancelConfirmationModal').classList.remove('hidden');
        }

        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('cancelConfirmationModal').classList.add('hidden');
        });

        // Show Modal
        function showRatingModal(workerProfile, workerName, workerServiceType, bookingId, bookingTitle, workerId) {
            console.log("Worker Service Type:", workerServiceType);
            console.log("Booking ID:", bookingId);

            // Store the raw bookingId (unformatted) as a data attribute
            const formattedBookingId = formatBookingId(bookingId, workerServiceType);
            console.log("Formatted Booking ID:", formattedBookingId);

            // Set all necessary data as attributes on the modal itself
            const modal = document.getElementById('ratingModal');
            modal.setAttribute('data-worker-id', workerId);
            modal.setAttribute('data-booking-id', bookingId);

            document.getElementById('workerProfilePic').src = workerProfile;
            document.getElementById('workerName').textContent = workerName;
            document.getElementById('workerServiceType').textContent = workerServiceType;
            document.getElementById('bookingId').textContent = formattedBookingId;
            document.getElementById('bookingTitle').textContent = bookingTitle;

            modal.classList.remove('hidden');
        }

        // Close Modal
        function closeRatingModal() {
            const modal = document.getElementById('ratingModal');
            modal.classList.add('hidden');
            document.getElementById('reviewComment').value = "";
            document.getElementById('photoPreview').innerHTML = "";

            // Reset selected rating
            selectedRating = 0;

            // Reset stars to initial state
            const stars = document.querySelectorAll("#starRating i");
            stars.forEach(star => {
                star.classList.remove("text-yellow-500");
                star.classList.add("text-gray-400");
            });
            document.getElementById("ratingValue").textContent = "";
        }

        // Star Rating System
        let selectedRating = 0;

        // Star Rating System
        document.addEventListener("DOMContentLoaded", function() {
            const stars = document.querySelectorAll("#starRating i");
            const ratingValue = document.getElementById("ratingValue");

            stars.forEach(star => {
                star.addEventListener("mouseover", function() {
                    highlightStars(this.getAttribute("data-value"));
                });

                star.addEventListener("click", function() {
                    setRating(this.getAttribute("data-value"));
                });

                star.addEventListener("mouseleave", function() {
                    resetStars();
                });
            });

            function highlightStars(value) {
                stars.forEach(star => {
                    star.classList.toggle("text-yellow-500", star.getAttribute("data-value") <= value);
                });
                ratingValue.textContent = value + " Star" + (value > 1 ? "s" : "");
            }

            function setRating(value) {
                selectedRating = value; // Set selected rating globally
                stars.forEach(star => {
                    star.classList.toggle("text-yellow-500", star.getAttribute("data-value") <= value);
                    star.classList.toggle("text-gray-400", star.getAttribute("data-value") > value);
                });
                ratingValue.textContent = value + " Star" + (value > 1 ? "s" : "");
            }

            function resetStars() {
                if (!selectedRating) {
                    stars.forEach(star => star.classList.add("text-gray-400"));
                    ratingValue.textContent = "";
                } else {
                    // If a rating is already selected, keep showing the selected stars
                    stars.forEach(star => {
                        star.classList.toggle("text-yellow-500", star.getAttribute("data-value") <=
                            selectedRating);
                        star.classList.toggle("text-gray-400", star.getAttribute("data-value") >
                            selectedRating);
                    });
                }
            }
        });

        function previewImages(event) {
            const previewContainer = document.getElementById('photoPreview');
            const files = event.target.files;

            if (files.length > 0) {
                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgContainer = document.createElement('div');
                        imgContainer.classList.add('relative');

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('w-16', 'h-16', 'rounded-lg', 'border', 'border-gray-300',
                            'object-cover');

                        // Remove button
                        const removeBtn = document.createElement('button');
                        removeBtn.innerHTML = '&times;';
                        removeBtn.classList.add('absolute', 'top-0', 'right-0', 'bg-red-500', 'text-white',
                            'rounded-full', 'w-5', 'h-5', 'text-xs', 'flex', 'items-center',
                            'justify-center');

                        removeBtn.onclick = () => imgContainer.remove();

                        imgContainer.appendChild(img);
                        imgContainer.appendChild(removeBtn);
                        previewContainer.appendChild(imgContainer);
                    };
                    reader.readAsDataURL(file);
                });
            }
        }

        function submitRating() {
            // Get data directly from the modal's data attributes
            const modal = document.getElementById('ratingModal');
            const workerId = modal.getAttribute('data-worker-id');
            const bookingId = modal.getAttribute('data-booking-id');

            const bookingTitle = document.getElementById('bookingTitle').textContent.trim();
            const rating = selectedRating;
            const comment = document.getElementById('reviewComment').value.trim();

            console.log("Submit Rating - Worker ID:", workerId);
            console.log("Submit Rating - Booking ID:", bookingId);

            let missingFields = [];

            if (!workerId) missingFields.push("Worker ID");
            if (!bookingId) missingFields.push("Booking ID");
            if (!rating || rating === 0) missingFields.push("Star Rating");

            // If any required field is missing, alert the user and highlight the missing fields.
            if (missingFields.length > 0) {
                alert(`Please complete all required fields before submitting:\n- ${missingFields.join("\n- ")}`);
                return;
            }

            const formData = new FormData();
            formData.append('worker_id', workerId);
            formData.append('booking_id', bookingId);
            formData.append('booking_title', bookingTitle);
            formData.append('rating', rating);
            formData.append('comment', comment);

            const reviewPhotos = document.getElementById('reviewPhotos').files;
            for (let i = 0; i < reviewPhotos.length; i++) {
                formData.append('review_photos[]', reviewPhotos[i]);
            }

            fetch('{{ route('rating.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Rating submitted successfully!");
                        closeRatingModal();

                        // Refresh the page after a slight delay to ensure updates are loaded
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    } else {
                        let errorMessage = "Failed to submit rating.";
                        if (data.errors) {
                            errorMessage += "\n" + Object.values(data.errors).flat().join("\n");
                        }
                        alert(errorMessage);
                    }
                })
                .catch(error => {
                    console.error("Error submitting rating:", error);
                    alert("An error occurred. Please try again.");
                });
        }

        function showReviewDetails(bookingId) {
            // Fetch the review details via AJAX
            fetch(`/bookings/${bookingId}/rating`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Populate the modal with existing review data
                        document.getElementById('workerProfilePic').src = data.worker_profile_picture;
                        document.getElementById('workerName').textContent = data.worker_name;
                        document.getElementById('workerServiceType').textContent = data.service_type;
                        document.getElementById('bookingId').textContent = data.booking_id;
                        document.getElementById('bookingTitle').textContent = data.booking_title;

                        // Set the rating value display
                        document.getElementById('ratingValue').textContent = `${data.rating} / 5`;

                        // Highlight the stars based on the rating
                        const stars = document.querySelectorAll('#starRating i');
                        stars.forEach((star, index) => {
                            if (index < data.rating) {
                                star.classList.remove('text-gray-400');
                                star.classList.add('text-yellow-500');
                            } else {
                                star.classList.remove('text-yellow-500');
                                star.classList.add('text-gray-400');
                            }
                            // Disable star clicking for viewing mode
                            star.classList.remove('cursor-pointer');
                        });

                        // Set the review comment
                        document.getElementById('reviewComment').value = data.comment;
                        document.getElementById('reviewComment').disabled = true;

                        // Display review photos if any
                        const photoPreview = document.getElementById('photoPreview');
                        photoPreview.innerHTML = '';
                        if (data.photos && data.photos.length > 0) {
                            data.photos.forEach(photo => {
                                const img = document.createElement('img');
                                img.src = photo;
                                img.className = 'w-16 h-16 object-cover rounded';
                                photoPreview.appendChild(img);
                            });
                        }

                        // Hide file upload
                        document.querySelector('label[for="reviewPhotos"]').style.display = 'none';
                        document.querySelector('input#reviewPhotos').parentElement.style.display = 'none';

                        // Change buttons for view mode
                        const buttonsContainer = document.querySelector('#ratingModal .flex.justify-between');
                        buttonsContainer.innerHTML = `
                    <button onclick="closeRatingModal()" class="px-4 py-2 bg-indigo-600 text-white rounded w-full">Close</button>
                `;

                        // Show the modal
                        document.getElementById('ratingModal').classList.remove('hidden');
                        document.getElementById('ratingModal').setAttribute('data-mode', 'view');
                    } else {
                        console.error('Failed to fetch review details');
                    }
                })
                .catch(error => {
                    console.error('Error fetching review:', error);
                });
        }

        // Modify the existing closeRatingModal function to handle view mode
        function closeRatingModal() {
            const modal = document.getElementById('ratingModal');
            modal.classList.add('hidden');

            // If modal was in view mode, reset it back to rating mode
            if (modal.getAttribute('data-mode') === 'view') {
                // Reset stars to be clickable
                const stars = document.querySelectorAll('#starRating i');
                stars.forEach(star => {
                    star.classList.remove('text-yellow-500');
                    star.classList.add('text-gray-400');
                    star.classList.add('cursor-pointer');
                });

                // Reset comment area
                document.getElementById('reviewComment').value = '';
                document.getElementById('reviewComment').disabled = false;

                // Show file upload again
                document.querySelector('label[for="reviewPhotos"]').style.display = 'block';
                document.querySelector('input#reviewPhotos').parentElement.style.display = 'block';

                // Reset buttons to rating mode
                const buttonsContainer = document.querySelector('#ratingModal .flex.justify-between');
                buttonsContainer.innerHTML = `
            <button onclick="closeRatingModal()" class="px-4 py-2 bg-gray-400 text-white rounded">Cancel</button>
            <button onclick="submitRating()" class="px-4 py-2 bg-green-500 text-white rounded">Submit</button>
        `;

                // Clear photo preview
                document.getElementById('photoPreview').innerHTML = '';

                // Reset data mode
                modal.setAttribute('data-mode', 'rate');
            }
        }
    </script>
@endsection
