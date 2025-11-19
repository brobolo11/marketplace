{{-- 
    Vista: Historial de Pagos
    Descripción: Muestra el historial de pagos del usuario (cliente o profesional)
    Ruta: GET /payments
--}}
@extends('layouts.marketplace')

@section('title', 'Historial de Pagos - HouseFixes')

@section('content')
    @php
        $isPro = Auth::user()->isPro();
    @endphp

    {{-- Breadcrumb --}}
    <section class="bg-gray-100 py-4">
        <div class="container mx-auto px-4">
            <nav class="flex items-center text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Inicio</a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <span class="text-gray-800 font-medium">{{ $isPro ? 'Mis Ingresos' : 'Mis Pagos' }}</span>
            </nav>
        </div>
    </section>

    {{-- Contenido Principal --}}
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                
                {{-- Header --}}
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-wallet text-blue-600 mr-3"></i>
                        {{ $isPro ? 'Mis Ingresos' : 'Historial de Pagos' }}
                    </h1>
                    <p class="text-gray-600">
                        {{ $isPro ? 'Gestiona tus ingresos y pagos recibidos' : 'Revisa tus pagos realizados' }}
                    </p>
                </div>

                {{-- Estadísticas --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    @if($isPro)
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Total Ganado</p>
                                    <p class="text-2xl font-bold text-green-600">{{ number_format($stats['total_earned'], 2) }}€</p>
                                </div>
                                <div class="bg-green-100 p-3 rounded-full">
                                    <i class="fas fa-euro-sign text-green-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Pendiente</p>
                                    <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['pending_amount'], 2) }}€</p>
                                </div>
                                <div class="bg-yellow-100 p-3 rounded-full">
                                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Comisiones Plataforma</p>
                                    <p class="text-2xl font-bold text-red-600">{{ number_format($stats['platform_fees'], 2) }}€</p>
                                </div>
                                <div class="bg-red-100 p-3 rounded-full">
                                    <i class="fas fa-percentage text-red-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Total Gastado</p>
                                    <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_spent'], 2) }}€</p>
                                </div>
                                <div class="bg-blue-100 p-3 rounded-full">
                                    <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Pagos Completados</p>
                                    <p class="text-2xl font-bold text-green-600">{{ $stats['completed_count'] }}</p>
                                </div>
                                <div class="bg-green-100 p-3 rounded-full">
                                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Pendiente</p>
                                    <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['pending_amount'], 2) }}€</p>
                                </div>
                                <div class="bg-yellow-100 p-3 rounded-full">
                                    <i class="fas fa-hourglass-half text-yellow-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Lista de Pagos --}}
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    @if($payments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">ID Transacción</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">{{ $isPro ? 'Cliente' : 'Profesional' }}</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($payments as $payment)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                                                {{ $payment->transaction_id }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $payment->booking->service->title }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    Reserva #{{ $payment->booking_id }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $otherUser = $isPro ? $payment->user : $payment->professional;
                                                @endphp
                                                <div class="flex items-center">
                                                    <img src="{{ $otherUser->profile_photo_url }}" 
                                                         alt="{{ $otherUser->name }}" 
                                                         class="w-8 h-8 rounded-full mr-3">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $otherUser->name }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : $payment->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-gray-900">
                                                    {{ number_format($isPro ? $payment->professional_amount : $payment->amount, 2) }}€
                                                </div>
                                                @if($isPro && $payment->isCompleted())
                                                    <div class="text-xs text-gray-500">
                                                        - {{ number_format($payment->platform_fee, 2) }}€ comisión
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'processing' => 'bg-blue-100 text-blue-800',
                                                        'completed' => 'bg-green-100 text-green-800',
                                                        'failed' => 'bg-red-100 text-red-800',
                                                        'refunded' => 'bg-purple-100 text-purple-800',
                                                    ];
                                                    $statusLabels = [
                                                        'pending' => 'Pendiente',
                                                        'processing' => 'Procesando',
                                                        'completed' => 'Completado',
                                                        'failed' => 'Fallido',
                                                        'refunded' => 'Reembolsado',
                                                    ];
                                                @endphp
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$payment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ $statusLabels[$payment->status] ?? $payment->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('payments.receipt', $payment) }}" 
                                                   class="text-blue-600 hover:text-blue-800 mr-3"
                                                   title="Ver recibo">
                                                    <i class="fas fa-file-invoice"></i>
                                                </a>
                                                <a href="{{ route('bookings.show', $payment->booking) }}" 
                                                   class="text-gray-600 hover:text-gray-800"
                                                   title="Ver reserva">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Paginación --}}
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $payments->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-receipt text-gray-300 text-6xl mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">
                                {{ $isPro ? 'Aún no has recibido pagos' : 'No has realizado pagos' }}
                            </h3>
                            <p class="text-gray-500 mb-6">
                                {{ $isPro ? 'Comienza a ofrecer tus servicios para recibir tus primeros ingresos' : 'Reserva un servicio para ver tus pagos aquí' }}
                            </p>
                            <a href="{{ $isPro ? route('services.create') : route('services.index') }}" 
                               class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                                <i class="fas {{ $isPro ? 'fa-plus' : 'fa-search' }} mr-2"></i>
                                {{ $isPro ? 'Crear Servicio' : 'Buscar Servicios' }}
                            </a>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>
@endsection
