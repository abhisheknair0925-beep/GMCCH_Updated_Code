<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DoctorController;

// Apply standard API rate limiting
Route::middleware('throttle:60,1')->group(function () {
    
    // Strict Rate Limiting for Auth
    Route::middleware('throttle:5,1')->group(function () {
        Route::post('/user/login', [AuthController::class, 'userLogin']);
        Route::post('/doctor/login', [AuthController::class, 'doctorLogin']);
        Route::post('/hospital/login', [AuthController::class, 'hospitalLogin']);
    });

    Route::get('/units', [UnitController::class, 'index']);

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        // Booking Endpoints (Users)
        Route::post('/booking/create', [BookingController::class, 'create']);
        Route::get('/booking/my-bookings', [BookingController::class, 'getByUser']);
        Route::post('/booking/cancel', [BookingController::class, 'cancel']);
        
        // Doctor Queue Endpoints
        Route::get('/doctor/queue/{unit_id}', [DoctorController::class, 'getQueue']);
        Route::get('/doctor/current/{unit_id}', [DoctorController::class, 'getCurrentToken']);
        Route::post('/doctor/call-next', [DoctorController::class, 'callNext']);
        Route::post('/doctor/complete', [DoctorController::class, 'markCompleted']);

        // Hospital Admin Dashboard Endpoints
        Route::prefix('hospital/dashboard')->group(function () {
            Route::get('/summary', [\App\Http\Controllers\HospitalDashboardController::class, 'getSummary']);
            Route::get('/units', [\App\Http\Controllers\HospitalDashboardController::class, 'getUnitStats']);
            Route::get('/doctors', [\App\Http\Controllers\HospitalDashboardController::class, 'getDoctorStats']);
        });

        // Hospital Unit Management Endpoints
        Route::prefix('hospital/units')->group(function () {
            Route::post('/', [\App\Http\Controllers\HospitalUnitController::class, 'store']);
            Route::put('/{id}', [\App\Http\Controllers\HospitalUnitController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\HospitalUnitController::class, 'destroy']);
        });
    });
});
