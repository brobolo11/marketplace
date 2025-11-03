<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfessionalController extends Controller
{
    /**
     * Muestra la lista de profesionales disponibles.
     * 
     * GET /professionals
     */
    public function index(Request $request)
    {
        // Query base para profesionales
        $query = User::where('role', 'pro')
            ->with(['services.category', 'reviewsReceived']);

        // Filtro por ciudad
        if ($request->has('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Filtro por categoría de servicio
        if ($request->has('category_id')) {
            $query->whereHas('services', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Búsqueda por nombre
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('bio', 'like', '%' . $search . '%');
            });
        }

        // Ordenar por calificación (requiere cálculo en memoria)
        $professionals = $query->get();

        // Si se solicita ordenar por rating
        if ($request->has('sort') && $request->sort === 'rating') {
            $professionals = $professionals->sortByDesc(function ($pro) {
                return $pro->averageRating();
            });
        }

        // Paginar manualmente si es necesario
        $perPage = 12;
        $currentPage = $request->get('page', 1);
        $professionals = $professionals->forPage($currentPage, $perPage);

        return view('professionals.index', compact('professionals'));
    }

    /**
     * Muestra el perfil completo de un profesional.
     * 
     * GET /professionals/{id}
     */
    public function show(User $professional)
    {
        // Verifica que el usuario sea un profesional
        if (!$professional->isPro()) {
            abort(404, 'Este usuario no es un profesional.');
        }

        // Carga todas las relaciones necesarias
        $professional->load([
            'services.category',
            'services.photos',
            'reviewsReceived.client',
            'availability',
        ]);

        // Calcula estadísticas
        $averageRating = $professional->averageRating();
        $totalReviews = $professional->reviewsReceived()->count();
        $completedBookings = $professional->bookingsAsPro()
            ->where('status', 'completed')
            ->count();

        return view('professionals.show', compact(
            'professional',
            'averageRating',
            'totalReviews',
            'completedBookings'
        ));
    }

    /**
     * Muestra los servicios de un profesional.
     * 
     * GET /professionals/{id}/services
     */
    public function services(User $professional)
    {
        // Verifica que el usuario sea un profesional
        if (!$professional->isPro()) {
            abort(404, 'Este usuario no es un profesional.');
        }

        // Obtiene los servicios del profesional
        $services = $professional->services()
            ->with(['category', 'photos'])
            ->paginate(9);

        return view('professionals.services', compact('professional', 'services'));
    }

    /**
     * Muestra la disponibilidad de un profesional.
     * 
     * GET /professionals/{id}/availability
     */
    public function availability(User $professional)
    {
        // Verifica que el usuario sea un profesional
        if (!$professional->isPro()) {
            abort(404, 'Este usuario no es un profesional.');
        }

        // Obtiene la disponibilidad del profesional
        $availability = $professional->availability()
            ->orderBy('weekday')
            ->orderBy('start_time')
            ->get()
            ->groupBy('weekday');

        return view('professionals.availability', compact('professional', 'availability'));
    }

    /**
     * Muestra las reseñas de un profesional.
     * 
     * GET /professionals/{id}/reviews
     */
    public function reviews(User $professional)
    {
        // Verifica que el usuario sea un profesional
        if (!$professional->isPro()) {
            abort(404, 'Este usuario no es un profesional.');
        }

        // Obtiene las reseñas del profesional
        $reviews = $professional->reviewsReceived()
            ->with(['client', 'booking.service'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calcula estadísticas
        $averageRating = $professional->averageRating();
        $totalReviews = $professional->reviewsReceived()->count();

        // Distribución de ratings (1-5 estrellas)
        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingDistribution[$i] = $professional->reviewsReceived()
                ->where('rating', $i)
                ->count();
        }

        return view('professionals.reviews', compact(
            'professional',
            'reviews',
            'averageRating',
            'totalReviews',
            'ratingDistribution'
        ));
    }
}
