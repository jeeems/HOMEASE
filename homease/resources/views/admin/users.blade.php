@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-3 py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex flex-column flex-md-row align-items-center mb-4">
                    <h1 class="display-6 fw-bold text-dark text-center text-md-start mb-3 mb-md-0">
                        <i class="fas fa-users-cog me-2 text-primary"></i>User Management
                    </h1>
                    <div class="d-flex flex-column flex-sm-row align-items-center ms-md-auto">
                        <div class="input-group rounded-pill overflow-hidden mb-2 mb-sm-0 me-sm-3" style="max-width: 300px;">
                            <form action="{{ route('admin.users') }}" method="GET" class="d-flex w-100">
                                @foreach (request()->except('search', 'page') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <input type="text" class="form-control border-0 px-3 py-2" placeholder="Search users..."
                                    id="searchInput" name="search" value="{{ request('search') }}">
                                <button class="btn btn-primary px-3" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                        {{-- <button class="btn btn-primary btn-lg rounded-pill shadow-sm w-100 w-md-auto"
                            style="max-width: 200px;" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="fas fa-plus me-2"></i>Add New User
                        </button> --}}
                    </div>
                </div>

                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0">
                        <ul class="nav nav-pills nav-fill nav-segment rounded-pill bg-light p-1 flex-column flex-sm-row"
                            id="userTabs">
                            <li class="nav-item">
                                <a class="nav-link rounded-pill active" data-bs-toggle="tab" href="#clients">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user-tie me-2"></i>Clients
                                        <span class="badge bg-primary ms-2">{{ $clients->total() }}</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link rounded-pill" data-bs-toggle="tab" href="#workers">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-hard-hat me-2"></i>Workers
                                        <span class="badge bg-success ms-2">{{ $workers->total() }}</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link rounded-pill" data-bs-toggle="tab" href="#admins">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user-shield me-2"></i>Admins
                                        <span class="badge bg-info ms-2">{{ $admins->total() }}</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-0">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="clients">
                                @include('admin.users.partials.clients-table', ['users' => $clients])
                            </div>
                            <div class="tab-pane fade" id="workers">
                                @include('admin.users.partials.workers-table', ['users' => $workers])
                            </div>
                            <div class="tab-pane fade" id="admins">
                                @include('admin.users.partials.admins-table', ['users' => $admins])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- users.blade.php -->
    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header bg-primary text-white border-0 rounded-top-4">
                    <h5 class="modal-title" id="addUserModalLabel">
                        <i class="fas fa-user-plus me-2"></i>Add New User
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Role Selection -->
                    <div class="role-selection mb-4">
                        <div class="role-container d-flex justify-content-center ">
                            <div class="custom-radio-container p-2">
                                <input type="radio" id="role-client" name="role" value="client"
                                    class="custom-radio-input" checked>
                                <label for="role-client" class="custom-radio-label">Client</label>
                            </div>
                            <div class="custom-radio-container p-2">
                                <input type="radio" id="role-worker" name="role" value="worker"
                                    class="custom-radio-input">
                                <label for="role-worker" class="custom-radio-label">Worker</label>
                            </div>
                            <div class="custom-radio-container p-2">
                                <input type="radio" id="role-admin" name="role" value="admin"
                                    class="custom-radio-input">
                                <label for="role-admin" class="custom-radio-label">Admin</label>
                            </div>
                        </div>
                    </div>

                    <form id="addUserForm" action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="role" id="selectedRole" value="client">

                        <!-- Personal Information Section -->
                        <div class="card border-0 shadow-sm rounded-4 mb-3">
                            <div class="card-header bg-light border-0 pt-3">
                                <h6 class="mb-0"><i class="fas fa-user me-2 text-primary"></i>Personal Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="first_name" class="form-label">First Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="first_name" id="first_name"
                                            class="form-control rounded-3" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="middle_name" class="form-label">Middle Name</label>
                                        <input type="text" name="middle_name" id="middle_name"
                                            class="form-control rounded-3">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="last_name" class="form-label">Last Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="last_name" id="last_name"
                                            class="form-control rounded-3" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Birthdate <span class="text-danger">*</span></label>
                                        <div class="row g-2">
                                            <div class="col-4">
                                                <select class="form-select" name="birth_month" required>
                                                    <option value="">Month</option>
                                                    @for ($m = 1; $m <= 12; $m++)
                                                        <option value="{{ $m }}">
                                                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="col-4">
                                                <select class="form-select" name="birth_day" required>
                                                    <option value="">Day</option>
                                                    @for ($d = 1; $d <= 31; $d++)
                                                        <option value="{{ $d }}">{{ $d }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="col-4">
                                                <select class="form-select" name="birth_year" required>
                                                    <option value="">Year</option>
                                                    @for ($y = date('Y') - 18; $y >= 1900; $y--)
                                                        <option value="{{ $y }}">{{ $y }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="gender" class="form-label">Gender <span
                                                class="text-danger client-worker-required">*</span></label>
                                        <select name="gender" id="gender" class="form-select rounded-3">
                                            <option value="">Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="card border-0 shadow-sm rounded-4 mb-3">
                            <div class="card-header bg-light border-0 pt-3">
                                <h6 class="mb-0"><i class="fas fa-address-card me-2 text-primary"></i>Contact
                                    Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" name="email" id="email"
                                            class="form-control rounded-3" required>
                                        <div id="emailFeedback" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Phone <span
                                                class="text-danger client-worker-required">*</span></label>
                                        <input type="text" name="phone" id="phone"
                                            class="form-control rounded-3">
                                        <div id="phoneFeedback" class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Address Information Section (Hidden for admin) -->
                        <div id="addressSection" class="card border-0 shadow-sm rounded-4 mb-3">
                            <div class="card-header bg-light border-0 pt-3">
                                <h6 class="mb-0"><i class="fas fa-map-marker-alt me-2 text-primary"></i>Address
                                    Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="street" class="form-label">Street <span
                                                class="text-danger client-worker-required">*</span></label>
                                        <input type="text" name="street" id="street"
                                            class="form-control rounded-3">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="barangay" class="form-label">Barangay <span
                                                class="text-danger client-worker-required">*</span></label>
                                        <input type="text" name="barangay" id="barangay"
                                            class="form-control rounded-3">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="city" class="form-label">City <span
                                                class="text-danger client-worker-required">*</span></label>
                                        <input type="text" name="city" id="city"
                                            class="form-control rounded-3">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="zip_code" class="form-label">Zip Code <span
                                                class="text-danger client-worker-required">*</span></label>
                                        <input type="text" name="zip_code" id="zip_code"
                                            class="form-control rounded-3">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Password Section -->
                        <div class="card border-0 shadow-sm rounded-4 mb-3">
                            <div class="card-header bg-light border-0 pt-3">
                                <h6 class="mb-0"><i class="fas fa-lock me-2 text-primary"></i>Security</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Password <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="password" id="password"
                                                class="form-control rounded-start-3" required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm Password <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="password_confirmation"
                                                id="password_confirmation" class="form-control rounded-start-3" required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div id="passwordFeedback" class="form-text text-danger"></div>
                                    </div>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="verifyEmail" name="verify_email"
                                        checked>
                                    <label class="form-check-label" for="verifyEmail">Mark email as verified</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveUserBtn" class="btn btn-primary rounded-3">
                        <i class="fas fa-save me-2"></i>Save User
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('#userTabs .nav-link');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');

            if (!searchInput || !searchButton) {
                console.error('Search input or button not found in the DOM');
                return;
            }

            function handleSearch() {
                const form = document.createElement('form');
                form.method = 'GET';
                form.action = window.location.pathname;

                const searchParam = document.createElement('input');
                searchParam.type = 'hidden';
                searchParam.name = 'search';
                searchParam.value = searchInput.value.trim();
                form.appendChild(searchParam);

                const urlParams = new URLSearchParams(window.location.search);
                urlParams.forEach((value, key) => {
                    if (key !== 'search' && key !== 'page') {
                        const param = document.createElement('input');
                        param.type = 'hidden';
                        param.name = key;
                        param.value = value;
                        form.appendChild(param);
                    }
                });

                document.body.appendChild(form);
                form.submit();
            }

            searchButton.addEventListener('click', function(e) {
                e.preventDefault();
                handleSearch();
            });

            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    handleSearch();
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Role Selection and Form Updates
            const roleRadios = document.querySelectorAll(".custom-radio-input");
            const selectedRoleInput = document.getElementById('selectedRole');
            const addressSection = document.getElementById('addressSection');
            const adminOnlyFields = document.querySelectorAll('.client-worker-required');
            const addUserForm = document.getElementById('addUserForm');
            const saveUserBtn = document.getElementById('saveUserBtn');

            // Function to update form based on selected role
            function updateFormForRole(role) {
                // Update the hidden selectedRole input
                selectedRoleInput.value = role;

                if (role === 'admin') {
                    // Hide address section for admin
                    addressSection.style.display = 'none';

                    // Make non-required fields for admin
                    adminOnlyFields.forEach(field => {
                        field.classList.add('d-none');
                        const input = field.closest('.col-md-6, .row')?.querySelector('input, select');
                        if (input) {
                            input.removeAttribute('required');
                        }
                    });

                    // Ensure email is verified for admin
                    document.getElementById('verifyEmail').checked = true;
                    document.getElementById('verifyEmail').disabled = true;
                } else {
                    // Show address section for clients and workers
                    addressSection.style.display = 'block';

                    // Make fields required for clients and workers
                    adminOnlyFields.forEach(field => {
                        field.classList.remove('d-none');
                        const input = field.closest('.col-md-6, .row')?.querySelector('input, select');
                        if (input) {
                            input.setAttribute('required', '');
                        }
                    });

                    // Allow email verification toggle for non-admins
                    document.getElementById('verifyEmail').disabled = false;
                }
            }

            // Add change event for role radios
            roleRadios.forEach(radio => {
                radio.addEventListener("change", function() {
                    if (this.checked) {
                        // Update form based on selected role
                        updateFormForRole(this.value);
                    }
                });
            });

            // Toggle password visibility - Fixed version
            const togglePasswordBtns = document.querySelectorAll('.toggle-password');
            togglePasswordBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = this.previousElementSibling;
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);

                    // Toggle the eye icon
                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            });

            // Email validation with format checking
            const emailInput = document.getElementById('email');
            const emailFeedback = document.getElementById('emailFeedback');

            emailInput.addEventListener('input', function() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (this.value && !emailRegex.test(this.value)) {
                    this.classList.add('is-invalid');
                    emailFeedback.innerHTML = 'Please enter a valid email address';
                } else {
                    this.classList.remove('is-invalid');
                    emailFeedback.innerHTML = '';
                }
            });

            // Email duplication check
            emailInput.addEventListener('blur', async function() {
                if (this.value.trim() === '') return;

                // Skip server check if format is invalid
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(this.value)) return;

                try {
                    const response = await fetch('/check/email', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            value: this.value
                        })
                    });

                    const data = await response.json();

                    if (data.exists) {
                        this.classList.add('is-invalid');
                        emailFeedback.innerHTML = data.message;
                    } else {
                        this.classList.remove('is-invalid');
                        emailFeedback.innerHTML = '';
                    }
                } catch (error) {
                    console.error('Error validating email:', error);
                }
            });

            // Phone validation
            const phoneInput = document.getElementById('phone');
            const phoneFeedback = document.getElementById('phoneFeedback');

            // Phone format validation
            phoneInput.addEventListener('input', function() {
                // Simple validation for numeric input with optional dashes
                const phoneRegex = /^[\d\+\-\(\)\.]{7,15}$/;
                if (this.value && !phoneRegex.test(this.value)) {
                    this.classList.add('is-invalid');
                    phoneFeedback.innerHTML = 'Please enter a valid phone number';
                } else {
                    this.classList.remove('is-invalid');
                    phoneFeedback.innerHTML = '';
                }
            });

            // Enhanced Password validation
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const passwordFeedback = document.getElementById('passwordFeedback');

            function validatePasswords() {
                if (passwordInput.value.length < 8) {
                    passwordInput.classList.add('is-invalid');
                    passwordFeedback.textContent = 'Password must be at least 8 characters';
                    return false;
                } else if (confirmPasswordInput.value && passwordInput.value !== confirmPasswordInput.value) {
                    confirmPasswordInput.classList.add('is-invalid');
                    passwordFeedback.textContent = 'Passwords do not match';
                    return false;
                } else {
                    passwordInput.classList.remove('is-invalid');
                    confirmPasswordInput.classList.remove('is-invalid');
                    passwordFeedback.textContent = '';
                    return true;
                }
            }

            confirmPasswordInput.addEventListener('input', validatePasswords);
            passwordInput.addEventListener('input', validatePasswords);

            // Save user button click - Fixed version
            saveUserBtn.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent default button behavior

                // Check if form is valid
                if (!addUserForm.checkValidity()) {
                    // Trigger browser's native validation
                    addUserForm.reportValidity();
                    return;
                }

                // Check passwords match
                if (!validatePasswords()) {
                    return;
                }

                // Create FormData object
                const formData = new FormData(addUserForm);

                // Ensure role is set correctly
                formData.set('role', document.getElementById('selectedRole').value);

                // Add email_verified_at if checkbox is checked
                if (document.getElementById('verifyEmail').checked) {
                    formData.append('email_verified_at', 'true');
                }

                // Send AJAX request with proper CSRF token
                fetch(addUserForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            // No need to set Content-Type with FormData
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok: ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            if (typeof bootstrap !== 'undefined') {
                                const toast = new bootstrap.Toast(document.getElementById(
                                    'successToast'));
                                document.getElementById('toastMessage').textContent = data.message ||
                                    'User created successfully!';
                                toast.show();
                            } else {
                                alert(data.message || 'User created successfully!');
                            }

                            // Close modal
                            if (typeof bootstrap !== 'undefined') {
                                const modal = bootstrap.Modal.getInstance(document.getElementById(
                                    'addUserModal'));
                                if (modal) modal.hide();
                            }

                            // Reload page after 1 second
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            // Show validation errors
                            if (data.errors) {
                                Object.keys(data.errors).forEach(key => {
                                    const input = addUserForm.querySelector(`[name="${key}"]`);
                                    if (input) {
                                        input.classList.add('is-invalid');

                                        // Look for existing feedback or create new one
                                        let feedbackElement = document.getElementById(
                                            `${key}Feedback`);

                                        if (!feedbackElement) {
                                            feedbackElement = document.createElement('div');
                                            feedbackElement.id = `${key}Feedback`;
                                            feedbackElement.className = 'invalid-feedback';
                                            if (input.parentNode.classList.contains(
                                                    'input-group')) {
                                                input.parentNode.parentNode.appendChild(
                                                    feedbackElement);
                                            } else {
                                                input.parentNode.appendChild(feedbackElement);
                                            }
                                        }

                                        feedbackElement.textContent = data.errors[key][0];
                                    }
                                });
                            } else {
                                alert(data.message || 'An error occurred while creating the user.');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while creating the user. Please try again.');
                    });
            });

            // Initialize the form when modal is shown
            const addUserModal = document.getElementById('addUserModal');
            addUserModal.addEventListener('shown.bs.modal', function() {
                // Reset form
                addUserForm.reset();

                // Clear validation states
                const invalidInputs = addUserForm.querySelectorAll('.is-invalid');
                invalidInputs.forEach(input => input.classList.remove('is-invalid'));

                const feedbacks = addUserForm.querySelectorAll('.invalid-feedback');
                feedbacks.forEach(feedback => feedback.textContent = '');

                // Find the checked radio and update form
                const checkedRadio = document.querySelector(".custom-radio-input:checked");
                if (checkedRadio) {
                    // Make sure selectedRole is updated
                    selectedRoleInput.value = checkedRadio.value;
                    updateFormForRole(checkedRadio.value);
                }
            });

            // Initialize on load - ensure defaults are set
            const checkedRadio = document.querySelector(".custom-radio-input:checked");
            if (checkedRadio) {
                selectedRoleInput.value = checkedRadio.value;
                updateFormForRole(checkedRadio.value);
            }
        });
    </script>
@endsection

@section('styles')
    <style>
        .user-profile-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }

        /* Modal CSS */
        /* Role Selection Styles */
        /* Enhanced Radio Selection Styles */
        .role-selection {
            position: relative;
            width: 100%;
        }

        .role-container {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            justify-content: center;
        }

        .custom-radio-container {
            position: relative;
        }

        /* Hide the default radio button */
        .custom-radio-input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        /* Style the custom radio label */
        .custom-radio-label {
            display: inline-block;
            position: relative;
            padding: 10px 22px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            color: #6c757d;
            background-color: #f8f9fa;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            text-align: center;
            min-width: 90px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* Hover state */
        .custom-radio-label:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.08);
        }

        /* Active/selected state */
        .custom-radio-input:checked+.custom-radio-label {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.25);
        }

        /* Focus state for accessibility */
        .custom-radio-input:focus+.custom-radio-label {
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
        }

        /* Add animation */
        .custom-radio-label {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0.8;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .role-container {
                flex-direction: column;
                align-items: center;
            }

            .custom-radio-label {
                width: 180px;
            }
        }

        #addUserModal .card {
            transition: all 0.3s ease;
        }

        #addUserModal .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        #addUserModal .card-header {
            border-top-left-radius: 0.75rem !important;
            border-top-right-radius: 0.75rem !important;
        }
    </style>
@endsection
