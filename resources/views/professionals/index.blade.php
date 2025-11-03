{{-- 
    Vista: Listado de Profesionales
    Descripción: Muestra todos los profesionales con filtros de búsqueda
    Ruta: GET /professionals
--}}
@extends('layouts.marketplace')

@section('title', 'Profesionales - Servicios Pro')

@section('content')
    {{-- Header --}}
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-bold mb-4 text-center">
                Encuentra Profesionales
            </h1>
            <p class="text-xl text-indigo-100 text-center">
                Conecta con expertos verificados en tu área
            </p>
        </div>
    </section>

    {{-- Filtros de Búsqueda --}}
    <section class="bg-white shadow-md sticky top-16 z-40">
        <div class="container mx-auto px-4 py-6">
            <form method="GET" action="{{ route('professionals.index') }}" class="flex flex-wrap gap-4">
                {{-- Búsqueda por nombre --}}
                <div class="flex-1 min-w-[200px]">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Buscar profesionales..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                {{-- Filtro por ciudad --}}
                <div class="min-w-[180px]">
                    <input type="text" 
                           name="city" 
                           value="{{ request('city') }}"
                           placeholder="Ciudad..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                {{-- Ordenar por rating --}}
                <div class="min-w-[150px]">
                    <select name="sort" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Ordenar por...</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Mejor valorados</option>
                    </select>
                </div>

                {{-- Botones --}}
                <div class="flex gap-2">
                    <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                        <i class="fas fa-search mr-2"></i>
                        Buscar
                    </button>
                    
                    @if(request()->hasAny(['search', 'city', 'sort']))
                        <a href="{{ route('professionals.index') }}" 
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
                        
                        <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden">
                            {{-- Avatar y Header --}}
                            <div class="relative bg-gradient-to-br from-indigo-500 to-purple-600 h-32">
                                <div class="absolute -bottom-12 left-1/2 -translate-x-1/2">
                                    <div class="w-24 h-24 rounded-full bg-white p-1 shadow-xl">
                                        <div class="w-full h-full rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-3xl font-bold">
                                            {{ substr($professional->name, 0, 1) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Info --}}
                            <div class="pt-16 px-6 pb-6 text-center">
                                <h3 class="text-xl font-semibold text-gray-800 mb-1">
                                    {{ $professional->name }}
                                </h3>

                                @if($professional->city)
                                    <p class="text-gray-500 text-sm mb-3">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        {{ $professional->city }}
                                    </p>
                                @endif

                                {{-- Rating --}}
                                @if($reviewCount > 0)
                                    <div class="flex items-center justify-center gap-2 mb-3">
                                        <div class="flex items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $avgRating ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                            @endfor
                                        </div>
                                        <span class="font-semibold text-gray-700">{{ number_format($avgRating, 1) }}</span>
                                        <span class="text-gray-500 text-sm">({{ $reviewCount }})</span>
                                    </div>
                                @else
                                    <p class="text-gray-400 text-sm mb-3">Sin reseñas aún</p>
                                @endif

                                {{-- Bio (si existe) --}}
                                @if($professional->bio)
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                        {{ $professional->bio }}
                                    </p>
                                @endif

                                {{-- Servicios ofrecidos --}}
                                <div class="flex items-center justify-center gap-2 mb-4 text-sm text-gray-600">
                                    <i class="fas fa-briefcase text-indigo-600"></i>
                                    <span>{{ $servicesCount }} servicio{{ $servicesCount != 1 ? 's' : '' }}</span>
                                </div>

                                {{-- Botón ver perfil --}}
                                <a href="{{ route('professionals.show', $professional) }}" 
                                   class="block w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white py-3 rounded-lg font-semibold transition duration-200">
                                    Ver Perfil
                                </a>
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
        <section class="py-16 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold mb-4">¿Eres un profesional?</h2>
                <p class="text-xl text-indigo-100 mb-8">
                    Únete a nuestra plataforma y comienza a recibir clientes
                </p>
                <a href="{{ route('register') }}" 
                   class="inline-block bg-white text-indigo-600 hover:bg-gray-100 px-8 py-4 rounded-lg font-bold text-lg transition duration-200 shadow-lg">
                    Regístrate como Profesional
                </a>
            </div>
        </section>
    @endguest
@endsection
