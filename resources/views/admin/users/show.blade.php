{{-- 
    Vista: Detalle de Usuario (Admin)
    Descripción: Información completa de un usuario con opciones de gestión
    Ruta: GET /admin/users/{user}
--}}
@extends('layouts.marketplace')

@section('title', 'Usuario: ' . $user->name . ' - Admin')

@section('content')
    {{-- Header --}}
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('admin.users.index') }}" 
                   class="text-white hover:text-indigo-100">
                    <i class="fas fa-arrow-left text-2xl"></i>
                </a>
                <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center text-white text-2xl font-bold">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-4xl font-bold">{{ $user->name }}</h1>
                    <p class="text-indigo-100">{{ $user->email }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Contenido --}}
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-3 gap-8">
                {{-- Columna Principal --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Información Personal --}}
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Información Personal</h2>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-gray-500">Nombre Completo</label>
                                <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-500">Email</label>
                                <p class="font-semibold text-gray-800">{{ $user->email }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-500">Teléfono</label>
                                <p class="font-semibold text-gray-800">{{ $user->phone ?? 'No especificado' }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-500">Ciudad</label>
                                <p class="font-semibold text-gray-800">{{ $user->city ?? 'No especificado' }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-500">Fecha de Registro</label>
                                <p class="font-semibold text-gray-800">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-500">Última Actualización</label>
                                <p class="font-semibold text-gray-800">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($user->bio)
                            <div class="mt-4">
                                <label class="text-sm text-gray-500">Biografía</label>
                                <p class="text-gray-700 mt-1">{{ $user->bio }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Servicios (si es profesional) --}}
                    @if($user->isPro())
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                                Servicios Ofrecidos ({{ $user->services->count() }})
                            </h2>
                            @if($user->services->count() > 0)
                                <div class="grid md:grid-cols-2 gap-4">
                                    @foreach($user->services as $service)
                                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                                            <h3 class="font-semibold text-gray-800 mb-1">{{ $service->title }}</h3>
                                            <p class="text-sm text-gray-600 mb-2">{{ $service->category->name }}</p>
                                            <p class="text-blue-600 font-bold">{{ number_format($service->price, 2) }}€/h</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">No ha publicado servicios aún</p>
                            @endif
                        </div>
                    @endif

                    {{-- Reservas --}}
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Reservas</h2>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <p class="text-sm text-blue-600 mb-1">Como Cliente</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $user->bookingsAsClient->count() }}</p>
                            </div>
                            @if($user->isPro())
                                <div class="bg-purple-50 rounded-lg p-4">
                                    <p class="text-sm text-purple-600 mb-1">Como Profesional</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $user->bookingsAsPro->count() }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Reseñas --}}
                    @if($user->isPro())
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h2 class="text-2xl font-bold text-gray-800 mb-4">Reseñas Recibidas</h2>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="bg-yellow-50 rounded-lg p-4">
                                    <p class="text-sm text-yellow-600 mb-1">Total de Reseñas</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $user->reviewsReceived->count() }}</p>
                                </div>
                                <div class="bg-green-50 rounded-lg p-4">
                                    <p class="text-sm text-green-600 mb-1">Calificación Promedio</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ number_format($user->averageRating(), 1) }} / 5</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Columna Lateral - Acciones --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24 space-y-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Acciones de Administración</h3>
                        
                        {{-- Cambiar Rol --}}
                        <div class="border-b pb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Cambiar Rol</label>
                            <form action="{{ route('admin.users.updateRole', $user) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="role" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 mb-2">
                                    <option value="client" {{ $user->role == 'client' ? 'selected' : '' }}>Cliente</option>
                                    <option value="pro" {{ $user->role == 'pro' ? 'selected' : '' }}>Profesional</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrador</option>
                                </select>
                                <button type="submit" 
                                        class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-lg font-semibold transition duration-200">
                                    <i class="fas fa-save mr-2"></i>
                                    Guardar Rol
                                </button>
                            </form>
                        </div>

                        {{-- Rol Actual --}}
                        <div class="border-b pb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Rol Actual</label>
                            <span class="inline-block px-4 py-2 rounded-lg text-sm font-semibold w-full text-center
                                {{ $user->role === 'admin' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $user->role === 'pro' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $user->role === 'client' ? 'bg-green-100 text-green-700' : '' }}">
                                {{ $user->role === 'admin' ? 'Administrador' : ($user->role === 'pro' ? 'Profesional' : 'Cliente') }}
                            </span>
                        </div>

                        {{-- Ver Perfil Público --}}
                        @if($user->isPro())
                            <a href="{{ route('professionals.show', $user) }}" 
                               class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-3 rounded-lg font-semibold transition duration-200">
                                <i class="fas fa-eye mr-2"></i>
                                Ver Perfil Público
                            </a>
                        @endif

                        {{-- Zona de Peligro --}}
                        @if($user->id !== auth()->id())
                            <div class="border-t pt-4">
                                <h4 class="text-sm font-semibold text-red-600 mb-3">Zona de Peligro</h4>
                                <form action="{{ route('admin.users.destroy', $user) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition duration-200">
                                        <i class="fas fa-trash mr-2"></i>
                                        Eliminar Usuario
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="border-t pt-4">
                                <p class="text-sm text-gray-500 italic">No puedes eliminarte a ti mismo</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
