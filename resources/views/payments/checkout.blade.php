{{-- 
    Vista: Checkout / Pasarela de Pago
    Descripción: Página de pago simulado para completar una reserva
    Ruta: GET /bookings/{booking}/checkout
--}}
@extends('layouts.marketplace')

@section('title', 'Checkout - HouseFixes')

@section('content')
    {{-- Breadcrumb --}}
    <section class="bg-gray-100 py-4">
        <div class="container mx-auto px-4">
            <nav class="flex items-center text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Inicio</a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <a href="{{ route('bookings.index') }}" class="hover:text-blue-600">Mis Reservas</a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <a href="{{ route('bookings.show', $booking) }}" class="hover:text-blue-600">Reserva #{{ $booking->id }}</a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <span class="text-gray-800 font-medium">Checkout</span>
            </nav>
        </div>
    </section>

    {{-- Contenido Principal --}}
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                
                {{-- Header --}}
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-credit-card text-blue-600 mr-3"></i>
                        Procesar Pago
                    </h1>
                    <p class="text-gray-600">Completa tu pago para confirmar la reserva</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    {{-- Formulario de Pago --}}
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-lg p-8">
                            
                            {{-- Alert Info --}}
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                                    <div>
                                        <h3 class="font-semibold text-blue-900 mb-1">Sistema de Pago Simulado</h3>
                                        <p class="text-sm text-blue-800">
                                            Este es un pago simulado para demostración. No se realizará ningún cargo real. 
                                            El pago tiene 90% de probabilidad de éxito.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('payments.process') }}" method="POST" id="payment-form">
                                @csrf
                                <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                                {{-- Método de Pago --}}
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Selecciona el método de pago
                                    </label>

                                    <div class="space-y-3">
                                        {{-- Tarjeta --}}
                                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                            <input type="radio" name="payment_method" value="card" class="text-blue-600 focus:ring-blue-500" checked>
                                            <div class="ml-4 flex items-center flex-1">
                                                <i class="fas fa-credit-card text-2xl text-gray-600 mr-4"></i>
                                                <div>
                                                    <div class="font-semibold text-gray-900">Tarjeta de Crédito/Débito</div>
                                                    <div class="text-sm text-gray-500">Visa, Mastercard, American Express</div>
                                                </div>
                                            </div>
                                        </label>

                                        {{-- Wallet --}}
                                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                            <input type="radio" name="payment_method" value="wallet" class="text-blue-600 focus:ring-blue-500">
                                            <div class="ml-4 flex items-center flex-1">
                                                <i class="fas fa-wallet text-2xl text-gray-600 mr-4"></i>
                                                <div>
                                                    <div class="font-semibold text-gray-900">Wallet Digital</div>
                                                    <div class="text-sm text-gray-500">PayPal, Apple Pay, Google Pay</div>
                                                </div>
                                            </div>
                                        </label>

                                        {{-- Transferencia --}}
                                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                            <input type="radio" name="payment_method" value="bank_transfer" class="text-blue-600 focus:ring-blue-500">
                                            <div class="ml-4 flex items-center flex-1">
                                                <i class="fas fa-university text-2xl text-gray-600 mr-4"></i>
                                                <div>
                                                    <div class="font-semibold text-gray-900">Transferencia Bancaria</div>
                                                    <div class="text-sm text-gray-500">Bizum, Transferencia SEPA</div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                {{-- Datos Simulados --}}
                                <div id="card-details" class="mb-6 space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Número de Tarjeta</label>
                                        <input type="text" 
                                               value="4532 1234 5678 9010" 
                                               readonly
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Expiración</label>
                                            <input type="text" 
                                                   value="12/25" 
                                                   readonly
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                                            <input type="text" 
                                                   value="123" 
                                                   readonly
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                                        </div>
                                    </div>
                                </div>

                                {{-- Términos --}}
                                <div class="mb-6">
                                    <label class="flex items-start">
                                        <input type="checkbox" required class="mt-1 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-3 text-sm text-gray-600">
                                            Acepto los <a href="#" class="text-blue-600 hover:underline">términos y condiciones</a> 
                                            y la <a href="#" class="text-blue-600 hover:underline">política de cancelación</a>
                                        </span>
                                    </label>
                                </div>

                                {{-- Botón Pagar --}}
                                <button type="submit" 
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-6 rounded-lg transition flex items-center justify-center"
                                        id="pay-button">
                                    <i class="fas fa-lock mr-2"></i>
                                    Pagar {{ number_format($amount, 2) }}€
                                </button>

                                <p class="text-center text-xs text-gray-500 mt-4">
                                    <i class="fas fa-shield-alt mr-1"></i>
                                    Pago 100% seguro y encriptado
                                </p>
                            </form>

                        </div>
                    </div>

                    {{-- Resumen del Pedido --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Resumen del Pedido</h3>

                            {{-- Servicio --}}
                            <div class="mb-4 pb-4 border-b border-gray-200">
                                <div class="flex items-start mb-3">
                                    @if($booking->service->photos->count() > 0)
                                        <img src="{{ asset('storage/' . $booking->service->photos->first()->image_path) }}" 
                                             alt="{{ $booking->service->title }}"
                                             class="w-16 h-16 rounded-lg object-cover mr-3">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-tools text-gray-400 text-xl"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $booking->service->title }}</h4>
                                        <p class="text-sm text-gray-500">{{ $booking->service->category->name }}</p>
                                    </div>
                                </div>
                                
                                <div class="text-sm text-gray-600 space-y-1">
                                    <div class="flex items-center">
                                        <i class="fas fa-user text-gray-400 mr-2 w-4"></i>
                                        {{ $booking->professional->name }}
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar text-gray-400 mr-2 w-4"></i>
                                        {{ $booking->datetime->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt text-gray-400 mr-2 w-4"></i>
                                        {{ $booking->address }}
                                    </div>
                                </div>
                            </div>

                            {{-- Desglose --}}
                            <div class="space-y-3 mb-4 pb-4 border-b border-gray-200">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Precio del servicio</span>
                                    <span class="font-medium text-gray-900">{{ number_format($amount, 2) }}€</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Comisión plataforma (10%)</span>
                                    <span class="font-medium text-gray-900">{{ number_format($platformFee, 2) }}€</span>
                                </div>
                                <div class="flex justify-between text-sm text-green-600">
                                    <span>Profesional recibe</span>
                                    <span class="font-medium">{{ number_format($professionalAmount, 2) }}€</span>
                                </div>
                            </div>

                            {{-- Total --}}
                            <div class="flex justify-between items-center mb-6">
                                <span class="text-lg font-semibold text-gray-900">Total a Pagar</span>
                                <span class="text-2xl font-bold text-blue-600">{{ number_format($amount, 2) }}€</span>
                            </div>

                            {{-- Info Adicional --}}
                            <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-600">
                                <div class="flex items-start mb-2">
                                    <i class="fas fa-info-circle text-blue-500 mr-2 mt-1"></i>
                                    <div>
                                        <p class="font-medium text-gray-900 mb-1">Política de Cancelación</p>
                                        <p class="text-xs">Puedes cancelar sin coste hasta 24h antes del servicio.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        // Simula procesamiento del pago
        document.getElementById('payment-form').addEventListener('submit', function(e) {
            const button = document.getElementById('pay-button');
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...';
        });
    </script>
    @endpush
@endsection
