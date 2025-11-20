{{-- 
    Vista: Dashboard Administrativo
    Descripción: Panel principal del administrador con estadísticas y métricas
    Ruta: GET /admin/dashboard
--}}
@extends('layouts.marketplace')

@section('title', 'Panel Administrativo - HouseFixes')

@section('content')
    {{-- Header --}}
    <section class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">
                        <i class="fas fa-chart-line mr-3"></i>
                        Panel Administrativo
                    </h1>
                    <p class="text-xl text-purple-100">
                        Gestión y estadísticas de la plataforma
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-purple-200">Bienvenido</p>
                    <p class="text-xl font-semibold">{{ auth()->user()->name }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Estadísticas Principales --}}
    <section class="py-8 bg-gray-50">
        <div class="container mx-auto px-4">
            {{-- Enlaces Rápidos --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <a href="{{ route('admin.users.index') }}" class="bg-white hover:bg-blue-50 rounded-lg shadow p-4 transition duration-200">
                    <i class="fas fa-users text-blue-600 text-2xl mb-2"></i>
                    <p class="font-semibold text-gray-800">Gestionar Usuarios</p>
                </a>
                <a href="{{ route('admin.services.index') }}" class="bg-white hover:bg-green-50 rounded-lg shadow p-4 transition duration-200">
                    <i class="fas fa-briefcase text-green-600 text-2xl mb-2"></i>
                    <p class="font-semibold text-gray-800">Gestionar Servicios</p>
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="bg-white hover:bg-orange-50 rounded-lg shadow p-4 transition duration-200">
                    <i class="fas fa-calendar-check text-orange-600 text-2xl mb-2"></i>
                    <p class="font-semibold text-gray-800">Gestionar Reservas</p>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="bg-white hover:bg-purple-50 rounded-lg shadow p-4 transition duration-200">
                    <i class="fas fa-layer-group text-purple-600 text-2xl mb-2"></i>
                    <p class="font-semibold text-gray-800">Gestionar Categorías</p>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- Total Usuarios --}}
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Total Usuarios</p>
                            <p class="text-3xl font-bold text-gray-800">{{ number_format($totalUsers) }}</p>
                            <div class="mt-2 flex gap-3 text-xs">
                                <span class="text-purple-600">
                                    <i class="fas fa-user-tie"></i> {{ $totalProfessionals }} pros
                                </span>
                                <span class="text-blue-600">
                                    <i class="fas fa-user"></i> {{ $totalClients }} clientes
                                </span>
                            </div>
                        </div>
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-users text-3xl text-blue-600"></i>
                        </div>
                    </div>
                </div>

                {{-- Total Servicios --}}
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Total Servicios</p>
                            <p class="text-3xl font-bold text-gray-800">{{ number_format($totalServices) }}</p>
                            <p class="mt-2 text-xs text-green-600">
                                <i class="fas fa-arrow-up"></i> Activos en plataforma
                            </p>
                        </div>
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-briefcase text-3xl text-green-600"></i>
                        </div>
                    </div>
                </div>

                {{-- Total Reservas --}}
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Total Reservas</p>
                            <p class="text-3xl font-bold text-gray-800">{{ number_format($totalBookings) }}</p>
                            <p class="mt-2 text-xs text-orange-600">
                                <i class="fas fa-clock"></i> {{ $pendingBookings }} pendientes
                            </p>
                        </div>
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar-check text-3xl text-orange-600"></i>
                        </div>
                    </div>
                </div>

                {{-- Ingresos Totales --}}
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Ingresos Totales</p>
                            <p class="text-3xl font-bold text-gray-800">{{ number_format($totalRevenue, 2) }}€</p>
                            <p class="mt-2 text-xs text-purple-600">
                                <i class="fas fa-check-circle"></i> {{ $completedBookings }} completadas
                            </p>
                        </div>
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-euro-sign text-3xl text-purple-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Estado de Reservas --}}
            <div class="grid lg:grid-cols-2 gap-6 mb-8">
                {{-- Gráfico de Estados --}}
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">
                        <i class="fas fa-chart-pie mr-2 text-indigo-600"></i>
                        Estado de Reservas
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Pendientes</span>
                                <span class="text-sm font-bold text-orange-600">{{ $pendingBookings }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-orange-500 h-3 rounded-full" style="width: {{ $totalBookings > 0 ? ($pendingBookings / $totalBookings * 100) : 0 }}%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Aceptadas</span>
                                <span class="text-sm font-bold text-blue-600">{{ $acceptedBookings }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-blue-500 h-3 rounded-full" style="width: {{ $totalBookings > 0 ? ($acceptedBookings / $totalBookings * 100) : 0 }}%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Completadas</span>
                                <span class="text-sm font-bold text-green-600">{{ $completedBookings }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-green-500 h-3 rounded-full" style="width: {{ $totalBookings > 0 ? ($completedBookings / $totalBookings * 100) : 0 }}%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Canceladas</span>
                                <span class="text-sm font-bold text-red-600">{{ $cancelledBookings }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-red-500 h-3 rounded-full" style="width: {{ $totalBookings > 0 ? ($cancelledBookings / $totalBookings * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Servicios por Categoría --}}
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">
                        <i class="fas fa-layer-group mr-2 text-indigo-600"></i>
                        Servicios por Categoría
                    </h3>
                    <div class="space-y-3">
                        @forelse($servicesByCategory as $item)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white">
                                        <i class="fas fa-{{ $item->category->icon }}"></i>
                                    </div>
                                    <span class="font-medium text-gray-700">{{ $item->category->name }}</span>
                                </div>
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ $item->total }}
                                </span>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No hay datos disponibles</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Últimas Reservas --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-history mr-2 text-indigo-600"></i>
                            Últimas Reservas
                        </h3>
                        <a href="{{ route('admin.bookings.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                            Ver todas <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Profesional</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentBookings as $booking)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $booking->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $booking->client->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $booking->professional->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ Str::limit($booking->service->title, 30) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->datetime->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-orange-100 text-orange-700',
                                                'accepted' => 'bg-blue-100 text-blue-700',
                                                'completed' => 'bg-green-100 text-green-700',
                                                'cancelled' => 'bg-red-100 text-red-700',
                                                'rejected' => 'bg-gray-100 text-gray-700',
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ number_format($booking->total_price, 2) }}€</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                        No hay reservas recientes
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
@endsection
