@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="register-card p-4 shadow-lg">
            <h3 class="text-center mb-3">Sign Up</h3>

            <!-- Role Selection -->
            <div class="role-selection d-flex flex-wrap justify-content-center position-relative mb-3">
                <div class="role-option active" data-role="client">Client</div>
                <div class="role-option" data-role="worker">Worker</div>
                <div class="underline"></div>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <input type="hidden" name="role" id="selectedRole" value="client">

                <!-- Step 1 -->
                <div id="step1">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="middle_name">Middle Name (Optional)</label>
                            <input type="text" name="middle_name" class="form-control">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label class="d-block">Birthdate:</label>
                        <div class="row g-2">
                            <div class="col-auto flex-grow-1">
                                <select class="form-control" name="birth_month" required>
                                    <option value="">Month</option>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-auto flex-grow-1">
                                <select class="form-control" name="birth_day" required>
                                    <option value="">Day</option>
                                    @for ($d = 1; $d <= 31; $d++)
                                        <option value="{{ $d }}">{{ $d }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-auto flex-grow-1">
                                <select class="form-control" name="birth_year" required>
                                    <option value="">Year</option>
                                    @for ($y = date('Y') - 18; $y >= 1900; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <small id="ageError" class="text-danger d-none">You must be at least 18 years old.</small>
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
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                        <span id="emailError" class="text-danger"></span>
                    </div>

                    <div class="form-group mb-2">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" required>
                        <span id="phoneError" class="text-danger"></span>
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

            <!-- Add this before the closing </div> of register-card -->
            <div id="loadingOverlay" class="loading-overlay d-none">
                <div class="loading-content">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h5 class="text-white">Creating your account...</h5>
                    <p class="text-white-50">Please wait while we process your registration.</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        body {
            background: url("{{ asset('assets/homepage/covers/HOMEASE COVER.png') }}") no-repeat center center fixed;
            background-size: cover;
            backdrop-filter: blur(8px);
        }

        .register-card {
            width: 100%;
            max-width: 500px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        @media (max-width: 576px) {
            .register-card {
                max-width: 90%;
                padding: 15px;
            }
        }

        .role-selection {
            display: flex;
            gap: 15px;
            cursor: pointer;
            font-weight: bold;
        }

        .role-option {
            padding: 8px;
            cursor: pointer;
        }

        .underline {
            position: absolute;
            bottom: 0;
            height: 3px;
            width: 50px;
            background: linear-gradient(to right, transparent, #007bff, transparent);
            border-radius: 50px;
            transition: left 0.3s ease-in-out, width 0.3s ease-in-out;
        }

        .role-option.active {
            color: #007bff;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-content {
            text-align: center;
            padding: 20px;
        }

        .d-none {
            display: none !important;
        }
    </style>


    <script>
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
            const form = document.querySelector("form");

            passwordError.classList.add("text-danger");
            confirmPasswordInput.parentNode.appendChild(passwordError);

            let isSubmitting = false;

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
                checkStep2Inputs();
            });

            // Navigate back to Step 1
            backStepBtn.addEventListener("click", function() {
                step2.style.display = "none";
                step1.style.display = "block";
            });

            // Before unload warning
            function beforeUnloadHandler(event) {
                if (!isSubmitting) {
                    event.preventDefault();
                    event.returnValue = "Are you sure you want to leave? Your progress may be lost.";
                }
            }

            window.addEventListener("beforeunload", beforeUnloadHandler);

            // Restore inputs on load
            restoreInputs([...step1Inputs, ...step2Inputs]);
            restoreRoleSelection();
            checkStep1Inputs();
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

        document.addEventListener("DOMContentLoaded", function() {
            const step1 = document.getElementById("step1");
            const step2 = document.getElementById("step2");
            const emailInput = document.getElementById("email");
            const phoneInput = document.getElementById("phone");
            const emailError = document.getElementById("emailError");
            const phoneError = document.getElementById("phoneError");
            const nextStepBtn = document.getElementById("nextStep");
            const form = document.querySelector("form");
            const loadingOverlay = document.getElementById('loadingOverlay');

            // Function to switch to step 1
            function switchToStep1() {
                step2.style.display = "none";
                step1.style.display = "block";
            }

            // Function to validate email and phone against server
            async function validateField(value, type) {
                try {
                    const response = await fetch(`/check/${type}`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                .getAttribute("content")
                        },
                        body: JSON.stringify({
                            value: value
                        })
                    });
                    return await response.json();
                } catch (error) {
                    console.error(`Error validating ${type}:`, error);
                    return {
                        exists: false,
                        message: ""
                    };
                }
            }

            // Handle form submission
            form.addEventListener("submit", async function(event) {
                event.preventDefault();

                if (!confirm("Are you sure you want to proceed with registration?")) {
                    return;
                }

                // Show loading overlay
                loadingOverlay.classList.remove('d-none');

                // Validate email and phone before submission
                const emailValidation = await validateField(emailInput.value, 'email');
                const phoneValidation = await validateField(phoneInput.value, 'phone');

                if (emailValidation.exists || phoneValidation.exists) {
                    loadingOverlay.classList.add('d-none');

                    // Show validation errors
                    if (emailValidation.exists) {
                        emailError.textContent = emailValidation.message;
                        emailInput.classList.add("is-invalid");
                    }
                    if (phoneValidation.exists) {
                        phoneError.textContent = phoneValidation.message;
                        phoneInput.classList.add("is-invalid");
                    }

                    // Switch back to step 1
                    switchToStep1();
                    return;
                }

                // Proceed with form submission
                const formData = new FormData(form);

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        credentials: 'same-origin'
                    });

                    const data = await response.json();

                    if (data.success) {
                        window.location.href = data.redirect;
                    } else if (data.errors) {
                        loadingOverlay.classList.add('d-none');

                        // Clear previous errors
                        document.querySelectorAll('.is-invalid').forEach(el => {
                            el.classList.remove('is-invalid');
                        });
                        document.querySelectorAll('.invalid-feedback').forEach(el => {
                            el.remove();
                        });

                        // Show new errors
                        Object.keys(data.errors).forEach(key => {
                            const input = form.querySelector(`[name="${key}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                const errorDiv = document.createElement('div');
                                errorDiv.className = 'invalid-feedback';
                                errorDiv.textContent = data.errors[key][0];
                                input.parentNode.appendChild(errorDiv);
                            }
                        });

                        // If there are errors in email or phone, switch back to step 1
                        if (data.errors.email || data.errors.phone) {
                            switchToStep1();
                        }
                    }
                } catch (error) {
                    loadingOverlay.classList.add('d-none');
                    console.error('Error:', error);
                    alert('An error occurred during registration. Please try again.');
                }
            });

            // Real-time validation for email and phone
            [emailInput, phoneInput].forEach(input => {
                input.addEventListener("input", async function() {
                    const value = this.value.trim();
                    const type = this.id;
                    const errorElement = type === 'email' ? emailError : phoneError;

                    if (value === "") {
                        errorElement.textContent = "";
                        this.classList.remove("is-invalid");
                        return;
                    }

                    const validation = await validateField(value, type);

                    if (validation.exists) {
                        errorElement.textContent = validation.message;
                        this.classList.add("is-invalid");
                        nextStepBtn.disabled = true;
                    } else {
                        errorElement.textContent = "";
                        this.classList.remove("is-invalid");
                        checkStep1Inputs();
                    }
                });
            });
        });
    </script>
@endsection
