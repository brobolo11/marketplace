{{-- 
    Vista: Crear Reseña
    Descripción: Formulario para que clientes dejen reseñas de servicios completados
    Ruta: GET /reviews/create?booking={booking_id}
--}}
@extends('layouts.marketplace')

@section('title', 'Dejar Reseña - HouseFixes')

@section('content')
    <section class="py-8 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto">
                {{-- Header --}}
                <div class="text-center mb-8">
                    <i class="fas fa-star text-5xl text-yellow-400 mb-4"></i>
                    <h1 class="text-4xl font-bold text-gray-800 mb-2">¿Cómo fue tu experiencia?</h1>
                    <p class="text-gray-600">Tu opinión ayuda a otros clientes a tomar decisiones</p>
                </div>

                {{-- Información del Servicio --}}
                <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Servicio Recibido</h2>
                    <div class="flex items-start gap-4">
                        <div class="w-20 h-20 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-3xl flex-shrink-0 overflow-hidden">
                            @if($booking->service->photos->count() > 0)
                                <img src="{{ Storage::url($booking->service->photos->first()->path) }}" 
                                     alt="{{ $booking->service->title }}"
                                     class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-{{ $booking->service->category->icon }}"></i>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ $booking->service->title }}</h3>
                            <p class="text-sm text-gray-500 mb-2">{{ $booking->service->category->name }}</p>
                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                <span><i class="fas fa-calendar-alt mr-1"></i>{{ $booking->datetime->format('d/m/Y') }}</span>
                                <span><i class="fas fa-user mr-1"></i>{{ $booking->professional->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Formulario de Reseña --}}
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <form action="{{ route('reviews.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                        {{-- Rating con Estrellas --}}
                        <div>
                            <label class="block text-lg font-semibold text-gray-800 mb-3 text-center">
                                Calificación <span class="text-red-500">*</span>
                            </label>
                            <div class="flex justify-center gap-3 mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer group">
                                        <input type="radio" 
                                               name="rating" 
                                               value="{{ $i }}" 
                                               class="hidden rating-input"
                                               {{ old('rating') == $i ? 'checked' : '' }}
                                               required>
                                        <i class="fas fa-star text-5xl text-gray-300 group-hover:text-yellow-300 transition duration-200 rating-star" 
                                           data-rating="{{ $i }}"></i>
                                    </label>
                                @endfor
                            </div>
                            <div class="text-center">
                                <span id="rating-text" class="text-gray-600 font-medium">Selecciona una calificación</span>
                            </div>
                            @error('rating')
                                <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Comentario --}}
                        <div>
                            <label for="comment" class="block text-sm font-semibold text-gray-700 mb-2">
                                Cuéntanos más sobre tu experiencia (Opcional)
                            </label>
                            <textarea name="comment" 
                                      id="comment" 
                                      rows="6"
                                      maxlength="1000"
                                      placeholder="¿Qué te gustó? ¿Cómo fue el servicio? ¿Recomendarías a este profesional?"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('comment') border-red-500 @enderror">{{ old('comment') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Máximo 1000 caracteres</p>
                            @error('comment')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Información --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800 mb-2">Ten en cuenta</h4>
                                    <ul class="text-sm text-gray-700 space-y-1">
                                        <li>• Tu reseña será pública y visible para todos los usuarios</li>
                                        <li>• Solo puedes dejar una reseña por servicio completado</li>
                                        <li>• Sé honesto y respetuoso en tus comentarios</li>
                                        <li>• Tu opinión ayuda a la comunidad</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Errores generales --}}
                        @if($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-exclamation-circle mt-1"></i>
                                    <div class="flex-1">
                                        <h4 class="font-semibold mb-2">Hay errores en el formulario:</h4>
                                        <ul class="text-sm list-disc list-inside">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Botones --}}
                        <div class="flex gap-4 pt-6 border-t">
                            <button type="submit" 
                                    class="flex-1 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white py-4 rounded-lg font-bold text-lg transition duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-star mr-2"></i>
                                Publicar Reseña
                            </button>
                            <a href="{{ route('bookings.show', $booking) }}" 
                               class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-4 rounded-lg font-bold text-lg transition duration-200 text-center">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        // Sistema de calificación con estrellas interactivo
        document.addEventListener('DOMContentLoaded', function() {
            const ratingInputs = document.querySelectorAll('.rating-input');
            const ratingStars = document.querySelectorAll('.rating-star');
            const ratingText = document.getElementById('rating-text');
            
            const ratingLabels = {
                1: 'Muy malo',
                2: 'Malo',
                3: 'Aceptable',
                4: 'Bueno',
                5: 'Excelente'
            };

            // Función para actualizar estrellas
            function updateStars(rating) {
                ratingStars.forEach((star, index) => {
                    const starRating = parseInt(star.dataset.rating);
                    if (starRating <= rating) {
                        star.classList.remove('text-gray-300');
                        star.classList.add('text-yellow-400');
                    } else {
                        star.classList.remove('text-yellow-400');
                        star.classList.add('text-gray-300');
                    }
                });
                
                if (rating > 0) {
                    ratingText.textContent = ratingLabels[rating];
                    ratingText.classList.remove('text-gray-600');
                    ratingText.classList.add('text-gray-800', 'font-bold');
                } else {
                    ratingText.textContent = 'Selecciona una calificación';
                    ratingText.classList.remove('text-gray-800', 'font-bold');
                    ratingText.classList.add('text-gray-600');
                }
            }

            // Evento click en inputs de rating
            ratingInputs.forEach(input => {
                input.addEventListener('change', function() {
                    updateStars(parseInt(this.value));
                });
            });

            // Hover effect
            ratingStars.forEach((star, index) => {
                star.addEventListener('mouseenter', function() {
                    const rating = parseInt(this.dataset.rating);
                    ratingStars.forEach((s, i) => {
                        if (parseInt(s.dataset.rating) <= rating) {
                            s.classList.remove('text-gray-300');
                            s.classList.add('text-yellow-300');
                        }
                    });
                });
            });

            // Mouse leave - restore selected rating
            document.querySelector('.flex.justify-center.gap-3').addEventListener('mouseleave', function() {
                const selectedInput = document.querySelector('.rating-input:checked');
                if (selectedInput) {
                    updateStars(parseInt(selectedInput.value));
                } else {
                    updateStars(0);
                }
            });

            // Inicializar con valor antiguo si existe
            const selectedInput = document.querySelector('.rating-input:checked');
            if (selectedInput) {
                updateStars(parseInt(selectedInput.value));
            }
        });
    </script>
    @endpush
@endsection
