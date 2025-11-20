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
Route::get('/availability/calendar/{userId}', [\App\Http\Controllers\AvailabilityController::class, 'getAvailability']);
Route::get('/bookings/{professional}/dates', [AvailabilityController::class, 'bookedDates']);
// Obtener disponibilidad del profesional por día
Route::get('/professional/{professional}/availability', function($professionalId, Request $request) {
    $day = $request->query('day'); // 0-6 (domingo-sábado)
    
    // Obtener horarios del profesional para ese día
    $schedules = \App\Models\Availability::where('user_id', $professionalId)
        ->where('weekday', $day)
        ->whereNull('specific_date')
        ->orderBy('start_time')
        ->get(['start_time', 'end_time']);
    
    return response()->json([
        'schedules' => $schedules
    ]);
});

// Obtener reservas del profesional en una fecha
Route::get('/professional/{professional}/bookings', function($professionalId, Request $request) {
    $date = $request->query('date');
    
    // Obtener todas las reservas del profesional para esa fecha
    $bookings = \App\Models\Booking::where('pro_id', $professionalId)
        ->whereDate('service_date', $date)
        ->whereIn('status', ['pending', 'accepted'])
        ->get();
    
    // Extraer las horas ocupadas
    $bookedTimes = $bookings->map(function($booking) {
        return \Carbon\Carbon::parse($booking->service_date)->format('H:i');
    })->toArray();
    
    return response()->json([
        'booked_times' => $bookedTimes
    ]);
});

// API de reservas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/bookings', [BookingController::class, 'store']);
});
