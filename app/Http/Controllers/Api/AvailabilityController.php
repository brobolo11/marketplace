<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\Availability;
use Illuminate\Http\JsonResponse;

class AvailabilityController extends Controller
{
    /**
     * Obtener la disponibilidad de un profesional
     * Retorna días bloqueados y disponibles en formato de eventos para Fullcalendar
     */
    public function show(User $professional): JsonResponse
    {
        // Verificar que el usuario sea profesional
        if (!$professional->isPro()) {
            return response()->json([
                'error' => 'El usuario no es un profesional'
            ], 404);
        }

        // Obtener reservas confirmadas del profesional (días ocupados)
        $bookedDates = Booking::where('pro_id', $professional->id)
            ->whereIn('status', ['accepted', 'pending'])
            ->get()
            ->pluck('datetime')
            ->map(function ($datetime) {
                return [
                    'start' => \Carbon\Carbon::parse($datetime)->format('Y-m-d'),
                    'display' => 'background',
                    'backgroundColor' => '#ef4444', // Rojo para ocupado
                    'classNames' => ['booked-date']
                ];
            })
            ->toArray();

        // Obtener disponibilidad del profesional
        $availability = Availability::where('user_id', $professional->id)
            ->where('is_available', true)
            ->get()
            ->map(function ($slot) {
                return [
                    'daysOfWeek' => [$slot->day_of_week],
                    'startTime' => $slot->start_time,
                    'endTime' => $slot->end_time,
                    'display' => 'background',
                    'backgroundColor' => '#10b981', // Verde para disponible
                    'classNames' => ['available-slot']
                ];
            })
            ->toArray();

        return response()->json([
            'events' => array_merge($bookedDates, $availability),
            'professionalId' => $professional->id,
            'professionalName' => $professional->name
        ]);
    }

    /**
     * Obtener fechas ya reservadas de un profesional
     * Útil para validación en frontend
     */
    public function bookedDates(User $professional): JsonResponse
    {
        if (!$professional->isPro()) {
            return response()->json([
                'error' => 'El usuario no es un profesional'
            ], 404);
        }

        $bookedDates = Booking::where('pro_id', $professional->id)
            ->whereIn('status', ['accepted', 'pending'])
            ->pluck('datetime')
            ->map(function ($datetime) {
                return \Carbon\Carbon::parse($datetime)->format('Y-m-d');
            })
            ->unique()
            ->values()
            ->toArray();

        return response()->json([
            'bookedDates' => $bookedDates,
            'professionalId' => $professional->id
        ]);
    }
}
