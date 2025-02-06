<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homease</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo-and-icon/title-icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest"></script>

    <style>
        /* Button hover animations */
        .register-btn,
        .login-btn {
            position: relative;
            overflow: hidden;
            transition: color 0.3s ease-in-out;
        }

        .register-btn::before,
        .login-btn::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            transition: left 0.4s ease-in-out;
            z-index: 0;
        }

        .register-btn::before {
            background-color: #2563eb;
        }

        .login-btn::before {
            background-color: white;
        }

        .register-btn:hover::before,
        .login-btn:hover::before {
            left: 0;
        }

        .register-btn span,
        .login-btn span {
            position: relative;
            z-index: 1;
        }

        .register-btn:hover {
            color: white;
        }

        .login-btn:hover {
            color: #1E40AF;
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
    </style>
</head>

<body class="bg-white">
    <!-- Navbar -->
    <nav class="bg-white text-blue-600 p-4">
        <div class="container mx-auto flex justify-between items-center relative">
            <div>
                <a href="{{ url('/') }}">
                    <img src="{{ asset('assets/logo-and-icon/LOGO.png') }}" alt="HomEase Logo"
                        class="h-8 sm:h-10 md:h-10 lg:h-10 w-auto">
                </a>
            </div>

            <ul class="hidden md:flex space-x-8 text-lg">
                <li><a href="#" class="hover:text-blue-400 transition">Home</a></li>
                <li><a href="#" class="hover:text-blue-400 transition">Services</a></li>
                <li><a href="#" class="hover:text-blue-400 transition">Pricing</a></li>
                <li><a href="#" class="hover:text-blue-400 transition">About Us</a></li>
            </ul>

            <div class="flex items-center space-x-4">
                <a href="{{ route('login') }}"
                    class="login-btn bg-blue-600 text-white px-4 py-2 rounded-md font-semibold">
                    <span>Login</span>
                </a>
                <a href="{{ route('register') }}"
                    class="register-btn border bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold shadow-md">
                    <span>Register</span>
                </a>

                <button id="mobile-menu-button" class="text-2xl md:hidden transition-all duration-300">
                    &#9776;
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="md:hidden hidden bg-white text-blue-600 p-4 space-y-4">
            <ul class="text-lg space-y-4">
                <li><a href="#" class="hover:text-blue-400 transition">Home</a></li>
                <li><a href="#" class="hover:text-blue-400 transition">Services</a></li>
                <li><a href="#" class="hover:text-blue-400 transition">Pricing</a></li>
                <li><a href="#" class="hover:text-blue-400 transition">About Us</a></li>
            </ul>
        </div>
    </nav>

    <div class="content-wrapper">
        <!-- Cover Section -->
        <div class="relative flex justify-center mt-2 mb-2 px-4 sm:px-16">
            <div
                class="w-full sm:w-[90%] md:w-[80%] lg:w-[70%] max-w-[1313px] h-[300px] sm:h-[400px] md:h-[461px] rounded-2xl overflow-hidden relative">
                <img src="{{ asset('assets/homepage/covers/HOMEASE COVER.png') }}" alt="Homease Cover"
                    class="w-full h-full object-cover">

                <!-- Gradient Overlay -->
                <div
                    class="absolute top-0 right-0 w-full md:w-1/2 h-full bg-gradient-to-l from-black/70 to-transparent rounded-2xl">
                </div>

                <!-- Testimonial -->
                <div class="absolute top-4 right-4 text-black text-right max-w-xs z-10">
                    <div class="flex justify-end space-x-1">
                        <i data-lucide="star" class="w-6 sm:w-8 h-6 sm:h-8 text-yellow-400"></i>
                        <i data-lucide="star" class="w-6 sm:w-8 h-6 sm:h-8 text-yellow-400"></i>
                        <i data-lucide="star" class="w-6 sm:w-8 h-6 sm:h-8 text-yellow-400"></i>
                        <i data-lucide="star" class="w-6 sm:w-8 h-6 sm:h-8 text-yellow-400"></i>
                        <i data-lucide="star" class="w-6 sm:w-8 h-6 sm:h-8 text-yellow-400"></i>
                    </div>
                    <p class="mt-2 text-xs sm:text-sm italic">“Booking took just minutes, and the results were amazing!
                        My
                        house went from chaos to spotless in one visit.”</p>
                    <p class="mt-1 sm:mt-2 text-xs font-semibold">- Kenn, Busy Financial Analyst</p>
                </div>

                <!-- Call to Action -->
                <div
                    class="absolute bottom-4 left-4 bg-white p-2 sm:p-4 rounded-lg shadow-lg flex items-center space-x-4">
                    <button class="login-btn bg-blue-600 text-white px-4 py-2 rounded-md font-semibold">
                        <span>Book Now!</span>
                    </button>
                    <span class="text-gray-700 font-semibold text-xs sm:text-base">As low as ₱300!</span>
                </div>
            </div>
        </div>

        <!-- Tagline Section -->
        <div class="text-center mt-0 px-4 sm:px-0">
            <h2 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-gray-900">YOUR HOME, OUR EXPERTISE.</h2>
            <div
                class="relative inline-block mt-2 sm:mt-2 transform -rotate-2 sm:left-1/2 sm:-translate-x-1/2 lg:left-96">
                <span class="absolute -inset-1 bg-blue-300 opacity-50 rounded-lg"></span>
                <p class="relative text-base sm:text-lg font-semibold text-gray-800">
                    Book as fast as 10 mins!
                </p>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            lucide.createIcons();
        });

        const menuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const contentWrapper = document.querySelector('.content-wrapper');

        let menuOpen = false;

        menuButton.addEventListener('click', function() {
            if (menuOpen) {
                mobileMenu.classList.remove('menu-slide-enter');
                mobileMenu.classList.add('menu-slide-exit');
                setTimeout(() => {
                    mobileMenu.classList.add('hidden'); // Hide after animation ends
                }, 500);
                contentWrapper.style.marginTop = "0px"; // Move content back up
            } else {
                mobileMenu.classList.remove('hidden', 'menu-slide-exit');
                mobileMenu.classList.add('menu-slide-enter');
                contentWrapper.style.marginTop = mobileMenu.offsetHeight + "px"; // Push content down
            }
            menuOpen = !menuOpen;
        });
    </script>
</body>

</html>
