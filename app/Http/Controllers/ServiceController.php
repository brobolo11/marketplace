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
        if ($request->filled('category_id')) {
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
        // Autorizar con Policy
        $this->authorize('create', Service::class);

        // Validación de datos
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'price' => 'required|numeric|min:0|max:999999.99',
            'duration' => 'required|integer|in:30,60,90,120,180,240,480',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB
        ], [
            'category_id.required' => 'La categoría es requerida',
            'title.required' => 'El título es requerido',
            'title.max' => 'El título no puede exceder 100 caracteres',
            'description.required' => 'La descripción es requerida',
            'description.max' => 'La descripción no puede exceder 500 caracteres',
            'price.required' => 'El precio es requerido',
            'price.min' => 'El precio debe ser mayor a 0',
            'duration.required' => 'La duración es requerida',
            'photos.*.max' => 'Cada imagen no puede exceder 5MB',
        ]);

        // Crea el servicio
        $service = Auth::user()->services()->create($validated);

        // Procesa las fotos si existen
        if ($request->hasFile('photos')) {
            $photosUploaded = 0;
            foreach ($request->file('photos') as $photo) {
                if ($photosUploaded >= 5) break; // Máximo 5 fotos
                
                $path = $photo->store('service_photos', 'public');
                $service->photos()->create(['path' => $path]);
                $photosUploaded++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Servicio creado exitosamente',
            'service' => $service->load('category', 'photos')
        ], 201);
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
        // Autorizar con Policy
        $this->authorize('update', $service);

        // Validación de datos
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'price' => 'required|numeric|min:0|max:999999.99',
            'duration' => 'required|integer|in:30,60,90,120,180,240,480',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'existing_photos' => 'nullable|json',
        ]);

        // Actualiza el servicio
        $service->update($validated);

        // Gestionar fotos existentes (eliminar las que no están en la lista)
        if ($request->has('existing_photos')) {
            $existingPhotoIds = json_decode($request->existing_photos, true);
            $photosToDelete = $service->photos()->whereNotIn('id', $existingPhotoIds)->get();
            
            foreach ($photosToDelete as $photo) {
                Storage::disk('public')->delete($photo->path);
                $photo->delete();
            }
        }

        // Procesa nuevas fotos si existen
        if ($request->hasFile('photos')) {
            $currentPhotos = $service->photos()->count();
            $maxPhotos = 5 - $currentPhotos;
            $photosUploaded = 0;
            
            foreach ($request->file('photos') as $photo) {
                if ($photosUploaded >= $maxPhotos) break;
                
                $path = $photo->store('service_photos', 'public');
                $service->photos()->create(['path' => $path]);
                $photosUploaded++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Servicio actualizado exitosamente',
            'service' => $service->load('category', 'photos')
        ], 200);
    }

    /**
     * Elimina un servicio de la base de datos.
     * 
     * DELETE /services/{id}
     */
    public function destroy(Service $service)
    {
        // Autorizar con Policy
        $this->authorize('delete', $service);

        // Verificar si tiene reservas activas
        $activeBookings = $service->bookings()
            ->whereIn('status', ['pending', 'accepted'])
            ->count();
        
        if ($activeBookings > 0) {
            return redirect()
                ->route('services.index')
                ->with('error', 'No puedes eliminar un servicio con reservas activas.');
        }

        // Elimina las fotos del almacenamiento
        foreach ($service->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
        }

        // Elimina el servicio (las fotos se eliminan en cascada)
        $service->delete();

        return redirect()
            ->route('services.index')
            ->with('success', 'Servicio eliminado exitosamente.');
    }
}
