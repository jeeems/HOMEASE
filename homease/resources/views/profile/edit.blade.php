@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-10">
        <div class="bg-white shadow-lg rounded-lg p-6 mb-6 max-w-lg mx-auto">
            <h2 class="text-2xl font-semibold text-gray-800 text-center mb-4">Edit Profile</h2>

            @if (session('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded-md mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Profile Picture Upload -->
                <div class="mb-4 text-center">
                    <div class="relative">
                        <img id="profilePreview"
                            src="{{ $user->profile && $user->profile->profile_picture ? asset('storage/' . $user->profile->profile_picture) : asset('default-profile.png') }}"
                            class="w-32 h-32 rounded-full mx-auto mb-4 shadow-md border-2 border-gray-300">
                        <input type="hidden" name="profile_picture" id="profilePictureInputHidden">
                    </div>
                    <input type="file" id="profilePictureInput" accept="image/*"
                        class="block w-full text-sm text-gray-600 mx-auto">
                </div>

                <!-- Full Name -->
                <div class="mb-4">
                    <label class="block text-gray-700">First Name</label>
                    <input type="text" name="first_name" value="{{ Auth::user()->first_name }}"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Middle Name</label>
                    <input type="text" name="middle_name" value="{{ Auth::user()->middle_name }}"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Last Name</label>
                    <input type="text" name="last_name" value="{{ Auth::user()->last_name }}"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>


                <!-- Role Display -->
                <div class="mb-4">
                    <label class="block text-gray-700">Role</label>
                    <input type="text" value="{{ ucfirst(Auth::user()->role) }}" disabled
                        class="w-full border bg-gray-100 rounded-lg px-3 py-2">
                </div>

                @if (Auth::user()->role == 'worker')
                    <!-- Service Type -->
                    <div class="mb-4">
                        <label class="block text-gray-700">Service Type</label>
                        <select name="service_type"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="Home Cleaning"
                                {{ Auth::user()->workerVerification->service_type == 'Home Cleaning' ? 'selected' : '' }}>
                                Home Cleaning</option>
                            <option value="Daycare"
                                {{ Auth::user()->workerVerification->service_type == 'Daycare' ? 'selected' : '' }}>Daycare
                            </option>
                            <option value="Carpentry"
                                {{ Auth::user()->workerVerification->service_type == 'Carpentry' ? 'selected' : '' }}>
                                Carpentry</option>
                            <option value="Plumbing"
                                {{ Auth::user()->workerVerification->service_type == 'Plumbing' ? 'selected' : '' }}>
                                Plumbing</option>
                            <option value="Electrician"
                                {{ Auth::user()->workerVerification->service_type == 'Electrician' ? 'selected' : '' }}>
                                Electrician</option>
                        </select>
                    </div>

                    <!-- Experience -->
                    <div class="mb-4">
                        <label class="block text-gray-700">Experience (Years)</label>
                        <input type="number" name="experience"
                            value="{{ Auth::user()->workerVerification->experience ?? '' }}"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Hourly Rate -->
                    <div class="mb-4">
                        <label class="block text-gray-700">Hourly Rate (₱)</label>
                        <input type="number" name="hourly_rate" step="0.01"
                            value="{{ Auth::user()->workerVerification->hourly_rate ?? '' }}"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                @endif

                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Save Changes
                </button>
            </form>
        </div>
    </div>

    <!-- Image Cropper Modal -->
    <div id="imageCropperModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-lg font-semibold mb-4">Adjust Your Profile Picture</h3>
            <div class="w-full">
                <img id="imageToCrop" class="w-full">
            </div>
            <div class="flex justify-end space-x-2 mt-4">
                <button id="cancelCrop" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                <button id="saveCroppedImage" class="bg-blue-600 text-white px-4 py-2 rounded">Crop & Save</button>
            </div>
        </div>
    </div>

    <!-- Include Cropper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let cropper;
            const profilePictureInput = document.getElementById('profilePictureInput');
            const imageCropperModal = document.getElementById('imageCropperModal');
            const imageToCrop = document.getElementById('imageToCrop');
            const profilePreview = document.getElementById('profilePreview');
            const saveCroppedImage = document.getElementById('saveCroppedImage');
            const cancelCrop = document.getElementById('cancelCrop');
            const croppedImageInput = document.getElementById('profilePictureInputHidden');

            profilePictureInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imageToCrop.src = e.target.result;
                        imageCropperModal.classList.remove('hidden');

                        if (cropper) {
                            cropper.destroy();
                        }

                        cropper = new Cropper(imageToCrop, {
                            aspectRatio: 1,
                            viewMode: 2,
                            autoCropArea: 1,
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });

            saveCroppedImage.addEventListener('click', function() {
                if (cropper) {
                    const canvas = cropper.getCroppedCanvas();
                    if (canvas) {
                        const croppedDataUrl = canvas.toDataURL(); // Convert to base64
                        profilePreview.src = croppedDataUrl; // Update preview
                        croppedImageInput.value = croppedDataUrl; // Save to hidden input field
                        imageCropperModal.classList.add('hidden'); // Hide modal
                    }
                }
            });

            cancelCrop.addEventListener('click', function() {
                imageCropperModal.classList.add('hidden');
                profilePictureInput.value = ""; // Reset input if canceled
            });
        });

        document.querySelector('form').addEventListener('submit', function() {
            const firstName = document.querySelector('[name="first_name"]').value;
            const middleName = document.querySelector('[name="middle_name"]').value;
            const lastName = document.querySelector('[name="last_name"]').value;

            const fullName = `${firstName} ${middleName ? middleName + ' ' : ''}${lastName}`;
            document.querySelector('[name="full_name"]').value = fullName;
        });
    </script>
@endsection
