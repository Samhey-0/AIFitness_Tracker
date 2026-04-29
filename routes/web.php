<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/track', [DashboardController::class, 'track'])->name('track');
    Route::get('/stats', [DashboardController::class, 'stats'])->name('stats');
    
    // API routes for AJAX
    Route::post('/api/workout', [DashboardController::class, 'storeWorkout'])->name('api.workout');
    Route::post('/api/steps', [DashboardController::class, 'addSteps'])->name('api.steps');

    // AI Workout Generator
    Route::get('/ai-generator', [\App\Http\Controllers\AIWorkoutController::class, 'index'])->name('ai.generator');
    Route::post('/ai-generator/generate', [\App\Http\Controllers\AIWorkoutController::class, 'generate'])->name('ai.generate');
    Route::post('/ai-generator/save', [\App\Http\Controllers\AIWorkoutController::class, 'store'])->name('ai.save');
});
