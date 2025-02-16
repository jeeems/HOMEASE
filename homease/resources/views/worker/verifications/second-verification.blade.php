@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-2xl font-bold mb-4">Worker Verification - Personal Information</h2>

        <form action="{{ route('verification.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Personal Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold">Full Name (As per Valid ID)</label>
                    <div class="grid grid-cols-3 gap-2">
                        <input type="text" name="first_name" class="w-full border rounded-md p-2 bg-gray-100"
                            value="{{ old('first_name', auth()->user()->first_name) }}" placeholder="First Name" readonly>

                        <input type="text" name="middle_name" class="w-full border rounded-md p-2 bg-gray-100"
                            value="{{ old('middle_name', auth()->user()->middle_name) }}"
                            placeholder="Middle Name (Optional)" readonly>

                        <input type="text" name="last_name" class="w-full border rounded-md p-2 bg-gray-100"
                            value="{{ old('last_name', auth()->user()->last_name) }}" placeholder="Last Name" readonly>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold">Email Address</label>
                    <input type="email" name="email" class="w-full border rounded-md p-2 bg-gray-100"
                        value="{{ old('email', auth()->user()->email) }}" readonly>
                </div>

                <div>
                    <label class="block font-semibold">Phone Number</label>
                    <input type="tel" name="phone" class="w-full border rounded-md p-2 bg-gray-100"
                        value="{{ old('phone', auth()->user()->phone) }}" readonly>
                </div>

                <!-- Home Address -->
                <div>
                    <label class="block font-semibold">Home Address</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        <input type="text" name="street" class="w-full border rounded-md p-2 bg-gray-100"
                            value="{{ old('street', auth()->user()->street) }}" placeholder="Street" readonly>

                        <input type="text" name="barangay" class="w-full border rounded-md p-2 bg-gray-100"
                            value="{{ old('barangay', auth()->user()->barangay) }}" placeholder="Barangay" readonly>

                        <input type="text" name="city" class="w-full border rounded-md p-2 bg-gray-100"
                            value="{{ old('city', auth()->user()->city) }}" placeholder="City" readonly>

                        <input type="text" name="zip_code" class="w-full border rounded-md p-2 bg-gray-100"
                            value="{{ old('zip_code', auth()->user()->zip_code) }}" placeholder="Zip Code" readonly>
                    </div>
                </div>

                <!-- Date of Birth -->
                <div>
                    <label class="block font-semibold">Date of Birth</label>
                    <div class="grid grid-cols-3 gap-2">
                        <select name="dob_month" class="w-full border rounded-md p-2 bg-gray-100" disabled>
                            <option value="" disabled selected>Month</option>
                            @foreach (range(1, 12) as $month)
                                <option value="{{ $month }}"
                                    {{ old('dob_month', date('n', strtotime(auth()->user()->birthdate))) == $month ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                </option>
                            @endforeach
                        </select>

                        <select name="dob_day" class="w-full border rounded-md p-2 bg-gray-100" disabled>
                            <option value="" disabled selected>Day</option>
                            @foreach (range(1, 31) as $day)
                                <option value="{{ $day }}"
                                    {{ old('dob_day', date('j', strtotime(auth()->user()->birthdate))) == $day ? 'selected' : '' }}>
                                    {{ $day }}
                                </option>
                            @endforeach
                        </select>

                        <select name="dob_year" class="w-full border rounded-md p-2 bg-gray-100" disabled>
                            <option value="" disabled selected>Year</option>
                            @for ($year = now()->year; $year >= 1900; $year--)
                                <option value="{{ $year }}"
                                    {{ old('dob_year', date('Y', strtotime(auth()->user()->birthdate))) == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>


            <h2 class="text-2xl font-bold mt-6">Emergency Contact Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold">Emergency Contact Name</label>
                    <input type="text" name="emergency_name" class="w-full border rounded-md p-2" required>
                </div>
                <div>
                    <label class="block font-semibold">Relationship</label>
                    <input type="text" name="emergency_relationship" class="w-full border rounded-md p-2" required>
                </div>
                <div>
                    <label class="block font-semibold">Emergency Contact Phone</label>
                    <input type="tel" name="emergency_phone" class="w-full border rounded-md p-2" required>
                </div>
            </div>

            <h2 class="text-2xl font-bold mt-6">Work & Service Details</h2>
            <div id="service-container" class="space-y-4">
                <div class="service-section border-t pt-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold">Type of Services Provided</label>
                            <select name="service_type" class="w-full border rounded-md p-2" required>
                                <option value="" disabled selected>Select a service</option>
                                <option value="Home Cleaning">Home Cleaning</option>
                                <option value="Daycare">Daycare</option>
                                <option value="Carpentry">Carpentry</option>
                                <option value="Plumbing">Plumbing</option>
                                <option value="Electrician">Electrician</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold">Hourly Rate</label>
                            <input type="number" name="hourly_rate" class="w-full border rounded-md p-2" required>
                        </div>
                        <div>
                            <label class="block font-semibold">Years of Experience</label>
                            <input type="number" name="experience" class="w-full border rounded-md p-2" required>
                        </div>
                        <div>
                            <label class="block font-semibold">Specialized Tasks (Optional)</label>
                            <input type="text" name="specialization" class="w-full border rounded-md p-2">
                        </div>
                        <div>
                            <label class="block font-semibold">Work Locations</label>
                            <input type="text" name="work_locations" class="w-full border rounded-md p-2" required>
                        </div>
                        <div>
                            <label class="block font-semibold">Professional Reference (Name & Contact)</label>
                            <input type="text" name="reference" class="w-full border rounded-md p-2" required>
                        </div>
                    </div>
                </div>
            </div>

            <h2 class="text-2xl font-bold mt-6">Background Check & Verification</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold">Upload Recent Photo (1x1 or 2x2)</label>
                    <input type="file" name="photo" id="photoInput" class="w-full border rounded-md p-2" required
                        accept="image/*">
                    <img id="photoPreview1" class="mt-2 w-32 h-32 object-cover rounded-md hidden" />
                </div>

                <div>
                    <label class="block font-semibold">Upload Government-issued ID</label>
                    <input type="file" name="gov_id" id="govIdInput" class="w-full border rounded-md p-2" required
                        accept="image/*">
                    <img id="photoPreview2" class="mt-2 w-32 h-32 object-cover rounded-md hidden" />
                </div>

                <div>
                    <label class="block font-semibold">Upload NBI & Barangay Clearance</label>
                    <input type="file" name="clearance" id="clearanceInput" class="w-full border rounded-md p-2"
                        required accept="image/*">
                    <img id="photoPreview3" class="mt-2 w-32 h-32 object-cover rounded-md hidden" />
                </div>
            </div>

            <h2 class="text-2xl font-bold mt-6">Transportation & Equipment</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold">Do you have your own transportation?</label>
                    <select name="transportation" class="w-full border rounded-md p-2" required>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold">Do you have your own tools?</label>
                    <select name="tools" class="w-full border rounded-md p-2" required>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
            </div>

            <h2 class="text-2xl font-bold mt-6">Terms & Agreements</h2>
            <div class="flex flex-col space-y-2">
                <label>
                    <input type="checkbox" name="agreed_terms" required>
                    I agree to the <a href="#" class="text-blue-600 underline">Service Provider Terms &
                        Conditions</a>.
                </label>
                <label>
                    <input type="checkbox" name="agreed_privacy_policy" required>
                    I accept the <a href="#" class="text-blue-600 underline">Privacy Policy</a>.
                </label>
            </div>

            <button type="submit" id="submit-button" class="bg-blue-500 text-white px-4 py-2 rounded-lg" disabled>
                Submit
            </button>

        </form>
    </div>

    @if (session('success'))
        <div class="bg-green-500 text-white p-3 rounded-md">
            {{ session('success') }}
        </div>
    @endif


    <script>
        function previewImage(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);

            input.addEventListener("change", function() {
                const file = input.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.classList.remove("hidden");
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        previewImage("photoInput", "photoPreview1");
        previewImage("govIdInput", "photoPreview2");
        previewImage("clearanceInput", "photoPreview3");

        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("form");
            const submitButton = document.querySelector("#submit-button"); // Add id="submit-button" to the button
            const inputs = form.querySelectorAll("input[required], select[required]");
            const checkboxes = form.querySelectorAll("input[type='checkbox'][required]");

            function checkFormValidity() {
                let isValid = true;

                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        isValid = false;
                    }
                });

                checkboxes.forEach(checkbox => {
                    if (!checkbox.checked) {
                        isValid = false;
                    }
                });

                submitButton.disabled = !isValid;
            }

            form.addEventListener("input", checkFormValidity);
            form.addEventListener("change", checkFormValidity);
            checkFormValidity(); // Initial check
        });
    </script>
@endsection
