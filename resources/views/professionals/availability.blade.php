{{-- 
    Vista: Disponibilidad del Profesional
    Descripción: Muestra el calendario de disponibilidad semanal del profesional
    Ruta: GET /professionals/{professional}/availability
--}}
@extends('layouts.marketplace')

@section('title', 'Disponibilidad de ' . $professional->name)

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
                <span class="text-gray-800 font-medium">Disponibilidad</span>
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
                    <h1 class="text-3xl font-bold text-gray-800">Disponibilidad de {{ $professional->name }}</h1>
                    <p class="text-gray-600">Horarios disponibles para reservas</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Calendario de Disponibilidad --}}
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                @php
                    $weekdays = [
                        0 => 'Lunes',
                        1 => 'Martes',
                        2 => 'Miércoles',
                        3 => 'Jueves',
                        4 => 'Viernes',
                        5 => 'Sábado',
                        6 => 'Domingo'
                    ];
                @endphp

                @if($availability->count() > 0)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-4">
                            <h2 class="text-xl font-bold flex items-center gap-2">
                                <i class="fas fa-calendar-alt"></i>
                                Horario Semanal
                            </h2>
                        </div>

                        <div class="divide-y">
                            @foreach($weekdays as $dayNumber => $dayName)
                                <div class="p-6 hover:bg-gray-50 transition duration-200">
                                    <div class="flex flex-col md:flex-row md:items-center gap-4">
                                        <div class="md:w-32">
                                            <h3 class="text-lg font-semibold text-gray-800">{{ $dayName }}</h3>
                                        </div>
                                        
                                        <div class="flex-1">
                                            @if(isset($availability[$dayNumber]) && $availability[$dayNumber]->count() > 0)
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($availability[$dayNumber] as $slot)
                                                        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-2 rounded-lg flex items-center gap-2">
                                                            <i class="fas fa-clock text-sm"></i>
                                                            <span class="font-medium">
                                                                {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - 
                                                                {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-gray-400 italic flex items-center gap-2">
                                                    <i class="fas fa-times-circle"></i>
                                                    No disponible
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Información adicional --}}
                    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-info-circle text-blue-600 text-xl mt-1"></i>
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-2">Información importante</h4>
                                <ul class="text-sm text-gray-700 space-y-1">
                                    <li>• Los horarios mostrados son orientativos</li>
                                    <li>• La disponibilidad puede variar según las reservas existentes</li>
                                    <li>• Recomendamos contactar al profesional para confirmar</li>
                                    <li>• El profesional puede tener disponibilidad fuera de estos horarios bajo consulta</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- CTA --}}
                    <div class="mt-8 text-center">
                        <p class="text-gray-600 mb-4">¿Quieres reservar un servicio?</p>
                        <div class="flex justify-center gap-4">
                            <a href="{{ route('professionals.services', $professional) }}" 
                               class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                                Ver Servicios Disponibles
                            </a>
                            <a href="{{ route('professionals.show', $professional) }}" 
                               class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold transition duration-200">
                                Volver al Perfil
                            </a>
                        </div>
                    </div>
                @else
                    {{-- Sin disponibilidad configurada --}}
                    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                        <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-2xl font-semibold text-gray-700 mb-2">
                            Disponibilidad no configurada
                        </h3>
                        <p class="text-gray-500 mb-6">
                            Este profesional aún no ha configurado su disponibilidad horaria
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
                        
                        @auth
                            @if(!Auth::user()->isPro())
                                <p class="text-sm text-gray-500 mt-6">
                                    Puedes contactar al profesional directamente para consultar disponibilidad
                                </p>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
