{{-- 
    Vista: Perfil del Profesional
    Descripción: Muestra el perfil completo del profesional con estadísticas, servicios y reseñas
    Ruta: GET /professionals/{professional}
--}}
@extends('layouts.marketplace')

@section('title', $professional->name . ' - Profesionales')

@section('content')
    {{-- Header del Perfil --}}
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center gap-8">
                {{-- Avatar --}}
                <div class="w-32 h-32 rounded-full bg-white p-2 shadow-2xl">
                    <div class="w-full h-full rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-5xl font-bold">
                        {{ substr($professional->name, 0, 1) }}
                    </div>
                </div>

                {{-- Info Principal --}}
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-4xl font-bold mb-2">{{ $professional->name }}</h1>
                    
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 mb-4">
                        @if($professional->city)
                            <span class="text-indigo-100">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                {{ $professional->city }}
                            </span>
                        @endif

                        @if($professional->email)
                            <span class="text-indigo-100">
                                <i class="fas fa-envelope mr-2"></i>
                                {{ $professional->email }}
                            </span>
                        @endif

                        @if($professional->phone)
                            <span class="text-indigo-100">
                                <i class="fas fa-phone mr-2"></i>
                                {{ $professional->phone }}
                            </span>
                        @endif
                    </div>

                    {{-- Rating --}}
                    @if($totalReviews > 0)
                        <div class="flex items-center justify-center md:justify-start gap-3">
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $averageRating ? 'text-yellow-400' : 'text-white/30' }} text-xl"></i>
                                @endfor
                            </div>
                            <span class="text-2xl font-bold">{{ number_format($averageRating, 1) }}</span>
                            <span class="text-indigo-100">({{ $totalReviews }} reseñas)</span>
                        </div>
                    @else
                        <p class="text-indigo-200">Aún no tiene reseñas</p>
                    @endif
                </div>

                {{-- Estadísticas --}}
                <div class="grid grid-cols-3 gap-6 text-center">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                        <div class="text-3xl font-bold">{{ $professional->services->count() }}</div>
                        <div class="text-sm text-indigo-100">Servicios</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                        <div class="text-3xl font-bold">{{ $totalReviews }}</div>
                        <div class="text-sm text-indigo-100">Reseñas</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                        <div class="text-3xl font-bold">{{ $completedBookings }}</div>
                        <div class="text-sm text-indigo-100">Trabajos</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Navegación por pestañas --}}
    <section class="bg-white border-b sticky top-16 z-40">
        <div class="container mx-auto px-4">
            <nav class="flex overflow-x-auto">
                <a href="#about" 
                   class="px-6 py-4 font-semibold text-indigo-600 border-b-2 border-indigo-600 hover:text-indigo-700 transition duration-200">
                    Sobre mí
                </a>
                <a href="#services" 
                   class="px-6 py-4 font-semibold text-gray-600 hover:text-indigo-600 hover:border-b-2 hover:border-indigo-600 transition duration-200">
                    Servicios ({{ $professional->services->count() }})
                </a>
                <a href="#reviews" 
                   class="px-6 py-4 font-semibold text-gray-600 hover:text-indigo-600 hover:border-b-2 hover:border-indigo-600 transition duration-200">
                    Reseñas ({{ $totalReviews }})
                </a>
                <a href="#availability" 
                   class="px-6 py-4 font-semibold text-gray-600 hover:text-indigo-600 hover:border-b-2 hover:border-indigo-600 transition duration-200">
                    Disponibilidad
                </a>
            </nav>
        </div>
    </section>

    {{-- Contenido del Perfil --}}
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-3 gap-8">
                {{-- Columna Principal --}}
                <div class="lg:col-span-2 space-y-8">
                    {{-- Sobre mí --}}
                    <div id="about" class="bg-white rounded-xl shadow-md p-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Sobre mí</h2>
                        @if($professional->bio)
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $professional->bio }}</p>
                        @else
                            <p class="text-gray-500 italic">Este profesional aún no ha agregado una biografía.</p>
                        @endif
                    </div>

                    {{-- Servicios Ofrecidos --}}
                    <div id="services" class="bg-white rounded-xl shadow-md p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Servicios Ofrecidos</h2>
                            <a href="{{ route('professionals.services', $professional) }}" 
                               class="text-indigo-600 hover:text-indigo-700 font-semibold">
                                Ver todos <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>

                        @if($professional->services->count() > 0)
                            <div class="grid md:grid-cols-2 gap-6">
                                @foreach($professional->services->take(4) as $service)
                                    <a href="{{ route('services.show', $service) }}" 
                                       class="group block bg-gray-50 rounded-lg overflow-hidden hover:shadow-lg transition duration-300">
                                        <div class="relative h-40 bg-gradient-to-br from-indigo-500 to-purple-600 overflow-hidden">
                                            @if($service->photos->count() > 0)
                                                <img src="{{ Storage::url($service->photos->first()->path) }}" 
                                                     alt="{{ $service->title }}"
                                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-white text-5xl">
                                                    <i class="fas fa-{{ $service->category->icon }}"></i>
                                                </div>
                                            @endif
                                            <div class="absolute bottom-2 right-2 bg-white px-3 py-1 rounded-full shadow-lg">
                                                <span class="font-bold text-indigo-600">{{ number_format($service->price, 2) }}€</span>
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <h3 class="font-semibold text-gray-800 group-hover:text-indigo-600 transition duration-200 mb-1">
                                                {{ $service->title }}
                                            </h3>
                                            <p class="text-sm text-gray-600 line-clamp-2">{{ $service->description }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>

                            @if($professional->services->count() > 4)
                                <div class="mt-6 text-center">
                                    <a href="{{ route('professionals.services', $professional) }}" 
                                       class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                                        Ver todos los servicios
                                    </a>
                                </div>
                            @endif
                        @else
                            <p class="text-gray-500 text-center py-8">Este profesional aún no ha publicado servicios.</p>
                        @endif
                    </div>

                    {{-- Reseñas --}}
                    <div id="reviews" class="bg-white rounded-xl shadow-md p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Reseñas de Clientes</h2>
                            @if($totalReviews > 3)
                                <a href="{{ route('professionals.reviews', $professional) }}" 
                                   class="text-indigo-600 hover:text-indigo-700 font-semibold">
                                    Ver todas <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            @endif
                        </div>

                        @if($totalReviews > 0)
                            {{-- Distribución de rating --}}
                            <div class="flex items-center gap-8 mb-6 pb-6 border-b">
                                <div class="text-center">
                                    <div class="text-5xl font-bold text-gray-800 mb-2">{{ number_format($averageRating, 1) }}</div>
                                    <div class="flex items-center justify-center gap-1 mb-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $averageRating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $totalReviews }} reseñas</div>
                                </div>
                            </div>

                            {{-- Lista de reseñas --}}
                            <div class="space-y-6">
                                @foreach($professional->reviewsReceived->take(3) as $review)
                                    <div class="border-b pb-6 last:border-0">
                                        <div class="flex items-start gap-4">
                                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                                {{ substr($review->client->name, 0, 1) }}
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between mb-2">
                                                    <h4 class="font-semibold text-gray-800">{{ $review->client->name }}</h4>
                                                    <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                                </div>
                                                <div class="flex items-center gap-1 mb-2">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                                    @endfor
                                                    <span class="ml-2 text-sm font-semibold text-gray-700">{{ $review->rating }}/5</span>
                                                </div>
                                                @if($review->comment)
                                                    <p class="text-gray-600 leading-relaxed">{{ $review->comment }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($totalReviews > 3)
                                <div class="mt-6 text-center">
                                    <a href="{{ route('professionals.reviews', $professional) }}" 
                                       class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold transition duration-200">
                                        Ver todas las reseñas
                                    </a>
                                </div>
                            @endif
                        @else
                            <p class="text-gray-500 text-center py-8">Este profesional aún no tiene reseñas.</p>
                        @endif
                    </div>
                </div>

                {{-- Columna Lateral --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-24 space-y-6">
                        {{-- Información de Contacto --}}
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Información de Contacto</h3>
                            <div class="space-y-3">
                                @if($professional->email)
                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-envelope text-indigo-600 mt-1"></i>
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-500">Email</p>
                                            <p class="text-gray-800">{{ $professional->email }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if($professional->phone)
                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-phone text-indigo-600 mt-1"></i>
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-500">Teléfono</p>
                                            <p class="text-gray-800">{{ $professional->phone }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if($professional->city)
                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-map-marker-alt text-indigo-600 mt-1"></i>
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-500">Ubicación</p>
                                            <p class="text-gray-800">{{ $professional->city }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Botón de Contacto --}}
                        @auth
                            @if(!Auth::user()->isPro() && Auth::id() !== $professional->id)
                                <a href="{{ route('messages.create', ['recipient' => $professional->id]) }}" 
                                   class="block w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-center py-4 rounded-lg font-bold shadow-lg hover:shadow-xl transition duration-200">
                                    <i class="fas fa-envelope mr-2"></i>
                                    Enviar Mensaje
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" 
                               class="block w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-center py-4 rounded-lg font-bold shadow-lg hover:shadow-xl transition duration-200">
                                Inicia sesión para contactar
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
