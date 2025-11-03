{{-- 
    Vista: Servicios del Profesional
    Descripción: Muestra todos los servicios ofrecidos por un profesional
    Ruta: GET /professionals/{professional}/services
--}}
@extends('layouts.marketplace')

@section('title', 'Servicios de ' . $professional->name)

@section('content')
    {{-- Breadcrumb --}}
    <section class="bg-gray-100 py-4">
        <div class="container mx-auto px-4">
            <nav class="flex items-center text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Inicio</a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <a href="{{ route('professionals.index') }}" class="hover:text-blue-600">Profesionales</a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <a href="{{ route('professionals.show', $professional) }}" class="hover:text-blue-600">{{ $professional->name }}</a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <span class="text-gray-800 font-medium">Servicios</span>
            </nav>
        </div>
    </section>

    {{-- Header --}}
    <section class="bg-white py-8 border-b">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-2xl font-bold">
                    {{ substr($professional->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Servicios de {{ $professional->name }}</h1>
                    <p class="text-gray-600">
                        {{ $services->total() }} servicio{{ $services->total() != 1 ? 's' : '' }} disponible{{ $services->total() != 1 ? 's' : '' }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Servicios --}}
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            @if($services->count() > 0)
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
                                    <span class="font-bold text-blue-600">{{ number_format($service->price, 2) }}€/h</span>
                                </div>
                            </div>

                            {{-- Info --}}
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-800 group-hover:text-blue-600 transition duration-200 mb-2">
                                    {{ $service->title }}
                                </h3>

                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                    {{ $service->description ?? 'Sin descripción' }}
                                </p>

                                {{-- Rating del servicio --}}
                                @php
                                    $serviceReviews = $service->reviews;
                                    $avgRating = $serviceReviews->avg('rating') ?? 0;
                                    $reviewCount = $serviceReviews->count();
                                @endphp
                                
                                @if($reviewCount > 0)
                                    <div class="flex items-center gap-2 pt-4 border-t">
                                        <div class="flex items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $avgRating ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                            @endfor
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700">{{ number_format($avgRating, 1) }}</span>
                                        <span class="text-xs text-gray-500">({{ $reviewCount }} reseñas)</span>
                                    </div>
                                @else
                                    <div class="pt-4 border-t">
                                        <span class="text-xs text-gray-400">Sin reseñas</span>
                                    </div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Paginación --}}
                <div class="mt-8">
                    {{ $services->links() }}
                </div>
            @else
                {{-- Sin servicios --}}
                <div class="text-center py-16">
                    <i class="fas fa-briefcase text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-2xl font-semibold text-gray-700 mb-2">
                        No hay servicios publicados
                    </h3>
                    <p class="text-gray-500 mb-6">
                        Este profesional aún no ha publicado ningún servicio
                    </p>
                    <a href="{{ route('professionals.show', $professional) }}" 
                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                        Volver al perfil
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white text-center">
                <h2 class="text-2xl font-bold mb-4">¿Te interesan estos servicios?</h2>
                <p class="text-indigo-100 mb-6">Contacta con {{ $professional->name }} para más información</p>
                <div class="flex justify-center gap-4">
                    <a href="{{ route('professionals.show', $professional) }}" 
                       class="bg-white text-indigo-600 hover:bg-gray-100 px-6 py-3 rounded-lg font-semibold transition duration-200">
                        Ver Perfil Completo
                    </a>
                    @auth
                        @if(!Auth::user()->isPro())
                            <a href="{{ route('messages.create', ['recipient' => $professional->id]) }}" 
                               class="bg-indigo-800 hover:bg-indigo-900 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                                Enviar Mensaje
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </section>
@endsection
