@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh; overflow: hidden;">
        <div class="register-card p-4 shadow-lg">
            <h3 class="text-center mb-3">Sign Up</h3>

            <!-- Role Selection with Animated Underline -->
            <div class="role-selection d-flex justify-content-center position-relative mb-3">
                <div class="role-option active" data-role="client">Client</div>
                <div class="role-option" data-role="worker">Worker</div>
                <div class="underline"></div>
            </div>

            <form method="POST" action="{{ route('register') }}" onsubmit="return confirmSubmission()">
                @csrf
                <input type="hidden" name="role" id="selectedRole" value="client">

                <!-- Step 1 -->
                <div id="step1">
                    <div class="form-group mb-2">
                        <label for="first_name">First Name</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="middle_name">Middle Name (Optional)</label>
                        <input type="text" name="middle_name" class="form-control">
                    </div>
                    <div class="form-group mb-2">
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>Birthdate:</label>
                        <div class="d-flex">
                            <select class="form-control" id="birth_month" name="birth_month" required>
                                <option value="">Month</option>
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                @endfor
                            </select>

                            <select class="form-control mx-2" id="birth_day" name="birth_day" required>
                                <option value="">Day</option>
                                @for ($d = 1; $d <= 31; $d++)
                                    <option value="{{ $d }}">{{ $d }}</option>
                                @endfor
                            </select>

                            <select class="form-control" id="birth_year" name="birth_year" required>
                                <option value="">Year</option>
                                @for ($y = date('Y') - 18; $y >= 1900; $y--)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <small id="ageError" class="text-danger" style="display: none;">You must be at least 18 years
                            old.</small>
                    </div>
                    <div class="form-group mb-2">
                        <label for="gender">Gender</label>
                        <select name="gender" class="form-control" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="phone">Phone Number</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <button type="button" class="btn btn-primary w-100" id="nextStep" disabled>Next</button>
                </div>

                <!-- Step 2 -->
                <div id="step2" style="display: none;">
                    <div class="form-group mb-2">
                        <label for="street">Street</label>
                        <input type="text" name="street" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="barangay">Barangay</label>
                        <input type="text" name="barangay" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="city">City</label>
                        <input type="text" name="city" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="zip_code">Zip Code</label>
                        <input type="text" name="zip_code" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="button" class="btn btn-secondary w-100 mt-2" id="backStep">Back</button>
                    <button type="submit" class="btn btn-primary w-100 mt-2" id="submitBtn" disabled>Sign Up</button>
                </div>
            </form>

            <div class="text-center mt-3">
                <span>Already have an account?</span> <a href="{{ route('login') }}"
                    class="text-decoration-none">Login</a>
            </div>
        </div>
    </div>

    <style>
        body {
            background: url("{{ asset('assets/homepage/covers/HOMEASE COVER.png') }}") no-repeat center center fixed;
            background-size: cover;
            backdrop-filter: blur(8px);
            overflow: hidden;
        }

        .register-card {
            position: relative;
            top: -10vh;
            width: 500px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .role-selection {
            display: flex;
            gap: 20px;
            position: relative;
            cursor: pointer;
            font-weight: bold;
        }

        .role-option {
            padding: 10px;
            cursor: pointer;
        }

        .underline {
            position: absolute;
            bottom: 0;
            height: 3px;
            width: 50px;
            /* Initial width */
            background: linear-gradient(to right, transparent, #007bff, transparent);
            border-radius: 50px;
            transition: left 0.3s ease-in-out, width 0.3s ease-in-out;
        }


        .role-option.active {
            color: #007bff;
        }
    </style>

    <script>
        function confirmSubmission() {
            return confirm("Are you sure you want to proceed with registration?");
        }

        document.addEventListener("DOMContentLoaded", function() {
            const step1 = document.getElementById("step1");
            const step2 = document.getElementById("step2");
            const nextStepBtn = document.getElementById("nextStep");
            const backStepBtn = document.getElementById("backStep");
            const submitBtn = document.getElementById("submitBtn");
            const step1Inputs = step1.querySelectorAll("input, select");
            const step2Inputs = step2.querySelectorAll("input");
            const emailInput = document.querySelector("input[name='email']");
            const roleOptions = document.querySelectorAll(".role-option");
            const selectedRole = document.getElementById("selectedRole");
            const passwordInput = document.querySelector("input[name='password']");
            const confirmPasswordInput = document.querySelector("input[name='password_confirmation']");
            const passwordError = document.createElement("small");

            passwordError.classList.add("text-danger");
            confirmPasswordInput.parentNode.appendChild(passwordError);

            // Function to validate email format
            function isValidEmail(email) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            }

            // Function to check Step 1 inputs
            function checkStep1Inputs() {
                const allFilled = [...step1Inputs].every(input => input.value.trim() !== "");
                const validEmail = isValidEmail(emailInput.value);
                nextStepBtn.disabled = !(allFilled && validEmail);
            }

            // Function to check Step 2 inputs
            function checkStep2Inputs() {
                const allFilled = [...step2Inputs].every(input => input.value.trim() !== "");
                submitBtn.disabled = !(allFilled && passwordsMatch());
            }

            // Function to check if passwords match
            function passwordsMatch() {
                if (passwordInput.value !== confirmPasswordInput.value) {
                    passwordError.textContent = "Passwords do not match.";
                    return false;
                } else {
                    passwordError.textContent = "";
                    return true;
                }
            }

            // Restore saved input values
            function restoreInputs(inputs) {
                inputs.forEach(input => {
                    const savedValue = sessionStorage.getItem(input.name);
                    if (savedValue) {
                        input.value = savedValue;
                    }
                    input.addEventListener("input", function() {
                        sessionStorage.setItem(input.name, input.value);
                        if (input.closest("#step1")) checkStep1Inputs();
                        else checkStep2Inputs();
                    });
                });
            }

            // Restore Role Selection
            function restoreRoleSelection() {
                const savedRole = sessionStorage.getItem("selectedRole") || "client";
                selectedRole.value = savedRole;
                roleOptions.forEach(option => {
                    option.classList.toggle("active", option.dataset.role === savedRole);
                });
            }

            // Handle Role Selection
            roleOptions.forEach(option => {
                option.addEventListener("click", function() {
                    roleOptions.forEach(opt => opt.classList.remove("active"));
                    this.classList.add("active");
                    selectedRole.value = this.dataset.role;
                    sessionStorage.setItem("selectedRole", this.dataset.role);
                });
            });

            // Navigate to Step 2
            nextStepBtn.addEventListener("click", function() {
                step1.style.display = "none";
                step2.style.display = "block";
                backStepBtn.style.display = "block";
            });

            // Navigate back to Step 1
            backStepBtn.addEventListener("click", function() {
                step1.style.display = "block";
                step2.style.display = "none";
                backStepBtn.style.display = "none";
            });

            // Refresh Warning
            window.addEventListener("beforeunload", function(event) {
                event.preventDefault();
                event.returnValue = "Are you sure you want to leave? Your progress may be lost.";
            });

            // Password confirmation validation
            passwordInput.addEventListener("input", passwordsMatch);
            confirmPasswordInput.addEventListener("input", function() {
                passwordsMatch();
                checkStep2Inputs();
            });

            // Restore inputs and role on page load
            restoreInputs(step1Inputs);
            restoreInputs(step2Inputs);
            restoreRoleSelection();
            checkStep1Inputs();
            checkStep2Inputs();
        });


        document.addEventListener("DOMContentLoaded", function() {
            const roleOptions = document.querySelectorAll(".role-option");
            const underline = document.querySelector(".underline");

            function updateUnderline(selected) {
                const selectedRect = selected.getBoundingClientRect();
                const parentRect = selected.parentElement.getBoundingClientRect();

                underline.style.width = `${selected.offsetWidth}px`;
                underline.style.left = `${selected.offsetLeft}px`;
            }

            roleOptions.forEach(option => {
                option.addEventListener("click", function() {
                    // Remove active class from all and add it to the selected one
                    roleOptions.forEach(opt => opt.classList.remove("active"));
                    this.classList.add("active");

                    // Move the underline
                    updateUnderline(this);
                });
            });

            // Initialize the underline position
            const activeRole = document.querySelector(".role-option.active");
            if (activeRole) {
                updateUnderline(activeRole);
            }
        });
    </script>
@endsection
