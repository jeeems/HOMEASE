<!-- reviews.blade.php  -->
@extends('layouts.app')

@section('content')
    <!-- Enhanced Modern Navbar with All Items Visible -->
    <div class="relative overflow-hidden bg-gradient-to-b from-blue-50 to-white py-4">
        <div class="container mx-auto px-4">
            <!-- For larger screens - all items visible, no scroll -->
            <ul
                class="hidden sm:flex justify-center space-x-4 py-3 px-4
    bg-transparent transition-all duration-300 mx-auto max-w-4xl">

                @php
                    $services = ['Home Cleaning', 'Daycare', 'Carpentry', 'Plumbing', 'Electrician'];
                @endphp
                @foreach ($services as $service)
                    @php
                        $isActive = strtolower($service_type) == strtolower(str_replace(' ', '-', $service));
                    @endphp
                    <li class="min-w-[160px]"> <!-- Increased from 140px to 160px -->
                        <a href="{{ route('reviews.all-reviews', ['service_type' => strtolower(str_replace(' ', '-', $service))]) }}"
                            class="flex items-center space-x-2 px-5 py-2.5 text-sm font-medium rounded-full transition-all duration-300 no-underline
                {{ $isActive
                    ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-md transform scale-105'
                    : 'bg-gray-50 text-gray-700 hover:bg-blue-50 hover:text-blue-600 hover:shadow-sm' }}">
                            <i
                                class="fas fa-{{ $service == 'Home Cleaning'
                                    ? 'broom'
                                    : ($service == 'Daycare'
                                        ? 'baby'
                                        : ($service == 'Carpentry'
                                            ? 'hammer'
                                            : ($service == 'Plumbing'
                                                ? 'faucet'
                                                : 'bolt'))) }} text-lg"></i>
                            <span class="whitespace-nowrap">{{ $service }}</span> <!-- Added whitespace-nowrap -->
                        </a>
                    </li>
                @endforeach
            </ul>

            <!-- Mobile Version -->
            <div class="sm:hidden w-full">
                <div class="flex flex-col items-center">
                    @php
                        $activeService = '';
                        foreach ($services as $service) {
                            if (strtolower($service_type) == strtolower(str_replace(' ', '-', $service))) {
                                $activeService = $service;
                                break;
                            }
                        }
                    @endphp

                    <a href="#"
                        class="flex items-center space-x-2 px-8 py-3 mb-3 text-base font-medium rounded-full
                        bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-md w-auto no-underline">
                        <i
                            class="fas fa-{{ $activeService == 'Home Cleaning'
                                ? 'broom'
                                : ($activeService == 'Daycare'
                                    ? 'baby'
                                    : ($activeService == 'Carpentry'
                                        ? 'hammer'
                                        : ($activeService == 'Plumbing'
                                            ? 'faucet'
                                            : 'bolt'))) }} text-lg"></i>
                        <span>{{ $activeService }}</span>
                    </a>

                    <div class="grid grid-cols-2 gap-2 w-full max-w-xs">
                        @foreach ($services as $service)
                            @php
                                $isActive = strtolower($service_type) == strtolower(str_replace(' ', '-', $service));
                            @endphp
                            @if (!$isActive)
                                <a href="{{ route('reviews.all-reviews', ['service_type' => strtolower(str_replace(' ', '-', $service))]) }}"
                                    class="flex items-center space-x-2 justify-center px-4 py-2 text-xs font-medium rounded-full
                                    bg-gray-50 text-gray-700 hover:bg-blue-50 hover:text-blue-600 shadow-sm transition-all duration-300 no-underline">
                                    <i
                                        class="fas fa-{{ $service == 'Home Cleaning'
                                            ? 'broom'
                                            : ($service == 'Daycare'
                                                ? 'baby'
                                                : ($service == 'Carpentry'
                                                    ? 'hammer'
                                                    : ($service == 'Plumbing'
                                                        ? 'faucet'
                                                        : 'bolt'))) }} text-lg"></i>
                                    <span>{{ $service }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Reviews Section -->
    <div class="container mx-auto px-4 md:px-8 mt-6 mb-12">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 text-center mb-8">
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-700">
                {{ ucwords(str_replace('-', ' ', $service_type)) }} Reviews
            </span>
        </h2>

        <!-- Sorting Options -->
        <div class="flex justify-end mb-6">
            <div class="relative w-52">
                <!-- Sort Button -->
                <button id="sort-button"
                    class="flex items-center justify-between w-full bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer shadow-sm">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt w-5 text-blue-500"></i>
                        <span id="selected-sort" class="ml-2 truncate">Newest First</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs text-gray-600 ml-2"></i>
                </button>

                <!-- Sort Dropdown -->
                <div id="sort-dropdown"
                    class="absolute left-0 right-0 mt-1 bg-white rounded-lg shadow-lg z-10 hidden border border-gray-100">
                    <ul class="py-1 m-0 p-0">
                        <li data-value="newest"
                            class="sort-option flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 cursor-pointer">
                            <i class="fas fa-calendar-alt w-5 text-center text-blue-500"></i>
                            <span class="ml-2 truncate">Newest First</span>
                        </li>
                        <li data-value="oldest"
                            class="sort-option flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 cursor-pointer">
                            <i class="fas fa-history w-5 text-center text-blue-500"></i>
                            <span class="ml-2 truncate">Oldest First</span>
                        </li>
                        <li data-value="highest"
                            class="sort-option flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 cursor-pointer">
                            <i class="fas fa-sort-amount-up w-5 text-center text-blue-500"></i>
                            <span class="ml-2 truncate">Highest Rating</span>
                        </li>
                        <li data-value="lowest"
                            class="sort-option flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 cursor-pointer">
                            <i class="fas fa-sort-amount-down w-5 text-center text-blue-500"></i>
                            <span class="ml-2 truncate">Lowest Rating</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        @if (count($reviews) > 0)
            <div id="reviews-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                @foreach ($reviews as $review)
                    <div data-rating="{{ $review->rating }}" data-date="{{ $review->created_at->timestamp }}"
                        class="review-card bg-white rounded-2xl shadow-md hover:shadow-xl p-6 transition-all duration-300 hover:-translate-y-1 border border-gray-100">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $review->worker->full_name }}</h3>
                            <span
                                class="text-xs font-medium bg-blue-50 px-3 py-1.5 rounded-full text-blue-600">{{ $review->booking_title }}</span>
                        </div>
                        <p class="text-sm text-gray-500">Client: <span
                                class="font-medium text-gray-700">{{ $review->client->full_name }}</span></p>

                        <!-- Enhanced Star Rating -->
                        <div class="flex items-center mt-3">
                            @for ($i = 1; $i <= 5; $i++)
                                <i
                                    class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }} text-lg mr-0.5"></i>
                            @endfor
                            <span class="ml-2 text-sm font-medium text-gray-700">{{ $review->rating }}.0</span>
                        </div>

                        <p class="text-gray-600 mt-4 leading-relaxed">{{ $review->comment }}</p>

                        <!-- Review Photos with Improved Layout -->
                        @if ($review->review_photos)
                            <div class="mt-4 grid grid-cols-2 gap-3">
                                @foreach (json_decode($review->review_photos) as $photo)
                                    <div class="overflow-hidden rounded-lg">
                                        <img src="{{ asset('storage/' . $photo) }}" alt="Review Photo"
                                            class="w-full h-24 object-cover rounded-lg shadow-sm hover:shadow-md transition-all duration-300 hover:scale-105 transform">
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between items-center">
                            <p class="text-xs text-gray-400">{{ $review->created_at->format('F d, Y') }}</p>
                            {{-- <button
                                class="text-xs text-blue-500 hover:text-blue-700 font-medium transition-colors duration-200">
                                <i class="fas fa-thumbs-up mr-1"></i> Helpful
                            </button> --}}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- No Reviews Message -->
            <div class="bg-white rounded-2xl shadow-md p-10 text-center max-w-2xl mx-auto">
                <div class="flex flex-col items-center">
                    <i class="fas fa-comment-slash text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-2xl font-semibold text-gray-700 mb-2">No Reviews Yet</h3>
                    <p class="text-gray-500 mb-6">Be the first to leave a review for
                        {{ ucwords(str_replace('-', ' ', $service_type)) }} services.</p>
                    <a href="{{ route('workers.list', ['service_type' => $service_type]) }}"
                        class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-3 rounded-full font-medium hover:shadow-lg transition-all duration-300">
                        Find {{ ucwords(str_replace('-', ' ', $service_type)) }} Services
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal for Photo Gallery -->
    <div id="photoModal"
        class="fixed inset-0 z-50 hidden overflow-hidden bg-black bg-opacity-75 flex items-center justify-center">
        <div class="relative w-full max-w-4xl p-2 md:p-6 mx-auto">
            <!-- Close button -->
            <button id="closeModal"
                class="absolute top-4 right-4 text-white bg-gray-800 bg-opacity-60 rounded-full p-2 hover:bg-opacity-80 transition-all z-10">
                <i class="fas fa-times text-xl"></i>
            </button>

            <!-- Navigation buttons -->
            <button id="prevPhoto"
                class="absolute left-4 top-1/2 -translate-y-1/2 text-white bg-gray-800 bg-opacity-60 rounded-full p-3 hover:bg-opacity-80 transition-all z-10">
                <i class="fas fa-chevron-left text-xl"></i>
            </button>
            <button id="nextPhoto"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-white bg-gray-800 bg-opacity-60 rounded-full p-3 hover:bg-opacity-80 transition-all z-10">
                <i class="fas fa-chevron-right text-xl"></i>
            </button>

            <!-- Image container -->
            <div class="relative flex items-center justify-center w-full max-h-[80vh] overflow-hidden">
                <img id="modalImage" src="" alt="Review Photo"
                    class="max-h-[80vh] max-w-full w-auto h-auto object-contain rounded-lg shadow-xl">
            </div>

            <!-- Review details panel -->
            <div id="reviewDetails" class="bg-white rounded-lg mt-4 p-4 shadow-lg max-w-2xl mx-auto">
                <div class="flex items-center justify-between mb-2">
                    <h3 id="workerName" class="text-lg font-semibold text-gray-900"></h3>
                    <span id="bookingTitle"
                        class="text-xs font-medium bg-blue-50 px-3 py-1.5 rounded-full text-blue-600"></span>
                </div>
                <p class="text-sm text-gray-500">Client: <span id="clientName" class="font-medium text-gray-700"></span>
                </p>

                <!-- Star Rating -->
                <div class="flex items-center mt-3" id="starRating"></div>

                <p id="reviewComment" class="text-gray-600 mt-3 leading-relaxed"></p>
                <p id="reviewDate" class="text-xs text-gray-400 mt-3"></p>
            </div>
        </div>
    </div>

    <style>
        /* Remove any default underlines from links */
        a {
            text-decoration: none;
        }

        /* Ensure proper vertical alignment for icons and text */
        .fas {
            display: inline-flex;
            align-items: center;
        }

        #modalImage {
            max-width: 100%;
            /* Ensure it doesn't exceed the modal's width */
            max-height: 70vh;
            /* Set a maximum height to prevent overflow */
            width: auto;
            /* Maintain aspect ratio */
            height: auto;
            /* Maintain aspect ratio */
            object-fit: contain;
            /* Ensure the whole image is visible */
        }

        /* Reset list styles */
        #sort-dropdown ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        /* Ensure icons are centered in their fixed width */
        #sort-dropdown i,
        #sort-button i.fas:not(.fa-chevron-down) {
            display: inline-block;
            text-align: center;
        }

        /* Ensure consistent icon alignment in button and dropdown */
        #sort-button .flex.items-center,
        #sort-dropdown .sort-option {
            display: flex;
            align-items: center;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal elements
            const modal = document.getElementById('photoModal');
            const modalImage = document.getElementById('modalImage');
            const closeBtn = document.getElementById('closeModal');
            const prevBtn = document.getElementById('prevPhoto');
            const nextBtn = document.getElementById('nextPhoto');

            // Review details elements
            const workerName = document.getElementById('workerName');
            const bookingTitle = document.getElementById('bookingTitle');
            const clientName = document.getElementById('clientName');
            const starRating = document.getElementById('starRating');
            const reviewComment = document.getElementById('reviewComment');
            const reviewDate = document.getElementById('reviewDate');

            let currentReview = null;
            let currentPhotoIndex = 0;
            let photos = [];

            // Add click event to all review photos
            document.querySelectorAll('.review-card').forEach(card => {
                const photosContainer = card.querySelector('.grid-cols-2');
                if (photosContainer) {
                    const photoElements = photosContainer.querySelectorAll('img');

                    photoElements.forEach((img, index) => {
                        img.style.cursor = 'pointer';
                        img.addEventListener('click', function() {
                            // Get review data
                            currentReview = {
                                worker: card.querySelector('h3').textContent,
                                bookingTitle: card.querySelector('.rounded-full')
                                    .textContent,
                                client: card.querySelector('.font-medium.text-gray-700')
                                    .textContent,
                                rating: parseInt(card.querySelector(
                                        '.text-sm.font-medium.text-gray-700')
                                    .textContent),
                                comment: card.querySelector('.text-gray-600.mt-4')
                                    .textContent,
                                date: card.querySelector('.text-xs.text-gray-400')
                                    .textContent
                            };

                            // Get all photos from this review
                            photos = Array.from(photoElements).map(img => img.src);
                            currentPhotoIndex = index;

                            // Open modal with current photo
                            openModal(photos[currentPhotoIndex], currentReview);
                        });
                    });
                }
            });

            // Open modal function
            function openModal(imageSrc, review) {
                modal.classList.remove('hidden');
                modalImage.src = imageSrc;

                // Set review details
                workerName.textContent = review.worker;
                bookingTitle.textContent = review.bookingTitle;
                clientName.textContent = review.client;
                reviewComment.textContent = review.comment;
                reviewDate.textContent = review.date;

                // Create star rating
                starRating.innerHTML = '';
                for (let i = 1; i <= 5; i++) {
                    const star = document.createElement('i');
                    star.className =
                        `fas fa-star ${i <= review.rating ? 'text-amber-400' : 'text-gray-200'} text-lg mr-0.5`;
                    starRating.appendChild(star);
                }
                const ratingText = document.createElement('span');
                ratingText.className = 'ml-2 text-sm font-medium text-gray-700';
                ratingText.textContent = review.rating + '.0';
                starRating.appendChild(ratingText);

                // Update buttons visibility based on number of photos
                prevBtn.style.display = photos.length > 1 ? 'block' : 'none';
                nextBtn.style.display = photos.length > 1 ? 'block' : 'none';

                // Prevent body scrolling
                document.body.style.overflow = 'hidden';
            }

            // Close modal function
            function closeModal() {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }

            // Next photo function
            function nextPhoto() {
                currentPhotoIndex = (currentPhotoIndex + 1) % photos.length;
                modalImage.src = photos[currentPhotoIndex];
            }

            // Previous photo function
            function prevPhoto() {
                currentPhotoIndex = (currentPhotoIndex - 1 + photos.length) % photos.length;
                modalImage.src = photos[currentPhotoIndex];
            }

            // Event listeners for modal controls
            closeBtn.addEventListener('click', closeModal);
            prevBtn.addEventListener('click', prevPhoto);
            nextBtn.addEventListener('click', nextPhoto);

            // Close modal when clicking outside of content
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (modal.classList.contains('hidden')) return;

                if (e.key === 'Escape') {
                    closeModal();
                } else if (e.key === 'ArrowRight' && photos.length > 1) {
                    nextPhoto();
                } else if (e.key === 'ArrowLeft' && photos.length > 1) {
                    prevPhoto();
                }
            });

            // Sorting functionality
            // Elements
            const sortButton = document.getElementById('sort-button');
            const sortDropdown = document.getElementById('sort-dropdown');
            const selectedSort = document.getElementById('selected-sort');
            const sortOptions = document.querySelectorAll('.sort-option');

            // Toggle dropdown
            sortButton.addEventListener('click', function() {
                sortDropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!sortButton.contains(e.target) && !sortDropdown.contains(e.target)) {
                    sortDropdown.classList.add('hidden');
                }
            });

            // Handle option selection
            sortOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const value = this.dataset.value;
                    const text = this.querySelector('span').textContent;
                    const icon = this.querySelector('i').cloneNode(true);

                    // Update button text and icon
                    selectedSort.textContent = text;
                    sortButton.querySelector('i').className = icon.className;

                    // Hide dropdown
                    sortDropdown.classList.add('hidden');

                    // Sort reviews
                    sortReviews(value);
                });
            });

            // Initial sort
            if (document.getElementById('reviews-container')) {
                sortReviews('newest');
            }

            // Rest of the sorting functionality remains the same
            function sortReviews(sortType) {
                const reviewsContainer = document.getElementById('reviews-container');
                if (!reviewsContainer) return;

                const reviews = Array.from(reviewsContainer.querySelectorAll('.review-card'));

                // Sort the reviews based on the selected option
                reviews.sort((a, b) => {
                    if (sortType === 'newest') {
                        return parseInt(b.dataset.date) - parseInt(a.dataset.date);
                    } else if (sortType === 'oldest') {
                        return parseInt(a.dataset.date) - parseInt(b.dataset.date);
                    } else if (sortType === 'highest') {
                        return parseInt(b.dataset.rating) - parseInt(a.dataset.rating);
                    } else if (sortType === 'lowest') {
                        return parseInt(a.dataset.rating) - parseInt(b.dataset.rating);
                    }
                    return 0;
                });

                // Remove all reviews from the container
                while (reviewsContainer.firstChild) {
                    reviewsContainer.removeChild(reviewsContainer.firstChild);
                }

                // Append the sorted reviews back to the container
                reviews.forEach(review => {
                    reviewsContainer.appendChild(review);
                });
            }

            // Initial sort (newest first)
            if (document.getElementById('reviews-container')) {
                sortReviews('newest');
            }
        });
    </script>
@endsection
