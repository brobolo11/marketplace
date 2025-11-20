<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Verifica que el usuario autenticado sea admin
     */
    private function checkAdmin()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Acceso denegado. Solo administradores.');
        }
    }

    /**
     * Dashboard principal con estadísticas
     * GET /admin/dashboard
     */
    public function dashboard()
    {
        $this->checkAdmin();
        // Estadísticas generales
        $totalUsers = User::count();
        $totalProfessionals = User::where('role', 'professional')->count();
        $totalClients = User::where('role', 'client')->count();
        $totalServices = Service::count();
        $totalBookings = Booking::count();
        $totalRevenue = Booking::where('status', 'completed')->sum('total_price');
        
        // Estadísticas de reservas por estado
        $pendingBookings = Booking::where('status', 'pending')->count();
        $acceptedBookings = Booking::where('status', 'accepted')->count();
        $completedBookings = Booking::where('status', 'completed')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();
        
        // Servicios por categoría
        $servicesByCategory = Service::select('category_id', DB::raw('count(*) as total'))
            ->with('category')
            ->groupBy('category_id')
            ->get();
        
        // Últimas reservas
        $recentBookings = Booking::with(['client', 'professional', 'service'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalUsers', 'totalProfessionals', 'totalClients',
            'totalServices', 'totalBookings', 'totalRevenue',
            'pendingBookings', 'acceptedBookings', 'completedBookings', 'cancelledBookings',
            'servicesByCategory', 'recentBookings'
        ));
    }

    /**
     * Gestión de usuarios
     * GET /admin/users
     */
    public function users(Request $request)
    {
        $this->checkAdmin();
        
        $search = $request->get('search');
        $role = $request->get('role');
        
        $users = User::query()
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role, function ($query, $role) {
                return $query->where('role', $role);
            })
            ->withCount(['services', 'bookingsAsClient', 'bookingsAsPro'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.users', compact('users', 'search', 'role'));
    }

    /**
     * Actualizar rol de un usuario
     * PATCH /admin/users/{user}/role
     */
    public function updateUserRole(Request $request, User $user)
    {
        $this->checkAdmin();
        
        $validated = $request->validate([
            'role' => 'required|in:client,professional,admin',
        ], [
            'role.required' => 'El rol es requerido.',
            'role.in' => 'El rol debe ser: cliente, profesional o administrador.',
        ]);
        
        $user->role = $validated['role'];
        $user->save();
        
        return back()->with('success', 'Rol de usuario actualizado correctamente.');
    }

    /**
     * Eliminar usuario
     * DELETE /admin/users/{user}
     */
    public function deleteUser(User $user)
    {
        $this->checkAdmin();
        
        // No permitir eliminar al propio admin
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'No puedes eliminarte a ti mismo.']);
        }
        
        $user->delete();
        
        return back()->with('success', 'Usuario eliminado correctamente.');
    }

    /**
     * Gestión de servicios
     * GET /admin/services
     */
    public function services(Request $request)
    {
        $this->checkAdmin();
        
        $search = $request->get('search');
        $category = $request->get('category');
        
        $services = Service::query()
            ->with(['user', 'category'])
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->when($category, function ($query, $category) {
                return $query->where('category_id', $category);
            })
            ->withCount('bookings')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $categories = Category::all();
        
        return view('admin.services', compact('services', 'categories', 'search', 'category'));
    }

    /**
     * Eliminar servicio
     * DELETE /admin/services/{service}
     */
    public function deleteService(Service $service)
    {
        $this->checkAdmin();
        
        $service->delete();
        
        return back()->with('success', 'Servicio eliminado correctamente.');
    }

    /**
     * Gestión de reservas
     * GET /admin/bookings
     */
    public function bookings(Request $request)
    {
        $this->checkAdmin();
        
        $status = $request->get('status');
        $search = $request->get('search');
        
        $bookings = Booking::query()
            ->with(['client', 'professional', 'service'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($search, function ($query, $search) {
                return $query->whereHas('client', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('professional', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.bookings', compact('bookings', 'status', 'search'));
    }

    /**
     * Gestión de categorías
     * GET /admin/categories
     */
    public function categories()
    {
        $this->checkAdmin();
        
        $categories = Category::withCount('services')->get();
        
        return view('admin.categories', compact('categories'));
    }

    /**
     * Crear categoría
     * POST /admin/categories
     */
    public function storeCategory(Request $request)
    {
        $this->checkAdmin();
        
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:500',
            'icon' => 'required|string|max:50',
        ], [
            'name.required' => 'El nombre de la categoría es requerido.',
            'name.max' => 'El nombre no puede exceder 100 caracteres.',
            'name.unique' => 'Ya existe una categoría con este nombre.',
            'description.max' => 'La descripción no puede exceder 500 caracteres.',
            'icon.required' => 'El icono es requerido.',
            'icon.max' => 'El icono no puede exceder 50 caracteres.',
        ]);
        
        Category::create($validated);
        
        return back()->with('success', 'Categoría creada correctamente.');
    }

    /**
     * Actualizar categoría
     * PUT /admin/categories/{category}
     */
    public function updateCategory(Request $request, Category $category)
    {
        $this->checkAdmin();
        
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'icon' => 'required|string|max:50',
        ], [
            'name.required' => 'El nombre de la categoría es requerido.',
            'name.max' => 'El nombre no puede exceder 100 caracteres.',
            'name.unique' => 'Ya existe una categoría con este nombre.',
            'description.max' => 'La descripción no puede exceder 500 caracteres.',
            'icon.required' => 'El icono es requerido.',
            'icon.max' => 'El icono no puede exceder 50 caracteres.',
        ]);
        
        $category->update($validated);
        
        return back()->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Eliminar categoría
     * DELETE /admin/categories/{category}
     */
    public function deleteCategory(Category $category)
    {
        $this->checkAdmin();
        
        // Verificar que no tenga servicios asociados
        if ($category->services()->count() > 0) {
            return back()->withErrors(['error' => 'No se puede eliminar una categoría con servicios asociados.']);
        }
        
        $category->delete();
        
        return back()->with('success', 'Categoría eliminada correctamente.');
    }
}
