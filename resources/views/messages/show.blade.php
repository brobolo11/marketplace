{{-- 
    Vista: Conversación de Mensajes
    Descripción: Muestra la conversación completa con un usuario y permite enviar mensajes
    Ruta: GET /messages/{user}
--}}
@extends('layouts.marketplace')

@section('title', 'Conversación con ' . $user->name)

@section('content')
    {{-- Header --}}
    <section class="bg-white border-b sticky top-16 z-40">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('messages.index') }}" 
                   class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div class="flex-1">
                    <h1 class="text-xl font-semibold text-gray-800">{{ $user->name }}</h1>
                    @if($user->isPro())
                        <span class="text-sm text-purple-600">Profesional</span>
                    @else
                        <span class="text-sm text-gray-500">Cliente</span>
                    @endif
                </div>
                <a href="{{ $user->isPro() ? route('professionals.show', $user) : '#' }}" 
                   class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                    @if($user->isPro())
                        Ver Perfil
                    @endif
                </a>
            </div>
        </div>
    </section>

    {{-- Mensajes --}}
    <section class="py-6 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                {{-- Área de Mensajes --}}
                <div id="messages-container" class="bg-white rounded-xl shadow-lg mb-6 overflow-hidden">
                    <div id="messages-list" class="p-6 space-y-4 max-h-[600px] overflow-y-auto">
                        @forelse($messages as $message)
                            @php
                                $isMine = $message->sender_id === Auth::id();
                            @endphp
                            
                            <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                                <div class="flex items-end gap-2 max-w-[70%] {{ $isMine ? 'flex-row-reverse' : '' }}">
                                    {{-- Avatar --}}
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br {{ $isMine ? 'from-blue-500 to-indigo-600' : 'from-purple-500 to-indigo-600' }} flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                        {{ substr($isMine ? Auth::user()->name : $user->name, 0, 1) }}
                                    </div>

                                    {{-- Mensaje --}}
                                    <div>
                                        <div class="px-4 py-3 rounded-2xl {{ $isMine ? 'bg-blue-600 text-white rounded-br-none' : 'bg-gray-200 text-gray-800 rounded-bl-none' }}">
                                            <p class="text-sm leading-relaxed break-words">{{ $message->message }}</p>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1 {{ $isMine ? 'text-right' : 'text-left' }} px-2">
                                            {{ $message->created_at->format('H:i') }}
                                            @if($isMine && $message->is_read)
                                                <i class="fas fa-check-double text-blue-500 ml-1"></i>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <i class="fas fa-comment-dots text-5xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">No hay mensajes en esta conversación</p>
                                <p class="text-sm text-gray-400 mt-2">Envía el primer mensaje para comenzar</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Formulario de Envío --}}
                <div class="bg-white rounded-xl shadow-lg p-4">
                    <form id="message-form" action="{{ route('messages.store') }}" method="POST" class="flex gap-3">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                        
                        <div class="flex-1">
                            <textarea name="message" 
                                      id="message-input"
                                      rows="1"
                                      maxlength="1000"
                                      placeholder="Escribe tu mensaje..."
                                      required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                      style="max-height: 120px;"></textarea>
                        </div>

                        <button type="submit" 
                                class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 rounded-lg font-semibold transition duration-200 self-end">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>

                    @if($errors->any())
                        <div class="mt-3 bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded-lg text-sm">
                            {{ $errors->first() }}
                        </div>
                    @endif
                </div>

                {{-- Info adicional --}}
                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-500">
                        Los mensajes se almacenan de forma segura. Sé respetuoso en tus conversaciones.
                    </p>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messagesList = document.getElementById('messages-list');
            const messageForm = document.getElementById('message-form');
            const messageInput = document.getElementById('message-input');

            // Auto-scroll al final de los mensajes
            function scrollToBottom() {
                messagesList.scrollTop = messagesList.scrollHeight;
            }
            
            // Scroll inicial
            scrollToBottom();

            // Auto-resize del textarea
            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 120) + 'px';
            });

            // Enviar mensaje con Enter (Shift+Enter para nueva línea)
            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    messageForm.submit();
                }
            });

            // Envío del formulario con AJAX
            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const messageText = messageInput.value.trim();
                
                if (!messageText) return;

                // Deshabilitar input mientras se envía
                messageInput.disabled = true;

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Agregar mensaje a la lista
                        const messageHtml = `
                            <div class="flex justify-end">
                                <div class="flex items-end gap-2 max-w-[70%] flex-row-reverse">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                        ${data.message.sender.name.charAt(0)}
                                    </div>
                                    <div>
                                        <div class="px-4 py-3 rounded-2xl bg-blue-600 text-white rounded-br-none">
                                            <p class="text-sm leading-relaxed break-words">${messageText}</p>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1 text-right px-2">
                                            Ahora
                                        </p>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        messagesList.insertAdjacentHTML('beforeend', messageHtml);
                        messageInput.value = '';
                        messageInput.style.height = 'auto';
                        scrollToBottom();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al enviar el mensaje. Por favor, intenta de nuevo.');
                })
                .finally(() => {
                    messageInput.disabled = false;
                    messageInput.focus();
                });
            });

            // Actualizar mensajes cada 10 segundos
            setInterval(function() {
                location.reload();
            }, 30000); // 30 segundos
        });
    </script>
    @endpush
@endsection
