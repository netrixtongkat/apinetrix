<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\JourneyController;
use App\Http\Controllers\SavedLocationController;
use App\Http\Controllers\EmergencyController;
use App\Http\Controllers\NotificationController;

// Auth routes (tidak perlu token)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/auth/google', [AuthController::class, 'googleLogin']);

// Routes yang butuh token
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/update-fcm-token', [AuthController::class, 'updateFcmToken']);

    // Device
    Route::get('/device', [DeviceController::class, 'index']);
    Route::post('/device', [DeviceController::class, 'update']);

    // Journey
    Route::get('/journeys', [JourneyController::class, 'index']);
    Route::post('/journeys', [JourneyController::class, 'store']);
    Route::post('/journeys/{id}/finish', [JourneyController::class, 'finish']);

    // Saved Locations
    Route::get('/saved-locations', [SavedLocationController::class, 'index']);
    Route::post('/saved-locations', [SavedLocationController::class, 'store']);
    Route::delete('/saved-locations/{id}', [SavedLocationController::class, 'destroy']);

    // Emergency
    Route::post('/emergency', [EmergencyController::class, 'trigger']);
    Route::get('/emergency/logs', [EmergencyController::class, 'logs']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
});