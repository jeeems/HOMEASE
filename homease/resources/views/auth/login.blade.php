@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="login-card p-4 shadow-lg">
            <h3 class="text-center mb-3">Login</h3>

            <!-- Role Selection -->
            <div class="role-selection d-flex justify-content-center position-relative">
                <div class="role-option active" data-role="client">Client</div>
                <div class="role-option" data-role="worker">Worker</div>
                <div class="underline"></div>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <input type="hidden" name="role" id="selectedRole" value="client">

                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('password.request') }}" class="text-decoration-none">Forgot Password?</a>
            </div>

            <div class="text-center mt-2">
                <span>Don't have an account?</span> <a href="{{ route('register') }}" class="text-decoration-none">Sign
                    up</a>
            </div>
        </div>
    </div>

    <style>
        /* Background Styling */
        body {
            background: url("{{ asset('assets/homepage/covers/HOMEASE COVER.png') }}") no-repeat center center fixed;
            background-size: cover;
            backdrop-filter: blur(8px);
            overflow: hidden;
        }

        /* Glass Morphic Login Card */
        .login-card {
            position: relative;
            top: -15vh;
            width: 400px;
            background: white;
            /* Transparent effect */
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            /* Frosted glass effect */
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Role Selection Styles */
        .role-selection {
            display: flex;
            gap: 40px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            position: relative;
            margin-bottom: 20px;
        }

        .role-option {
            padding-bottom: 5px;
            position: relative;
            transition: color 0.3s ease-in-out;
        }

        .underline {
            position: absolute;
            bottom: 0;
            height: 2px;
            /* Keep it thin */
            background: linear-gradient(to right, transparent, #007bff, transparent);
            /* Makes endpoints thinner */
            border-radius: 50%;
            /* Adds sharp effect to edges */
            transition: transform 0.3s ease-in-out, width 0.3s ease-in-out;
            left: 0;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const roleOptions = document.querySelectorAll(".role-option");
            const underline = document.querySelector(".underline");
            const roleInput = document.getElementById("selectedRole");

            function updateUnderline(selectedOption) {
                const rect = selectedOption.getBoundingClientRect();
                const parentRect = selectedOption.parentNode.getBoundingClientRect();

                underline.style.width = `${rect.width + 10}px`; // Extend underline width
                underline.style.transform =
                    `translateX(${rect.left - parentRect.left}px)`; // Move underline under selected text
            }

            // Set initial position
            updateUnderline(document.querySelector(".role-option.active"));

            roleOptions.forEach(option => {
                option.addEventListener("click", function() {
                    // Remove active class from all
                    roleOptions.forEach(opt => opt.classList.remove("active"));

                    // Add active class to clicked option
                    this.classList.add("active");

                    // Update hidden input value
                    roleInput.value = this.getAttribute("data-role");

                    // Move underline
                    updateUnderline(this);
                });
            });
        });
    </script>
@endsection
