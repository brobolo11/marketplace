<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    /**
     * Muestra la lista de todos los servicios.
     * 
     * GET /services
     */
    public function index(Request $request)
    {
        // Query base
        $query = Service::with(['user', 'category', 'photos']);

        // Filtro por categoría
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filtro por ciudad del profesional
        if ($request->has('city')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('city', 'like', '%' . $request->city . '%');
            });
        }

        // Filtro por rango de precio
        if ($request->has('min_price')) {
            $query->where('price_hour', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price_hour', '<=', $request->max_price);
        }

        // Búsqueda por texto en título o descripción
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $services = $query->paginate(12);
        $categories = Category::all();

        return view('services.index', compact('services', 'categories'));
    }

    /**
     * Muestra el detalle de un servicio específico.
     * 
     * GET /services/{id}
     */
    public function show(Service $service)
    {
        // Carga las relaciones necesarias
        $service->load([
            'user',           // Profesional
            'category',       // Categoría
            'photos',         // Fotos del servicio
            'user.reviewsReceived.client',  // Reseñas del profesional
        ]);

        return view('services.show', compact('service'));
    }

    /**
     * Muestra el formulario para crear un nuevo servicio.
     * Solo para profesionales autenticados.
     * 
     * GET /services/create
     */
    public function create()
    {
        // Verifica que el usuario sea profesional
        if (!Auth::user()->isPro()) {
            abort(403, 'Solo los profesionales pueden crear servicios.');
        }

        $categories = Category::all();
        return view('services.create', compact('categories'));
    }

    /**
     * Guarda un nuevo servicio en la base de datos.
     * 
     * POST /services
     */
    public function store(Request $request)
    {
        // Verifica que el usuario sea profesional
        if (!Auth::user()->isPro()) {
            abort(403, 'Solo los profesionales pueden crear servicios.');
        }

        // Validación de datos
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price_hour' => 'required|numeric|min:0|max:999999.99',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Crea el servicio
        $service = Auth::user()->services()->create($validated);

        // Procesa las fotos si existen
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('service_photos', 'public');
                $service->photos()->create(['image_path' => $path]);
            }
        }

        return redirect()
            ->route('services.show', $service)
            ->with('success', 'Servicio creado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un servicio existente.
     * 
     * GET /services/{id}/edit
     */
    public function edit(Service $service)
    {
        // Verifica que el usuario sea el propietario del servicio
        if (Auth::id() !== $service->user_id) {
            abort(403, 'No tienes permiso para editar este servicio.');
        }

        $categories = Category::all();
        return view('services.edit', compact('service', 'categories'));
    }

    /**
     * Actualiza un servicio existente en la base de datos.
     * 
     * PUT /services/{id}
     */
    public function update(Request $request, Service $service)
    {
        // Verifica que el usuario sea el propietario del servicio
        if (Auth::id() !== $service->user_id) {
            abort(403, 'No tienes permiso para actualizar este servicio.');
        }

        // Validación de datos
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price_hour' => 'required|numeric|min:0|max:999999.99',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Actualiza el servicio
        $service->update($validated);

        // Procesa nuevas fotos si existen
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('service_photos', 'public');
                $service->photos()->create(['image_path' => $path]);
            }
        }

        return redirect()
            ->route('services.show', $service)
            ->with('success', 'Servicio actualizado exitosamente.');
    }

    /**
     * Elimina un servicio de la base de datos.
     * 
     * DELETE /services/{id}
     */
    public function destroy(Service $service)
    {
        // Verifica que el usuario sea el propietario del servicio
        if (Auth::id() !== $service->user_id) {
            abort(403, 'No tienes permiso para eliminar este servicio.');
        }

        // Elimina las fotos del almacenamiento
        foreach ($service->photos as $photo) {
            Storage::disk('public')->delete($photo->image_path);
        }

        // Elimina el servicio (las fotos se eliminan en cascada)
        $service->delete();

        return redirect()
            ->route('services.index')
            ->with('success', 'Servicio eliminado exitosamente.');
    }
}
