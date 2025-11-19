<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Muestra las reseñas de un profesional específico.
     * 
     * GET /professionals/{id}/reviews
     */
    public function index(User $professional)
    {
        // Verifica que el usuario sea un profesional
        if (!$professional->isPro()) {
            abort(404, 'Este usuario no es un profesional.');
        }

        // Obtiene las reseñas del profesional con paginación
        $reviews = $professional->reviewsReceived()
            ->with(['client', 'booking.service'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calcula la calificación promedio
        $averageRating = $professional->averageRating();
        $totalReviews = $professional->reviewsReceived()->count();

        return view('reviews.index', compact('professional', 'reviews', 'averageRating', 'totalReviews'));
    }

    /**
     * Guarda una nueva reseña para un servicio completado.
     * 
     * POST /reviews
     */
    public function store(Request $request)
    {
        // Validación de datos
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ], [
            'booking_id.required' => 'La reserva es requerida.',
            'booking_id.exists' => 'La reserva seleccionada no existe.',
            'rating.required' => 'La calificación es requerida.',
            'rating.integer' => 'La calificación debe ser un número entero.',
            'rating.min' => 'La calificación debe ser al menos 1 estrella.',
            'rating.max' => 'La calificación no puede exceder 5 estrellas.',
            'comment.max' => 'El comentario no puede exceder 1000 caracteres.',
        ]);

        // Obtiene la reserva
        $booking = Booking::findOrFail($validated['booking_id']);

        // Verifica que el usuario sea el cliente de la reserva
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para reseñar esta reserva.');
        }

        // Verifica que la reserva esté completada
        if (!$booking->isCompleted()) {
            return back()->withErrors(['error' => 'Solo puedes reseñar servicios completados.']);
        }

        // Verifica que no exista ya una reseña para esta reserva
        if ($booking->review()->exists()) {
            return back()->withErrors(['error' => 'Ya has dejado una reseña para esta reserva.']);
        }

        // Crea la reseña
        $review = Review::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'pro_id' => $booking->pro_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()
            ->route('bookings.show', $booking)
            ->with('success', '¡Reseña publicada exitosamente!');
    }

    /**
     * Muestra el formulario para crear una reseña.
     * 
     * GET /bookings/{booking}/review/create
     */
    public function create(Booking $booking)
    {
        // Verifica que el usuario sea el cliente de la reserva
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para reseñar esta reserva.');
        }

        // Verifica que la reserva esté completada
        if (!$booking->isCompleted()) {
            abort(403, 'Solo puedes reseñar servicios completados.');
        }

        // Verifica que no exista ya una reseña
        if ($booking->review()->exists()) {
            return redirect()
                ->route('bookings.show', $booking)
                ->withErrors(['error' => 'Ya has dejado una reseña para esta reserva.']);
        }

        return view('reviews.create', compact('booking'));
    }

    /**
     * Elimina una reseña (solo el autor puede eliminarla).
     * 
     * DELETE /reviews/{review}
     */
    public function destroy(Review $review)
    {
        // Verifica que el usuario sea el autor de la reseña
        if ($review->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar esta reseña.');
        }

        $review->delete();

        return back()->with('success', 'Reseña eliminada correctamente.');
    }
}
