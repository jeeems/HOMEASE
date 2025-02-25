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
                                                        <a href="{{ route('rate.worker', ['worker_id' => $booking->worker_id]) }}"
                                                            class="bg-gradient-to-r from-green-500 to-green-600 text-white px-3 py-1.5 rounded-md inline-flex items-center text-sm transition-all duration-200 hover:from-green-600 hover:to-green-700 shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 no-underline">
                                                            <i class="fas fa-star text-sm mr-1.5"></i> Rate
                                                        </a>
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
                                                            <a href="{{ route('rate.worker', ['worker_id' => $booking->worker_id]) }}"
                                                                class="bg-gradient-to-r from-green-500 to-green-600 text-white px-3 py-1.5 rounded-md inline-flex items-center transition-all duration-200 hover:from-green-600 hover:to-green-700 shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 no-underline">
                                                                <i class="fas fa-star mr-1.5"></i> Rate Worker
                                                            </a>
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

        function showCancelModal(route) {
            document.getElementById('cancelForm').action = route;
            document.getElementById('cancelConfirmationModal').classList.remove('hidden');
        }

        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('cancelConfirmationModal').classList.add('hidden');
        });
    </script>
@endsection
