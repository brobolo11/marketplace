@extends('layouts.marketplace')

@section('title', 'Solicitudes Pendientes - HouseFixes')

@section('content')
    {{-- Header --}}
    <section class="bg-gradient-to-r from-orange-600 to-red-600 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Solicitudes Pendientes</h1>
                    <p class="text-orange-100">Gestiona las solicitudes de reserva de tus servicios</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Contenido --}}
    <section class="py-12 bg-gradient-to-b from-gray-50 to-gray-100 min-h-screen">
        <div class="container mx-auto px-4">
            
            {{-- Estadísticas rápidas --}}
            <div class="grid md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ $pendingBookings->count() }}</p>
                            <p class="text-sm text-gray-600">Pendientes</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ $acceptedToday }}</p>
                            <p class="text-sm text-gray-600">Aceptadas hoy</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($estimatedRevenue, 2) }}€</p>
                            <p class="text-sm text-gray-600">Ingreso potencial</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($pendingBookings->count() > 0)
                {{-- Lista de solicitudes --}}
                <div class="space-y-6">
                    @foreach($pendingBookings as $booking)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200 hover:shadow-2xl transition-all duration-300">
                            <div class="p-6">
                                <div class="flex flex-col lg:flex-row gap-6">
                                    {{-- Información del cliente --}}
                                    <div class="flex items-start gap-4 flex-1">
                                        @if($booking->user->profile_photo_path)
                                            <img src="{{ $booking->user->profile_photo_path }}" 
                                                 alt="{{ $booking->user->name }}" 
                                                 class="w-16 h-16 rounded-full object-cover border-4 border-gray-100">
                                        @else
                                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xl font-bold border-4 border-gray-100">
                                                {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                                            </div>
                                        @endif

                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <h3 class="text-xl font-bold text-gray-800">{{ $booking->user->name }}</h3>
                                                <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-xs font-semibold">
                                                    Pendiente
                                                </span>
                                            </div>
                                            
                                            <div class="space-y-2 text-sm text-gray-600">
                                                <p class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <span>{{ $booking->user->email }}</span>
                                                </p>
                                                @if($booking->user->phone)
                                                    <p class="flex items-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                        </svg>
                                                        <span>{{ $booking->user->phone }}</span>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Información del servicio --}}
                                    <div class="lg:w-1/3 bg-gradient-to-br from-blue-50 to-purple-50 rounded-lg p-4">
                                        <div class="flex items-center gap-2 mb-3">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            <h4 class="font-semibold text-gray-800">{{ $booking->service->title }}</h4>
                                        </div>
                                        
                                        <div class="space-y-2 text-sm">
                                            <div class="flex items-center justify-between">
                                                <span class="text-gray-600">Fecha:</span>
                                                <span class="font-semibold text-gray-800">
                                                    {{ \Carbon\Carbon::parse($booking->datetime)->format('d/m/Y') }}
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-gray-600">Hora:</span>
                                                <span class="font-semibold text-gray-800">
                                                    {{ \Carbon\Carbon::parse($booking->datetime)->format('H:i') }}
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-gray-600">Duración:</span>
                                                <span class="font-semibold text-gray-800">{{ $booking->service->duration }} min</span>
                                            </div>
                                            <div class="flex items-center justify-between pt-2 border-t border-blue-200">
                                                <span class="text-gray-600">Precio:</span>
                                                <span class="text-2xl font-bold text-blue-600">{{ number_format($booking->total_price, 2) }}€</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Descripción del cliente --}}
                                @if($booking->description)
                                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                        <p class="text-sm font-semibold text-gray-700 mb-1">Descripción del cliente:</p>
                                        <p class="text-gray-600">{{ $booking->description }}</p>
                                    </div>
                                @endif

                                {{-- Dirección --}}
                                <div class="mt-4 p-4 bg-blue-50 rounded-lg flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">Dirección del servicio:</p>
                                        <p class="text-gray-600">{{ $booking->address }}</p>
                                    </div>
                                </div>

                                {{-- Botones de acción --}}
                                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                                    <form action="{{ route('bookings.approve', $booking) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white py-3 px-6 rounded-lg font-semibold transition duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Aceptar Solicitud
                                        </button>
                                    </form>

                                    <button 
                                        @click="$dispatch('open-reject-modal-{{ $booking->id }}')"
                                        type="button"
                                        class="flex-1 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white py-3 px-6 rounded-lg font-semibold transition duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Rechazar
                                    </button>

                                    {{-- Modal de rechazo --}}
                                    <div x-data="{ openReject: false }" 
                                         @open-reject-modal-{{ $booking->id }}.window="openReject = true"
                                         x-show="openReject"
                                         x-cloak
                                         class="fixed inset-0 z-50 overflow-y-auto"
                                         style="display: none;">
                                        
                                        <div class="fixed inset-0 bg-gray-900 bg-opacity-75" @click="openReject = false"></div>
                                        
                                        <div class="flex min-h-full items-center justify-center p-4">
                                            <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6" @click.away="openReject = false">
                                                <h3 class="text-xl font-bold text-gray-800 mb-4">Motivo del rechazo</h3>
                                                
                                                <form action="{{ route('bookings.reject', $booking) }}" method="POST">
                                                    @csrf
                                                    <textarea 
                                                        name="rejection_reason" 
                                                        required
                                                        rows="4"
                                                        placeholder="Explica brevemente por qué rechazas esta solicitud..."
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none mb-4"></textarea>
                                                    
                                                    <div class="flex gap-3">
                                                        <button 
                                                            @click="openReject = false"
                                                            type="button"
                                                            class="flex-1 bg-gray-200 text-gray-700 py-2 px-4 rounded-lg font-semibold hover:bg-gray-300 transition">
                                                            Cancelar
                                                        </button>
                                                        <button 
                                                            type="submit"
                                                            class="flex-1 bg-red-600 text-white py-2 px-4 rounded-lg font-semibold hover:bg-red-700 transition">
                                                            Rechazar
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tiempo transcurrido --}}
                                <div class="mt-4 text-xs text-gray-500 text-center">
                                    Solicitud recibida {{ $booking->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Sin solicitudes --}}
                <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">No tienes solicitudes pendientes</h3>
                    <p class="text-gray-600 mb-6">Cuando los clientes soliciten tus servicios, aparecerán aquí.</p>
                    <a href="{{ route('services.index') }}" 
                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                        Ver Mis Servicios
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection
