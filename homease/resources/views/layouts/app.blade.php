<!-- app.blade.php -->
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="{{ asset('assets/logo-and-icon/title-icon.png') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts & Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-white">
    <div id="app">
        <!-- Navbar -->
        @php
            $currentRoute = request()->route()->getName();
            $isAuthPage = in_array($currentRoute, ['login', 'register.form', 'verify', 'admin.login']);
        @endphp

        <nav
            class="{{ $isAuthPage ? 'navbar fixed top-0 left-0 w-full z-50 text-blue-600 p-4 transition-all duration-300' : 'fixed top-0 left-0 w-full z-50 bg-white text-blue-600 p-3 shadow-md' }}">
            <div class="container mx-auto flex justify-between items-center">
                <!-- Logo -->
                <div>
                    <a
                        href="
    @if (Auth::check()) @if (Auth::user()->role == 'worker')
            {{ route('worker.home') }}
        @elseif (Auth::user()->role == 'admin')
            {{ route('admin.dashboard') }}
        @else
            {{ route('home') }} @endif
@else
{{ route('welcome') }}
    @endif
">
                        <img src="{{ asset('assets/logo-and-icon/LOGO.png') }}" alt="HomEase Logo" class="h-10 w-auto">
                    </a>
                </div>

                <!-- Desktop Navigation (Only show if not on login/register page) -->
                @if (Auth::check() && Auth::user()->role == 'client' && !$isAuthPage)
                    <div class="hidden md:flex absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2">
                        <ul class="flex space-x-8 text-lg items-center">
                            <li><a href="{{ route('home') }}"
                                    class="hover:text-blue-400 transition no-underline">Home</a></li>
                            <li>
                                @if (request()->is('home'))
                                    <a href="#services" class="hover:text-blue-400 transition no-underline">Services</a>
                                @else
                                    <a href="{{ url('/') }}#services"
                                        class="hover:text-blue-400 transition no-underline">Services</a>
                                @endif


                            </li>
                            <li><a href="{{ route('pricing') }}"
                                    class="hover:text-blue-400 transition no-underline">Pricing</a></li>
                            <li><a href="{{ route('bookings.index') }}"
                                    class="hover:text-blue-400 transition no-underline">My Bookings</a></li>
                            <li><a href="#" class="hover:text-blue-400 transition no-underline">About Us</a></li>
                        </ul>
                    </div>
                @endif

                <!-- Mobile Menu Button & Profile (Hide on login/register pages) -->
                @if (!$isAuthPage)
                    <div class="flex items-center space-x-4">

                        @if (Auth::check())
                            <div class="flex items-center space-x-2 md:hidden">
                                <span class="text-sm font-medium">{{ Auth::user()->first_name }}</span>
                            </div>

                            <!-- Mobile Menu Button -->
                            <button id="menuToggle" class="md:hidden text-blue-600 text-2xl focus:outline-none">
                                <i class="fas fa-bars"></i>
                            </button>
                        @endif

                        @if (Auth::check())
                            <!-- Desktop Profile: First Name + Profile Icon -->
                            <div class="hidden md:flex items-center space-x-3 relative">
                                <button id="profileDropdownBtn" class="flex items-center focus:outline-none space-x-2">
                                    <span class="text-lg font-medium">{{ Auth::user()->first_name }}</span>
                                    @if (Auth::user()->profile && Auth::user()->profile->profile_picture)
                                        <img src="{{ asset('storage/' . Auth::user()->profile->profile_picture) }}"
                                            alt="Profile" class="w-10 h-10 rounded-full border">
                                    @else
                                        <i class="fas fa-user-circle text-3xl text-gray-600"></i>
                                    @endif
                                </button>

                                <!-- Dropdown Menu -->
                                <div id="profileDropdown"
                                    class="hidden absolute right-0 top-full w-48 bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 z-50">
                                    <a href="{{ route('profile') }}"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 no-underline">Profile</a>
                                    @if (Auth::check() && Auth::user()->role == 'worker')
                                        <div class="px-4 py-2 flex items-center justify-between hover:bg-gray-100 cursor-pointer"
                                            onclick="document.getElementById('desktopAvailabilityToggle').click()">
                                            <span class="text-gray-700 ">Availability</span>
                                            <label class="switch">
                                                <input type="checkbox" class="availability-toggle"
                                                    id="desktopAvailabilityToggle"
                                                    {{ Auth::user()->workerAvailability->is_available ?? 0 ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    @endif

                                    @if (Auth::check() && Auth::user()->role == 'client')
                                        <a href="{{ route('bookings.index') }}"
                                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100 no-underline">My
                                            Bookings</a>


                                        {{-- <a href="{{ route('settings') }}"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 no-underline">Settings</a> --}}
                                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                                            @csrf
                                            <button type="submit" onclick="console.log('Logout button clicked')"
                                                class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100 focus:outline-none">Logout</button>
                                        </form>
                                    @endif
                                    @if (Auth::check() && Auth::user()->role == 'worker')
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                            class="block px-4 py-2 text-red-600 hover:bg-gray-100 focus:outline-none no-underline">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="hidden">
                                            @csrf
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </nav>

        <!-- Mobile Drawer (Hidden on login/register pages) -->
        @if (!$isAuthPage)
            <div id="mobileDrawer"
                class="fixed top-0 right-0 h-full w-64 bg-white shadow-lg transform translate-x-full transition-transform duration-300 p-4 md:hidden z-50">

                <!-- Close Button -->
                <button id="closeDrawer" class="text-2xl text-gray-600 absolute top-4 right-4 focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>

                @if (Auth::check())
                    <!-- Profile Info -->
                    <div class="flex flex-col items-start mt-8 pl-4">
                        @if (Auth::user()->profile && Auth::user()->profile->profile_picture)
                            <img src="{{ asset('storage/' . Auth::user()->profile->profile_picture) }}" alt="Profile"
                                class="w-16 h-16 rounded-full border">
                        @else
                            <i class="fas fa-user-circle text-5xl text-gray-600"></i>
                        @endif
                        <span class="mt-2 text-lg font-semibold">{{ Auth::user()->first_name }}
                            {{ Auth::user()->last_name }}</span>
                        <span class="text-sm text-gray-500">
                            {{ ucfirst(Auth::user()->role) }}
                            @if (Auth::user()->role == 'worker' && Auth::user()->workerVerification)
                                | {{ Auth::user()->workerVerification->service_type }}
                            @endif
                        </span>
                    </div>
                @endif

                @if (Auth::check() && Auth::user()->role == 'client' && !$isAuthPage)
                    <!-- Navigation Links -->
                    <ul class="mt-6 space-y-4 text-lg text-left pl-4">
                        <li><a href="{{ route('home') }}"
                                class="block hover:text-blue-400 transition no-underline">Home</a></li>
                        <li>
                            @if (request()->is('home'))
                                <a href="#services"
                                    class="block hover:text-blue-400 transition no-underline">Services</a>
                            @else
                                <a href="{{ url('/') }}#services"
                                    class="block hover:text-blue-400 transition no-underline">Services</a>
                            @endif
                        </li>
                        <li><a href="{{ route('pricing') }}"
                                class="block hover:text-blue-400 transition no-underline">Pricing</a></li>
                        <li><a href="{{ route('bookings.index') }}"
                                class="block hover:text-blue-400 transition no-underline">My Bookings</a></li>
                        <li><a href="#" class="block hover:text-blue-400 transition no-underline">About Us</a>
                        </li>
                    </ul>
                @endif

                <!-- Bottom Actions -->
                <div class="absolute bottom-6 left-0 w-full text-left bg-white border-t border-gray-200">
                    <!-- Profile Link -->
                    <a href="{{ route('profile') }}"
                        class="block w-full px-4 py-3 text-blue-600 hover:bg-gray-100 transition duration-200 no-underline">
                        Profile
                    </a>

                    <!-- Worker Availability Toggle -->
                    @if (Auth::check() && Auth::user()->role == 'worker')
                        <div class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-100 transition duration-200 cursor-pointer"
                            onclick="document.getElementById('mobileAvailabilityToggle').click()">
                            <span class="text-blue-600">Availability</span>
                            <label class="switch">
                                <input type="checkbox" class="availability-toggle" id="mobileAvailabilityToggle"
                                    {{ Auth::user()->workerAvailability->is_available ?? 0 ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    @endif

                    <!-- Client Logout -->
                    @if (Auth::check() && Auth::user()->role == 'client')
                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" onclick="console.log('Logout button clicked')"
                                class="w-full text-left px-4 py-3 text-red-600 hover:bg-gray-100 transition duration-200 focus:outline-none">
                                Logout
                            </button>
                        </form>
                    @endif

                    <!-- Worker Logout -->
                    @if (Auth::check() && Auth::user()->role == 'worker')
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="block w-full text-left px-4 py-3 text-red-600 hover:bg-gray-100 transition duration-200 focus:outline-none no-underline">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    @endif
                </div>
            </div>
        @endif


        <main class="py-4 mt-16">
            @yield('content')
        </main>

        <!-- CSS -->
        <style>
            .switch {
                position: relative;
                display: inline-block;
                width: 34px;
                height: 20px;
            }

            .switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }

            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                transition: .4s;
                border-radius: 34px;
            }

            .slider:before {
                position: absolute;
                content: "";
                height: 14px;
                width: 14px;
                left: 3px;
                bottom: 3px;
                background-color: white;
                transition: .4s;
                border-radius: 50%;
            }

            input:checked+.slider {
                background-color: #4CAF50;
            }

            input:checked+.slider:before {
                transform: translateX(14px);
            }
        </style>

        <!-- JavaScript -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const menuToggle = document.getElementById("menuToggle");
                const mobileDrawer = document.getElementById("mobileDrawer");
                const closeDrawer = document.getElementById("closeDrawer");

                // Open drawer from the right
                menuToggle.addEventListener("click", function() {
                    mobileDrawer.classList.remove("translate-x-full");
                });

                // Close drawer
                closeDrawer.addEventListener("click", function() {
                    mobileDrawer.classList.add("translate-x-full");
                });

                // Close drawer when clicking outside
                document.addEventListener("click", function(event) {
                    if (!mobileDrawer.contains(event.target) && !menuToggle.contains(event.target)) {
                        mobileDrawer.classList.add("translate-x-full");
                    }
                });
            });

            document.addEventListener("DOMContentLoaded", function() {
                const profileDropdownBtn = document.getElementById("profileDropdownBtn");
                const profileDropdown = document.getElementById("profileDropdown");

                profileDropdownBtn.addEventListener("click", function(event) {
                    event.stopPropagation(); // Prevents the event from bubbling up
                    profileDropdown.classList.toggle("hidden");
                });

                // Close the dropdown when clicking outside
                document.addEventListener("click", function(event) {
                    if (!profileDropdown.contains(event.target) && !profileDropdownBtn.contains(event.target)) {
                        profileDropdown.classList.add("hidden");
                    }
                });
            });

            document.addEventListener("DOMContentLoaded", function() {
                // Function to handle availability toggle
                function handleAvailabilityToggle(event) {
                    const isAvailable = event.target.checked ? 1 : 0;
                    const otherToggle = document.querySelector('.availability-toggle:not(#' + event.target.id + ')');

                    // Update the other toggle to match
                    if (otherToggle) {
                        otherToggle.checked = event.target.checked;
                    }

                    fetch("{{ route('worker.availability.toggle') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                is_available: isAvailable
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log("Availability status updated successfully!");
                            } else {
                                console.error("Failed to update availability:", data.message);
                                // Revert the toggle if the update failed
                                event.target.checked = !event.target.checked;
                                if (otherToggle) {
                                    otherToggle.checked = event.target.checked;
                                }
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            // Revert the toggle on error
                            event.target.checked = !event.target.checked;
                            if (otherToggle) {
                                otherToggle.checked = event.target.checked;
                            }
                        });
                }

                // Add event listeners to all availability toggles
                document.querySelectorAll('.availability-toggle').forEach(toggle => {
                    toggle.addEventListener('change', handleAvailabilityToggle);
                });
            });
        </script>
</body>

</html>
