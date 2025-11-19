<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Crear una nueva reserva
     */
    public function store(Request $request): JsonResponse
    {
        // Validación
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|exists:services,id',
            'professional_id' => 'required|exists:users,id',
            'dates' => 'required|array|min:1',
            'dates.*' => 'required|date|after_or_equal:today',
            'description' => 'nullable|string|max:1000'
        ], [
            'service_id.required' => 'El servicio es requerido',
            'service_id.exists' => 'El servicio no existe',
            'professional_id.required' => 'El profesional es requerido',
            'professional_id.exists' => 'El profesional no existe',
            'dates.required' => 'Debes seleccionar al menos una fecha',
            'dates.min' => 'Debes seleccionar al menos una fecha',
            'dates.*.date' => 'Formato de fecha inválido',
            'dates.*.after_or_equal' => 'No puedes seleccionar fechas pasadas',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // Obtener servicio y profesional
        $service = Service::findOrFail($request->service_id);
        $professional = User::findOrFail($request->professional_id);

        // Verificar que el usuario sea profesional
        if (!$professional->isPro()) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario seleccionado no es un profesional'
            ], 400);
        }

        // Verificar que el servicio pertenezca al profesional
        if ($service->user_id !== $professional->id) {
            return response()->json([
                'success' => false,
                'message' => 'El servicio no pertenece a este profesional'
            ], 400);
        }

        $bookingsCreated = [];
        $errors = [];

        // Crear una reserva por cada fecha seleccionada
        foreach ($request->dates as $date) {
            try {
                // Verificar disponibilidad
                $existingBooking = Booking::where('pro_id', $professional->id)
                    ->whereDate('datetime', $date)
                    ->whereIn('status', ['pending', 'accepted'])
                    ->exists();

                if ($existingBooking) {
                    $errors[] = "La fecha {$date} ya está reservada";
                    continue;
                }

                // Crear la reserva
                $booking = Booking::create([
                    'user_id' => auth()->id(),
                    'pro_id' => $professional->id,
                    'service_id' => $service->id,
                    'datetime' => Carbon::parse($date)->setTime(9, 0), // Hora por defecto 9:00 AM
                    'address' => auth()->user()->city ?? 'Por definir',
                    'status' => 'pending',
                    'total_price' => $service->price,
                    'description' => $request->description
                ]);

                $bookingsCreated[] = $booking;

                // Crear notificación para el profesional
                NotificationService::bookingRequest($booking);

            } catch (\Exception $e) {
                $errors[] = "Error al procesar la fecha {$date}: " . $e->getMessage();
            }
        }

        if (empty($bookingsCreated)) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo crear ninguna reserva',
                'errors' => $errors
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => count($bookingsCreated) . ' reserva(s) creada(s) exitosamente',
            'bookings' => $bookingsCreated,
            'errors' => $errors
        ], 201);
    }
}
