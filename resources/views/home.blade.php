{{-- 
    Vista: Página de Inicio (Welcome)
    Descripción: Landing page del marketplace con hero, buscador, categorías y profesionales destacados
    Ruta: GET /
--}}
@extends('layouts.marketplace')

@section('title', 'Encuentra Profesionales de Confianza para tus Tareas del Hogar')

@section('content')
    <!-- DEBUG: Este es el archivo welcome.blade.php personalizado del marketplace -->
    {{-- Hero Section con Búsqueda --}}
    <section class="bg-gradient-to-br from-blue-600 via-blue-500 to-indigo-600 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl font-bold mb-6">
                    Encuentra el Profesional Perfecto para tu Hogar
                </h1>
                <p class="text-xl mb-8 text-blue-100">
                    Miles de profesionales verificados listos para ayudarte con fontanería, electricidad, limpieza y más
                </p>
                
                {{-- Buscador Principal --}}
                <form action="{{ route('services.index') }}" method="GET" class="bg-white rounded-lg shadow-xl p-4 flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="¿Qué servicio necesitas?" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                    <div class="flex-1">
                        <input 
                            type="text" 
                            name="city" 
                            placeholder="¿En qué ciudad?" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition duration-200">
                        <i class="fas fa-search mr-2"></i>
                        Buscar
                    </button>
                </form>
            </div>
        </div>
    </section>

    {{-- Categorías Destacadas --}}
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">
                    Categorías Más Populares
                </h2>
                <p class="text-lg text-gray-600">
                    Explora nuestros servicios más solicitados
                </p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
                @forelse($categories as $category)
                    <a href="{{ route('categories.show', $category) }}" 
                       class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 text-center group border border-gray-100 hover:border-blue-200 transform hover:-translate-y-2">
                        <div class="text-5xl mb-4 group-hover:scale-110 transition-transform duration-200 text-blue-600">
                            <i class="fas fa-{{ $category->icon }}"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 group-hover:text-blue-600 transition duration-200 text-base">
                            {{ $category->name }}
                        </h3>
                    </a>
                @empty
                    <div class="col-span-full text-center text-gray-500 py-8">
                        No hay categorías disponibles
                    </div>
                @endforelse
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('categories.index') }}" 
                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-lg font-bold text-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-th-large mr-2"></i>
                    Ver Todas las Categorías
                </a>
            </div>
        </div>
    </section>

    {{-- Profesionales Destacados --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">
                    Profesionales Destacados
                </h2>
                <p class="text-lg text-gray-600">
                    Conecta con expertos verificados en tu zona
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($professionals as $pro)
                    <a href="{{ route('professionals.show', $pro) }}" 
                       class="bg-white rounded-2xl border-2 border-gray-100 hover:border-blue-400 shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden group transform hover:-translate-y-2">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold mr-4 shadow-md">
                                    {{ strtoupper(substr($pro->name, 0, 1)) }}
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-800 group-hover:text-blue-600 transition duration-200 text-lg">
                                        {{ $pro->name }}
                                    </h3>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        {{ $pro->city }}
                                    </div>
                                </div>
                            </div>
                            
                            @if($pro->averageRating() > 0)
                                <div class="flex items-center mb-3 bg-yellow-50 rounded-lg p-2">
                                    <div class="flex text-yellow-400 mr-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= $pro->averageRating() ? '' : '-half-alt' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-sm font-bold text-gray-700">
                                        {{ number_format($pro->averageRating(), 1) }}
                                    </span>
                                    <span class="text-xs text-gray-500 ml-1">
                                        ({{ $pro->reviewsReceived->count() }})
                                    </span>
                                </div>
                            @endif
                            
                            @if($pro->bio)
                                <p class="text-sm text-gray-600 line-clamp-2 mb-4">
                                    {{ $pro->bio }}
                                </p>
                            @endif
                            
                            <div class="flex flex-wrap gap-2">
                                @foreach($pro->services->take(3) as $service)
                                    <span class="bg-blue-100 text-blue-700 text-xs font-medium px-3 py-1 rounded-full">
                                        {{ $service->category->name }}
                                    </span>
                                @endforeach
                                @if($pro->services->count() > 3)
                                    <span class="text-xs text-gray-500 font-medium px-3 py-1">
                                        +{{ $pro->services->count() - 3 }} más
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center text-gray-500 py-8">
                        No hay profesionales disponibles
                    </div>
                @endforelse
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('professionals.index') }}" 
                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-lg font-bold text-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-users mr-2"></i>
                    Ver Todos los Profesionales
                </a>
            </div>
        </div>
    </section>

    {{-- Cómo Funciona --}}
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">
                    ¿Cómo Funciona?
                </h2>
                <p class="text-gray-600">
                    Encontrar ayuda profesional es más fácil que nunca
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center text-white text-3xl mx-auto mb-4">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">1. Busca el Servicio</h3>
                    <p class="text-gray-600">
                        Explora categorías o busca el servicio específico que necesitas para tu hogar
                    </p>
                </div>
                
                <div class="text-center">
                    <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center text-white text-3xl mx-auto mb-4">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">2. Elige tu Profesional</h3>
                    <p class="text-gray-600">
                        Compara perfiles, precios y reseñas para encontrar el profesional perfecto
                    </p>
                </div>
                
                <div class="text-center">
                    <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center text-white text-3xl mx-auto mb-4">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">3. Reserva y Listo</h3>
                    <p class="text-gray-600">
                        Selecciona fecha y hora, y recibe confirmación instantánea de tu reserva
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Call to Action --}}
    <section class="py-20 bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-3xl mx-auto">
                <div class="mb-6">
                    <i class="fas fa-user-tie text-6xl mb-4 opacity-90"></i>
                </div>
                <h2 class="text-4xl font-bold mb-6">
                    ¿Eres un Profesional?
                </h2>
                <p class="text-xl mb-10 text-blue-100 leading-relaxed">
                    Únete a nuestra plataforma y conecta con miles de clientes que necesitan tus servicios. 
                    Aumenta tu visibilidad y haz crecer tu negocio.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @guest
                        <a href="{{ route('register') }}" 
                           class="bg-white text-blue-600 hover:bg-gray-100 px-10 py-4 rounded-lg font-bold text-lg transition duration-200 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                            <i class="fas fa-user-plus mr-2"></i>
                            Únete a Nosotros
                        </a>
                    @endguest
                    <a href="{{ route('professionals.index') }}" 
                       class="bg-transparent border-2 border-white hover:bg-white hover:text-blue-600 px-10 py-4 rounded-lg font-semibold text-lg transition duration-200">
                        <i class="fas fa-users mr-2"></i>
                        Conoce Nuestros Profesionales
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
