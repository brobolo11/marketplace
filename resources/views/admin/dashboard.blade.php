{{-- 
    Vista: Dashboard Administrativo
    Descripción: Panel de control para administradores con estadísticas del sistema
    Ruta: GET /admin/dashboard
--}}
@extends('layouts.marketplace')

@section('title', 'Dashboard Admin - Servicios Pro')

@section('content')
    {{-- Header --}}
    <section class="bg-gradient-to-r from-red-600 to-pink-600 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Panel de Administración
                    </h1>
                    <p class="text-xl text-red-100">
                        Gestión completa del sistema
                    </p>
                </div>
                <a href="{{ route('dashboard') }}" 
                   class="bg-white text-red-600 hover:bg-red-50 px-6 py-3 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver al Dashboard
                </a>
            </div>
        </div>
    </section>

    {{-- Estadísticas Generales --}}
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Estadísticas Generales</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6 mb-12">
                {{-- Total Usuarios --}}
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 text-2xl"></i>
                        </div>
                        <span class="text-sm text-gray-500">Total</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-800 mb-1">{{ $stats['total_users'] }}</div>
                    <div class="text-sm text-gray-600">Usuarios</div>
                </div>

                {{-- Clientes --}}
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user text-green-600 text-2xl"></i>
                        </div>
                        <span class="text-sm text-gray-500">Clientes</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-800 mb-1">{{ $stats['total_clients'] }}</div>
                    <div class="text-sm text-gray-600">Registrados</div>
                </div>

                {{-- Profesionales --}}
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-tie text-purple-600 text-2xl"></i>
                        </div>
                        <span class="text-sm text-gray-500">Profesionales</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-800 mb-1">{{ $stats['total_professionals'] }}</div>
                    <div class="text-sm text-gray-600">Activos</div>
                </div>

                {{-- Servicios --}}
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-briefcase text-indigo-600 text-2xl"></i>
                        </div>
                        <span class="text-sm text-gray-500">Servicios</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-800 mb-1">{{ $stats['total_services'] }}</div>
                    <div class="text-sm text-gray-600">Publicados</div>
                </div>

                {{-- Reservas Totales --}}
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-check text-yellow-600 text-2xl"></i>
                        </div>
                        <span class="text-sm text-gray-500">Reservas</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-800 mb-1">{{ $stats['total_bookings'] }}</div>
                    <div class="text-sm text-gray-600">Total</div>
                </div>

                {{-- Reservas Pendientes --}}
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-hourglass-half text-orange-600 text-2xl"></i>
                        </div>
                        <span class="text-sm text-gray-500">Pendientes</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-800 mb-1">{{ $stats['pending_bookings'] }}</div>
                    <div class="text-sm text-gray-600">Reservas</div>
                </div>

                {{-- Reseñas --}}
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-star text-pink-600 text-2xl"></i>
                        </div>
                        <span class="text-sm text-gray-500">Reseñas</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-800 mb-1">{{ $stats['total_reviews'] }}</div>
                    <div class="text-sm text-gray-600">Publicadas</div>
                </div>

                {{-- Ingresos del mes --}}
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-euro-sign text-emerald-600 text-2xl"></i>
                        </div>
                        <span class="text-sm text-gray-500">Este mes</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($monthlyRevenue, 0) }}€</div>
                    <div class="text-sm text-gray-600">Ingresos</div>
                </div>
            </div>

            {{-- Acciones Rápidas --}}
            <div class="grid md:grid-cols-3 gap-6 mb-12">
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition duration-300">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white text-2xl">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Gestionar Usuarios</h3>
                            <p class="text-sm text-gray-600">Ver, editar y eliminar usuarios</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('services.index') }}" 
                   class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition duration-300">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center text-white text-2xl">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Ver Servicios</h3>
                            <p class="text-sm text-gray-600">Todos los servicios publicados</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('bookings.index') }}" 
                   class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition duration-300">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center text-white text-2xl">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Ver Reservas</h3>
                            <p class="text-sm text-gray-600">Todas las reservas del sistema</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="grid lg:grid-cols-2 gap-8">
                {{-- Últimos Usuarios Registrados --}}
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Últimos Usuarios Registrados</h3>
                    <div class="space-y-3">
                        @foreach($recentUsers->take(5) as $user)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $user->role === 'admin' ? 'bg-red-100 text-red-700' : '' }}
                                        {{ $user->role === 'pro' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $user->role === 'client' ? 'bg-green-100 text-green-700' : '' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('admin.users.index') }}" 
                       class="block text-center mt-4 text-blue-600 hover:text-blue-700 font-semibold">
                        Ver todos los usuarios →
                    </a>
                </div>

                {{-- Últimas Reservas --}}
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Últimas Reservas</h3>
                    <div class="space-y-3">
                        @foreach($recentBookings->take(5) as $booking)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="font-semibold text-gray-800 text-sm">{{ $booking->service->title }}</p>
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $booking->status === 'confirmed' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $booking->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-xs text-gray-600">
                                    <span>{{ $booking->client->name }} → {{ $booking->professional->name }}</span>
                                    <span>{{ number_format($booking->total_price, 2) }}€</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
