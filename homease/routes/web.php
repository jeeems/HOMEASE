<?php

use Illuminate\Support\Facades\Route;

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
