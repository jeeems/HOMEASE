<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\WorkerVerificationController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;

// Authentication routes with email verification
Auth::routes(['verify' => true]);

// Homepage Redirect Logic - Public
Route::get('/', function () {
    if (Auth::check()) {
        return match (Auth::user()->role) {
            'worker' => redirect()->route('worker.home'),
            'client' => redirect('/home'),
            'admin' => redirect()->route('admin.dashboard'),
        };
    }
    return view('welcome');
})->name('welcome');

// No Internet Page - Public
Route::view('/no-internet', 'no-internet');

// Public Views
Route::get('/reviews/{service_type}', [RatingController::class, 'allReviews'])->name('reviews.all-reviews');
Route::view('/pricing', 'homepage_resources.pricing')->name('pricing');

// Admin Login Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);

    // Secured Admin Routes (Using 'admin' middleware for better security)
    Route::middleware(['auth'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');

        Route::get('/bookings/create', [AdminController::class, 'createBooking'])->name('bookings.create');
        Route::get('/bookings/{booking}', [AdminController::class, 'showBooking'])->name('bookings.show');
        Route::get('/bookings/{booking}/edit', [AdminController::class, 'editBooking'])->name('bookings.edit');
        Route::put('/bookings/{booking}/approve', [AdminController::class, 'approveBooking'])->name('bookings.approve');
        Route::put('/bookings/{booking}/cancel', [AdminController::class, 'cancelBooking'])->name('bookings.cancel');
        Route::put('/bookings/{booking}', [AdminController::class, 'updateBooking'])->name('bookings.update');
        Route::get('admin/bookings/ajax', [AdminController::class, 'ajaxBookings'])->name('admin.bookings.ajax');

        Route::get('/services', [AdminController::class, 'services'])->name('services');
        Route::get('/ratings', [AdminController::class, 'ratings'])->name('ratings');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::get('/workers', [AdminController::class, 'workers'])->name('workers');
        Route::get('/workers/{worker}', [AdminController::class, 'showWorker'])->name('workers.show');


        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
        Route::post('/users/store', [AdminController::class, 'store'])->name('users.store');
    });
});

// Public Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/check-email', [RegisterController::class, 'checkEmail'])->name('check.email');
Route::post('/check-phone', [RegisterController::class, 'checkPhone'])->name('check.phone');

// Password Reset - Public
Route::view('password/reset', 'auth.passwords.email')->name('password.request');

// All Other Routes Require Authentication
Route::middleware(['auth'])->group(function () {
    // Worker Verification Routes
    Route::get('/worker/verification-step-2', [WorkerVerificationController::class, 'showSecondVerification'])->name('verification.second');
    Route::post('/worker/verification-submit', [WorkerVerificationController::class, 'store'])->name('verification.submit');

    // Worker Routes
    Route::get('/worker/home', [WorkerController::class, 'home'])->name('worker.home');
    Route::post('/worker/availability-toggle', [WorkerController::class, 'toggleAvailability'])->name('worker.availability.toggle');
    Route::get('/worker/profile/{user}', [ProfileController::class, 'viewWorkerProfile'])->name('worker.profile');
    Route::get('/worker/rating/{id}', [WorkerController::class, 'showRating'])->name('worker.rating.show');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Booking Routes
    Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.update');
    Route::post('/book-service', [BookingController::class, 'store'])->name('book.service');
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel'); // Changed from PUT to POST for browser support
    Route::get('/bookings/{id}/completion-details', [BookingController::class, 'getCompletionDetails'])->name('bookings.completion-details');

    // Workers Listing
    Route::get('/workers', [WorkerController::class, 'showWorkers'])->name('workers.list');
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');

    // Ratings Routes
    Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');
    Route::post('/rating/store', [App\Http\Controllers\RatingController::class, 'store'])->name('rating.store');
    Route::get('/worker/rating/{id}', [WorkerController::class, 'showRating']);

    Route::get('/worker/ratings/{id}', [App\Http\Controllers\WorkerController::class, 'getRatingDetails'])->name('worker.rating.details');

    // Home, Pricing, and Settings
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/settings', [UserController::class, 'settings'])->name('settings');

    // Logout Route
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
