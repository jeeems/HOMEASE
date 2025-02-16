<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\WorkerVerificationController;
use App\Http\Controllers\UserController;

Auth::routes(['verify' => true]); // Enable email verification

Route::get('/', function () {
    return view('welcome'); // Ensure there is a welcome.blade.php file
})->name('welcome');

Route::view('/no-internet', 'no-internet');

Route::get('/login', function () {
    return view('auth.login'); // Replace with your actual login view
})->name('login');

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


Route::get('password/reset', function () {
    return view('auth.passwords.email'); // Create this view if it doesn't exist
})->name('password.request');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/pricing', function () {
    return view('homepage_resources.pricing');
})->name('pricing');

Route::get('/profile', [UserController::class, 'profile'])->name('profile');
Route::get('/settings', [UserController::class, 'settings'])->name('settings');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
