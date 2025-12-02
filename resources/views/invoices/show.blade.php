@extends('layouts.marketplace')

@section('title', 'Factura ' . $invoice->invoice_number)

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
                        <h1 class="text-3xl font-bold">Factura {{ $invoice->invoice_number }}</h1>
                        <p class="text-blue-100">{{ $invoice->issued_at->format('d/m/Y') }}</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    @if($invoice->status === 'paid')
                        <a href="{{ route('invoices.download', $invoice) }}" 
                           class="px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Descargar Factura Pagada
                        </a>
                    @else
                        <a href="{{ route('invoices.download', $invoice) }}" 
                           class="px-6 py-3 bg-yellow-600 text-white rounded-lg font-semibold hover:bg-yellow-700 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Descargar Factura Pendiente
                        </a>
                    @endif
                    <a href="{{ route('invoices.index') }}" 
                       class="px-6 py-3 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-400 transition">
                        Volver
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Contenido --}}
    <section class="py-12 bg-gradient-to-b from-gray-50 to-gray-100 min-h-screen">
        <div class="container mx-auto px-4 max-w-4xl">
            
            {{-- Tarjeta de la Factura --}}
            <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
                
                {{-- Cabecera de la factura --}}
                <div class="p-8 bg-gradient-to-r from-blue-50 to-indigo-50 border-b-4 border-blue-600">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-800 mb-2">{{ config('app.name', 'ServiConnect') }}</h2>
                            <p class="text-gray-600">Plataforma de Servicios Profesionales</p>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-blue-600 mb-2">FACTURA</div>
                            <div class="text-xl font-mono text-gray-700">{{ $invoice->invoice_number }}</div>
                            @if($invoice->status === 'pending')
                                <span class="inline-block mt-2 px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                    Pendiente
                                </span>
                            @elseif($invoice->status === 'paid')
                                <span class="inline-block mt-2 px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                    Pagada
                                </span>
                            @else
                                <span class="inline-block mt-2 px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                    Cancelada
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Información del Profesional y Cliente --}}
                <div class="p-8 grid md:grid-cols-2 gap-8">
                    {{-- Profesional --}}
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">Profesional</h3>
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="flex items-center gap-3 mb-3">
                                @if($invoice->booking->professional->profile_photo_path)
                                    <img src="{{ $invoice->booking->professional->profile_photo_path }}" 
                                         alt="{{ $invoice->booking->professional->name }}" 
                                         class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($invoice->booking->professional->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold text-gray-800">{{ $invoice->booking->professional->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $invoice->booking->professional->email }}</p>
                                </div>
                            </div>
                            @if($invoice->booking->professional->phone)
                                <p class="text-sm text-gray-600">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    {{ $invoice->booking->professional->phone }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Cliente --}}
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">Cliente</h3>
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="flex items-center gap-3 mb-3">
                                @if($invoice->booking->client->profile_photo_path)
                                    <img src="{{ $invoice->booking->client->profile_photo_path }}" 
                                         alt="{{ $invoice->booking->client->name }}" 
                                         class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($invoice->booking->client->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold text-gray-800">{{ $invoice->booking->client->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $invoice->booking->client->email }}</p>
                                </div>
                            </div>
                            @if($invoice->booking->client->phone)
                                <p class="text-sm text-gray-600">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    {{ $invoice->booking->client->phone }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Información del Servicio --}}
                <div class="px-8 pb-8">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">Datos del Servicio</h3>
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Fecha del Servicio</p>
                                <p class="font-semibold text-gray-800">{{ $invoice->booking->datetime->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Dirección</p>
                                <p class="font-semibold text-gray-800">{{ $invoice->booking->address }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Detalles --}}
                    <div class="overflow-hidden rounded-lg border border-gray-200">
                        <table class="w-full">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Descripción</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Categoría</th>
                                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <tr>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-gray-800">{{ $invoice->booking->service->title }}</p>
                                        <p class="text-sm text-gray-600 mt-1">{{ $invoice->booking->service->description }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $invoice->booking->service->category->name }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold text-gray-800">
                                        {{ number_format($invoice->booking->total_price, 2) }}€
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Totales --}}
                    <div class="mt-6 flex justify-end">
                        <div class="w-full md:w-1/2 space-y-3">
                            <div class="flex justify-between py-2">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-semibold">{{ number_format($invoice->booking->total_price, 2) }}€</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-gray-600">IVA (21%):</span>
                                <span class="font-semibold">{{ number_format($invoice->booking->total_price * 0.21, 2) }}€</span>
                            </div>
                            <div class="flex justify-between py-4 border-t-2 border-blue-600">
                                <span class="text-xl font-bold text-gray-800">TOTAL:</span>
                                <span class="text-2xl font-bold text-blue-600">{{ number_format($invoice->booking->total_price * 1.21, 2) }}€</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Notas --}}
                @if($invoice->notes)
                    <div class="px-8 pb-8">
                        <div class="bg-blue-50 border-l-4 border-blue-600 p-4 rounded">
                            <p class="text-sm font-semibold text-gray-700 mb-1">Notas:</p>
                            <p class="text-gray-600">{{ $invoice->notes }}</p>
                        </div>
                    </div>
                @endif

                {{-- Acciones --}}
                @if(Auth::user()->isPro() && $invoice->booking->pro_id === Auth::id())
                    <div class="px-8 pb-8">
                        <div class="flex gap-3">
                            @if($invoice->status === 'pending')
                                <form action="{{ route('invoices.mark-paid', $invoice) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-lg font-semibold transition shadow-lg hover:shadow-xl">
                                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Marcar como Pagada
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Footer --}}
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 text-center text-sm text-gray-600">
                    <p>Esta factura ha sido generada electrónicamente por {{ config('app.name') }}.</p>
                    <p class="mt-1">Para cualquier consulta, contacte con nosotros en contacto@serviconnect.com</p>
                </div>
            </div>
        </div>
    </section>
@endsection
