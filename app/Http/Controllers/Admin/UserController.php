<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Muestra la lista de usuarios
     * 
     * GET /admin/users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filtro por rol
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        // Búsqueda
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Muestra el detalle de un usuario
     * 
     * GET /admin/users/{user}
     */
    public function show(User $user)
    {
        $user->load(['services', 'bookingsAsClient', 'bookingsAsPro', 'reviewsGiven', 'reviewsReceived']);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Actualiza el rol de un usuario
     * 
     * PATCH /admin/users/{user}/role
     */
    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:client,pro,admin',
        ]);

        $user->update(['role' => $validated['role']]);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Rol actualizado correctamente.');
    }

    /**
     * Elimina un usuario
     * 
     * DELETE /admin/users/{user}
     */
    public function destroy(User $user)
    {
        // No permitir que el admin se elimine a sí mismo
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'No puedes eliminarte a ti mismo.']);
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
