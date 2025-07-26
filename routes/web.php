<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
// use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
|                        Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::get('index', [AdminController::class, 'index'])->middleware('auth');
Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login.form');

Route::post('logincheck', [AdminController::class, 'logincheck']);

Route::get('/otp', [AdminController::class, 'showOtpForm'])->name('otp.form');
Route::post('/verify-otp', [AdminController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/resend-otp', [AdminController::class, 'resendOtp'])->name('otp.resend');
