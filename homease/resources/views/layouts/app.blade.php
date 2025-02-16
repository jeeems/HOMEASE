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

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-white">
    <div id="app">
        <!-- Navbar -->
        @php
            $currentRoute = request()->route()->getName();
            $isAuthPage = in_array($currentRoute, ['login', 'register.form', 'verify']);
        @endphp

        <nav
            class="{{ $isAuthPage ? 'navbar fixed top-0 left-0 w-full z-50 text-blue-600 p-4 transition-all duration-300' : 'fixed top-0 left-0 w-full z-50 bg-white text-blue-600 p-3 shadow-md' }}">
            <div class="container mx-auto flex justify-between items-center">
                <!-- Logo -->
                <div>
                    <a href="{{ Auth::check() ? route('home') : route('welcome') }}">
                        <img src="{{ asset('assets/logo-and-icon/LOGO.png') }}" alt="HomEase Logo" class="h-10 w-auto">
                    </a>
                </div>

                @if (Auth::check() && Auth::user()->role == 'client')
                    <!-- Desktop Navigation (Centered) -->
                    <div class="hidden md:flex absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2">
                        <ul class="flex space-x-8 text-lg items-center">
                            <li><a href="{{ route('home') }}"
                                    class="hover:text-blue-400 transition no-underline">Home</a></li>
                            <li><a href="#services" class="hover:text-blue-400 transition no-underline">Services</a>
                            </li>
                            <li><a href="{{ route('pricing') }}"
                                    class="hover:text-blue-400 transition no-underline">Pricing</a></li>
                            <li><a href="#" class="hover:text-blue-400 transition no-underline">About Us</a></li>
                        </ul>
                    </div>
                @endif

                <!-- Mobile Menu Button & Profile Icon -->
                <div class="flex items-center space-x-4">
                    <!-- Hamburger Menu Button -->
                    <button id="menuToggle" class="md:hidden text-blue-600 text-2xl focus:outline-none">
                        <i class="fas fa-bars"></i>
                    </button>

                    <!-- Profile Dropdown -->
                    @if (Auth::check())
                        <div class="relative">
                            <button id="profileDropdownBtn" class="focus:outline-none">
                                @if (Auth::user()->profile_picture)
                                    <img src="{{ Auth::user()->profile_picture }}" alt="Profile"
                                        class="w-10 h-10 rounded-full border">
                                @else
                                    <i
                                        class="fas fa-user-circle text-3xl {{ Auth::user()->gender == 'male' ? 'text-blue-600' : (Auth::user()->gender == 'female' ? 'text-pink-600' : 'text-gray-600') }}"></i>
                                @endif
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="profileDropdown"
                                class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg hidden">
                                <a href="{{ route('profile') }}"
                                    class="block px-4 py-2 hover:bg-gray-100 no-underline">Profile</a>
                                <a href="{{ route('settings') }}"
                                    class="block px-4 py-2 hover:bg-gray-100 no-underline">Settings</a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left block px-4 py-2 hover:bg-gray-100 no-underline">Logout</button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div id="mobileMenu" class="md:hidden hidden bg-white text-blue-600 p-4 space-y-4 border-t">
                <ul class="text-lg space-y-4">
                    <li><a href="{{ route('home') }}" class="hover:text-blue-400 transition no-underline">Home</a></li>
                    <li><a href="#services" class="hover:text-blue-400 transition no-underline">Services</a></li>
                    <li><a href="{{ route('pricing') }}"
                            class="hover:text-blue-400 transition no-underline">Pricing</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition no-underline">About Us</a></li>
                </ul>
            </div>
        </nav>

        <main class="py-4 mt-16">
            @yield('content')
        </main>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const menuToggle = document.getElementById("menuToggle");
            const mobileMenu = document.getElementById("mobileMenu");
            const profileBtn = document.getElementById("profileDropdownBtn");
            const dropdown = document.getElementById("profileDropdown");

            // Toggle mobile menu
            menuToggle.addEventListener("click", function() {
                mobileMenu.classList.toggle("hidden");
            });

            // Toggle profile dropdown
            profileBtn?.addEventListener("click", function(event) {
                dropdown.classList.toggle("hidden");
                event.stopPropagation(); // Prevent immediate closure
            });

            // Close dropdown when clicking outside
            document.addEventListener("click", function(event) {
                if (!profileBtn?.contains(event.target) && !dropdown.contains(event.target)) {
                    dropdown.classList.add("hidden");
                }
            });
        });
    </script>
</body>

</html>
