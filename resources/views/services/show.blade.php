{{-- 
    Vista: Detalle del Servicio
    Descripción: Muestra información completa del servicio con formulario de reserva
    Ruta: GET /services/{service}
--}}
@extends('layouts.marketplace')

@section('title', $service->title . ' - HouseFixes')

@section('content')
    {{-- Breadcrumb --}}
    <section class="bg-gray-100 py-4">
        <div class="container mx-auto px-4">
            <nav class="flex items-center text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Inicio</a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <a href="{{ route('services.index') }}" class="hover:text-blue-600">Servicios</a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <a href="{{ route('categories.show', $service->category) }}" class="hover:text-blue-600">{{ $service->category->name }}</a>
                <i class="fas fa-chevron-right mx-2 text-xs"></i>
                <span class="text-gray-800 font-medium">{{ $service->title }}</span>
            </nav>
        </div>
    </section>

    {{-- Contenido Principal --}}
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-3 gap-8">
                {{-- Columna Principal - Servicio --}}
                <div class="lg:col-span-2">
                    {{-- Título y Categoría --}}
                    <div class="mb-6">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm font-semibold">
                                <i class="fas fa-{{ $service->category->icon }} mr-1"></i>
                                {{ $service->category->name }}
                            </span>
                        </div>
                        <h1 class="text-4xl font-bold text-gray-800 mb-2">{{ $service->title }}</h1>
                        <div class="flex items-center gap-4 text-gray-600">
                            <span class="text-3xl font-bold text-blue-600">{{ number_format($service->price, 2) }}€</span>
                            <span class="text-sm">por hora</span>
                        </div>
                    </div>

                    {{-- Galería de Fotos --}}
                    @if($service->photos->count() > 0)
                        <div class="mb-8">
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($service->photos as $photo)
                                    <div class="aspect-square rounded-lg overflow-hidden shadow-md hover:shadow-xl transition duration-300">
                                        <img src="{{ Storage::url($photo->path) }}" 
                                             alt="Foto del servicio"
                                             class="w-full h-full object-cover hover:scale-110 transition duration-300 cursor-pointer">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="mb-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl h-64 flex items-center justify-center text-white text-8xl">
                            <i class="fas fa-{{ $service->category->icon }}"></i>
                        </div>
                    @endif

                    {{-- Descripción --}}
                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Descripción del servicio</h2>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                                {{ $service->description ?? 'No hay descripción disponible para este servicio.' }}
                            </p>
                        </div>
                    </div>

                    {{-- Información del Profesional --}}
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 mb-8">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Sobre el profesional</h2>
                        <div class="flex items-start gap-4">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                                {{ substr($service->user->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold text-gray-800 mb-1">{{ $service->user->name }}</h3>
                                
                                @if($service->user->city)
                                    <p class="text-gray-600 mb-2">
                                        <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>
                                        {{ $service->user->city }}
                                    </p>
                                @endif

                                @if($service->user->email)
                                    <p class="text-gray-600 mb-2">
                                        <i class="fas fa-envelope mr-2 text-blue-600"></i>
                                        {{ $service->user->email }}
                                    </p>
                                @endif

                                @if($service->user->phone)
                                    <p class="text-gray-600 mb-3">
                                        <i class="fas fa-phone mr-2 text-blue-600"></i>
                                        {{ $service->user->phone }}
                                    </p>
                                @endif

                                {{-- Rating del profesional --}}
                                @php
                                    $avgRating = $service->user->averageRating();
                                    $totalReviews = $service->user->reviewsReceived->count();
                                @endphp
                                
                                @if($totalReviews > 0)
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $avgRating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="font-semibold text-gray-700">{{ number_format($avgRating, 1) }}</span>
                                        <span class="text-gray-500">({{ $totalReviews }} reseñas)</span>
                                    </div>
                                @else
                                    <p class="text-gray-500 text-sm">Sin reseñas aún</p>
                                @endif

                                <a href="{{ route('professionals.show', $service->user) }}" 
                                   class="inline-block mt-4 text-blue-600 hover:text-blue-700 font-semibold">
                                    Ver perfil completo <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Reseñas del Servicio --}}
                    @php
                        $serviceReviews = $service->reviews()->with('user')->latest()->take(5)->get();
                    @endphp
                    
                    @if($serviceReviews->count() > 0)
                        <div class="mb-8">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Reseñas de este servicio</h2>
                            <div class="space-y-4">
                                @foreach($serviceReviews as $review)
                                    <div class="bg-gray-50 rounded-lg p-6">
                                        <div class="flex items-start gap-4">
                                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                                                {{ substr($review->user->name, 0, 1) }}
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between mb-2">
                                                    <h4 class="font-semibold text-gray-800">{{ $review->user->name }}</h4>
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
                        </div>
                    @endif
                </div>

                {{-- Columna Lateral - Formulario de Reserva --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-xl p-6 sticky top-24">
                        <h3 class="text-2xl font-semibold text-gray-800 mb-6">Solicitar reserva</h3>

                        @auth
                            @if(Auth::user()->isPro() && $service->user_id === Auth::id())
                                {{-- El profesional ve sus propias opciones --}}
                                <div class="text-center py-8">
                                    <i class="fas fa-tools text-5xl text-blue-600 mb-4"></i>
                                    <p class="text-gray-600 mb-6">Este es tu servicio</p>
                                    <div class="space-y-3">
                                        <a href="{{ route('services.edit', $service) }}" 
                                           class="block w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition duration-200">
                                            <i class="fas fa-edit mr-2"></i>
                                            Editar servicio
                                        </a>
                                        <form action="{{ route('services.destroy', $service) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('¿Estás seguro de eliminar este servicio?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition duration-200">
                                                <i class="fas fa-trash mr-2"></i>
                                                Eliminar servicio
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @elseif(Auth::user()->isPro())
                                <div class="text-center py-8">
                                    <i class="fas fa-info-circle text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-600">Los profesionales no pueden reservar servicios</p>
                                </div>
                            @else
                                {{-- Sección de reserva para clientes --}}
                                <div class="space-y-4">
                                    <div class="bg-blue-50 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-gray-700">Precio:</span>
                                            <span class="font-bold text-gray-800">{{ number_format($service->price, 2) }}€</span>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-600">Duración:</span>
                                            <span class="text-gray-800">{{ $service->duration }} min</span>
                                        </div>
                                    </div>

                                    <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg p-4 border border-green-200">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="font-semibold text-gray-800">Disponibilidad en tiempo real</span>
                                        </div>
                                        <p class="text-xs text-gray-600">
                                            Selecciona las fechas que necesitas en nuestro calendario interactivo
                                        </p>
                                    </div>

                                    @if($errors->any())
                                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                                            <ul class="list-disc list-inside">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div x-data="{ openModal: false }">
                                        <button 
                                            @click="openModal = true; $nextTick(() => { if (window.bookingCalendarInstance) window.bookingCalendarInstance.open = true; })"
                                            type="button"
                                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-4 rounded-lg font-bold text-lg transition duration-200 shadow-lg hover:shadow-xl">
                                            <i class="fas fa-calendar-check mr-2"></i>
                                            Seleccionar Fechas y Reservar
                                        </button>

                                        <!-- Modal de Calendario -->
                                        <div x-show="openModal" style="display: none;">
                                            <x-booking-calendar-modal 
                                                :service="$service" 
                                                :professional="$service->user" 
                                            />
                                        </div>
                                    </div>

                                    <p class="text-xs text-gray-500 text-center">
                                        Tu solicitud estará pendiente hasta que el profesional la confirme
                                    </p>
                                </div>
                            @endif
                        @else
                            {{-- Usuario no autenticado --}}
                            <div class="text-center py-8">
                                <i class="fas fa-lock text-5xl text-gray-400 mb-4"></i>
                                <p class="text-gray-600 mb-6">Inicia sesión para solicitar este servicio</p>
                                <div class="space-y-3">
                                    <a href="{{ route('login') }}" 
                                       class="block w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition duration-200">
                                        Iniciar Sesión
                                    </a>
                                    <a href="{{ route('register') }}" 
                                       class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg font-semibold transition duration-200">
                                        Crear Cuenta
                                    </a>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Servicios Relacionados --}}
    @php
        $relatedServices = App\Models\Service::where('category_id', $service->category_id)
            ->where('id', '!=', $service->id)
            ->with('user', 'photos', 'category')
            ->inRandomOrder()
            ->take(3)
            ->get();
    @endphp

    @if($relatedServices->count() > 0)
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-gray-800 mb-8">Servicios Similares</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($relatedServices as $related)
                        <a href="{{ route('services.show', $related) }}" 
                           class="bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden group">
                            <div class="relative h-48 bg-gradient-to-br from-blue-500 to-indigo-600 overflow-hidden">
                                @if($related->photos->count() > 0)
                                    <img src="{{ Storage::url($related->photos->first()->path) }}" 
                                         alt="{{ $related->title }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-white text-6xl">
                                        <i class="fas fa-{{ $related->category->icon }}"></i>
                                    </div>
                                @endif
                                <div class="absolute top-4 right-4 bg-white px-3 py-1 rounded-full shadow-lg">
                                    <span class="font-bold text-blue-600">{{ number_format($related->price, 2) }}€</span>
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-800 group-hover:text-blue-600 transition duration-200 mb-2">
                                    {{ $related->title }}
                                </h3>
                                <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ $related->description }}</p>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-sm font-bold">
                                        {{ substr($related->user->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">{{ $related->user->name }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
