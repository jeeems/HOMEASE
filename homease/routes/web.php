<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PricingController;

Auth::routes(['verify' => true]); // Enable email verification

Route::get('/', function () {
    return view('welcome');
});

Route::view('/no-internet', 'no-internet');

Route::get('/login', function () {
    return view('auth.login'); // Replace with your actual login view
})->name('login');

Route::get('/register', function () {
    return view('auth.register'); // Replace with your actual login view
})->name('register');

Route::get('password/reset', function () {
    return view('auth.passwords.email'); // Create this view if it doesn't exist
})->name('password.request');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/pricing', function () {
    return view('homepage_resources.pricing');
})->name('pricing');
