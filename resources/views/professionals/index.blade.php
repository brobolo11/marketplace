{{-- 
    Vista: Listado de Profesionales
    Descripción: Muestra todos los profesionales con filtros de búsqueda
    Ruta: GET /professionals
--}}
@extends('layouts.marketplace')

@section('title', 'Profesionales - HouseFixes')

@section('content')
    {{-- Header Compacto --}}
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-8">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold text-center">
                Encuentra Profesionales
            </h1>
        </div>
    </section>

    {{-- Filtros de Búsqueda --}}
    <section class="bg-white shadow-md border-b">
        <div class="container mx-auto px-4 py-4">
            <form method="GET" action="{{ route('professionals.index') }}" class="flex flex-wrap gap-3">
                {{-- Búsqueda por nombre --}}
                <div class="flex-1 min-w-[200px]">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Buscar profesionales..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                </div>

                {{-- Filtro por ciudad --}}
                <div class="min-w-[150px]">
                    <input type="text" 
                           name="city" 
                           value="{{ request('city') }}"
                           placeholder="Ciudad..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                </div>

                {{-- Ordenar por rating --}}
                <div class="min-w-[150px]">
                    <select name="sort" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                        <option value="">Ordenar por...</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Mejor valorados</option>
                    </select>
                </div>

                {{-- Botones --}}
                <div class="flex gap-2">
                    <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg font-semibold transition duration-200 text-sm">
                        <i class="fas fa-search mr-1"></i>
                        Buscar
                    </button>
                    
                    @if(request()->hasAny(['search', 'city', 'sort']))
                        <a href="{{ route('professionals.index') }}" 
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
            {{-- Contador de resultados --}}
            <div class="mb-6">
                <p class="text-gray-600">
                    Se encontraron <span class="font-semibold text-gray-800">{{ $professionals->count() }}</span> profesionales
                </p>
            </div>

            @if($professionals->count() > 0)
                {{-- Grid de profesionales --}}
                <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($professionals as $professional)
                        @php
                            $avgRating = $professional->averageRating();
                            $reviewCount = $professional->reviewsReceived->count();
                            $servicesCount = $professional->services->count();
                        @endphp
                        
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-200 hover:border-indigo-300 transform hover:-translate-y-2">
                            {{-- Avatar y Header --}}
                            <div class="relative bg-gradient-to-br from-indigo-500 to-purple-600 h-20">
                                <div class="absolute -bottom-7 left-1/2 -translate-x-1/2">
                                    <div class="w-14 h-14 rounded-full bg-white p-1 shadow-xl ring-4 ring-white">
                                        @if($professional->profile_photo_path)
                                            @if(str_starts_with($professional->profile_photo_path, 'http'))
                                                <img src="{{ $professional->profile_photo_path }}" 
                                                     alt="{{ $professional->name }}"
                                                     class="w-full h-full rounded-full object-cover">
                                            @else
                                                <img src="{{ Storage::url($professional->profile_photo_path) }}" 
                                                     alt="{{ $professional->name }}"
                                                     class="w-full h-full rounded-full object-cover">
                                            @endif
                                        @else
                                            <div class="w-full h-full rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-lg font-bold">
                                                {{ substr($professional->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Info --}}
                            <div class="pt-9 px-4 pb-4 text-center bg-gradient-to-b from-gray-50 to-white">
                                <h3 class="text-sm font-bold text-gray-800 mb-0.5 line-clamp-1">
                                    {{ $professional->name }}
                                </h3>

                                @if($professional->city)
                                    <p class="text-gray-500 text-xs mb-2.5">
                                        <i class="fas fa-map-marker-alt mr-0.5"></i>
                                        {{ $professional->city }}
                                    </p>
                                @endif

                                {{-- Rating --}}
                                @if($reviewCount > 0)
                                    <div class="flex items-center justify-center gap-1 mb-2.5 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-md py-1.5 px-2 border border-yellow-200 mx-auto inline-flex">
                                        <div class="flex items-center gap-0.5">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $avgRating ? 'text-yellow-500' : 'text-gray-300' }} text-xs"></i>
                                            @endfor
                                        </div>
                                        <span class="font-bold text-gray-800 text-xs">{{ number_format($avgRating, 1) }}</span>
                                        <span class="text-gray-500 text-xs">({{ $reviewCount }})</span>
                                    </div>
                                @else
                                    <p class="text-gray-400 text-xs mb-2.5 py-1 bg-gray-100 rounded-md inline-block px-3">Sin reseñas</p>
                                @endif

                                {{-- Bio (si existe) --}}
                                @if($professional->bio)
                                    <p class="text-gray-600 text-xs mb-3 line-clamp-2 leading-relaxed px-1">
                                        {{ $professional->bio }}
                                    </p>
                                @endif

                                {{-- Servicios ofrecidos --}}
                                <div class="mb-3 mx-auto inline-flex items-center justify-center gap-1 text-xs text-white bg-gradient-to-r from-indigo-500 to-purple-500 rounded-md py-1.5 px-3 shadow-sm">
                                    <i class="fas fa-briefcase text-xs"></i>
                                    <span class="font-semibold">{{ $servicesCount }} servicio{{ $servicesCount != 1 ? 's' : '' }}</span>
                                </div>

                                {{-- Botones de acción --}}
                                <div class="flex flex-col gap-2 mt-3">
                                    <a href="{{ route('professionals.show', $professional) }}" 
                                       class="block w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white py-2 rounded-lg font-bold transition duration-200 shadow-md hover:shadow-lg text-xs">
                                        <i class="fas fa-user mr-1"></i>Ver Perfil
                                    </a>
                                    @if($servicesCount > 0)
                                        <a href="{{ route('professionals.show', $professional) }}#services" 
                                           class="block w-full bg-white border-2 border-indigo-600 text-indigo-600 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 py-2 rounded-lg font-semibold transition duration-200 text-xs">
                                            <i class="fas fa-handshake mr-1"></i>Contratar
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Sin resultados --}}
                <div class="text-center py-16">
                    <i class="fas fa-user-slash text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-2xl font-semibold text-gray-700 mb-2">
                        No se encontraron profesionales
                    </h3>
                    <p class="text-gray-500 mb-6">
                        Intenta ajustar los filtros de búsqueda
                    </p>
                    <a href="{{ route('professionals.index') }}" 
                       class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                        Ver Todos los Profesionales
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- CTA para convertirse en profesional --}}
    @guest
        <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
            <div class="container mx-auto px-4 text-center">
                <div class="max-w-3xl mx-auto">
                    <div class="mb-6">
                        <i class="fas fa-user-tie text-6xl mb-4 opacity-90"></i>
                    </div>
                    <h2 class="text-4xl font-bold mb-6">¿Eres un Profesional?</h2>
                    <p class="text-xl text-indigo-100 mb-10 leading-relaxed">
                        Únete a nuestra plataforma y comienza a recibir clientes que buscan tus servicios. 
                        Gestiona tu agenda, tus servicios y tus ingresos desde un solo lugar.
                    </p>
                    <a href="{{ route('register') }}" 
                       class="inline-block bg-white text-indigo-600 hover:bg-gray-100 px-10 py-4 rounded-lg font-bold text-lg transition duration-200 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                        <i class="fas fa-user-plus mr-2"></i>
                        Únete a Nosotros
                    </a>
                </div>
            </div>
        </section>
    @endguest
@endsection
