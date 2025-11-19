{{-- 
    Vista: Confirmación de Pago
    Descripción: Muestra confirmación exitosa del pago
    Ruta: GET /payments/{payment}/confirmation
--}}
@extends('layouts.marketplace')

@section('title', 'Pago Confirmado - HouseFixes')

@section('content')
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto">
                
                {{-- Success Card --}}
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    
                    {{-- Header Success --}}
                    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white text-center py-12">
                        <div class="mb-4">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full">
                                <i class="fas fa-check text-green-500 text-4xl"></i>
                            </div>
                        </div>
                        <h1 class="text-3xl font-bold mb-2">¡Pago Completado!</h1>
                        <p class="text-green-100">Tu reserva ha sido confirmada exitosamente</p>
                    </div>

                    {{-- Detalles del Pago --}}
                    <div class="p-8">
                        
                        {{-- ID Transacción --}}
                        <div class="bg-gray-50 rounded-lg p-4 mb-6 text-center">
                            <p class="text-sm text-gray-600 mb-1">ID de Transacción</p>
                            <p class="text-xl font-mono font-bold text-gray-900">{{ $payment->transaction_id }}</p>
                        </div>

                        {{-- Resumen --}}
                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-receipt text-blue-600 mr-2"></i>
                                Resumen del Pago
                            </h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Servicio</span>
                                    <span class="font-medium text-gray-900">{{ $payment->booking->service->title }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Profesional</span>
                                    <span class="font-medium text-gray-900">{{ $payment->professional->name }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Fecha del servicio</span>
                                    <span class="font-medium text-gray-900">{{ $payment->booking->datetime->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Dirección</span>
                                    <span class="font-medium text-gray-900 text-right">{{ $payment->booking->address }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Método de pago</span>
                                    <span class="font-medium text-gray-900">
                                        @if($payment->payment_method === 'card')
                                            <i class="fas fa-credit-card mr-1"></i> Tarjeta
                                        @elseif($payment->payment_method === 'wallet')
                                            <i class="fas fa-wallet mr-1"></i> Wallet
                                        @else
                                            <i class="fas fa-university mr-1"></i> Transferencia
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Fecha de pago</span>
                                    <span class="font-medium text-gray-900">{{ $payment->paid_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between py-3 bg-blue-50 px-4 rounded-lg mt-4">
                                    <span class="text-lg font-semibold text-gray-900">Total Pagado</span>
                                    <span class="text-2xl font-bold text-blue-600">{{ number_format($payment->amount, 2) }}€</span>
                                </div>
                            </div>
                        </div>

                        {{-- Qué sigue --}}
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                            <h4 class="font-semibold text-blue-900 mb-2">
                                <i class="fas fa-info-circle mr-2"></i>
                                ¿Qué sigue ahora?
                            </h4>
                            <ul class="text-sm text-blue-800 space-y-2">
                                <li><i class="fas fa-check mr-2 text-blue-600"></i> El profesional ha sido notificado</li>
                                <li><i class="fas fa-check mr-2 text-blue-600"></i> Recibirás un email de confirmación</li>
                                <li><i class="fas fa-check mr-2 text-blue-600"></i> Puedes contactar al profesional por mensajes</li>
                                <li><i class="fas fa-check mr-2 text-blue-600"></i> Recuerda la fecha: {{ $payment->booking->datetime->format('d/m/Y H:i') }}</li>
                            </ul>
                        </div>

                        {{-- Acciones --}}
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('payments.receipt', $payment) }}" 
                               class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                                <i class="fas fa-download mr-2"></i>
                                Descargar Recibo
                            </a>
                            <a href="{{ route('bookings.show', $payment->booking) }}" 
                               class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition">
                                <i class="fas fa-eye mr-2"></i>
                                Ver Reserva
                            </a>
                        </div>

                        <div class="mt-4 text-center">
                            <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-home mr-1"></i>
                                Volver al inicio
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
