{{-- 
    Vista: Detalle de Reserva
    Descripción: Muestra información completa de una reserva con acciones disponibles
    Ruta: GET /bookings/{booking}
--}}
@extends('layouts.marketplace')

@section('title', 'Detalle de Reserva - HouseFixes')

@section('content')
    @php
        $isPro = Auth::user()->isPro();
        $otherUser = $isPro ? $booking->client : $booking->professional;
        
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

    <div x-data="{ showCancelModal: false, showRejectModal: false }">

    {{-- Mensaje de éxito --}}
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                {{-- Header con estado --}}
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800 mb-2">Reserva #{{ $booking->id }}</h1>
                            <p class="text-gray-600">Creada el {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold border {{ $statusColors[$booking->status] }}">
                                {{ $statusLabels[$booking->status] }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid lg:grid-cols-3 gap-6">
                    {{-- Columna Principal --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Información del Servicio --}}
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fas fa-briefcase text-blue-600"></i>
                                Servicio Solicitado
                            </h2>
                            <div class="flex gap-4">
                                <div class="w-24 h-24 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-3xl flex-shrink-0 overflow-hidden">
                                    @if($booking->service->photos->count() > 0)
                                        <img src="{{ Storage::url($booking->service->photos->first()->path) }}" 
                                             alt="{{ $booking->service->title }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-{{ $booking->service->category->icon }}"></i>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ $booking->service->title }}</h3>
                                    <p class="text-sm text-gray-500 mb-2">
                                        <i class="fas fa-tag mr-1"></i>
                                        {{ $booking->service->category->name }}
                                    </p>
                                    <a href="{{ route('services.show', $booking->service) }}" 
                                       class="text-blue-600 hover:text-blue-700 text-sm font-semibold">
                                        Ver servicio completo <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Información de la Persona --}}
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fas fa-user text-blue-600"></i>
                                {{ $isPro ? 'Cliente' : 'Profesional' }}
                            </h2>
                            <div class="flex items-start gap-4">
                                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
                                    {{ substr($otherUser->name, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $otherUser->name }}</h3>
                                    <div class="space-y-2 text-sm text-gray-600">
                                        @if($otherUser->email)
                                            <p class="flex items-center gap-2">
                                                <i class="fas fa-envelope text-blue-600 w-5"></i>
                                                {{ $otherUser->email }}
                                            </p>
                                        @endif
                                        @if($otherUser->phone)
                                            <p class="flex items-center gap-2">
                                                <i class="fas fa-phone text-blue-600 w-5"></i>
                                                {{ $otherUser->phone }}
                                            </p>
                                        @endif
                                        @if($otherUser->city)
                                            <p class="flex items-center gap-2">
                                                <i class="fas fa-map-marker-alt text-blue-600 w-5"></i>
                                                {{ $otherUser->city }}
                                            </p>
                                        @endif
                                    </div>
                                    @if(!$isPro)
                                        <a href="{{ route('professionals.show', $otherUser) }}" 
                                           class="inline-block mt-3 text-blue-600 hover:text-blue-700 text-sm font-semibold">
                                            Ver perfil completo <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Detalles de la Cita --}}
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fas fa-info-circle text-blue-600"></i>
                                Detalles de la Cita
                            </h2>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-500 mb-1">Fecha</p>
                                    <p class="font-semibold text-gray-800 flex items-center gap-2">
                                        <i class="fas fa-calendar-alt text-blue-600"></i>
                                        {{ $booking->datetime->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-500 mb-1">Hora</p>
                                    <p class="font-semibold text-gray-800 flex items-center gap-2">
                                        <i class="fas fa-clock text-blue-600"></i>
                                        {{ $booking->datetime->format('H:i') }}
                                    </p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 md:col-span-2">
                                    <p class="text-sm text-gray-500 mb-1">Dirección</p>
                                    <p class="font-semibold text-gray-800 flex items-center gap-2">
                                        <i class="fas fa-map-marker-alt text-blue-600"></i>
                                        {{ $booking->address }}
                                    </p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 md:col-span-2">
                                    <p class="text-sm text-gray-500 mb-1">Precio Total</p>
                                    <p class="text-2xl font-bold text-blue-600 flex items-center gap-2">
                                        <i class="fas fa-euro-sign"></i>
                                        {{ number_format($booking->total_price, 2) }}€
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Reseña (si existe) --}}
                        @if($booking->review)
                            <div class="bg-white rounded-xl shadow-lg p-6">
                                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                                    <i class="fas fa-star text-yellow-400"></i>
                                    Reseña del Cliente
                                </h2>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center gap-2 mb-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $booking->review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                        <span class="font-semibold text-gray-700">{{ $booking->review->rating }}/5</span>
                                    </div>
                                    @if($booking->review->comment)
                                        <p class="text-gray-700 leading-relaxed">{{ $booking->review->comment }}</p>
                                    @else
                                        <p class="text-gray-400 italic text-sm">Sin comentario</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Columna Lateral - Acciones --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                            <h2 class="text-xl font-bold text-gray-800 mb-4">Acciones</h2>
                            
                            <div class="space-y-3">
                                {{-- Acciones del Profesional --}}
                                @if($isPro)
                                    @if($booking->status == 'pending')
                                        <form action="{{ route('bookings.approve', $booking) }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition duration-200">
                                                <i class="fas fa-check mr-2"></i>
                                                Aceptar Reserva
                                            </button>
                                        </form>
                                        <button @click="showRejectModal = true"
                                                class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition duration-200">
                                            <i class="fas fa-times mr-2"></i>
                                            Rechazar Reserva
                                        </button>
                                    @endif

                                    @if($booking->status == 'accepted')
                                        <form action="{{ route('bookings.complete', $booking) }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition duration-200">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                Marcar como Completada
                                            </button>
                                        </form>
                                    @endif
                                @endif

                                {{-- Acciones del Cliente --}}
                                @if(!$isPro)
                                    {{-- Botón de Pago (si está aceptada y no pagada) --}}
                                    @if($booking->isAccepted())
                                        @php
                                            $payment = $booking->payment;
                                        @endphp
                                        @if(!$payment || !$payment->isCompleted())
                                            <a href="{{ route('bookings.checkout', $booking) }}" 
                                               class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-3 rounded-lg font-semibold transition duration-200 shadow-lg">
                                                <i class="fas fa-credit-card mr-2"></i>
                                                Pagar Servicio
                                            </a>
                                        @else
                                            <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-center">
                                                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                                <span class="text-green-800 font-semibold">Pago Completado</span>
                                            </div>
                                        @endif
                                    @endif

                                    @if($booking->status == 'completed' && !$booking->review)
                                        <a href="{{ route('reviews.create', ['booking' => $booking->id]) }}" 
                                           class="block w-full bg-yellow-500 hover:bg-yellow-600 text-white text-center py-3 rounded-lg font-semibold transition duration-200">
                                            <i class="fas fa-star mr-2"></i>
                                            Dejar Reseña
                                        </a>
                                    @endif

                                    @if($booking->status == 'pending')
                                        <button @click="showCancelModal = true"
                                                class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition duration-200">
                                            <i class="fas fa-times mr-2"></i>
                                            Cancelar Reserva
                                        </button>
                                    @endif

                                    {{-- Ver pago si existe --}}
                                    @if($booking->payment && $booking->payment->isCompleted())
                                        <a href="{{ route('payments.receipt', $booking->payment) }}" 
                                           class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-3 rounded-lg font-semibold transition duration-200">
                                            <i class="fas fa-file-invoice mr-2"></i>
                                            Ver Recibo
                                        </a>
                                    @endif
                                @endif

                                {{-- Enviar mensaje --}}
                                <a href="{{ route('messages.show', $otherUser) }}" 
                                   class="block w-full bg-gray-600 hover:bg-gray-700 text-white text-center py-3 rounded-lg font-semibold transition duration-200">
                                    <i class="fas fa-envelope mr-2"></i>
                                    Enviar Mensaje
                                </a>

                                {{-- Volver --}}
                                <a href="{{ route('bookings.index') }}" 
                                   class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-700 text-center py-3 rounded-lg font-semibold transition duration-200">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Volver a Reservas
                                </a>
                            </div>

                            {{-- Información de estado --}}
                            <div class="mt-6 pt-6 border-t">
                                <h3 class="font-semibold text-gray-800 mb-3">Estado de la Reserva</h3>
                                <div class="space-y-2 text-sm">
                                    @if($booking->status == 'pending')
                                        <p class="text-yellow-700">
                                            <i class="fas fa-hourglass-half mr-2"></i>
                                            Esperando confirmación del profesional
                                        </p>
                                    @elseif($booking->status == 'accepted')
                                        <p class="text-blue-700">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            Reserva aceptada, pendiente de realizar
                                        </p>
                                    @elseif($booking->status == 'completed')
                                        <p class="text-green-700">
                                            <i class="fas fa-check-double mr-2"></i>
                                            Servicio completado exitosamente
                                        </p>
                                    @elseif($booking->status == 'cancelled')
                                        <p class="text-red-700">
                                            <i class="fas fa-times-circle mr-2"></i>
                                            Reserva cancelada
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Modal de Confirmación de Cancelación (Cliente) --}}
    <div x-show="showCancelModal" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        <div class="w-full max-w-md">
            <!-- Background overlay -->
            <div x-show="showCancelModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                 @click="showCancelModal = false"
                 aria-hidden="true"></div>

            <!-- Modal panel -->
            <div x-show="showCancelModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-white rounded-lg shadow-xl w-full"
                 @click.stop>
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Cancelar Reserva
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    ¿Estás seguro que deseas cancelar esta reserva? Esta acción no se puede deshacer y el profesional será notificado.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Sí, cancelar reserva
                        </button>
                    </form>
                    <button type="button" 
                            @click="showCancelModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        No, mantener reserva
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de Confirmación de Rechazo (Profesional) --}}
    <div x-show="showRejectModal" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        <div class="w-full max-w-md">
            <!-- Background overlay -->
            <div x-show="showRejectModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                 @click="showRejectModal = false"
                 aria-hidden="true"></div>

            <!-- Modal panel -->
            <div x-show="showRejectModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-white rounded-lg shadow-xl w-full"
                 @click.stop>
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Rechazar Reserva
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    ¿Estás seguro que deseas rechazar esta solicitud de reserva? El cliente será notificado y no podrá continuar con esta reserva.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form action="{{ route('bookings.reject', $booking) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" 
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Sí, rechazar reserva
                        </button>
                    </form>
                    <button type="button" 
                            @click="showRejectModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        No, mantener pendiente
                    </button>
                </div>
            </div>
        </div>
    </div>

    </div> {{-- Cierre del div x-data --}}
@endsection
