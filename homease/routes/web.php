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

// Public Admin Login Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);

    // Secured Admin Routes
    Route::middleware(['auth'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
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
// The auth middleware will automatically redirect to login page if user is not authenticated
Route::middleware(['auth'])->group(function () {
    // Worker Verification Routes
    Route::get('/worker/verification-step-2', [WorkerVerificationController::class, 'showSecondVerification'])->name('verification.second');
    Route::post('/worker/verification-submit', [WorkerVerificationController::class, 'store'])->name('verification.submit');

    // Worker Routes
    Route::get('/worker/home', [WorkerController::class, 'home'])->name('worker.home');
    Route::post('/worker/availability-toggle', [WorkerController::class, 'toggleAvailability'])->name('worker.availability.toggle');
    Route::get('/worker/profile/{user}', [ProfileController::class, 'viewWorkerProfile'])->name('worker.profile');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Booking Routes
    Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.update');
    Route::post('/book-service', [BookingController::class, 'store'])->name('book.service');
    Route::put('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');


    // Workers Listing
    Route::get('/workers', [WorkerController::class, 'showWorkers'])->name('workers.list');
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');

    // Rating
    Route::post('/rate-worker', [RatingController::class, 'store'])->name('rate.worker');

    // Home, Pricing, and Settings
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::view('/pricing', 'homepage_resources.pricing')->name('pricing');
    Route::get('/settings', [UserController::class, 'settings'])->name('settings');

    // Logout Route
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
