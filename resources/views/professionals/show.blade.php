{{-- 
    Vista: Perfil del Profesional
    Descripción: Muestra el perfil completo del profesional con estadísticas, servicios y reseñas
    Ruta: GET /professionals/{professional}
--}}
@extends('layouts.marketplace')

@section('title', $professional->name . ' - Profesionales')

@section('content')
    {{-- Header del Perfil Compacto --}}
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center gap-6">
                {{-- Avatar --}}
                <div class="w-28 h-28 rounded-full bg-white p-2 shadow-2xl ring-4 ring-white/20">
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
                        <div class="w-full h-full rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-5xl font-bold">
                            {{ substr($professional->name, 0, 1) }}
                        </div>
                    @endif
                </div>

                {{-- Info Principal --}}
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-4xl font-bold mb-3">{{ $professional->name }}</h1>
                    
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mb-3 text-sm">
                        @if($professional->city)
                            <span class="text-indigo-100 bg-white/10 px-3 py-1 rounded-lg">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $professional->city }}
                            </span>
                        @endif

                        @if($professional->email)
                            <span class="text-indigo-100 bg-white/10 px-3 py-1 rounded-lg">
                                <i class="fas fa-envelope mr-1"></i>
                                {{ $professional->email }}
                            </span>
                        @endif

                        @if($professional->phone)
                            <span class="text-indigo-100 bg-white/10 px-3 py-1 rounded-lg">
                                <i class="fas fa-phone mr-1"></i>
                                {{ $professional->phone }}
                            </span>
                        @endif
                    </div>

                    {{-- Rating --}}
                    @if($totalReviews > 0)
                        <div class="flex items-center justify-center md:justify-start gap-2 bg-white/15 backdrop-blur-sm rounded-lg px-5 py-2.5 inline-flex">
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $averageRating ? 'text-yellow-400' : 'text-white/30' }} text-lg"></i>
                                @endfor
                            </div>
                            <span class="text-2xl font-bold">{{ number_format($averageRating, 1) }}</span>
                            <span class="text-indigo-100 text-sm">({{ $totalReviews }} reseñas)</span>
                        </div>
                    @else
                        <p class="text-indigo-200">Aún no tiene reseñas</p>
                    @endif
                </div>

                {{-- Estadísticas --}}
                <div class="grid grid-cols-3 gap-3 text-center">
                    <div class="bg-white/15 backdrop-blur-sm rounded-lg p-4 shadow-lg">
                        <div class="text-3xl font-bold mb-1">{{ $professional->services->count() }}</div>
                        <div class="text-xs text-indigo-100 font-medium">Servicios</div>
                    </div>
                    <div class="bg-white/15 backdrop-blur-sm rounded-lg p-4 shadow-lg">
                        <div class="text-3xl font-bold mb-1">{{ $totalReviews }}</div>
                        <div class="text-xs text-indigo-100 font-medium">Reseñas</div>
                    </div>
                    <div class="bg-white/15 backdrop-blur-sm rounded-lg p-4 shadow-lg">
                        <div class="text-3xl font-bold mb-1">{{ $completedBookings }}</div>
                        <div class="text-xs text-indigo-100 font-medium">Trabajos</div>
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
                            <div class="grid md:grid-cols-2 gap-5">
                                @foreach($professional->services->take(4) as $service)
                                    <div class="group bg-white rounded-lg overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-200 hover:border-indigo-300">
                                        <div class="relative h-36 bg-gradient-to-br from-indigo-500 to-purple-600 overflow-hidden flex items-center justify-center">
                                            @if($service->photos->count() > 0)
                                                <img src="{{ Storage::url($service->photos->first()->path) }}" 
                                                     alt="{{ $service->title }}"
                                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                            @else
                                                <div class="text-white text-4xl opacity-70">
                                                    <i class="fas fa-{{ $service->category->icon }}"></i>
                                                </div>
                                            @endif
                                            <div class="absolute top-2 left-2 bg-white px-2.5 py-1 rounded-md shadow-md">
                                                <span class="text-xs font-bold text-gray-700">{{ $service->category->name }}</span>
                                            </div>
                                            <div class="absolute bottom-2 right-2 bg-indigo-600 px-3 py-1 rounded-md shadow-lg">
                                                <span class="font-bold text-white text-sm">{{ number_format($service->price, 2) }}€</span>
                                            </div>
                                        </div>
                                        <div class="p-4 bg-gradient-to-b from-gray-50 to-white">
                                            <h3 class="font-bold text-gray-800 group-hover:text-indigo-600 transition duration-200 mb-1.5 text-sm line-clamp-1">
                                                {{ $service->title }}
                                            </h3>
                                            <p class="text-xs text-gray-600 line-clamp-2 mb-3 leading-relaxed">{{ $service->description }}</p>
                                            
                                            {{-- Botones de acción --}}
                                            <div class="flex gap-2">
                                                <a href="{{ route('services.show', $service) }}" 
                                                   class="flex-1 text-center bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white py-2 px-3 rounded-lg font-bold text-xs transition duration-200 shadow-md">
                                                    <i class="fas fa-handshake mr-1"></i>Contratar
                                                </a>
                                                <a href="{{ route('services.show', $service) }}" 
                                                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-3 rounded-lg font-semibold text-xs transition duration-200">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
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

                    {{-- Disponibilidad --}}
                    <div id="availability" class="bg-white rounded-xl shadow-md p-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">
                            <i class="fas fa-calendar-alt text-indigo-600 mr-2"></i>
                            Disponibilidad
                        </h2>

                        @php
                            $weeklyAvailability = $professional->availability()
                                ->whereNull('specific_date')
                                ->orderBy('weekday')
                                ->orderBy('start_time')
                                ->get()
                                ->groupBy('weekday');
                            
                            $daysOfWeek = [
                                1 => 'Lunes',
                                2 => 'Martes',
                                3 => 'Miércoles',
                                4 => 'Jueves',
                                5 => 'Viernes',
                                6 => 'Sábado',
                                0 => 'Domingo'
                            ];
                        @endphp

                        @if($weeklyAvailability->count() > 0)
                            <div class="space-y-4">
                                @foreach($daysOfWeek as $dayNum => $dayName)
                                    @if($weeklyAvailability->has($dayNum))
                                        <div class="border border-gray-200 rounded-lg p-4 bg-gradient-to-r from-indigo-50 to-purple-50">
                                            <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                                                <i class="fas fa-clock text-indigo-600 mr-2"></i>
                                                {{ $dayName }}
                                            </h3>
                                            <div class="grid md:grid-cols-2 gap-2">
                                                @foreach($weeklyAvailability[$dayNum] as $slot)
                                                    <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-indigo-200">
                                                        <i class="fas fa-check-circle text-green-500 text-sm"></i>
                                                        <span class="text-sm text-gray-700">
                                                            {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - 
                                                            {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-sm text-blue-800">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Los horarios mostrados son orientativos. Al reservar un servicio, podrás acordar la fecha y hora específica con el profesional.
                                </p>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-calendar-times text-gray-300 text-6xl mb-4"></i>
                                <p class="text-gray-500 text-lg">Este profesional aún no ha configurado su disponibilidad</p>
                            </div>
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
                                <a href="{{ route('messages.show', $professional) }}" 
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
