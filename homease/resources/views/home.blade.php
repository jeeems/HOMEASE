@extends('layouts.app')

@section('content')
    @if (Auth::user()->role == 'client')
        <!-- Main Page Content -->
        <div class="content-wrapper">
            <!-- Cover Section -->
            <div class="relative flex justify-center mt-4 mb-2 px-4 sm:px-16">
                <div
                    class="w-full sm:w-[90%] md:w-[80%] lg:w-[70%] max-w-[1313px] h-[300px] sm:h-[400px] md:h-[461px] rounded-2xl overflow-hidden relative">
                    <img src="{{ asset('assets/homepage/covers/HOMEASE COVER.png') }}" alt="Homease Cover"
                        class="w-full h-full object-cover object-center sm:object-right md:object-center">

                    <!-- Gradient Overlay -->
                    <div
                        class="absolute top-0 right-0 w-full md:w-1/2 h-full bg-gradient-to-l from-black/70 to-transparent rounded-2xl">
                    </div>

                    <!-- Testimonial -->
                    <div class="absolute top-4 right-4 text-white sm:text-black text-right max-w-xs z-10">
                        <div class="flex justify-end space-x-1">
                            <!-- FontAwesome Stars with brighter yellow and larger size -->
                            <i class="fas fa-star w-6 sm:w-8 md:w-10 h-6 sm:h-8 md:h-10 text-yellow-300"></i>
                            <i class="fas fa-star w-6 sm:w-8 md:w-10 h-6 sm:h-8 md:h-10 text-yellow-300"></i>
                            <i class="fas fa-star w-6 sm:w-8 md:w-10 h-6 sm:h-8 md:h-10 text-yellow-300"></i>
                            <i class="fas fa-star w-6 sm:w-8 md:w-10 h-6 sm:h-8 md:h-10 text-yellow-300"></i>
                            <i class="fas fa-star w-6 sm:w-8 md:w-10 h-6 sm:h-8 md:h-10 text-yellow-300"></i>
                        </div>
                        <p class="text-gray-700 mt-2 text-xs sm:text-sm italic text-shadow-sm">"Booking took
                            just
                            minutes, and the
                            results
                            were amazing! My house went from chaos to spotless in one visit."</p>
                        <p class="mt-1 sm:mt-2 text-xs font-semibold text-shadow-sm">- Kenn, Busy Financial
                            Analyst</p>
                    </div>

                    <!-- Call to Action -->
                    <div
                        class="absolute bottom-4 left-4 bg-white/90 backdrop-blur-sm p-2 sm:p-4 rounded-lg shadow-lg flex items-center space-x-4">
                        {{-- <a href="{{ route('booking') }}" class="book-now-btn relative inline-block px-6 py-3 text-white font-bold rounded-lg overflow-hidden transition-all duration-300 ease-in-out bg-blue-600 hover:text-blue-600">
                            <span class="absolute inset-0 bg-white scale-0 transition-transform duration-300 ease-in-out" aria-hidden="true"></span>
                            <span class="relative z-10">Book Now!</span>
                        </a> --}}

                        <a href="{{ route('home') }}"
                            class="book-now-btn relative inline-block px-3 py-2.5 text-white font-bold rounded-lg overflow-hidden transition-all duration-300 ease-in-out bg-blue-600 hover:text-blue-600">
                            <span
                                class="absolute inset-0 bg-white scale-x-0 transition-transform duration-300 ease-in-out origin-left"></span>
                            <span class="relative z-10 no-underline">Book Now!</span>
                        </a>

                        <span class="as-low text-gray-700 font-semibold text-xs sm:text-base">As low as
                            ₱300!</span>
                    </div>
                </div>
            </div>

            <!-- Tagline Section -->
            <div class="text-center mt-8 mb-8 px-4 sm:px-0">
                <h2 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-gray-900">YOUR HOME, OUR
                    EXPERTISE.</h2>
                <div
                    class="relative inline-block mt-2 sm:mt-2 transform -rotate-2 sm:left-1/2 sm:-translate-x-1/2 lg:left-96">
                    <span class="absolute -inset-1 bg-blue-300 opacity-50 rounded-lg"></span>
                    <p class="relative text-base sm:text-lg font-semibold text-gray-800">
                        Book as fast as 10 mins!
                    </p>
                </div>
            </div>

            <!-- Services Section -->
            <section id="services" class="relative mt-16 py-16 bg-blue-900">
                <div class="relative container mx-auto px-6 md:px-12">
                    <h2 class="text-4xl font-bold text-white text-center mb-16">OUR SERVICES</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Service Cards -->
                        <div class="service-card bg-white rounded-lg overflow-hidden shadow-lg">
                            <img src="{{ asset('assets/homepage/service_banners/home-cleaning.png') }}" alt="Home Cleaning"
                                class="w-full h-40 object-cover rounded-t-lg">
                            <div class="p-6">
                                <h3 class="text-xl font-semibold mt-2">Home Cleaning</h3>
                                <p class="text-gray-600 mt-2">Discover the joy of coming home to a spotless,
                                    fresh-smelling haven. Our professional home cleaning service is designed to
                                    give you
                                    more time for what matters most while we take care of the rest.</p>
                                <div class="flex items-center mt-2">
                                    <p class="font-bold text-blue-600">Starts at ₱600</p>
                                    <p class="text-gray-400 text-sm ml-2">/ session</p>
                                </div>
                                <div class="mt-4 flex space-x-4">
                                    <button class="bg-gray-200 px-4 py-2 rounded-md">Check Reviews</button>

                                    <a href="{{ route('home') }}"
                                        class="book-now-btn relative inline-block px-3 py-2.5 text-white rounded-lg overflow-hidden transition-all duration-300 ease-in-out bg-blue-600 hover:text-blue-600">
                                        <span
                                            class="absolute inset-0 bg-white scale-x-0 transition-transform duration-300 ease-in-out origin-left"></span>
                                        <span class="relative z-10 no-underline">Book Now</span>
                                    </a>
                                </div>
                            </div>
                        </div>


                        <div class="service-card bg-white rounded-lg overflow-hidden shadow-lg">
                            <img src="{{ asset('assets/homepage/service_banners/daycare.png') }}" alt="Daycare"
                                class="w-full h-40 object-cover rounded-t-lg">
                            <div class="p-6">
                                <h3 class="text-xl font-semibold mt-2">Daycare</h3>
                                <p class="text-gray-600 mt-2">Give your children, disabled adults, and elderly
                                    the
                                    attention and care they deserve with our professional daycare services.
                                    We're here
                                    to provide a safe, fun, and enriching environment when you can't be there.
                                </p>
                                <div class="flex items-center mt-2">
                                    <p class="font-bold text-blue-600">Starts at ₱300</p>
                                    <p class="text-gray-400 text-sm ml-2">/ hour</p>
                                </div>
                                <div class="mt-4 flex space-x-4">
                                    <button class="bg-gray-200 px-4 py-2 rounded-md">Check Reviews</button>
                                    <a href="{{ route('home') }}"
                                        class="book-now-btn relative inline-block px-3 py-2.5 text-white rounded-lg overflow-hidden transition-all duration-300 ease-in-out bg-blue-600 hover:text-blue-600">
                                        <span
                                            class="absolute inset-0 bg-white scale-x-0 transition-transform duration-300 ease-in-out origin-left"></span>
                                        <span class="relative z-10 no-underline">Book Now</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="service-card bg-white rounded-lg overflow-hidden shadow-lg">
                            <img src="{{ asset('assets/homepage/service_banners/carpentry.png') }}" alt="Carpentry"
                                class="w-full h-40 object-cover rounded-t-lg">
                            <div class="p-6">
                                <h3 class="text-xl font-semibold mt-2">Carpentry</h3>
                                <p class="text-gray-600 mt-2">Don't let those nagging home repairs bring you
                                    down. Our
                                    skilled technicians are ready to tackle any issue, big or small, to keep
                                    your home
                                    running smoothly and efficiently.
                                </p>
                                <div class="flex items-center mt-2">
                                    <p class="font-bold text-blue-600">Starts at ₱500</p>
                                    <p class="text-gray-400 text-sm ml-2">/ session</p>
                                </div>
                                <div class="mt-4 flex space-x-4">
                                    <button class="bg-gray-200 px-4 py-2 rounded-md">Check Reviews</button>
                                    <a href="{{ route('home') }}"
                                        class="book-now-btn relative inline-block px-3 py-2.5 text-white rounded-lg overflow-hidden transition-all duration-300 ease-in-out bg-blue-600 hover:text-blue-600">
                                        <span
                                            class="absolute inset-0 bg-white scale-x-0 transition-transform duration-300 ease-in-out origin-left"></span>
                                        <span class="relative z-10 no-underline">Book Now</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="service-card bg-white rounded-lg overflow-hidden shadow-lg">
                            <img src="{{ asset('assets/homepage/service_banners/plumbing.png') }}" alt="Plumbing"
                                class="w-full h-40 object-cover rounded-t-lg">
                            <div class="p-6">
                                <h3 class="text-xl font-semibold mt-2">Plumbing</h3>
                                <p class="text-gray-600 mt-2">Experience the peace of mind that comes with a
                                    perfectly
                                    functioning home. Our professional plumbing service is here to swiftly
                                    handle leaks,
                                    clogs, and installations, so you can have peace of mind</p>
                                <div class="flex items-center mt-2">
                                    <p class="font-bold text-blue-600">Starts at ₱500</p>
                                    <p class="text-gray-400 text-sm ml-2">/ session</p>
                                </div>
                                <div class="mt-4 flex space-x-4">
                                    <button class="bg-gray-200 px-4 py-2 rounded-md">Check Reviews</button>
                                    <a href="{{ route('home') }}"
                                        class="book-now-btn relative inline-block px-3 py-2.5 text-white rounded-lg overflow-hidden transition-all duration-300 ease-in-out bg-blue-600 hover:text-blue-600">
                                        <span
                                            class="absolute inset-0 bg-white scale-x-0 transition-transform duration-300 ease-in-out origin-left"></span>
                                        <span class="relative z-10 no-underline">Book Now</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="service-card bg-white rounded-lg overflow-hidden shadow-lg">
                            <img src="{{ asset('assets/homepage/service_banners/electrician.png') }}" alt="Electrician"
                                class="w-full h-40 object-cover rounded-t-lg">
                            <div class="p-6">
                                <h3 class="text-xl font-semibold mt-2">Electrician</h3>
                                <p class="text-gray-600 mt-2">Feel the comfort of a well-lit, safely wired home.
                                    Our
                                    expert electricians are ready to tackle everything from minor repairs to
                                    major
                                    installations, ensuring your space stays bright and secure while you enjoy
                                    the
                                    moments that matter most.</p>
                                <div class="flex items-center mt-2">
                                    <p class="font-bold text-blue-600">Starts at ₱500</p>
                                    <p class="text-gray-400 text-sm ml-2">/ session</p>
                                </div>
                                <div class="mt-4 flex space-x-4">
                                    <button class="bg-gray-200 px-4 py-2 rounded-md">Check Reviews</button>
                                    <a href="{{ route('home') }}"
                                        class="book-now-btn relative inline-block px-3 py-2.5 text-white rounded-lg overflow-hidden transition-all duration-300 ease-in-out bg-blue-600 hover:text-blue-600">
                                        <span
                                            class="absolute inset-0 bg-white scale-x-0 transition-transform duration-300 ease-in-out origin-left"></span>
                                        <span class="relative z-10 no-underline">Book Now</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <footer class="bg-white py-12 mt-6">
                <div class="container mx-auto px-6 md:px-12 flex flex-col items-center">
                    <div class="flex flex-col md:flex-row justify-center items-center w-full max-w-4xl text-center">
                        <!-- Left Column -->
                        <div class="w-full md:w-2/5 flex flex-col items-center mb-6 md:mb-0">
                            <h3 class="text-2xl font-semibold text-gray-800">NEED SOMETHING SPECIFIC?</h3>
                            <div class="mt-2 bg-gray-300 text-gray-700 px-8 py-3 rounded-lg ">
                                CALL US HERE | +(63) 912-345-6789
                            </div>
                        </div>

                        <!-- Separator -->
                        <div class="hidden md:block w-px bg-gray-400 h-20 mx-12"></div>

                        <!-- Right Column -->
                        <div class="w-full md:w-2/5 flex flex-col items-center">
                            <h3 class="text-2xl font-semibold text-gray-800">WANT MORE DETAILS?</h3>
                            <a href="{{ route('pricing') }}"
                                class="mt-2 bg-blue-600 text-white px-8 py-3 rounded-lg shadow-md hover:bg-blue-700 transition no-underline">
                                DETAILED PRICING
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="text-center text-gray-600 text-sm mt-8">
                    <img src="{{ asset('assets/logo-and-icon/LOGO.png') }}" alt="Homease Logo" class="h-8 mx-auto mb-2">
                    &copy; 2025 Homease. All rights reserved.
                </div>
            </footer>


        </div>
    @elseif (Auth::user()->role == 'worker')
        <h3>Welcome, Worker!</h3>
        {{-- <p>This is your homepage with worker-specific features.</p> --}}
        <!-- Add worker-related content here -->
    @else
        <p>{{ __('You are logged in!') }}</p>
    @endif

    <style>
        * {
            scroll-behavior: smooth;
        }

        .scrolled {
            background: rgba(255, 255, 255, 0.9);
            /* White with slight transparency */
            backdrop-filter: blur(10px);
            transition: background 0.3s ease-in-out;
        }

        #mobile-menu {
            background: rgba(255, 255, 255, 0.9);
            /* White with slight transparency */
            backdrop-filter: blur(10px);
            /* Blur effect */
            transition: all 0.3s ease-in-out;
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Soft shadow */
        }


        /* Button hover animations */
        .book-now-btn {
            position: relative;
            display: inline-block;
            overflow: hidden;
            transition: color 0.3s ease-in-out;
            text-decoration: none !important;
        }

        .book-now-btn span:first-child {
            position: absolute;
            inset: 0;
            background-color: white;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease-in-out;
            z-index: 5;
            /* Keep background behind text */
        }

        .book-now-btn:hover span:first-child {
            transform: scaleX(1);
        }

        .book-now-btn:hover {
            color: #2563eb !important;
            /* Ensures text color changes to blue-600 */
        }


        /* Slide-down animation */
        @keyframes slideDown {
            from {
                transform: translateY(-10px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(0);
                opacity: 1;
            }

            to {
                transform: translateY(-10px);
                opacity: 0;
            }
        }

        .menu-slide-enter {
            animation: slideDown 0.3s ease-out forwards;
        }

        .menu-slide-exit {
            animation: slideUp 0.3s ease-in forwards;
        }

        .content-wrapper {
            transition: margin-top 0.5s ease-in-out;
        }


        /* Initially hidden */
        .service-card {
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        /* Visible state - animation trigger */
        .service-card.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .text-shadow-sm {
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
        }

        @media (max-width: 640px) {
            img[alt="Homease Cover"] {
                object-position: center;
                object-fit: cover;
                height: 70vh;
                /* Cover the full viewport height */
                width: 100%;
                /* Ensure the image takes up the full width */
            }

            .as-low {
                color: white;
            }
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Hide Skeleton Loader
            const skeletonLoader = document.getElementById("skeleton-loader");
            const mainContent = document.getElementById("main-content");
            if (skeletonLoader && mainContent) {
                skeletonLoader.style.display = "none";
                mainContent.classList.remove("hidden");
            }

            // Initialize Lucide Icons
            if (typeof lucide !== "undefined") {
                lucide.createIcons();
            }

            // Navbar Scroll Effect
            const navbar = document.getElementById("navbar");
            if (navbar) {
                window.addEventListener("scroll", function() {
                    navbar.classList.toggle("scrolled", window.scrollY > 10);
                });
            }

            // Mobile Menu Toggle Logic
            const menuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const contentWrapper = document.querySelector('.content-wrapper');
            let menuOpen = false;

            if (menuButton && mobileMenu) {
                menuButton.addEventListener('click', function() {
                    toggleMobileMenu();
                });

                function toggleMobileMenu() {
                    if (menuOpen) {
                        mobileMenu.classList.remove('menu-slide-enter');
                        mobileMenu.classList.add('menu-slide-exit');
                        setTimeout(() => mobileMenu.classList.add('hidden'), 500);
                        if (contentWrapper) contentWrapper.style.marginTop = "0px";
                    } else {
                        mobileMenu.classList.remove('hidden', 'menu-slide-exit');
                        mobileMenu.classList.add('menu-slide-enter');
                        if (contentWrapper) contentWrapper.style.marginTop = mobileMenu.offsetHeight + "px";
                    }
                    menuOpen = !menuOpen;
                }
            }

            // Service Card Visibility on Scroll
            const cards = document.querySelectorAll(".service-card");
            if (cards.length > 0) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry, index) => {
                        if (entry.isIntersecting) {
                            setTimeout(() => {
                                entry.target.classList.add("visible");
                            }, index * 200);
                        }
                    });
                }, {
                    threshold: 0.2
                });

                cards.forEach(card => observer.observe(card));
            }
        });
    </script>
@endsection
