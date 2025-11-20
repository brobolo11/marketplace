<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AvailabilityController extends Controller
{
    /**
     * Muestra la disponibilidad del profesional autenticado.
     * 
     * GET /availability
     */
    public function index()
    {
        $user = Auth::user();

        // Verifica que el usuario sea un profesional
        if (!$user->isPro()) {
            abort(403, 'Solo los profesionales pueden gestionar su disponibilidad.');
        }

        // Obtiene toda la disponibilidad del profesional
        $availability = $user->availability()
            ->whereNull('specific_date')
            ->orderBy('weekday')
            ->orderBy('start_time')
            ->get();

        // Agrupa la disponibilidad por día de la semana
        $weeklyAvailability = $availability->groupBy('weekday');

        // Obtiene los días específicos bloqueados
        $specificBlocks = $user->availability()
            ->whereNotNull('specific_date')
            ->where('specific_date', '>=', now()->toDateString())
            ->orderBy('specific_date')
            ->get();

        return view('availability.index', compact('availability', 'weeklyAvailability', 'specificBlocks'));
    }

    /**
     * Guarda un nuevo bloque de disponibilidad.
     * 
     * POST /availability
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Verifica que el usuario sea un profesional
        if (!$user->isPro()) {
            abort(403, 'Solo los profesionales pueden gestionar su disponibilidad.');
        }

        // Validación de datos
        $validated = $request->validate([
            'weekday' => 'required|integer|min:0|max:6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ], [
            'weekday.required' => 'El día de la semana es requerido.',
            'weekday.min' => 'El día de la semana debe estar entre 0 (Domingo) y 6 (Sábado).',
            'weekday.max' => 'El día de la semana debe estar entre 0 (Domingo) y 6 (Sábado).',
            'start_time.required' => 'La hora de inicio es requerida.',
            'start_time.date_format' => 'El formato de hora debe ser HH:MM.',
            'end_time.required' => 'La hora de fin es requerida.',
            'end_time.date_format' => 'El formato de hora debe ser HH:MM.',
            'end_time.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
        ]);

        // Verifica que no haya solapamiento de horarios
        $overlap = Availability::where('user_id', $user->id)
            ->where('weekday', $validated['weekday'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                          ->where('end_time', '>=', $validated['end_time']);
                    });
            })
            ->exists();

        if ($overlap) {
            return back()->withErrors(['error' => 'Ya tienes disponibilidad registrada en ese horario.']);
        }

        // Crea el bloque de disponibilidad
        $availability = $user->availability()->create($validated);

        return back()->with('success', 'Disponibilidad agregada exitosamente.');
    }

    /**
     * Actualiza un bloque de disponibilidad existente.
     * 
     * PUT /availability/{id}
     */
    public function update(Request $request, Availability $availability)
    {
        // Verifica que el usuario sea el propietario de la disponibilidad
        if ($availability->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para actualizar esta disponibilidad.');
        }

        // Validación de datos
        $validated = $request->validate([
            'weekday' => 'required|integer|min:0|max:6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ], [
            'weekday.required' => 'El día de la semana es requerido.',
            'weekday.min' => 'El día de la semana debe estar entre 0 (Domingo) y 6 (Sábado).',
            'weekday.max' => 'El día de la semana debe estar entre 0 (Domingo) y 6 (Sábado).',
            'start_time.required' => 'La hora de inicio es requerida.',
            'start_time.date_format' => 'El formato de hora debe ser HH:MM.',
            'end_time.required' => 'La hora de fin es requerida.',
            'end_time.date_format' => 'El formato de hora debe ser HH:MM.',
            'end_time.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
        ]);

        // Verifica que no haya solapamiento con otros bloques (excluyendo el actual)
        $overlap = Availability::where('user_id', Auth::id())
            ->where('id', '!=', $availability->id)
            ->where('weekday', $validated['weekday'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                          ->where('end_time', '>=', $validated['end_time']);
                    });
            })
            ->exists();

        if ($overlap) {
            return back()->withErrors(['error' => 'Ya tienes disponibilidad registrada en ese horario.']);
        }

        // Actualiza el bloque de disponibilidad
        $availability->update($validated);

        return back()->with('success', 'Disponibilidad actualizada exitosamente.');
    }

    /**
     * Elimina un bloque de disponibilidad.
     * 
     * DELETE /availability/{id}
     */
    public function destroy(Availability $availability)
    {
        // Verifica que el usuario sea el propietario de la disponibilidad
        if ($availability->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar esta disponibilidad.');
        }

        $availability->delete();

        return back()->with('success', 'Disponibilidad eliminada exitosamente.');
    }

    /**
     * Crear un bloqueo específico (vacaciones, día festivo, etc.)
     * 
     * POST /availability/block
     */
    public function createBlock(Request $request)
    {
        $user = Auth::user();

        if (!$user->isPro()) {
            abort(403, 'Solo los profesionales pueden gestionar bloqueos.');
        }

        $request->validate([
            'specific_date' => 'required|date|after_or_equal:today',
            'reason' => 'nullable|string|max:255',
        ]);

        Availability::create([
            'user_id' => $user->id,
            'weekday' => \Carbon\Carbon::parse($request->specific_date)->dayOfWeek,
            'specific_date' => $request->specific_date,
            'start_time' => '00:00',
            'end_time' => '23:59',
            'is_available' => false,
            'reason' => $request->reason ?? 'Día no disponible',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bloqueo creado correctamente'
        ]);
    }

    /**
     * Eliminar un bloqueo específico
     * 
     * DELETE /availability/block/{id}
     */
    public function deleteBlock($id)
    {
        $block = Availability::where('user_id', Auth::id())
            ->whereNotNull('specific_date')
            ->findOrFail($id);
        
        $block->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bloqueo eliminado correctamente'
        ]);
    }

    /**
     * API: Obtener disponibilidad de un profesional (para clientes al contratar)
     * 
     * GET /api/availability/{userId}
     */
    public function getAvailability($userId)
    {
        // Obtener horarios semanales
        $weeklySchedule = Availability::where('user_id', $userId)
            ->whereNull('specific_date')
            ->where('is_available', true)
            ->orderBy('weekday')
            ->get()
            ->groupBy('weekday');

        // Obtener bloqueos específicos (próximos 90 días)
        $blockedDates = Availability::where('user_id', $userId)
            ->whereNotNull('specific_date')
            ->where('is_available', false)
            ->where('specific_date', '>=', Carbon::today())
            ->where('specific_date', '<=', Carbon::today()->addDays(90))
            ->pluck('specific_date')
            ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))
            ->toArray();

        // Obtener fechas con reservas confirmadas
        $bookedDates = Booking::where('professional_id', $userId)
            ->whereIn('status', ['approved', 'paid', 'completed'])
            ->where('booking_date', '>=', Carbon::today())
            ->where('booking_date', '<=', Carbon::today()->addDays(90))
            ->pluck('booking_date')
            ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))
            ->toArray();

        // Generar calendario de disponibilidad para los próximos 90 días
        $availableDates = [];
        $today = Carbon::today();
        
        for ($i = 0; $i < 90; $i++) {
            $date = $today->copy()->addDays($i);
            $dateString = $date->format('Y-m-d');
            $dayOfWeek = $date->dayOfWeek;

            // Verificar si el día tiene horario configurado
            $hasSchedule = isset($weeklySchedule[$dayOfWeek]) && $weeklySchedule[$dayOfWeek]->isNotEmpty();
            
            // Verificar si está bloqueado específicamente
            $isBlocked = in_array($dateString, $blockedDates);
            
            // Verificar si ya tiene reserva
            $isBooked = in_array($dateString, $bookedDates);

            $availableDates[] = [
                'date' => $dateString,
                'dayOfWeek' => $dayOfWeek,
                'dayName' => $date->isoFormat('dddd'),
                'available' => $hasSchedule && !$isBlocked && !$isBooked,
                'schedule' => $hasSchedule ? $weeklySchedule[$dayOfWeek]->map(function($slot) {
                    return [
                        'start' => $slot->start_time,
                        'end' => $slot->end_time,
                    ];
                })->toArray() : [],
                'reason' => $isBlocked ? Availability::where('specific_date', $dateString)->value('reason') : null,
            ];
        }

        return response()->json([
            'success' => true,
            'professional_id' => $userId,
            'weekly_schedule' => $weeklySchedule,
            'availability_calendar' => $availableDates,
        ]);
    }
}
