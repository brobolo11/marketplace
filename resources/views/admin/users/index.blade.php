{{-- 
    Vista: Gestión de Usuarios (Admin)
    Descripción: Lista de todos los usuarios del sistema con filtros
    Ruta: GET /admin/users
--}}
@extends('layouts.marketplace')

@section('title', 'Gestión de Usuarios - Admin')

@section('content')
    {{-- Header --}}
    <section class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">
                        <i class="fas fa-users-cog mr-2"></i>
                        Gestión de Usuarios
                    </h1>
                    <p class="text-xl text-purple-100">
                        Administra todos los usuarios del sistema
                    </p>
                </div>
                <a href="{{ route('admin.dashboard') }}" 
                   class="bg-white text-purple-600 hover:bg-purple-50 px-6 py-3 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Dashboard
                </a>
            </div>
        </div>
    </section>

    {{-- Filtros --}}
    <section class="bg-white shadow-md sticky top-16 z-40">
        <div class="container mx-auto px-4 py-6">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Buscar por nombre, email, ciudad..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div class="min-w-[150px]">
                    <select name="role" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Todos los roles</option>
                        <option value="client" {{ request('role') == 'client' ? 'selected' : '' }}>Clientes</option>
                        <option value="pro" {{ request('role') == 'pro' ? 'selected' : '' }}>Profesionales</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administradores</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" 
                            class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                        <i class="fas fa-search mr-2"></i>
                        Buscar
                    </button>
                    
                    @if(request()->hasAny(['search', 'role']))
                        <a href="{{ route('admin.users.index') }}" 
                           class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold transition duration-200">
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </section>

    {{-- Lista de Usuarios --}}
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Usuario</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Rol</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Ciudad</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Registro</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                                @if($user->phone)
                                                    <p class="text-xs text-gray-500">{{ $user->phone }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                            {{ $user->role === 'admin' ? 'bg-red-100 text-red-700' : '' }}
                                            {{ $user->role === 'pro' ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ $user->role === 'client' ? 'bg-green-100 text-green-700' : '' }}">
                                            {{ $user->role === 'admin' ? 'Admin' : ($user->role === 'pro' ? 'Profesional' : 'Cliente') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $user->city ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $user->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('admin.users.show', $user) }}" 
                                           class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                                            Ver detalles
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        No se encontraron usuarios
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="px-6 py-4 border-t">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

            {{-- Resumen --}}
            <div class="mt-6 text-center text-gray-600">
                Mostrando {{ $users->count() }} de {{ $users->total() }} usuarios
            </div>
        </div>
    </section>
@endsection
