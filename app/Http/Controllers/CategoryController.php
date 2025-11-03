<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Muestra la lista de todas las categorías.
     * 
     * GET /categories
     */
    public function index()
    {
        // Obtiene todas las categorías con el conteo de servicios
        $categories = Category::withCount('services')->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Muestra los servicios de una categoría específica.
     * 
     * GET /categories/{id}/services
     */
    public function show(Category $category)
    {
        // Carga la categoría con sus servicios y las relaciones necesarias
        $category->load([
            'services.user',      // Profesional que ofrece el servicio
            'services.photos',    // Fotos del servicio
        ]);

        return view('categories.show', compact('category'));
    }
}
