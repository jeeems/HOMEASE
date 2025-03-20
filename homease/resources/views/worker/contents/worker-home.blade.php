@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 py-6">
        @if (Auth::user()->role == 'worker')
            <div class="text-center mb-6">
                <h3 class="text-xl sm:text-2xl font-bold flex items-center justify-center">
                    <span>Welcome, {{ Auth::user()->first_name }}!</span>
                    <span class="ml-2">👋</span>
                </h3>
                <p class="text-gray-500 mt-1">Here are your pending or ongoing bookings.</p>
            </div>

            @php
                $workerServiceType = Auth::user()->workerVerification->service_type ?? null;
                $filteredBookings = $bookings->filter(function ($booking) use ($workerServiceType) {
                    return $booking->service_type == $workerServiceType;
                });

                // Group bookings by status
                $pendingBookings = $filteredBookings->where('status', 'pending');
                $ongoingBookings = $filteredBookings->where('status', 'ongoing');
                $completedBookings = $filteredBookings->where('status', 'completed');
                $cancelledBookings = $filteredBookings->where('status', 'cancelled');
            @endphp

            @if ($filteredBookings->count() > 0)
                {{-- Responsive Sliding Navbar --}}
                <div class="mb-4 sm:mb-6">
                    <div class="flex overflow-x-auto pb-2 scrollbar-hide" id="booking-tabs">
                        <button
                            class="tab-link flex items-center whitespace-nowrap px-3 sm:px-5 py-2 sm:py-3 mr-2 rounded-lg shadow-sm bg-white border-l-4 border-yellow-400 font-medium transition-all duration-200 hover:shadow-md active text-sm sm:text-base"
                            onclick="showTab('pending')">
                            <i class="fas fa-hourglass-half mr-1 sm:mr-2 text-yellow-500"></i>
                            <span>New Requests</span>
                            <span
                                class="ml-1 sm:ml-2 bg-yellow-200 text-yellow-800 rounded-full px-1.5 sm:px-2 py-0.5 text-xs">
                                {{ $pendingBookings->count() }}
                            </span>
                        </button>
                        <button
                            class="tab-link flex items-center whitespace-nowrap px-3 sm:px-5 py-2 sm:py-3 mr-2 rounded-lg shadow-sm bg-white border-l-4 border-blue-400 font-medium transition-all duration-200 hover:shadow-md text-sm sm:text-base"
                            onclick="showTab('ongoing')">
                            <i class="fas fa-spinner fa-spin mr-1 sm:mr-2 text-blue-500"></i>
                            <span>In Progress</span>
                            <span class="ml-1 sm:ml-2 bg-blue-200 text-blue-800 rounded-full px-1.5 sm:px-2 py-0.5 text-xs">
                                {{ $ongoingBookings->count() }}
                            </span>
                        </button>
                        <button
                            class="tab-link flex items-center whitespace-nowrap px-3 sm:px-5 py-2 sm:py-3 mr-2 rounded-lg shadow-sm bg-white border-l-4 border-green-400 font-medium transition-all duration-200 hover:shadow-md text-sm sm:text-base"
                            onclick="showTab('completed')">
                            <i class="fas fa-check-circle mr-1 sm:mr-2 text-green-500"></i>
                            <span>Completed</span>
                            <span
                                class="ml-1 sm:ml-2 bg-green-200 text-green-800 rounded-full px-1.5 sm:px-2 py-0.5 text-xs">
                                {{ $completedBookings->count() }}
                            </span>
                        </button>
                        <button
                            class="tab-link flex items-center whitespace-nowrap px-3 sm:px-5 py-2 sm:py-3 mr-2 rounded-lg shadow-sm bg-white border-l-4 border-red-400 font-medium transition-all duration-200 hover:shadow-md text-sm sm:text-base"
                            onclick="showTab('cancelled')">
                            <i class="fas fa-times-circle mr-1 sm:mr-2 text-red-500"></i>
                            <span>Cancelled</span>
                            <span class="ml-1 sm:ml-2 bg-red-200 text-red-800 rounded-full px-1.5 sm:px-2 py-0.5 text-xs">
                                {{ $cancelledBookings->count() }}
                            </span>
                        </button>
                    </div>
                </div>

                {{-- Booking Content with Tabs --}}
                <div class="booking-content">
                    {{-- Pending Bookings Tab --}}
                    <div id="pending-tab" class="booking-tab transition-all duration-300">
                        <div class="flex items-center mb-3 sm:mb-4">
                            <h3 class="text-lg sm:text-xl font-semibold capitalize flex items-center">
                                <i class="fas fa-clipboard-list mr-1.5 sm:mr-2 text-yellow-500"></i>
                                New Job Requests
                            </h3>
                        </div>

                        @if ($pendingBookings->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                @foreach ($pendingBookings as $booking)
                                    <div
                                        class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-yellow-400 hover:shadow-lg transition-shadow duration-200">
                                        <div class="p-4 sm:p-5">
                                            <div class="flex justify-between items-start mb-3">
                                                <h4 class="text-lg font-bold text-indigo-700">
                                                    <i class="fas fa-clipboard-list mr-1.5"></i>
                                                    {{ $booking->booking_title }}
                                                </h4>
                                                <span
                                                    class="px-2.5 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full flex items-center">
                                                    <i class="fas fa-hourglass-half mr-1"></i> New Request
                                                </span>
                                            </div>

                                            <!-- Client Profile and Name -->
                                            <div class="flex items-center gap-2 mb-3">
                                                <span class="text-sm font-medium text-gray-900">Client: </span>
                                                <span class="text-sm text-gray-900">{{ $booking->client->full_name }}</span>
                                            </div>

                                            <div class="space-y-2 mb-4">
                                                {{-- <div class="flex items-start text-sm text-gray-600">
                                                    <i class="fas fa-phone mt-0.5 mr-2 w-4 text-gray-500"></i>
                                                    <span>{{ $booking->client->phone }}</span>
                                                </div> --}}

                                                <div class="flex items-start text-sm text-gray-600">
                                                    <i class="far fa-calendar-alt mt-0.5 mr-2 w-4 text-gray-500"></i>
                                                    <div>
                                                        <span class="text-gray-700 font-medium">Scheduled on:</span><br>
                                                        {{ \Carbon\Carbon::parse($booking->scheduled_date)->format('F d, Y h:i A') }}
                                                    </div>
                                                </div>

                                                <div class="flex items-start text-sm text-gray-600">
                                                    <i class="fas fa-map-marker-alt mt-0.5 mr-2 w-4 text-gray-500"></i>
                                                    <div>
                                                        <span class="text-gray-700 font-medium">Client Address:</span><br>
                                                        @if ($booking->client_address)
                                                            @php
                                                                $googleMapsLink =
                                                                    'https://www.google.com/maps/dir/?api=1&destination=' .
                                                                    urlencode($booking->client_address) .
                                                                    '&travelmode=driving';
                                                            @endphp
                                                            <a href="{{ $googleMapsLink }}" target="_blank"
                                                                class="text-blue-600 hover:underline">
                                                                {{ $booking->client_address }}
                                                            </a>
                                                        @else
                                                            <p>No client data available</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex flex-col sm:flex-row gap-2 mt-4">
                                                <form action="{{ route('bookings.update', $booking->id) }}" method="POST"
                                                    class="w-full">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="ongoing">
                                                    <button type="button"
                                                        class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-md flex items-center justify-center transition-all duration-200 hover:from-green-600 hover:to-green-700 shadow-sm accept-job-btn"
                                                        data-booking-id="{{ $booking->id }}"
                                                        data-target="#acceptJobConfirmModal">
                                                        <i class="fas fa-check-circle mr-2"></i> Accept Job
                                                    </button>
                                                </form>

                                                <form action="{{ route('bookings.update', $booking->id) }}" method="POST"
                                                    class="w-full">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="button"
                                                        class="w-full border border-red-500 text-red-500 px-4 py-2 rounded-md flex items-center justify-center transition-all duration-200 hover:bg-red-50 decline-job-btn"
                                                        data-booking-id="{{ $booking->id }}"
                                                        data-target="#declineJobConfirmModal">
                                                        <i class="fas fa-times-circle mr-2"></i> Decline
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-white rounded-lg shadow-md p-6 sm:p-8 text-center">
                                <i class="fas fa-clipboard-check text-gray-300 text-4xl sm:text-5xl mb-3"></i>
                                <h5 class="text-lg text-gray-500 mt-2">No new job requests yet!</h5>
                                <p class="text-gray-500 mt-1">Check back soon for new opportunities.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Ongoing Bookings Tab --}}
                    <div id="ongoing-tab" class="booking-tab hidden transition-all duration-300">
                        <div class="flex items-center mb-3 sm:mb-4">
                            <h3 class="text-lg sm:text-xl font-semibold capitalize flex items-center">
                                <i class="fas fa-spinner fa-spin mr-1.5 sm:mr-2 text-blue-500"></i>
                                Jobs In Progress
                            </h3>
                        </div>

                        @if ($ongoingBookings->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                @foreach ($ongoingBookings as $booking)
                                    <div
                                        class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-blue-400 hover:shadow-lg transition-shadow duration-200">
                                        <div class="p-4 sm:p-5">
                                            <div class="flex justify-between items-start mb-3">
                                                <h4 class="text-lg font-bold text-indigo-700">
                                                    <i class="fas fa-clipboard-list mr-1.5"></i>
                                                    {{ $booking->booking_title }}
                                                </h4>
                                                <span
                                                    class="px-2.5 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full flex items-center">
                                                    <i class="fas fa-spinner fa-spin mr-1"></i> In Progress
                                                </span>
                                            </div>

                                            <!-- Client Profile and Name -->
                                            <div class="flex items-center gap-2 mb-3">
                                                <span class="text-sm font-medium text-gray-900">Client: </span>
                                                <span
                                                    class="text-sm text-gray-900">{{ $booking->client->full_name }}</span>
                                            </div>

                                            <div class="space-y-2 mb-4">

                                                <div class="flex items-start text-sm text-gray-600">
                                                    <i class="fas fa-phone mt-0.5 mr-2 w-4 text-gray-500"></i>
                                                    <a href="sms:{{ $booking->client->phone }}"
                                                        class="text-blue-600 hover:underline">
                                                        {{ $booking->client->phone }}
                                                    </a>
                                                </div>

                                                <div class="flex items-start text-sm text-gray-600">
                                                    <i class="far fa-calendar-alt mt-0.5 mr-2 w-4 text-gray-500"></i>
                                                    <div>
                                                        <span class="text-gray-700 font-medium">Scheduled on:</span><br>
                                                        @php
                                                            // Format the scheduled date as displayed
                                                            $formattedDate = \Carbon\Carbon::parse(
                                                                $booking->scheduled_date,
                                                            )->format('F d, Y h:i A');

                                                            // Google Calendar format (without converting timezones)
                                                            $calendarDate = \Carbon\Carbon::parse(
                                                                $booking->scheduled_date,
                                                            )->format('Ymd\THis'); // Removed "Z" to prevent UTC shift

                                                            // Google Calendar event link
                                                            $calendarLink = "https://www.google.com/calendar/render?action=TEMPLATE&text=Scheduled+Service&dates={$calendarDate}/{$calendarDate}&details=Service+Appointment";
                                                        @endphp
                                                        <a href="{{ $calendarLink }}" target="_blank"
                                                            class="text-blue-600 hover:underline">
                                                            {{ $formattedDate }}
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="flex items-start text-sm text-gray-600">
                                                    <i class="fas fa-map-marker-alt mt-0.5 mr-2 w-4 text-gray-500"></i>
                                                    <div>
                                                        <span class="text-gray-700 font-medium">Client Address:</span><br>
                                                        @if ($booking->client_address)
                                                            @php
                                                                $googleMapsLink =
                                                                    'https://www.google.com/maps/dir/?api=1&destination=' .
                                                                    urlencode($booking->client_address) .
                                                                    '&travelmode=driving';
                                                            @endphp
                                                            <a href="{{ $googleMapsLink }}" target="_blank"
                                                                class="text-blue-600 hover:underline">
                                                                {{ $booking->client_address }}
                                                            </a>
                                                        @else
                                                            <p>No client data available</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <form action="{{ route('bookings.update', $booking->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="completed">
                                                    @php
                                                        // Get current UTC time and add 8 hours for Manila time
                                                        $now = \Carbon\Carbon::now()->addHours(8);

                                                        // Parse scheduled time without timezone conversion
                                                        $scheduledTime = \Carbon\Carbon::parse(
                                                            $booking->scheduled_date,
                                                        );

                                                        // Enable button when current time (with 8 hours added) is >= scheduled time
                                                        $isDisabled = $now->lessThan($scheduledTime);

                                                        // For debugging - you can temporarily uncomment these lines
                                                        // echo "Current time (+8hrs): " . $now->format('Y-m-d H:i:s') . "<br>";
                                                        // echo "Scheduled time: " . $scheduledTime->format('Y-m-d H:i:s') . "<br>";
                                                        // echo "Is Disabled: " . ($isDisabled ? 'Yes' : 'No') . "<br>";

                                                    @endphp

                                                    <button type="submit"
                                                        class="w-full px-4 py-2.5 rounded-md flex items-center justify-center transition-all duration-200 shadow-sm
    {{ $isDisabled ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white hover:from-blue-600 hover:to-indigo-700 hover:shadow' }}"
                                                        {{ $isDisabled ? 'disabled' : '' }}>
                                                        <i class="fas fa-check-circle mr-2"></i> Mark as Completed
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-white rounded-lg shadow-md p-6 sm:p-8 text-center">
                                <i class="fas fa-clipboard text-gray-300 text-4xl sm:text-5xl mb-3"></i>
                                <h5 class="text-lg text-gray-500 mt-2">No jobs in progress!</h5>
                                <p class="text-gray-500 mt-1">Accept new job requests to get started.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Completed Bookings Tab --}}
                    <div id="completed-tab" class="booking-tab hidden transition-all duration-300">
                        <div class="flex items-center mb-3 sm:mb-4">
                            <h3 class="text-lg sm:text-xl font-semibold capitalize flex items-center">
                                <i class="fas fa-check-circle mr-1.5 sm:mr-2 text-green-500"></i>
                                Completed Jobs
                            </h3>
                        </div>

                        @if ($completedBookings->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                @foreach ($completedBookings as $booking)
                                    <div
                                        class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-green-400 hover:shadow-lg transition-shadow duration-200">
                                        <div class="p-4 sm:p-5">
                                            <div class="flex justify-between items-start mb-3">
                                                <h4 class="text-lg font-bold text-indigo-700">
                                                    <i class="fas fa-clipboard-list mr-1.5"></i>
                                                    {{ $booking->booking_title }}
                                                </h4>
                                                <span
                                                    class="px-2.5 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full flex items-center">
                                                    <i class="fas fa-check-circle mr-1"></i> Completed
                                                </span>
                                            </div>

                                            <div class="flex items-center gap-2 mb-3">
                                                <span class="text-sm font-medium text-gray-900">Client: </span>
                                                <span
                                                    class="text-sm text-gray-900">{{ $booking->client->full_name }}</span>
                                            </div>

                                            <!-- Completed booking details -->
                                            <div class="space-y-2 mb-4">
                                                <div class="flex items-start text-sm text-gray-600">
                                                    <i class="fas fa-hashtag mt-0.5 mr-2 w-4 text-gray-500"></i>
                                                    <div>
                                                        <span class="text-gray-700 font-medium">Booking ID:</span>
                                                        <span class="ml-1">
                                                            @php
                                                                // Pad the booking ID to 7 digits
                                                                $paddedId = str_pad($booking->id, 7, '0', STR_PAD_LEFT);

                                                                // Format based on service type
                                                                $prefix = '';
                                                                switch ($booking->service_type) {
                                                                    case 'Electrician':
                                                                        $prefix = 'ELECT-';
                                                                        break;
                                                                    case 'Daycare':
                                                                        $prefix = 'DC-';
                                                                        break;
                                                                    case 'Home Cleaning':
                                                                        $prefix = 'HC-';
                                                                        break;
                                                                    case 'Plumbing':
                                                                        $prefix = 'PLUMB-';
                                                                        break;
                                                                    case 'Carpentry':
                                                                        $prefix = 'CARP-';
                                                                        break;
                                                                }

                                                                $formattedId = $prefix . $paddedId;
                                                            @endphp
                                                            {{ $formattedId }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="flex items-start text-sm text-gray-600">
                                                    <i class="far fa-calendar-alt mt-0.5 mr-2 w-4 text-gray-500"></i>
                                                    <div>
                                                        <span class="text-gray-700 font-medium">Start Time:</span><br>
                                                        {{ \Carbon\Carbon::parse($booking->scheduled_date)->format('F d, Y h:i A') }}
                                                    </div>
                                                </div>

                                                <div class="flex items-start text-sm text-gray-600">
                                                    <i class="far fa-clock mt-0.5 mr-2 w-4 text-gray-500"></i>
                                                    <div>
                                                        <span class="text-gray-700 font-medium">End Time:</span><br>
                                                        {{ isset($booking->completion_date) ? \Carbon\Carbon::parse($booking->completion_date)->format('F d, Y h:i A') : 'Not recorded' }}
                                                    </div>
                                                </div>

                                                <div class="flex items-start text-sm text-gray-600">
                                                    <i class="fas fa-hourglass-end mt-0.5 mr-2 w-4 text-gray-500"></i>
                                                    <div>
                                                        <span class="text-gray-700 font-medium">Hours Rendered:</span>
                                                        <span class="ml-1">{{ $booking->hours_worked ?? 'N/A' }}
                                                            hrs</span>
                                                    </div>
                                                </div>

                                                <div class="flex items-start text-sm text-gray-600">
                                                    <i class="fas fa-money-bill-wave mt-0.5 mr-2 w-4 text-gray-500"></i>
                                                    <div>
                                                        <span class="text-gray-700 font-medium">Total Amount:</span>
                                                        <span
                                                            class="ml-1">₱{{ number_format($booking->total_amount ?? 0, 2) }}</span>
                                                    </div>
                                                </div>

                                                <!-- Rating display section -->
                                                <div class="flex items-start text-sm text-gray-600">
                                                    <i class="fas fa-star mt-0.5 mr-2 w-4 text-gray-500"></i>
                                                    <div>
                                                        <span class="text-gray-700 font-medium">Client's Review:</span>
                                                        @php
                                                            $rating = App\Models\Rating::where(
                                                                'booking_id',
                                                                $booking->id,
                                                            )
                                                                ->where('worker_id', Auth::id())
                                                                ->first();
                                                        @endphp

                                                        @if ($rating)
                                                            <span
                                                                class="ml-1 cursor-pointer text-yellow-500 hover:underline"
                                                                onclick="viewReview({{ $rating->id }})"
                                                                title="Click to view details">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($i <= $rating->rating)
                                                                        <i class="fas fa-star text-yellow-400"></i>
                                                                    @else
                                                                        <i class="far fa-star text-yellow-400"></i>
                                                                    @endif
                                                                @endfor
                                                                ({{ $rating->rating }}/5)
                                                            </span>
                                                        @else
                                                            <span class="ml-1 text-gray-500 italic">Not yet reviewed by the
                                                                Client</span>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-white rounded-lg shadow-md p-6 sm:p-8 text-center">
                                <i class="fas fa-clipboard-check text-gray-300 text-4xl sm:text-5xl mb-3"></i>
                                <h5 class="text-lg text-gray-500 mt-2">No completed jobs yet!</h5>
                                <p class="text-gray-500 mt-1">Complete your ongoing jobs to see them here.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Cancelled Bookings Tab --}}
                    <div id="cancelled-tab" class="booking-tab hidden transition-all duration-300">
                        <div class="flex items-center mb-3 sm:mb-4">
                            <h3 class="text-lg sm:text-xl font-semibold capitalize flex items-center">
                                <i class="fas fa-times-circle mr-1.5 sm:mr-2 text-red-500"></i>
                                Cancelled Jobs
                            </h3>
                        </div>

                        @if ($cancelledBookings->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                @foreach ($cancelledBookings as $booking)
                                    <div
                                        class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-red-400 hover:shadow-lg transition-shadow duration-200">
                                        <div class="p-4 sm:p-5">
                                            <div class="flex justify-between items-start mb-3">
                                                <h4 class="text-lg font-bold text-indigo-700">
                                                    <i class="fas fa-clipboard-list mr-1.5"></i>
                                                    {{ $booking->booking_title }}
                                                </h4>
                                                <span
                                                    class="px-2.5 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full flex items-center">
                                                    <i class="fas fa-times-circle mr-1"></i> Cancelled
                                                </span>
                                            </div>

                                            <!-- Client Profile and Name -->
                                            <div class="flex items-center gap-2 mb-3">
                                                <span class="text-sm font-medium text-gray-900">Client: </span>
                                                <span
                                                    class="text-sm text-gray-900">{{ $booking->client->full_name }}</span>
                                            </div>

                                            <div class="space-y-2 mb-4">
                                                <div class="flex items-start text-sm text-gray-600">
                                                    <i class="far fa-calendar-alt mt-0.5 mr-2 w-4 text-gray-500"></i>
                                                    <div>
                                                        <span class="text-gray-700 font-medium">Scheduled
                                                            on:</span><br>
                                                        {{ \Carbon\Carbon::parse($booking->scheduled_date)->format('F d, Y h:i A') }}
                                                    </div>
                                                </div>

                                                <div class="flex items-start text-sm text-gray-600">
                                                    <i class="fas fa-map-marker-alt mt-0.5 mr-2 w-4 text-gray-500"></i>
                                                    <div>
                                                        <span class="text-gray-700 font-medium">Client
                                                            Address:</span><br>
                                                        {{ $booking->client_address ?? 'No address provided' }}
                                                    </div>
                                                </div>

                                                <div class="flex items-start text-sm text-gray-600">
                                                    <i class="fas fa-info-circle mt-0.5 mr-2 w-4 text-gray-500"></i>
                                                    <div>
                                                        <span class="text-gray-700 font-medium">Cancellation
                                                            Reason:</span><br>
                                                        {{ $booking->cancellation_reason ?? 'No reason provided' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-white rounded-lg shadow-md p-6 sm:p-8 text-center">
                                <i class="fas fa-ban text-gray-300 text-4xl sm:text-5xl mb-3"></i>
                                <h5 class="text-lg text-gray-500 mt-2">No cancelled jobs!</h5>
                                <p class="text-gray-500 mt-1">That's great! Keep up the good work.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="flex flex-col items-center justify-center text-center p-8 bg-white rounded-lg shadow-md">
                    <i class="fas fa-calendar-times text-gray-300 text-5xl mb-4"></i>
                    <h5 class="text-xl text-gray-500 mb-2">There's no booking for you now!</h5>
                    <p class="text-gray-500">Please check back later for new opportunities.</p>
                </div>
            @endif
        @else
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-sm flex items-center">
                <i class="fas fa-exclamation-triangle text-xl mr-3"></i>
                <span>You are not authorized to view this page.</span>
            </div>
        @endif
    </div>

    <!-- Confirmation Modal -->
    <div id="completionConfirmModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 w-11/12 max-w-md mx-auto">
            <div class="text-center mb-4">
                <i class="fas fa-check-circle text-green-500 text-4xl mb-3"></i>
                <h3 class="text-xl font-bold">Confirm Job Completion</h3>
                <p class="text-gray-600 mt-1">Are you sure you want to mark this job as completed?</p>
            </div>
            <div class="flex gap-3 mt-5">
                <button id="cancelCompletion"
                    class="flex-1 py-2 px-4 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <button id="confirmCompletion"
                    class="flex-1 py-2 px-4 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors">
                    Yes, Complete
                </button>
            </div>
        </div>
    </div>

    <!-- Receipt Modal -->
    <div id="receiptModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 w-11/12 max-w-md mx-auto">
            <!-- Close button in the top-right corner -->
            <button id="closeReceiptBtn" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>

            <div class="text-center mb-4">
                <i class="fas fa-receipt text-blue-500 text-4xl mb-3"></i>
                <h3 class="text-xl font-bold">Job Completion Receipt</h3>
            </div>
            <div id="receiptContent" class="border-t border-b border-gray-200 py-4 my-4">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Booking ID:</span>
                    <span id="receipt-booking-id" class="font-medium"></span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Service:</span>
                    <span id="receipt-service" class="font-medium"></span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Start Time:</span>
                    <span id="receipt-start-time" class="font-medium"></span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">End Time:</span>
                    <span id="receipt-end-time" class="font-medium"></span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Hours Worked:</span>
                    <span id="receipt-hours" class="font-medium"></span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Hourly Rate:</span>
                    <span id="receipt-rate" class="font-medium"></span>
                </div>
                <div class="flex justify-between text-lg font-bold mt-4 pt-4 border-t">
                    <span>Total Amount:</span>
                    <span id="receipt-total"></span>
                </div>
            </div>
            <form id="completionForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="completed">
                <input type="hidden" name="hours_worked" id="hours-worked-input">
                <input type="hidden" name="total_amount" id="total-amount-input">
                <div class="mt-5 flex flex-col gap-2">
                    <!-- Submit button -->
                    <button type="submit"
                        class="w-full py-2 px-4 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                        Confirm & Complete
                    </button>
                    <!-- Cancel button -->
                    <button type="button" id="cancelCompletionBtn"
                        class="w-full py-2 px-4 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Accept Job Confirmation Modal -->
    <div id="acceptJobConfirmModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 w-11/12 max-w-md mx-auto">
            <div class="text-center mb-4">
                <i class="fas fa-check-circle text-green-500 text-4xl mb-3"></i>
                <h3 class="text-xl font-bold">Confirm Accept Job</h3>
                <p class="text-gray-600 mt-1">Are you sure you want to accept this job request?</p>
            </div>
            <div class="flex gap-3 mt-5">
                <button
                    class="close-modal flex-1 py-2 px-4 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <form id="acceptJobForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="ongoing">
                    <button type="submit"
                        class="flex-1 py-2 px-4 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors">
                        Yes, Accept
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Decline Job Confirmation Modal -->
    <div id="declineJobConfirmModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 w-11/12 max-w-md mx-auto">
            <div class="text-center mb-4">
                <i class="fas fa-times-circle text-red-500 text-4xl mb-3"></i>
                <h3 class="text-xl font-bold">Confirm Decline Job</h3>
                <p class="text-gray-600 mt-1">Are you sure you want to decline this job request?</p>
            </div>
            <div class="flex gap-3 mt-5">
                <button
                    class="close-modal flex-1 py-2 px-4 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <form id="declineJobForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit"
                        class="flex-1 py-2 px-4 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                        Yes, Decline
                    </button>
                </form>
            </div>
        </div>
    </div>


    {{-- Popup Notification for New Bookings --}}
    <div id="new-booking-popup"
        class="fixed bottom-4 right-4 bg-white rounded-lg shadow-lg border-l-4 border-yellow-400 p-4 transform translate-y-full opacity-0 transition-all duration-500 z-50 max-w-xs">
        <div class="flex items-start">
            <div class="flex-shrink-0 pt-0.5">
                <i class="fas fa-bell text-yellow-500 text-xl animate-pulse"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-gray-900">New Booking Request!</h3>
                <div class="mt-1 text-sm text-gray-500">
                    <p id="booking-notification-text">You have a new job request. Check it out now!</p>
                </div>
            </div>
            <button type="button" onclick="closePopup()" class="ml-4 text-gray-400 hover:text-gray-500">
                <span class="sr-only">Close</span>
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mt-3">
            <button onclick="refreshAndShowTab('pending'); closePopup()"
                class="w-full bg-gradient-to-r from-yellow-400 to-yellow-500 text-white px-3 py-1.5 rounded text-sm font-medium hover:from-yellow-500 hover:to-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-400">
                View Request
            </button>
        </div>
    </div>

    <!-- Modern Modal for viewing client's review -->
    <div id="viewReviewModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay with blur effect -->
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 backdrop-blur-sm" aria-hidden="true">
            </div>

            <!-- Modal panel with animation -->
            <div
                class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-fade-scale-in">
                <!-- Header with gradient -->
                <div class="relative px-6 py-5 bg-gradient-to-r from-indigo-500 to-purple-600">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold leading-6 text-white" id="modal-title">
                            Client's Feedback
                        </h3>
                        <button type="button" onclick="closeReviewModal()"
                            class="text-white transition-colors duration-200 rounded-full hover:text-gray-200 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="px-6 py-5">
                    <!-- Rating section with larger stars -->
                    <div class="p-4 mb-5 bg-gray-50 rounded-xl">
                        <div class="mb-2 text-sm font-medium text-gray-500 uppercase">Overall Rating</div>
                        <div class="flex items-center space-x-1">
                            <div id="displayRating" class="flex items-center">
                                <!-- Stars will be inserted here via JS -->
                            </div>
                        </div>
                    </div>

                    <!-- Comment section with styled container -->
                    <div class="mb-5">
                        <div class="mb-2 text-sm font-medium text-gray-500 uppercase">Feedback</div>
                        <div id="displayComment"
                            class="p-4 italic text-gray-700 bg-gray-50 rounded-xl border border-gray-100"></div>
                    </div>

                    <!-- Photos section with improved layout -->
                    <div id="reviewPhotosContainer" class="mb-4">
                        <div class="mb-2 text-sm font-medium text-gray-500 uppercase">Photos</div>
                        <div id="reviewPhotos" class="grid grid-cols-2 gap-3">
                            <!-- Photos will be inserted here via JS -->
                        </div>
                    </div>
                </div>

                <!-- Footer with gradient button -->
                <div class="px-6 py-4 bg-gray-50 sm:flex sm:flex-row-reverse">
                    <button type="button"
                        class="w-full px-5 py-3 text-sm font-medium text-white transition-all duration-200 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto"
                        onclick="closeReviewModal()">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .animate-fade-scale-in {
            animation: fadeScaleIn 0.3s ease-out forwards;
        }

        @keyframes fadeScaleIn {
            0% {
                opacity: 0;
                transform: scale(0.95);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* For image hover effects */
        .review-image {
            transition: all 0.3s ease;
        }

        .review-image:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>


    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('3b9a45793abe9017e46c', {
            cluster: 'ap1'
        });

        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
            alert(JSON.stringify(data));
        });
    </script>

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

        // Set the default active tab on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check if there's a tab parameter in the URL
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');

            if (tabParam && ['pending', 'ongoing', 'completed', 'cancelled'].includes(tabParam)) {
                showTab(tabParam);
            } else {
                // Default to pending tab
                showTab('pending');
            }

            // Add click event listeners to all tab buttons for better compatibility
            document.querySelectorAll('.tab-link').forEach(button => {
                button.addEventListener('click', function() {
                    const tabName = this.getAttribute('onclick').match(/'(.*?)'/)[1];
                    showTab(tabName);
                });
            });
        });

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

        if (tabContent) {
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
                    if (!currentTab) return;

                    const tabId = currentTab.id.replace('-tab', '');

                    // Get tab order
                    const tabOrder = ['pending', 'ongoing'];
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
        }

        // Function to show popup
        function showNewBookingPopup(bookingTitle = null) {
            const popup = document.getElementById('new-booking-popup');

            // If we have a specific booking title, show it in the notification
            if (bookingTitle) {
                document.getElementById('booking-notification-text').textContent =
                    `New request: "${bookingTitle}". Check it out now!`;
            }

            popup.classList.remove('translate-y-full', 'opacity-0');
            popup.classList.add('translate-y-0', 'opacity-100');

            // Play notification sound
            playNotificationSound();

            // Auto hide after 10 seconds
            setTimeout(() => {
                closePopup();
            }, 10000);
        }

        // Function to close popup
        function closePopup() {
            const popup = document.getElementById('new-booking-popup');
            popup.classList.add('translate-y-full', 'opacity-0');
            popup.classList.remove('translate-y-0', 'opacity-100');
        }

        // Function to play notification sound
        function playNotificationSound() {
            const audio = new Audio('/notification-sound.mp3'); // Add your sound file to the public folder
            audio.volume = 0.5;
            audio.play().catch(e => console.log('Audio playback prevented:', e));
        }

        // Function to refresh the page and show the pending tab
        function refreshAndShowTab(tabName) {
            // Save the tab we want to open after refresh
            localStorage.setItem('activeTab', tabName);
            // Reload the page to get the latest bookings
            window.location.reload();
        }

        // Check if there are new bookings on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check for saved active tab from refresh
            const savedTab = localStorage.getItem('activeTab');
            if (savedTab) {
                showTab(savedTab);
                localStorage.removeItem('activeTab');
            }

            const pendingCount = {{ $pendingBookings->count() ?? 0 }};

            // If there are pending bookings and we haven't shown the popup yet
            if (pendingCount > 0 && !sessionStorage.getItem('initialPopupShown')) {
                // Small delay to ensure page is fully loaded
                setTimeout(() => {
                    showNewBookingPopup();
                    // Set flag to avoid showing popup multiple times in the same session
                    sessionStorage.setItem('initialPopupShown', 'true');
                }, 1000);
            }

            // Set up real-time listener for new bookings
            setupRealTimeNotifications();
        });

        // Setup real-time notifications
        function setupRealTimeNotifications() {
            // Only set up notifications for workers with the right role
            const userRole = '{{ Auth::user()->role }}';
            const workerServiceType = '{{ $workerServiceType }}';

            if (userRole === 'worker' && workerServiceType) {
                // Listen for new bookings on the public channel
                window.Echo.channel('bookings')
                    .listen('NewBookingEvent', (e) => {
                        console.log('New booking received:', e.booking);

                        // Only show notification if the booking matches the worker's service type
                        if (e.booking.service_type === workerServiceType && e.booking.status === 'pending') {
                            // Show notification with the booking title
                            showNewBookingPopup(e.booking.booking_title);

                            // Update the pending count badge
                            updatePendingCount();
                        }
                    });
            }
        }

        // Function to update the pending count badge without refreshing
        function updatePendingCount() {
            const pendingCountBadge = document.querySelector('button[onclick="showTab(\'pending\')"] span.rounded-full');
            if (pendingCountBadge) {
                let currentCount = parseInt(pendingCountBadge.textContent.trim());
                pendingCountBadge.textContent = currentCount + 1;
            }
        }



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
                    return `PLUMB-${paddedId}`;
                case 'Carpentry':
                    return `CARP-${paddedId}`;
                default:
                    return paddedId;
            }
        }

        // Job completion handling
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM fully loaded');

            // We need to target the correct "Mark as Completed" buttons
            const completeButtons = document.querySelectorAll(
                'button[type="submit"].w-full:not(#completionForm button)');
            console.log('Found complete buttons:', completeButtons.length);

            const confirmModal = document.getElementById('completionConfirmModal');
            console.log('Confirm modal found:', !!confirmModal);

            const receiptModal = document.getElementById('receiptModal');
            console.log('Receipt modal found:', !!receiptModal);

            const cancelBtn = document.getElementById('cancelCompletion');
            console.log('Cancel button found:', !!cancelBtn);

            const confirmBtn = document.getElementById('confirmCompletion');
            console.log('Confirm button found:', !!confirmBtn);

            if (!completeButtons.length || !confirmModal || !receiptModal || !cancelBtn || !confirmBtn) {
                console.error('Some elements are missing from the DOM!');
                return;
            }

            let currentBooking = null;
            let currentForm = null;

            // Show confirmation modal when "Mark as Completed" is clicked
            completeButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    console.log('Complete button clicked');
                    e.preventDefault();
                    currentForm = this.closest('form');
                    console.log('Form found:', !!currentForm);

                    // Get booking details from the card
                    const bookingCard = this.closest('.border-l-4');
                    console.log('Booking card found:', !!bookingCard);

                    if (!bookingCard) {
                        console.error('Could not find booking card with class .border-l-4');
                        return;
                    }

                    const bookingTitle = bookingCard.querySelector('h4')?.innerText.trim();
                    console.log('Booking title:', bookingTitle);

                    const scheduledDateElement = bookingCard.querySelector('.far.fa-calendar-alt')
                        ?.closest('.flex.items-start');
                    console.log('Scheduled date element found:', !!scheduledDateElement);

                    if (!scheduledDateElement) {
                        console.error('Could not find scheduled date element');
                        return;
                    }

                    // Extract the scheduled date from the element's text
                    const scheduledDateText = scheduledDateElement.textContent.trim();
                    const scheduledDate = scheduledDateText.includes('Scheduled on:') ?
                        scheduledDateText.split('Scheduled on:')[1].trim() :
                        scheduledDateText;
                    console.log('Scheduled date:', scheduledDate);

                    // Get booking ID from the form action URL
                    const actionUrl = currentForm.getAttribute('action');
                    const bookingId = actionUrl.split('/').pop();
                    console.log('Booking ID:', bookingId);

                    currentBooking = {
                        id: bookingId,
                        title: bookingTitle,
                        scheduledDate: scheduledDate
                    };

                    console.log('Current booking:', currentBooking);

                    // Show confirmation modal
                    confirmModal.classList.remove('hidden');
                    console.log('Confirmation modal displayed');
                });
            });

            // Cancel button in confirmation modal
            cancelBtn.addEventListener('click', function() {
                console.log('Cancel button clicked');
                confirmModal.classList.add('hidden');
            });

            // Confirm button in confirmation modal
            confirmBtn.addEventListener('click', function() {
                console.log('Confirm button clicked');
                confirmModal.classList.add('hidden');

                console.log('Fetching completion details for booking ID:', currentBooking.id);
                // Fetch completion details
                fetch(`/bookings/${currentBooking.id}/completion-details`)
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`Network response was not ok: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Received data:', data);

                        // Format the booking ID based on service type
                        const formattedBookingId = formatBookingId(data.booking_id || currentBooking.id,
                            data.service_type);

                        // Handle the Manila timezone adjustment (UTC+8)
                        // Add 8 hours to hours worked
                        let hoursWorked = parseFloat(data.hours_worked) + 8;

                        // Calculate the total amount based on the adjusted hours worked
                        const hourlyRate = parseFloat(data.hourly_rate.replace(/,/g, ''));
                        const totalAmount = hoursWorked * hourlyRate;

                        // Format the total amount for display
                        const formattedTotalAmount = totalAmount.toLocaleString('en-PH', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });

                        // Adjust end time by subtracting 8 hours
                        let endTime = data.completion_time;
                        try {
                            // Parse the completion time string
                            const completionDate = new Date(data.completion_time);
                            // Subtract 8 hours
                            completionDate.setHours(completionDate.getHours() + 8);
                            // Format the adjusted date back to a string
                            endTime = completionDate.toLocaleString('en-US', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric',
                                hour: 'numeric',
                                minute: 'numeric',
                                hour12: true
                            });
                        } catch (e) {
                            console.error('Error adjusting end time:', e);
                            // If there's an error, keep the original completion time
                        }

                        // Fill receipt data
                        document.getElementById('receipt-booking-id').textContent = formattedBookingId;
                        document.getElementById('receipt-service').textContent = data.service_type;
                        document.getElementById('receipt-start-time').textContent = data.scheduled_date;
                        document.getElementById('receipt-end-time').textContent = endTime;
                        document.getElementById('receipt-hours').textContent = hoursWorked + ' hrs';
                        document.getElementById('receipt-rate').textContent = '₱' + data.hourly_rate +
                            '/hr';
                        document.getElementById('receipt-total').textContent = '₱' +
                            formattedTotalAmount;

                        // Set hidden form values - use the raw numeric value for the database
                        document.getElementById('hours-worked-input').value = hoursWorked;
                        document.getElementById('total-amount-input').value = totalAmount;

                        // Set form action using currentForm's action
                        document.getElementById('completionForm').action = currentForm.action;
                        console.log('Form action set to:', document.getElementById('completionForm')
                            .action);

                        // Show receipt modal
                        receiptModal.classList.remove('hidden');
                        console.log('Receipt modal displayed');
                    })
                    .catch(error => {
                        console.error('Error fetching booking details:', error);
                        alert('There was an error processing the completion. Please try again.');
                    });
            });

            // Handle the final submission from the receipt modal
            const completionForm = document.getElementById('completionForm');
            if (completionForm) {
                completionForm.addEventListener('submit', function(e) {
                    console.log('Completion form submitted');
                    // No need to prevent default here - we want the form to submit
                });
            }

            // Close button in receipt modal
            const closeReceiptBtn = document.getElementById('closeReceiptBtn');
            if (closeReceiptBtn) {
                closeReceiptBtn.addEventListener('click', function() {
                    console.log('Close receipt button clicked');
                    receiptModal.classList.add('hidden');
                });
            }

            // Cancel button in receipt modal
            const cancelCompletionBtn = document.getElementById('cancelCompletionBtn');
            if (cancelCompletionBtn) {
                cancelCompletionBtn.addEventListener('click', function() {
                    console.log('Cancel completion button clicked');
                    receiptModal.classList.add('hidden');
                });
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            // Open the correct modal when a button is clicked
            document.querySelectorAll(".accept-job-btn, .decline-job-btn").forEach(button => {
                button.addEventListener("click", function() {
                    let modalId = this.getAttribute("data-target");
                    let bookingId = this.getAttribute("data-booking-id");

                    let modal = document.querySelector(modalId);
                    if (modal) {
                        modal.classList.remove("hidden");

                        // Update the form action dynamically
                        let form = modal.querySelector("form");
                        if (form) {
                            form.setAttribute("action", `/bookings/${bookingId}`);
                        }
                    }
                });
            });

            // Close modals when clicking cancel
            document.querySelectorAll(".close-modal").forEach(button => {
                button.addEventListener("click", function() {
                    this.closest(".fixed").classList.add("hidden");
                });
            });
        });

        function viewReview(ratingId) {
            // Show loading state
            document.getElementById('viewReviewModal').classList.remove('hidden');
            document.getElementById('displayRating').innerHTML =
                '<div class="flex items-center space-x-2"><div class="w-5 h-5 border-t-2 border-b-2 border-indigo-500 rounded-full animate-spin"></div><span class="text-sm text-gray-500">Loading...</span></div>';
            document.getElementById('displayComment').innerHTML = 'Loading review...';

            // Fetch rating details
            fetch(`/worker/rating/${ratingId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Display rating stars with animation
                    const displayRating = document.getElementById('displayRating');
                    displayRating.innerHTML = '';

                    // Create stars based on rating with delay for animation
                    for (let i = 1; i <= 5; i++) {
                        const star = document.createElement('i');
                        star.classList.add('fas', 'fa-star', 'text-xl', 'transition-all', 'duration-300');

                        if (i <= data.rating) {
                            star.classList.add('text-yellow-400');
                            // Animate stars appearing
                            setTimeout(() => {
                                star.classList.add('scale-110');
                                setTimeout(() => {
                                    star.classList.remove('scale-110');
                                }, 300);
                            }, i * 100);
                        } else {
                            star.classList.add('text-gray-300');
                        }

                        displayRating.appendChild(star);
                    }

                    // Add numeric rating with prettier styling
                    const ratingText = document.createElement('span');
                    ratingText.textContent = ` ${data.rating}/5`;
                    ratingText.classList.add('ml-2', 'text-lg', 'font-bold', 'text-indigo-600');
                    displayRating.appendChild(ratingText);

                    // Display comment with fallback text
                    const commentEl = document.getElementById('displayComment');
                    if (data.comment && data.comment.trim()) {
                        commentEl.textContent = data.comment;
                    } else {
                        commentEl.innerHTML = '<span class="text-gray-400">No written feedback provided</span>';
                    }

                    // Display photos if any with nicer styling
                    const photosContainer = document.getElementById('reviewPhotosContainer');
                    const photosGrid = document.getElementById('reviewPhotos');
                    photosGrid.innerHTML = '';

                    if (data.review_photos && data.review_photos.length > 0) {
                        photosContainer.classList.remove('hidden');

                        data.review_photos.forEach((photo, index) => {
                            const imgContainer = document.createElement('div');
                            imgContainer.classList.add('overflow-hidden', 'rounded-lg', 'shadow-sm', 'border',
                                'border-gray-200');

                            const img = document.createElement('img');
                            img.src = `/storage/${photo}`;
                            img.alt = 'Review photo';
                            img.classList.add('object-cover', 'w-full', 'h-40', 'cursor-pointer',
                                'review-image');

                            // Add lightbox effect
                            img.onclick = function() {
                                openLightbox(data.review_photos, index);
                            };

                            imgContainer.appendChild(img);
                            photosGrid.appendChild(imgContainer);
                        });
                    } else {
                        photosContainer.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error fetching rating details:', error);
                    document.getElementById('displayRating').innerHTML =
                        '<span class="text-red-500"><i class="fas fa-exclamation-circle mr-2"></i>Error loading rating</span>';
                    document.getElementById('displayComment').innerHTML =
                        '<span class="text-red-500">Failed to load review details. Please try again.</span>';
                });
        }

        function closeReviewModal() {
            // Add fade-out animation
            const modal = document.getElementById('viewReviewModal');
            modal.classList.add('opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('opacity-0');
            }, 300);
        }

        // Simple lightbox for review photos
        function openLightbox(photos, index) {
            // Create lightbox container
            const lightbox = document.createElement('div');
            lightbox.classList.add('fixed', 'inset-0', 'z-50', 'flex', 'items-center', 'justify-center', 'p-4', 'bg-black',
                'bg-opacity-90');

            // Create image element
            const img = document.createElement('img');
            img.src = `/storage/${photos[index]}`;
            img.classList.add('max-w-full', 'max-h-screen', 'object-contain');

            // Create close button
            const closeBtn = document.createElement('button');
            closeBtn.innerHTML =
                '<svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="white"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            closeBtn.classList.add('absolute', 'top-4', 'right-4', 'text-white', 'focus:outline-none');
            closeBtn.onclick = () => document.body.removeChild(lightbox);

            // Navigation buttons
            if (photos.length > 1) {
                // Previous button
                const prevBtn = document.createElement('button');
                prevBtn.innerHTML =
                    '<svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="white"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>';
                prevBtn.classList.add('absolute', 'left-4', 'text-white', 'focus:outline-none');
                prevBtn.onclick = (e) => {
                    e.stopPropagation();
                    const newIndex = (index - 1 + photos.length) % photos.length;
                    img.src = `/storage/${photos[newIndex]}`;
                    index = newIndex;
                };

                // Next button
                const nextBtn = document.createElement('button');
                nextBtn.innerHTML =
                    '<svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="white"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>';
                nextBtn.classList.add('absolute', 'right-4', 'text-white', 'focus:outline-none');
                nextBtn.onclick = (e) => {
                    e.stopPropagation();
                    const newIndex = (index + 1) % photos.length;
                    img.src = `/storage/${photos[newIndex]}`;
                    index = newIndex;
                };

                lightbox.appendChild(prevBtn);
                lightbox.appendChild(nextBtn);
            }

            // Close on background click
            lightbox.onclick = () => document.body.removeChild(lightbox);
            img.onclick = (e) => e.stopPropagation();

            lightbox.appendChild(img);
            lightbox.appendChild(closeBtn);
            document.body.appendChild(lightbox);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            document.cookie = `user_timezone=${timezone}; path=/; max-age=31536000`; // 1 year expiry
        });
    </script>
@endsection
