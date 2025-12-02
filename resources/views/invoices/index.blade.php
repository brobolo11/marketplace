@extends('layouts.marketplace')

@section('title', 'Mis Facturas')

@section('content')
    {{-- Header --}}
    <section class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">Mis Facturas</h1>
                        <p class="text-blue-100">Gestiona todas las facturas de tus servicios</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Contenido --}}
    <section class="py-12 bg-gradient-to-b from-gray-50 to-gray-100 min-h-screen">
        <div class="container mx-auto px-4">
            
            {{-- Estadísticas --}}
            <div class="grid md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_invoiced'], 2) }}€</p>
                            <p class="text-sm text-gray-600">Total Facturado</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['paid_amount'], 2) }}€</p>
                            <p class="text-sm text-gray-600">Pagado</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['pending_amount'], 2) }}€</p>
                            <p class="text-sm text-gray-600">Pendiente</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filtros --}}
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <form method="GET" action="{{ route('invoices.index') }}" class="flex flex-wrap gap-4 items-center">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Todas</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendientes</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Pagadas</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Canceladas</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Filtrar
                        </button>
                        <a href="{{ route('invoices.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            {{-- Lista de facturas --}}
            @if($invoices->count() > 0)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">Número</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">Fecha</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">Cliente</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">Servicio</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">Total</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">Estado</th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($invoices as $invoice)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-sm font-semibold text-blue-600">
                                                {{ $invoice->invoice_number }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $invoice->issued_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                @if($invoice->booking->client->profile_photo_path)
                                                    <img src="{{ $invoice->booking->client->profile_photo_path }}" 
                                                         alt="{{ $invoice->booking->client->name }}" 
                                                         class="w-8 h-8 rounded-full object-cover">
                                                @else
                                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                                        {{ strtoupper(substr($invoice->booking->client->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <span class="text-sm font-medium text-gray-800">
                                                    {{ $invoice->booking->client->name }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ Str::limit($invoice->booking->service->title, 30) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-lg font-bold text-gray-800">
                                                {{ number_format($invoice->total, 2) }}€
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($invoice->status === 'pending')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Pendiente
                                                </span>
                                            @elseif($invoice->status === 'paid')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Pagada
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Cancelada
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('invoices.show', $invoice) }}" 
                                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                                   title="Ver factura">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('invoices.download', $invoice) }}" 
                                                   class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition"
                                                   title="Descargar PDF">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </a>
                                                @if($invoice->status === 'pending')
                                                    <form action="{{ route('invoices.mark-paid', $invoice) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition"
                                                                title="Marcar como pagada">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    @if($invoices->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $invoices->links() }}
                        </div>
                    @endif
                </div>
            @else
                {{-- Sin facturas --}}
                <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">No hay facturas aún</h3>
                    <p class="text-gray-600 mb-6">Las facturas se generarán automáticamente cuando completes servicios.</p>
                    <a href="{{ route('bookings.index') }}" 
                       class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Ver mis Reservas
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection
