{{-- 
    Vista: Listado de Servicios
    Descripción: Muestra todos los servicios con filtros de búsqueda
    Ruta: GET /services
--}}
@extends('layouts.marketplace')

@section('title', 'Todos los Servicios - HouseFixes')

@section('content')
    {{-- Header Compacto --}}
    <section class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-8">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold text-center">
                Buscar Servicios
            </h1>
        </div>
    </section>

    {{-- Filtros de Búsqueda --}}
    <section class="bg-white shadow-md border-b">
        <div class="container mx-auto px-4 py-4">
            <form method="GET" action="{{ route('services.index') }}" class="flex flex-wrap gap-3">
                {{-- Búsqueda por texto --}}
                <div class="flex-1 min-w-[200px]">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Buscar servicios..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>

                {{-- Filtro por categoría --}}
                <div class="min-w-[180px]">
                    <select name="category_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtro por ciudad --}}
                <div class="min-w-[150px]">
                    <input type="text" 
                           name="city" 
                           value="{{ request('city') }}"
                           placeholder="Ciudad..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>

                {{-- Botones --}}
                <div class="flex gap-2">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-semibold transition duration-200 text-sm">
                        <i class="fas fa-search mr-1"></i>
                        Buscar
                    </button>
                    
                    @if(request()->hasAny(['search', 'category_id', 'city', 'min_price', 'max_price']))
                        <a href="{{ route('services.index') }}" 
                           class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2 rounded-lg font-semibold transition duration-200 text-sm">
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </section>

    {{-- Resultados --}}
    <section class="py-12 bg-gradient-to-b from-gray-50 to-gray-100">
        <div class="container mx-auto px-4">
            {{-- Header con contador y botón para profesionales --}}
            <div class="mb-6 flex items-center justify-between">
                <p class="text-gray-600">
                    Se encontraron <span class="font-semibold text-gray-800">{{ $services->total() }}</span> servicios
                </p>

                {{-- Botón Añadir Servicio (solo para profesionales) --}}
                @auth
                    @if(auth()->user()->isPro())
                        <div x-data="{ openModal: false }">
                            <button 
                                @click="openModal = true; $nextTick(() => { if (window.serviceModalInstance) window.serviceModalInstance.open = true; })"
                                type="button"
                                class="bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200 shadow-lg hover:shadow-xl flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Añadir Servicio
                            </button>

                            <!-- Modal de Crear Servicio -->
                            <div x-show="openModal" style="display: none;">
                                <x-service-modal mode="create" />
                            </div>
                        </div>
                    @endif
                @endauth
            </div>

            @if($services->count() > 0)
                {{-- Grid de servicios --}}
                <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    @foreach($services as $service)
                        @php
                            $isOwnService = auth()->check() && auth()->user()->isPro() && $service->user_id === auth()->id();
                        @endphp
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden group border @if($isOwnService) border-purple-400 ring-2 ring-purple-200 @else border-gray-200 hover:border-blue-300 @endif transform hover:-translate-y-2 flex flex-col relative">
                            
                            {{-- Badge "Tu Servicio" --}}
                            @if($isOwnService)
                                <div class="absolute top-2 left-2 z-10 bg-purple-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    Tu Servicio
                                </div>
                            @endif

                            <a href="{{ route('services.show', $service) }}" class="flex-1 flex flex-col">
                            {{-- Imagen --}}
                            <div class="relative h-44 bg-gradient-to-br from-blue-500 to-indigo-600 overflow-hidden flex items-center justify-center">
                                @if($service->photos->count() > 0)
                                    <img src="{{ Storage::url($service->photos->first()->path) }}" 
                                         alt="{{ $service->title }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                @else
                                    <div class="text-white text-5xl opacity-70">
                                        <i class="fas fa-{{ $service->category->icon }}"></i>
                                    </div>
                                @endif
                                
                                {{-- Badge de categoría --}}
                                <div class="absolute top-2.5 left-2.5 bg-white px-2.5 py-1 rounded-lg text-xs font-bold text-gray-700 shadow-lg">
                                    {{ $service->category->name }}
                                </div>

                                {{-- Badge de precio --}}
                                <div class="absolute bottom-2.5 right-2.5 bg-blue-600 px-3 py-1.5 rounded-lg shadow-xl">
                                    <span class="font-bold text-white text-sm">{{ number_format($service->price, 2) }}€</span>
                                </div>
                            </div>

                            {{-- Info --}}
                            <div class="p-4 flex flex-col flex-grow bg-gray-50">
                                <h3 class="text-base font-bold text-gray-800 group-hover:text-blue-600 transition duration-200 mb-2 line-clamp-2 min-h-[2.5rem]">
                                    {{ $service->title }}
                                </h3>

                                <p class="text-gray-600 text-xs mb-3 line-clamp-2 flex-grow leading-relaxed">
                                    {{ $service->description }}
                                </p>

                                {{-- Profesional --}}
                                <div class="flex items-center gap-2.5 pt-3 border-t border-gray-200 mt-auto bg-white rounded-lg px-3 py-2.5">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-md flex-shrink-0">
                                        {{ substr($service->user->name, 0, 1) }}
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-gray-800 text-xs truncate">{{ $service->user->name }}</p>
                                        @if($service->user->city)
                                            <p class="text-xs text-gray-500 truncate">
                                                <i class="fas fa-map-marker-alt mr-0.5"></i>
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
                                        <div class="flex-shrink-0">
                                            <div class="flex items-center gap-0.5 bg-yellow-50 px-2 py-1 rounded-md">
                                                <i class="fas fa-star text-yellow-500 text-xs"></i>
                                                <span class="text-xs font-bold text-gray-700">{{ number_format($avgRating, 1) }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            </a>

                            {{-- Botones de acción para servicios propios --}}
                            @if($isOwnService)
                                <div class="px-4 pb-4 flex gap-2 bg-purple-50 border-t border-purple-200">
                                    <button 
                                        @click="$dispatch('edit-service-{{ $service->id }}')"
                                        type="button"
                                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-3 rounded-lg font-semibold text-xs transition duration-200 flex items-center justify-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Editar
                                    </button>
                                    <form action="{{ route('services.destroy', $service) }}" method="POST" 
                                          onsubmit="return confirm('¿Estás seguro de eliminar este servicio?')"
                                          class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit"
                                            class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-3 rounded-lg font-semibold text-xs transition duration-200 flex items-center justify-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Eliminar
                                        </button>
                                    </form>
                                </div>

                                {{-- Modal de Edición (uno por servicio) --}}
                                <div x-data="{ openEdit: false }" 
                                     @edit-service-{{ $service->id }}.window="openEdit = true; $nextTick(() => { if (window.editServiceModal{{ $service->id }}) window.editServiceModal{{ $service->id }}.open = true; })">
                                    <div x-show="openEdit" style="display: none;">
                                        <x-service-modal :service="$service" mode="edit" />
                                    </div>
                                </div>
                            @endif
                        </div>
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
