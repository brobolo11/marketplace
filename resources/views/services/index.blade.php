{{-- 
    Vista: Listado de Servicios
    Descripción: Muestra todos los servicios con filtros de búsqueda
    Ruta: GET /services
--}}
@extends('layouts.marketplace')

@section('title', 'Todos los Servicios - Servicios Pro')

@section('content')
    {{-- Header --}}
    <section class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-bold mb-4 text-center">
                Buscar Servicios
            </h1>
            <p class="text-xl text-blue-100 text-center">
                Encuentra el profesional perfecto para tu proyecto
            </p>
        </div>
    </section>

    {{-- Filtros de Búsqueda --}}
    <section class="bg-white shadow-md sticky top-16 z-40">
        <div class="container mx-auto px-4 py-6">
            <form method="GET" action="{{ route('services.index') }}" class="flex flex-wrap gap-4">
                {{-- Búsqueda por texto --}}
                <div class="flex-1 min-w-[200px]">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Buscar servicios..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Filtro por categoría --}}
                <div class="min-w-[200px]">
                    <select name="category_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtro por ciudad --}}
                <div class="min-w-[180px]">
                    <input type="text" 
                           name="city" 
                           value="{{ request('city') }}"
                           placeholder="Ciudad..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Botones --}}
                <div class="flex gap-2">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                        <i class="fas fa-search mr-2"></i>
                        Buscar
                    </button>
                    
                    @if(request()->hasAny(['search', 'category_id', 'city', 'min_price', 'max_price']))
                        <a href="{{ route('services.index') }}" 
                           class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold transition duration-200">
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </section>

    {{-- Resultados --}}
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            {{-- Contador de resultados --}}
            <div class="mb-6">
                <p class="text-gray-600">
                    Se encontraron <span class="font-semibold text-gray-800">{{ $services->total() }}</span> servicios
                </p>
            </div>

            @if($services->count() > 0)
                {{-- Grid de servicios --}}
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($services as $service)
                        <a href="{{ route('services.show', $service) }}" 
                           class="bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden group">
                            {{-- Imagen --}}
                            <div class="relative h-48 bg-gradient-to-br from-blue-500 to-indigo-600 overflow-hidden">
                                @if($service->photos->count() > 0)
                                    <img src="{{ Storage::url($service->photos->first()->path) }}" 
                                         alt="{{ $service->title }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-white text-6xl">
                                        <i class="fas fa-{{ $service->category->icon }}"></i>
                                    </div>
                                @endif
                                
                                {{-- Badge de categoría --}}
                                <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold text-gray-700">
                                    {{ $service->category->name }}
                                </div>

                                {{-- Badge de precio --}}
                                <div class="absolute top-4 right-4 bg-white px-3 py-1 rounded-full shadow-lg">
                                    <span class="font-bold text-blue-600">{{ number_format($service->price, 2) }}€</span>
                                </div>
                            </div>

                            {{-- Info --}}
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-800 group-hover:text-blue-600 transition duration-200 mb-2">
                                    {{ $service->title }}
                                </h3>

                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    {{ $service->description }}
                                </p>

                                {{-- Profesional --}}
                                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                                        {{ substr($service->user->name, 0, 1) }}
                                    </div>
                                    
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800">{{ $service->user->name }}</p>
                                        @if($service->user->city)
                                            <p class="text-xs text-gray-500">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                {{ $service->user->city }}
                                            </p>
                                        @endif
                                    </div>

                                    {{-- Rating --}}
                                    @php
                                        $avgRating = $service->user->averageRating();
                                        $reviewsCount = $service->user->reviewsReceived->count();
                                    @endphp
                                    @if($reviewsCount > 0)
                                        <div class="text-right">
                                            <div class="flex items-center gap-1 text-yellow-400">
                                                <i class="fas fa-star text-sm"></i>
                                                <span class="text-sm font-semibold text-gray-700">{{ number_format($avgRating, 1) }}</span>
                                            </div>
                                            <p class="text-xs text-gray-500">({{ $reviewsCount }})</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Paginación --}}
                <div class="mt-8">
                    {{ $services->links() }}
                </div>
            @else
                {{-- Sin resultados --}}
                <div class="text-center py-16">
                    <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-2xl font-semibold text-gray-700 mb-2">
                        No se encontraron servicios
                    </h3>
                    <p class="text-gray-500 mb-6">
                        Intenta ajustar los filtros de búsqueda
                    </p>
                    <a href="{{ route('services.index') }}" 
                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                        Ver Todos los Servicios
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection
