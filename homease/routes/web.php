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

Auth::routes(['verify' => true]); // Enable email verification

Route::get('/', function () {
    if (Auth::check()) {
        // Check if the user is authenticated and has a role
        if (Auth::user()->role === 'worker') {
            return redirect()->route('worker.home'); // Redirect to worker home
        } elseif (Auth::user()->role === 'client') {
            return redirect('/home'); // Redirect to client home
        }
    }
    return view('welcome'); // If no user is signed in, show the welcome view
})->name('welcome');


Route::view('/no-internet', 'no-internet');

Route::get('/login', function () {
    if (Auth::check() && Auth::user()->role === 'worker') {
        return redirect()->route('worker.home'); // Redirect to worker home
    }
    if (Auth::check() && Auth::user()->role === 'client') {
        return redirect('/home'); // Redirect to client home (or use route('home') if named)
    }
    return view('auth.login'); // Show login page if not authenticated
})->name('login');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register.form'); // Renamed to avoid conflict

Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/check-email', [RegisterController::class, 'checkEmail'])->name('check.email');
Route::post('/check-phone', [RegisterController::class, 'checkPhone'])->name('check.phone');

Route::middleware(['auth'])->group(function () {
    Route::get('/worker/verification-step-2', [WorkerVerificationController::class, 'showSecondVerification'])->name('verification.second');

    Route::post('/worker/verification-submit', [WorkerVerificationController::class, 'store']) // Change to `store`
        ->name('verification.submit');
});

Route::get('/worker/home', function () {
    return view('worker.contents.worker-home');
})->name('worker.home');

Route::middleware(['auth'])->group(function () {
    Route::get('/worker/home', [WorkerController::class, 'home'])->name('worker.home');
});
Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.update');

Route::get('/workers', [WorkerController::class, 'showWorkers'])->name('workers.list');
Route::post('/rate-worker', [RatingController::class, 'store'])->middleware('auth')->name('rate.worker');

Route::get('password/reset', function () {
    return view('auth.passwords.email'); // Create this view if it doesn't exist
})->name('password.request');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/pricing', function () {
    return view('homepage_resources.pricing');
})->name('pricing');

// Profile route - Separate the profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [UserController::class, 'update'])->name('profile.update');
});

Route::get('/settings', [UserController::class, 'settings'])->name('settings');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
