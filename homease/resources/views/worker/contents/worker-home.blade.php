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

                                            <div class="space-y-2 mb-4">
                                                <div class="flex items-start text-sm text-gray-600">
                                                    <i class="fas fa-tools mt-0.5 mr-2 w-4 text-gray-500"></i>
                                                    <span>{{ $booking->service_type }}</span>
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

                                            <div class="flex flex-col sm:flex-row gap-2 mt-4">
                                                <form action="{{ route('bookings.update', $booking->id) }}" method="POST"
                                                    class="w-full">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="ongoing">
                                                    <button type="submit"
                                                        class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-md flex items-center justify-center transition-all duration-200 hover:from-green-600 hover:to-green-700 shadow-sm">
                                                        <i class="fas fa-check-circle mr-2"></i> Accept Job
                                                    </button>
                                                </form>

                                                <form action="{{ route('bookings.update', $booking->id) }}" method="POST"
                                                    class="w-full">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit"
                                                        class="w-full border border-red-500 text-red-500 px-4 py-2 rounded-md flex items-center justify-center transition-all duration-200 hover:bg-red-50">
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

                                            <div class="space-y-2 mb-4">
                                                <div class="flex items-start text-sm text-gray-600">
                                                    <i class="fas fa-tools mt-0.5 mr-2 w-4 text-gray-500"></i>
                                                    <span>{{ $booking->service_type }}</span>
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
                                            <form action="{{ route('bookings.update', $booking->id) }}" method="POST"
                                                class="mt-4">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit"
                                                    class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-2.5 rounded-md flex items-center justify-center transition-all duration-200 hover:from-blue-600 hover:to-indigo-700 shadow-sm hover:shadow">
                                                    <i class="fas fa-check-circle mr-2"></i> Mark as Completed
                                                </button>
                                            </form>
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
    </script>
@endsection
