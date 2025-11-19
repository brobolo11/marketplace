<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AvailabilityController;
use App\Http\Controllers\Api\BookingController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API de disponibilidad de profesionales
Route::get('/availability/{professional}', [AvailabilityController::class, 'show']);
Route::get('/bookings/{professional}/dates', [AvailabilityController::class, 'bookedDates']);

// API de reservas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/bookings', [BookingController::class, 'store']);
});
