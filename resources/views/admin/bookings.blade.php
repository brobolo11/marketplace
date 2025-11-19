@extends('layouts.marketplace')

@section('title', 'Gestión de Reservas - Admin')

@section('content')
<section class="bg-gradient-to-r from-orange-600 to-amber-600 text-white py-8">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">
                    <i class="fas fa-calendar-check mr-2"></i> Gestión de Reservas
                </h1>
                <p class="text-orange-100">Administrar todas las reservas del sistema</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-white text-orange-600 hover:bg-orange-50 px-4 py-2 rounded-lg font-semibold transition">
                <i class="fas fa-arrow-left mr-2"></i> Volver
            </a>
        </div>
    </div>
</section>

<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-4">
        {{-- Filtros --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('admin.bookings.index') }}" class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cliente o profesional..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                        <option value="">Todos los estados</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pendientes</option>
                        <option value="accepted" {{ $status == 'accepted' ? 'selected' : '' }}>Aceptadas</option>
                        <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completadas</option>
                        <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Canceladas</option>
                        <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rechazadas</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                        <i class="fas fa-search mr-2"></i> Filtrar
                    </button>
                    <a href="{{ route('admin.bookings.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold transition">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>

        {{-- Tabla de Reservas --}}
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($bookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $booking->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->client->name }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->client->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->professional->name }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->professional->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ Str::limit($booking->service->title, 30) }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->service->category->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $booking->datetime->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->datetime->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-orange-100 text-orange-700',
                                        'accepted' => 'bg-blue-100 text-blue-700',
                                        'completed' => 'bg-green-100 text-green-700',
                                        'cancelled' => 'bg-red-100 text-red-700',
                                        'rejected' => 'bg-gray-100 text-gray-700',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Pendiente',
                                        'accepted' => 'Aceptada',
                                        'completed' => 'Completada',
                                        'cancelled' => 'Cancelada',
                                        'rejected' => 'Rechazada',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $statusLabels[$booking->status] ?? ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ number_format($booking->total_price, 2) }}€</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('bookings.show', $booking) }}" class="text-blue-600 hover:text-blue-900" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">No se encontraron reservas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Paginación --}}
        <div class="mt-6">
            {{ $bookings->links() }}
        </div>
    </div>
</section>
@endsection
