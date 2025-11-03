<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Category;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard administrativo con estadísticas
     * 
     * GET /admin/dashboard
     */
    public function index()
    {
        // Estadísticas generales
        $stats = [
            'total_users' => User::count(),
            'total_clients' => User::where('role', 'client')->count(),
            'total_professionals' => User::where('role', 'pro')->count(),
            'total_services' => Service::count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'total_reviews' => Review::count(),
            'total_categories' => Category::count(),
        ];

        // Últimos usuarios registrados
        $recentUsers = User::latest()->take(10)->get();

        // Últimas reservas
        $recentBookings = Booking::with(['client', 'professional', 'service'])
            ->latest()
            ->take(10)
            ->get();

        // Servicios más populares
        $popularServices = Service::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->take(10)
            ->get();

        // Profesionales mejor valorados
        $topProfessionals = User::where('role', 'pro')
            ->with('reviewsReceived')
            ->get()
            ->sortByDesc(function ($pro) {
                return $pro->averageRating();
            })
            ->take(10);

        // Ingresos del mes (suma de bookings completadas)
        $monthlyRevenue = Booking::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('total_price');

        return view('admin.dashboard', compact(
            'stats',
            'recentUsers',
            'recentBookings',
            'popularServices',
            'topProfessionals',
            'monthlyRevenue'
        ));
    }
}
