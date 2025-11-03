<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            ->orderBy('weekday')
            ->orderBy('start_time')
            ->get();

        return view('availability.index', compact('availability'));
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
}
