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

// Homepage Redirect Logic
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

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Secured Admin Routes (No Duplicate Prefix)
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    });
});


// No Internet Page
Route::view('/no-internet', 'no-internet');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/check-email', [RegisterController::class, 'checkEmail'])->name('check.email');
Route::post('/check-phone', [RegisterController::class, 'checkPhone'])->name('check.phone');

// Authenticated User Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/worker/verification-step-2', [WorkerVerificationController::class, 'showSecondVerification'])->name('verification.second');
    Route::post('/worker/verification-submit', [WorkerVerificationController::class, 'store'])->name('verification.submit');

    Route::get('/worker/home', [WorkerController::class, 'home'])->name('worker.home');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

Route::post('/worker/availability-toggle', [WorkerController::class, 'toggleAvailability'])->name('worker.availability.toggle');

// Public Routes
Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.update');
Route::get('/workers', [WorkerController::class, 'showWorkers'])->name('workers.list');
Route::post('/rate-worker', [RatingController::class, 'store'])->middleware('auth')->name('rate.worker');

// Password Reset
Route::view('password/reset', 'auth.passwords.email')->name('password.request');

// Home, Pricing, and Settings
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::view('/pricing', 'homepage_resources.pricing')->name('pricing');
Route::get('/settings', [UserController::class, 'settings'])->name('settings');

// Logout Route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
