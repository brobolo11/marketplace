<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Muestra las solicitudes pendientes del profesional autenticado.
     * 
     * GET /bookings/pending-requests
     */
    public function pendingRequests()
    {
        $user = Auth::user();

        // Verifica que el usuario sea profesional
        if (!$user->isPro()) {
            abort(403, 'Solo los profesionales pueden acceder a esta página.');
        }

        // Obtiene las reservas pendientes
        $pendingBookings = Booking::where('pro_id', $user->id)
            ->where('status', 'pending')
            ->with(['client', 'service'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calcula las estadísticas
        $acceptedToday = Booking::where('pro_id', $user->id)
            ->where('status', 'accepted')
            ->whereDate('approved_at', today())
            ->count();

        $estimatedRevenue = $pendingBookings->sum('total_price');

        return view('bookings.pending-requests', compact('pendingBookings', 'acceptedToday', 'estimatedRevenue'));
    }

    /**
     * Muestra las reservas del usuario autenticado.
     * Si es cliente, muestra las reservas realizadas.
     * Si es profesional, muestra las reservas recibidas.
     * 
     * GET /bookings
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isPro()) {
            // Reservas recibidas como profesional
            $bookings = $user->bookingsAsPro()
                ->with(['client', 'service'])
                ->orderBy('datetime', 'desc')
                ->paginate(10);
        } else {
            // Reservas realizadas como cliente
            $bookings = $user->bookingsAsClient()
                ->with(['professional', 'service'])
                ->orderBy('datetime', 'desc')
                ->paginate(10);
        }

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Muestra el detalle de una reserva específica.
     * 
     * GET /bookings/{id}
     */
    public function show(Booking $booking)
    {
        // Verifica que el usuario tenga permiso para ver esta reserva
        $user = Auth::user();
        if ($booking->user_id !== $user->id && $booking->pro_id !== $user->id) {
            abort(403, 'No tienes permiso para ver esta reserva.');
        }

        // Carga las relaciones necesarias
        $booking->load(['client', 'professional', 'service', 'review']);

        return view('bookings.show', compact('booking'));
    }

    /**
     * Crea una nueva reserva.
     * 
     * POST /bookings
     */
    public function store(Request $request)
    {
        // Validación de datos
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'datetime' => 'required|date|after:now',
            'address' => 'required|string|max:255',
            'total_price' => 'required|numeric|min:0',
        ]);

        // Obtiene el servicio y el profesional
        $service = Service::findOrFail($validated['service_id']);
        
        // Verifica que el usuario no reserve su propio servicio
        if ($service->user_id === Auth::id()) {
            return back()->withErrors(['error' => 'No puedes reservar tu propio servicio.']);
        }

        // Crea la reserva
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'pro_id' => $service->user_id,
            'service_id' => $service->id,
            'datetime' => $validated['datetime'],
            'address' => $validated['address'],
            'total_price' => $validated['total_price'],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('bookings.show', $booking)
            ->with('success', 'Reserva creada exitosamente. Esperando confirmación del profesional.');
    }

    /**
     * El profesional aprueba una reserva.
     * 
     * POST /bookings/{booking}/approve
     */
    public function approve(Booking $booking)
    {
        // Verifica que el usuario sea el profesional de la reserva
        if ($booking->pro_id !== Auth::id()) {
            abort(403, 'No tienes permiso para aprobar esta reserva.');
        }

        // Verifica que la reserva esté pendiente
        if (!$booking->isPending()) {
            return back()->withErrors(['error' => 'Esta reserva ya no está pendiente.']);
        }

        // Actualiza el estado y la fecha de aprobación
        $booking->status = 'accepted';
        $booking->approved_at = now();
        $booking->save();

        // Crea notificación para el cliente
        NotificationService::bookingAccepted($booking);

        return redirect()
            ->route('bookings.pendingRequests')
            ->with('success', 'Reserva aprobada exitosamente. El cliente ha sido notificado.');
    }

    /**
     * El profesional rechaza una reserva.
     * 
     * POST /bookings/{booking}/reject
     */
    public function reject(Request $request, Booking $booking)
    {
        // Verifica que el usuario sea el profesional de la reserva
        if ($booking->pro_id !== Auth::id()) {
            abort(403, 'No tienes permiso para rechazar esta reserva.');
        }

        // Verifica que la reserva esté pendiente
        if (!$booking->isPending()) {
            return back()->withErrors(['error' => 'Esta reserva ya no está pendiente.']);
        }

        // Valida el motivo de rechazo
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        // Actualiza el estado, fecha de rechazo y motivo
        $booking->status = 'rejected';
        $booking->rejected_at = now();
        $booking->rejection_reason = $validated['rejection_reason'];
        $booking->save();

        // Crea notificación para el cliente
        NotificationService::bookingRejected($booking, $validated['rejection_reason']);

        return redirect()
            ->route('bookings.pendingRequests')
            ->with('success', 'Reserva rechazada. El cliente ha sido notificado.');
    }

    /**
     * El cliente cancela una reserva.
     * 
     * PATCH /bookings/{id}/cancel
     */
    public function cancel(Booking $booking)
    {
        // Verifica que el usuario sea el cliente de la reserva
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para cancelar esta reserva.');
        }

        // Verifica que la reserva no esté completada
        if ($booking->isCompleted()) {
            return back()->withErrors(['error' => 'No puedes cancelar una reserva completada.']);
        }

        $booking->cancel();

        return redirect()
            ->route('bookings.show', $booking)
            ->with('success', 'Reserva cancelada exitosamente.');
    }

    /**
     * El profesional marca una reserva como completada.
     * 
     * PATCH /bookings/{id}/complete
     */
    public function complete(Booking $booking)
    {
        // Verifica que el usuario sea el profesional de la reserva
        if ($booking->pro_id !== Auth::id()) {
            abort(403, 'No tienes permiso para completar esta reserva.');
        }

        // Verifica que la reserva esté aceptada
        if (!$booking->isAccepted()) {
            return back()->withErrors(['error' => 'Solo puedes completar reservas aceptadas.']);
        }

        $booking->complete();

        // Crear notificación para el cliente invitando a dejar reseña
        \App\Models\Notification::create([
            'user_id' => $booking->user_id,
            'type' => 'review_requested',
            'title' => '¡Deja tu reseña!',
            'message' => "El servicio '{$booking->service->title}' ha sido completado. ¿Qué tal fue tu experiencia?",
            'link' => route('reviews.create', $booking->id),
        ]);

        return redirect()
            ->route('bookings.show', $booking)
            ->with('success', 'Reserva marcada como completada. El cliente ya puede dejar una reseña.');
    }
}
