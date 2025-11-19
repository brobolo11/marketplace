@extends('layouts.marketplace')

@section('title', 'Gestión de Servicios - Admin')

@section('content')
<section class="bg-gradient-to-r from-green-600 to-emerald-600 text-white py-8">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">
                    <i class="fas fa-briefcase mr-2"></i> Gestión de Servicios
                </h1>
                <p class="text-green-100">Administrar todos los servicios publicados</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-white text-green-600 hover:bg-green-50 px-4 py-2 rounded-lg font-semibold transition">
                <i class="fas fa-arrow-left mr-2"></i> Volver
            </a>
        </div>
    </div>
</section>

<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-4">
        @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
        @endif

        @if($errors->any())
        <x-alert type="error">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
        @endif

        {{-- Filtros --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('admin.services.index') }}" class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Título del servicio..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                    <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $category == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                        <i class="fas fa-search mr-2"></i> Filtrar
                    </button>
                    <a href="{{ route('admin.services.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold transition">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>

        {{-- Tabla de Servicios --}}
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Profesional</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reservas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Creado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($services as $service)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $service->id }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ Str::limit($service->title, 40) }}</div>
                                <div class="text-xs text-gray-500">{{ $service->duration }} min</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $service->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                    <i class="fas fa-{{ $service->category->icon }}"></i> {{ $service->category->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ number_format($service->price, 2) }}€</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $service->bookings_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $service->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                <a href="{{ route('services.show', $service) }}" class="text-blue-600 hover:text-blue-900" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('¿Eliminar este servicio?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">No se encontraron servicios</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Paginación --}}
        <div class="mt-6">
            {{ $services->links() }}
        </div>
    </div>
</section>
@endsection
