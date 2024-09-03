<?php

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Google OAuth Routes
Route::get('auth/google', [App\Http\Controllers\GoogleAuthController::class, 'redirectToGoogle'])->name('google-auth');
Route::get('auth/google/callback', [App\Http\Controllers\GoogleAuthController::class, 'callbackGoogle']);

require __DIR__.'/auth.php';