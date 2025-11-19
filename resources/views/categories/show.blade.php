{{-- 
    Vista: Servicios de una Categoría
    Descripción: Muestra todos los servicios de una categoría específica
    Ruta: GET /categories/{category}
--}}
@extends('layouts.marketplace')

@section('title', $category->name . ' - HouseFixes')

@section('content')
    {{-- Header de Categoría --}}
    <section class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                {{-- Breadcrumb --}}
                <div class="mb-4">
                    <a href="{{ route('categories.index') }}" class="text-blue-200 hover:text-white transition">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver a Categorías
                    </a>
                </div>

                <div class="flex items-center gap-6">
                    {{-- Icono --}}
                    <div class="text-6xl">
                        <i class="fas fa-{{ $category->icon }}"></i>
                    </div>
                    
                    {{-- Info --}}
                    <div class="flex-1">
                        <h1 class="text-4xl font-bold mb-2">
                            {{ $category->name }}
                        </h1>
                        @if($category->description)
                            <p class="text-xl text-blue-100">
                                {{ $category->description }}
                            </p>
                        @endif
                        <p class="text-blue-200 mt-2">
                            {{ $category->services->count() }} 
                            {{ $category->services->count() === 1 ? 'servicio disponible' : 'servicios disponibles' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Servicios --}}
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            @if($category->services->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($category->services as $service)
                        <a href="{{ route('services.show', $service) }}" 
                           class="bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden group">
                            {{-- Imagen del servicio --}}
                            <div class="relative h-48 bg-gradient-to-br from-blue-500 to-indigo-600 overflow-hidden">
                                @if($service->photos->count() > 0)
                                    <img src="{{ Storage::url($service->photos->first()->path) }}" 
                                         alt="{{ $service->title }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-white text-6xl">
                                        <i class="fas fa-{{ $category->icon }}"></i>
                                    </div>
                                @endif
                                
                                {{-- Badge de precio --}}
                                <div class="absolute top-4 right-4 bg-white px-3 py-1 rounded-full shadow-lg">
                                    <span class="font-bold text-blue-600">{{ number_format($service->price, 2) }}€</span>
                                </div>
                            </div>

                            {{-- Info del servicio --}}
                            <div class="p-6">
                                {{-- Título --}}
                                <h3 class="text-xl font-semibold text-gray-800 group-hover:text-blue-600 transition duration-200 mb-2">
                                    {{ $service->title }}
                                </h3>

                                {{-- Descripción --}}
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    {{ $service->description }}
                                </p>

                                {{-- Profesional --}}
                                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                                    {{-- Avatar --}}
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                                        {{ substr($service->user->name, 0, 1) }}
                                    </div>
                                    
                                    {{-- Info --}}
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800">{{ $service->user->name }}</p>
                                        @if($service->user->city)
                                            <p class="text-xs text-gray-500">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                {{ $service->user->city }}
                                            </p>
                                        @endif
                                    </div>

                                    {{-- Rating (si tiene) --}}
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
            @else
                {{-- Sin servicios --}}
                <div class="text-center py-16">
                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-2xl font-semibold text-gray-700 mb-2">
                        No hay servicios disponibles
                    </h3>
                    <p class="text-gray-500 mb-6">
                        Aún no hay profesionales ofreciendo servicios en esta categoría
                    </p>
                    @auth
                        @if(Auth::user()->isPro())
                            <a href="{{ route('services.create') }}" 
                               class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Sé el Primero en Ofrecer
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </section>

    {{-- Categorías Relacionadas --}}
    @php
        $relatedCategories = \App\Models\Category::where('id', '!=', $category->id)
            ->withCount('services')
            ->limit(6)
            ->get();
    @endphp

    @if($relatedCategories->count() > 0)
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">
                    Otras Categorías
                </h2>
                
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    @foreach($relatedCategories as $relatedCategory)
                        <a href="{{ route('categories.show', $relatedCategory) }}" 
                           class="bg-gray-50 rounded-lg p-4 hover:bg-blue-50 hover:shadow-md transition duration-200 text-center group">
                            <div class="text-3xl mb-2 group-hover:scale-110 transition duration-200">
                                <i class="fas fa-{{ $relatedCategory->icon }}"></i>
                            </div>
                            <h4 class="font-medium text-gray-800 text-sm group-hover:text-blue-600 transition duration-200">
                                {{ $relatedCategory->name }}
                            </h4>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $relatedCategory->services_count }} servicios
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
