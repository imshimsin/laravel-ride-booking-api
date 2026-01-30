<?php

use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\PassengerController;
use Illuminate\Support\Facades\Route;

// X-User-Id header required. Passenger/driver type enforced per prefix.

Route::middleware('api.auth')->group(function () {
    Route::prefix('passenger')->middleware('passenger')->group(function () {
        Route::post('/rides', [PassengerController::class, 'createRide']);
        Route::post('/rides/{ride}/approve-driver/{rideDriverRequest}', [PassengerController::class, 'approveDriver']);
        Route::post('/rides/{ride}/complete', [PassengerController::class, 'markCompleted']);
    });

    Route::prefix('driver')->middleware('driver')->group(function () {
        Route::post('/location', [DriverController::class, 'updateLocation']);
        Route::get('/rides/nearby', [DriverController::class, 'nearbyRides']);
        Route::post('/rides/{ride}/request', [DriverController::class, 'requestRide']);
        Route::post('/rides/{ride}/complete', [DriverController::class, 'markCompleted']);
    });
});
