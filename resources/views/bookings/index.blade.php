{{-- 
    Vista: Mis Reservas
    Descripción: Muestra las reservas del usuario (como cliente o profesional)
    Ruta: GET /bookings
--}}
@extends('layouts.marketplace')

@section('title', 'Mis Reservas - HouseFixes')

@section('content')
    {{-- Header --}}
    <section class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-bold mb-2 text-center">
                Mis Reservas
            </h1>
            <p class="text-xl text-blue-100 text-center">
                @if(Auth::user()->isPro())
                    Gestiona las reservas que has recibido
                @else
                    Gestiona tus reservas de servicios
                @endif
            </p>
        </div>
    </section>

    {{-- Filtros por Estado --}}
    <section class="bg-white shadow-md sticky top-16 z-40">
        <div class="container mx-auto px-4">
            <nav class="flex overflow-x-auto">
                <a href="{{ route('bookings.index') }}" 
                   class="px-6 py-4 font-semibold whitespace-nowrap {{ !request('status') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                    Todas
                    <span class="ml-2 bg-gray-200 px-2 py-1 rounded-full text-xs">{{ $bookings->total() }}</span>
                </a>
                <a href="{{ route('bookings.index', ['status' => 'pending']) }}" 
                   class="px-6 py-4 font-semibold whitespace-nowrap {{ request('status') == 'pending' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                    Pendientes
                </a>
                <a href="{{ route('bookings.index', ['status' => 'accepted']) }}" 
                   class="px-6 py-4 font-semibold whitespace-nowrap {{ request('status') == 'accepted' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                    Aceptadas
                </a>
                <a href="{{ route('bookings.index', ['status' => 'completed']) }}" 
                   class="px-6 py-4 font-semibold whitespace-nowrap {{ request('status') == 'completed' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                    Completadas
                </a>
                <a href="{{ route('bookings.index', ['status' => 'cancelled']) }}" 
                   class="px-6 py-4 font-semibold whitespace-nowrap {{ request('status') == 'cancelled' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                    Canceladas
                </a>
            </nav>
        </div>
    </section>

    {{-- Lista de Reservas --}}
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                @if($bookings->count() > 0)
                    <div class="space-y-6">
                        @foreach($bookings as $booking)
                            @php
                                $isPro = Auth::user()->isPro();
                                $otherUser = $isPro ? $booking->client : $booking->professional;
                                
                                // Badges de estado
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                    'accepted' => 'bg-blue-100 text-blue-800 border-blue-300',
                                    'completed' => 'bg-green-100 text-green-800 border-green-300',
                                    'cancelled' => 'bg-red-100 text-red-800 border-red-300',
                                    'rejected' => 'bg-red-100 text-red-800 border-red-300',
                                ];
                                
                                $statusLabels = [
                                    'pending' => 'Pendiente',
                                    'accepted' => 'Aceptada',
                                    'completed' => 'Completada',
                                    'cancelled' => 'Cancelada',
                                    'rejected' => 'Rechazada',
                                ];
                            @endphp
                            
                            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition duration-300 overflow-hidden">
                                <div class="p-6">
                                    <div class="flex flex-col lg:flex-row lg:items-center gap-6">
                                        {{-- Info del servicio --}}
                                        <div class="flex-1">
                                            <div class="flex items-start justify-between mb-3">
                                                <div>
                                                    <h3 class="text-xl font-semibold text-gray-800 mb-1">
                                                        {{ $booking->service->title }}
                                                    </h3>
                                                    <p class="text-sm text-gray-500">
                                                        <i class="fas fa-tag mr-1"></i>
                                                        {{ $booking->service->category->name }}
                                                    </p>
                                                </div>
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $statusColors[$booking->status] }}">
                                                    {{ $statusLabels[$booking->status] }}
                                                </span>
                                            </div>

                                            {{-- Información de la otra persona --}}
                                            <div class="flex items-center gap-3 mb-3 pb-3 border-b">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                                                    {{ substr($otherUser->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">{{ $isPro ? 'Cliente' : 'Profesional' }}</p>
                                                    <p class="font-medium text-gray-800">{{ $otherUser->name }}</p>
                                                </div>
                                            </div>

                                            {{-- Detalles de la reserva --}}
                                            <div class="grid md:grid-cols-2 gap-4 text-sm">
                                                <div class="flex items-center gap-2 text-gray-600">
                                                    <i class="fas fa-calendar-alt text-blue-600 w-5"></i>
                                                    <span>{{ $booking->datetime->format('d/m/Y') }}</span>
                                                </div>
                                                <div class="flex items-center gap-2 text-gray-600">
                                                    <i class="fas fa-clock text-blue-600 w-5"></i>
                                                    <span>{{ $booking->datetime->format('H:i') }}</span>
                                                </div>
                                                <div class="flex items-center gap-2 text-gray-600">
                                                    <i class="fas fa-map-marker-alt text-blue-600 w-5"></i>
                                                    <span class="truncate">{{ $booking->address }}</span>
                                                </div>
                                                <div class="flex items-center gap-2 text-gray-600">
                                                    <i class="fas fa-euro-sign text-blue-600 w-5"></i>
                                                    <span class="font-semibold text-gray-800">{{ number_format($booking->total_price, 2) }}€</span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Acciones --}}
                                        <div class="flex flex-col gap-2 lg:w-48">
                                            <a href="{{ route('bookings.show', $booking) }}" 
                                               class="bg-blue-600 hover:bg-blue-700 text-white text-center py-3 rounded-lg font-semibold transition duration-200">
                                                Ver Detalles
                                            </a>

                                            @if($isPro && $booking->status == 'pending')
                                                <form action="{{ route('bookings.approve', $booking) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-semibold transition duration-200 text-sm">
                                                        <i class="fas fa-check mr-1"></i>
                                                        Aceptar
                                                    </button>
                                                </form>
                                                <form action="{{ route('bookings.reject', $booking) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg font-semibold transition duration-200 text-sm">
                                                        <i class="fas fa-times mr-1"></i>
                                                        Rechazar
                                                    </button>
                                                </form>
                                            @endif

                                            @if($isPro && $booking->status == 'accepted')
                                                <form action="{{ route('bookings.complete', $booking) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-semibold transition duration-200 text-sm">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Completar
                                                    </button>
                                                </form>
                                            @endif

                                            @if(!$isPro && $booking->status == 'completed' && !$booking->review)
                                                <a href="{{ route('reviews.create', ['booking' => $booking->id]) }}" 
                                                   class="bg-yellow-500 hover:bg-yellow-600 text-white text-center py-2 rounded-lg font-semibold transition duration-200 text-sm">
                                                    <i class="fas fa-star mr-1"></i>
                                                    Dejar Reseña
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Paginación --}}
                    <div class="mt-8">
                        {{ $bookings->links() }}
                    </div>
                @else
                    {{-- Sin reservas --}}
                    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                        <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-2xl font-semibold text-gray-700 mb-2">
                            No hay reservas
                        </h3>
                        <p class="text-gray-500 mb-6">
                            @if(Auth::user()->isPro())
                                Aún no has recibido ninguna reserva para tus servicios
                            @else
                                Aún no has realizado ninguna reserva
                            @endif
                        </p>
                        @if(!Auth::user()->isPro())
                            <a href="{{ route('services.index') }}" 
                               class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                                Explorar Servicios
                            </a>
                        @else
                            <a href="{{ route('services.create') }}" 
                               class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                                Crear un Servicio
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
