{{-- 
    Vista: Reseñas del Profesional
    Descripción: Muestra todas las reseñas recibidas por el profesional con estadísticas
    Ruta: GET /professionals/{professional}/reviews
--}}
@extends('layouts.marketplace')

@section('title', 'Reseñas de ' . $professional->name)

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
                <span class="text-gray-800 font-medium">Reseñas</span>
            </nav>
        </div>
    </section>

    {{-- Header --}}
    <section class="bg-white py-8 border-b">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-2xl font-bold">
                    {{ substr($professional->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Reseñas de {{ $professional->name }}</h1>
                    <p class="text-gray-600">{{ $totalReviews }} reseña{{ $totalReviews != 1 ? 's' : '' }} de clientes</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Contenido --}}
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            @if($totalReviews > 0)
                <div class="grid lg:grid-cols-3 gap-8">
                    {{-- Resumen de Calificaciones --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-lg p-8 sticky top-24">
                            <h2 class="text-xl font-bold text-gray-800 mb-6">Resumen de Calificaciones</h2>
                            
                            {{-- Rating promedio --}}
                            <div class="text-center mb-8 pb-8 border-b">
                                <div class="text-6xl font-bold text-gray-800 mb-2">{{ number_format($averageRating, 1) }}</div>
                                <div class="flex items-center justify-center gap-1 mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $averageRating ? 'text-yellow-400' : 'text-gray-300' }} text-xl"></i>
                                    @endfor
                                </div>
                                <div class="text-sm text-gray-600">Basado en {{ $totalReviews }} reseñas</div>
                            </div>

                            {{-- Distribución de ratings --}}
                            <div class="space-y-3">
                                @for($i = 5; $i >= 1; $i--)
                                    @php
                                        $count = $ratingDistribution[$i] ?? 0;
                                        $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                    @endphp
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-medium text-gray-700 w-12">{{ $i }} <i class="fas fa-star text-yellow-400 text-xs"></i></span>
                                        <div class="flex-1 bg-gray-200 rounded-full h-3 overflow-hidden">
                                            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-full rounded-full" 
                                                 style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600 w-12 text-right">{{ $count }}</span>
                                    </div>
                                @endfor
                            </div>

                            {{-- Botón volver --}}
                            <div class="mt-8">
                                <a href="{{ route('professionals.show', $professional) }}" 
                                   class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-700 text-center py-3 rounded-lg font-semibold transition duration-200">
                                    Volver al Perfil
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Lista de Reseñas --}}
                    <div class="lg:col-span-2">
                        <div class="space-y-6">
                            @foreach($reviews as $review)
                                <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition duration-300">
                                    {{-- Header de la reseña --}}
                                    <div class="flex items-start gap-4 mb-4">
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold flex-shrink-0">
                                            {{ substr($review->client->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-start justify-between mb-2">
                                                <div>
                                                    <h3 class="font-semibold text-gray-800">{{ $review->client->name }}</h3>
                                                    @if($review->booking && $review->booking->service)
                                                        <p class="text-sm text-gray-500">
                                                            Servicio: {{ $review->booking->service->title }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                            
                                            {{-- Rating --}}
                                            <div class="flex items-center gap-2">
                                                <div class="flex items-center gap-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                                    @endfor
                                                </div>
                                                <span class="font-semibold text-gray-700">{{ $review->rating }}/5</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Comentario --}}
                                    @if($review->comment)
                                        <div class="bg-gray-50 rounded-lg p-4 mt-4">
                                            <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>
                                        </div>
                                    @else
                                        <p class="text-gray-400 italic text-sm mt-4">Sin comentario escrito</p>
                                    @endif

                                    {{-- Fecha de la reserva --}}
                                    @if($review->booking)
                                        <div class="mt-4 pt-4 border-t flex items-center gap-2 text-sm text-gray-500">
                                            <i class="fas fa-calendar-check"></i>
                                            <span>Servicio realizado el {{ $review->booking->datetime->format('d/m/Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Paginación --}}
                        <div class="mt-8">
                            {{ $reviews->links() }}
                        </div>
                    </div>
                </div>
            @else
                {{-- Sin reseñas --}}
                <div class="max-w-2xl mx-auto">
                    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                        <i class="fas fa-star text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-2xl font-semibold text-gray-700 mb-2">
                            Aún no hay reseñas
                        </h3>
                        <p class="text-gray-500 mb-6">
                            {{ $professional->name }} aún no ha recibido reseñas de sus clientes
                        </p>
                        <p class="text-sm text-gray-400 mb-8">
                            Las reseñas aparecerán aquí una vez que los clientes completen servicios y dejen sus opiniones
                        </p>
                        <div class="flex justify-center gap-4">
                            <a href="{{ route('professionals.services', $professional) }}" 
                               class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                                Ver Servicios
                            </a>
                            <a href="{{ route('professionals.show', $professional) }}" 
                               class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold transition duration-200">
                                Volver al Perfil
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- CTA --}}
    @if($totalReviews > 0)
        <section class="py-12 bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white text-center">
                    <h2 class="text-2xl font-bold mb-4">¿Convencido por las reseñas?</h2>
                    <p class="text-indigo-100 mb-6">Contrata los servicios de {{ $professional->name }} ahora</p>
                    <div class="flex justify-center gap-4">
                        <a href="{{ route('professionals.services', $professional) }}" 
                           class="bg-white text-indigo-600 hover:bg-gray-100 px-6 py-3 rounded-lg font-semibold transition duration-200">
                            Ver Servicios Disponibles
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
    @endif
@endsection
