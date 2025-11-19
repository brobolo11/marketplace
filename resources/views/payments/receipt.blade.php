{{-- 
    Vista: Recibo de Pago
    Descripción: Recibo descargable/imprimible del pago
    Ruta: GET /payments/{payment}/receipt
--}}
@extends('layouts.marketplace')

@section('title', 'Recibo de Pago - HouseFixes')

@section('content')
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                
                {{-- Botones Acción --}}
                <div class="mb-6 flex justify-between items-center">
                    <a href="{{ route('payments.index') }}" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver a pagos
                    </a>
                    <button onclick="window.print()" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                        <i class="fas fa-print mr-2"></i>
                        Imprimir
                    </button>
                </div>

                {{-- Recibo --}}
                <div class="bg-white rounded-xl shadow-lg overflow-hidden" id="receipt">
                    
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-8">
                        <div class="flex justify-between items-start">
                            <div>
                                <h1 class="text-3xl font-bold mb-2">HouseFixes</h1>
                                <p class="text-blue-100">Plataforma de HouseFixesfesionales</p>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-blue-100 mb-1">RECIBO</div>
                                <div class="text-2xl font-bold">#{{ $payment->id }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Contenido --}}
                    <div class="p-8">
                        
                        {{-- Info Principal --}}
                        <div class="grid grid-cols-2 gap-8 mb-8">
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-3">De:</h3>
                                <div class="text-sm text-gray-600 space-y-1">
                                    <p class="font-medium text-gray-900">{{ $payment->professional->name }}</p>
                                    <p>{{ $payment->professional->email }}</p>
                                    <p>{{ $payment->professional->phone ?? 'N/A' }}</p>
                                    <p>{{ $payment->professional->city ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-3">Para:</h3>
                                <div class="text-sm text-gray-600 space-y-1">
                                    <p class="font-medium text-gray-900">{{ $payment->user->name }}</p>
                                    <p>{{ $payment->user->email }}</p>
                                    <p>{{ $payment->user->phone ?? 'N/A' }}</p>
                                    <p>{{ $payment->user->city ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Detalles Transacción --}}
                        <div class="mb-8">
                            <h3 class="font-semibold text-gray-900 mb-3">Detalles de la Transacción</h3>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div class="bg-gray-50 p-3 rounded">
                                    <p class="text-gray-600 mb-1">ID Transacción</p>
                                    <p class="font-mono font-medium text-gray-900">{{ $payment->transaction_id }}</p>
                                </div>
                                <div class="bg-gray-50 p-3 rounded">
                                    <p class="text-gray-600 mb-1">Fecha de Pago</p>
                                    <p class="font-medium text-gray-900">{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : 'N/A' }}</p>
                                </div>
                                <div class="bg-gray-50 p-3 rounded">
                                    <p class="text-gray-600 mb-1">Método de Pago</p>
                                    <p class="font-medium text-gray-900">
                                        @if($payment->payment_method === 'card')
                                            Tarjeta de Crédito/Débito
                                        @elseif($payment->payment_method === 'wallet')
                                            Wallet Digital
                                        @else
                                            Transferencia Bancaria
                                        @endif
                                    </p>
                                </div>
                                <div class="bg-gray-50 p-3 rounded">
                                    <p class="text-gray-600 mb-1">Estado</p>
                                    <p class="font-medium">
                                        @if($payment->isCompleted())
                                            <span class="text-green-600">✓ Completado</span>
                                        @elseif($payment->isPending())
                                            <span class="text-yellow-600">⏱ Pendiente</span>
                                        @elseif($payment->isRefunded())
                                            <span class="text-purple-600">↩ Reembolsado</span>
                                        @else
                                            <span class="text-red-600">✗ {{ ucfirst($payment->status) }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Detalles del Servicio --}}
                        <div class="mb-8">
                            <h3 class="font-semibold text-gray-900 mb-3">Servicio Contratado</h3>
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Precio</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-4 py-4">
                                            <div class="font-medium text-gray-900">{{ $payment->booking->service->title }}</div>
                                            <div class="text-sm text-gray-500">{{ $payment->booking->service->category->name }}</div>
                                            <div class="text-sm text-gray-500 mt-1">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                {{ $payment->booking->address }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-gray-600">
                                            {{ $payment->booking->datetime->format('d/m/Y') }}<br>
                                            <span class="text-sm text-gray-500">{{ $payment->booking->datetime->format('H:i') }}</span>
                                        </td>
                                        <td class="px-4 py-4 text-right font-medium text-gray-900">
                                            {{ number_format($payment->amount, 2) }}€
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Totales --}}
                        <div class="flex justify-end mb-8">
                            <div class="w-80">
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Subtotal</span>
                                        <span class="font-medium text-gray-900">{{ number_format($payment->amount, 2) }}€</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Comisión Plataforma (10%)</span>
                                        <span class="font-medium text-gray-900">-{{ number_format($payment->platform_fee, 2) }}€</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Importe Profesional</span>
                                        <span class="font-medium text-green-600">{{ number_format($payment->professional_amount, 2) }}€</span>
                                    </div>
                                    <div class="border-t-2 border-gray-300 pt-2 mt-2">
                                        <div class="flex justify-between">
                                            <span class="text-lg font-semibold text-gray-900">TOTAL PAGADO</span>
                                            <span class="text-2xl font-bold text-blue-600">{{ number_format($payment->amount, 2) }}€</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Notas --}}
                        @if($payment->notes)
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                                <p class="text-sm font-medium text-yellow-800">Notas:</p>
                                <p class="text-sm text-yellow-700 mt-1">{{ $payment->notes }}</p>
                            </div>
                        @endif

                        {{-- Footer --}}
                        <div class="border-t border-gray-200 pt-6 text-center">
                            <p class="text-sm text-gray-600 mb-2">
                                Gracias por usar HouseFixes
                            </p>
                            <p class="text-xs text-gray-500">
                                Este es un documento electrónico generado automáticamente.<br>
                                Para cualquier consulta, contacta a soporte@serviciospro.com
                            </p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #receipt, #receipt * {
                visibility: visible;
            }
            #receipt {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
@endsection
