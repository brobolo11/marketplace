{{-- 
    Vista: Mensajes - Bandeja de Entrada
    Descripción: Lista de conversaciones del usuario
    Ruta: GET /messages
--}}
@extends('layouts.marketplace')

@section('title', 'Mensajes - Servicios Pro')

@section('content')
    {{-- Header --}}
    <section class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-bold mb-2 text-center">
                <i class="fas fa-envelope mr-2"></i>
                Mensajes
            </h1>
            <p class="text-xl text-purple-100 text-center">
                Gestiona tus conversaciones
            </p>
        </div>
    </section>

    {{-- Contenido --}}
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                @if($conversationUsers->count() > 0)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="divide-y">
                            @foreach($conversationUsers as $otherUser)
                                @php
                                    // Obtener el último mensaje de la conversación
                                    $lastMessage = App\Models\Message::conversationBetween(Auth::id(), $otherUser->id)->first();
                                    
                                    // Contar mensajes no leídos de este usuario
                                    $unreadCount = App\Models\Message::where('sender_id', $otherUser->id)
                                        ->where('receiver_id', Auth::id())
                                        ->where('is_read', false)
                                        ->count();
                                @endphp
                                
                                <a href="{{ route('messages.show', $otherUser) }}" 
                                   class="flex items-center gap-4 p-6 hover:bg-gray-50 transition duration-200 {{ $unreadCount > 0 ? 'bg-blue-50' : '' }}">
                                    {{-- Avatar --}}
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                                        {{ substr($otherUser->name, 0, 1) }}
                                    </div>

                                    {{-- Info --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <h3 class="text-lg font-semibold text-gray-800 truncate">
                                                {{ $otherUser->name }}
                                            </h3>
                                            @if($lastMessage)
                                                <span class="text-xs text-gray-500 flex-shrink-0 ml-2">
                                                    {{ $lastMessage->created_at->diffForHumans() }}
                                                </span>
                                            @endif
                                        </div>

                                        @if($otherUser->isPro())
                                            <span class="inline-block bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded-full mb-1">
                                                Profesional
                                            </span>
                                        @endif

                                        @if($lastMessage)
                                            <p class="text-sm text-gray-600 truncate {{ $unreadCount > 0 ? 'font-semibold' : '' }}">
                                                @if($lastMessage->sender_id === Auth::id())
                                                    <span class="text-gray-500">Tú:</span>
                                                @endif
                                                {{ $lastMessage->message }}
                                            </p>
                                        @endif
                                    </div>

                                    {{-- Badge de no leídos --}}
                                    @if($unreadCount > 0)
                                        <div class="flex-shrink-0">
                                            <span class="bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded-full min-w-[24px] text-center inline-block">
                                                {{ $unreadCount }}
                                            </span>
                                        </div>
                                    @else
                                        <i class="fas fa-chevron-right text-gray-400"></i>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    {{-- Sin conversaciones --}}
                    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                        <i class="fas fa-comments text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-2xl font-semibold text-gray-700 mb-2">
                            No tienes conversaciones
                        </h3>
                        <p class="text-gray-500 mb-6">
                            Comienza a comunicarte con profesionales o clientes
                        </p>
                        <div class="flex justify-center gap-4">
                            @if(Auth::user()->isPro())
                                <a href="{{ route('services.create') }}" 
                                   class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                                    Crear un Servicio
                                </a>
                            @else
                                <a href="{{ route('professionals.index') }}" 
                                   class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                                    Buscar Profesionales
                                </a>
                            @endif
                            <a href="{{ route('services.index') }}" 
                               class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold transition duration-200">
                                Ver Servicios
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
