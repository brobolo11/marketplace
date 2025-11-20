@props([
    'service',
    'professional'
])

<div x-data="bookingCalendar()">
    <!-- Botón para abrir el modal -->
    <button 
        @click="open = true"
        type="button"
        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-4 rounded-lg font-bold text-lg transition duration-200 shadow-lg hover:shadow-xl">
        <i class="fas fa-calendar-check mr-2"></i>
        Seleccionar Fechas y Reservar
    </button>

    <!-- Modal -->
    <div x-show="open" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true"
         style="display: none;">
        
        <!-- Overlay -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
         @click="open = false"
         x-show="open"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    <!-- Modal Content -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-lg bg-white shadow-xl transition-all w-full max-w-4xl"
             x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             @click.away="open = false">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Reservar Servicio</h3>
                            <p class="text-blue-100 text-sm">{{ $service->title }}</p>
                        </div>
                    </div>
                    <button @click="open = false" class="text-white hover:text-gray-200 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="px-6 py-6">
                <!-- Service Info -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        @if($professional->profile_photo_path)
                            <img src="{{ $professional->profile_photo_path }}" alt="{{ $professional->name }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($professional->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-semibold text-gray-900">{{ $professional->name }}</p>
                            <p class="text-sm text-gray-500">{{ $service->category->name }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($service->price, 2) }}€</p>
                        <p class="text-xs text-gray-500">{{ $service->duration }} min</p>
                    </div>
                </div>

                <!-- Calendar Container -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Selecciona una fecha para el servicio
                    </label>
                    <input 
                        type="date" 
                        x-model="selectedDate"
                        @change="loadAvailability()"
                        :min="new Date().toISOString().split('T')[0]"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg">
                    
                    <!-- Loading availability -->
                    <div x-show="loadingAvailability" class="mt-4 text-center py-4">
                        <svg class="animate-spin h-6 w-6 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-sm text-gray-600 mt-2">Cargando disponibilidad...</p>
                    </div>

                    <!-- Available Time Slots -->
                    <div x-show="selectedDate && !loadingAvailability && availableSlots.length > 0" class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Horarios disponibles
                        </label>
                        <div class="grid grid-cols-3 gap-2 max-h-60 overflow-y-auto">
                            <template x-for="slot in availableSlots" :key="slot">
                                <button 
                                    type="button"
                                    @click="selectedTime = slot"
                                    :class="{
                                        'bg-blue-600 text-white': selectedTime === slot,
                                        'bg-white text-gray-700 hover:bg-blue-50': selectedTime !== slot
                                    }"
                                    class="px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium transition">
                                    <span x-text="slot"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- No availability message -->
                    <div x-show="selectedDate && !loadingAvailability && availableSlots.length === 0" class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-800">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            No hay horarios disponibles para esta fecha. Por favor, selecciona otra fecha.
                        </p>
                    </div>
                </div>

                <!-- Selected Date Display -->
                <div x-show="selectedDate" class="mb-4 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm font-medium text-blue-900 mb-1">Fecha seleccionada:</p>
                    <p class="text-lg font-semibold text-blue-700" x-text="selectedDate"></p>
                    <p x-show="selectedTime" class="text-sm text-blue-600 mt-1">
                        Hora preferida: <span x-text="selectedTime"></span>
                    </p>
                </div>

                <!-- Description Field -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Describe tu necesidad
                    </label>
                    <textarea 
                        x-model="description"
                        id="description"
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Describe qué necesitas, detalles importantes, horarios preferidos, etc."></textarea>
                </div>

                <!-- Error Message -->
                <div x-show="errorMessage" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm text-red-600" x-text="errorMessage"></p>
                </div>

                <!-- Success Message -->
                <div x-show="successMessage" class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-sm text-green-600" x-text="successMessage"></p>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
                <button 
                    @click="open = false" 
                    type="button"
                    class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Cancelar
                </button>
                <button 
                    @click="submitBooking()"
                    :disabled="loading || !selectedDate"
                    :class="{'opacity-50 cursor-not-allowed': loading || !selectedDate}"
                    type="button"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                    <svg x-show="loading" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="loading ? 'Enviando...' : 'Solicitar Reserva'"></span>
                </button>
            </div>
        </div>
    </div>
    <!-- Fin del Modal -->
</div>
<!-- Fin del contenedor x-data -->

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('bookingCalendar', () => ({
        open: false,
        selectedDate: '',
        selectedTime: '',
        description: '',
        loading: false,
        loadingAvailability: false,
        errorMessage: '',
        successMessage: '',
        serviceId: {{ $service->id }},
        professionalId: {{ $professional->id }},
        availableSlots: [],

        async loadAvailability() {
            if (!this.selectedDate) return;

            this.loadingAvailability = true;
            this.availableSlots = [];
            this.selectedTime = '';
            this.errorMessage = '';

            try {
                // Obtener día de la semana (0 = domingo, 1 = lunes, etc.)
                const dateObj = new Date(this.selectedDate + 'T12:00:00');
                const dayOfWeek = dateObj.getDay();

                console.log('Fecha seleccionada:', this.selectedDate);
                console.log('Día de la semana:', dayOfWeek);

                // Cargar disponibilidad del profesional para ese día
                const availResponse = await fetch(`/json/professional/${this.professionalId}/availability?day=${dayOfWeek}`);
                
                if (!availResponse.ok) {
                    throw new Error('Error al obtener disponibilidad');
                }
                
                const availData = await availResponse.json();
                console.log('Disponibilidad recibida:', availData);

                if (!availData.schedules || availData.schedules.length === 0) {
                    console.log('No hay horarios para este día');
                    this.loadingAvailability = false;
                    return;
                }

                // Obtener reservas existentes para esa fecha
                const bookingsResponse = await fetch(`/json/professional/${this.professionalId}/bookings?date=${this.selectedDate}`);
                const bookingsData = await bookingsResponse.json();
                const bookedTimes = bookingsData.booked_times || [];
                
                console.log('Horas ocupadas:', bookedTimes);

                // Generar slots de 30 minutos para cada bloque de disponibilidad
                const allSlots = [];
                
                availData.schedules.forEach(schedule => {
                    const startTime = schedule.start_time.substring(0, 5); // HH:MM
                    const endTime = schedule.end_time.substring(0, 5);
                    
                    console.log(`Procesando horario: ${startTime} - ${endTime}`);
                    
                    const start = this.parseTime(startTime);
                    const end = this.parseTime(endTime);
                    
                    // Generar slots cada 30 minutos
                    for (let time = start; time < end; time += 30) {
                        const slotTime = this.formatTime(time);
                        // Verificar que el slot no esté ocupado
                        if (!bookedTimes.includes(slotTime)) {
                            allSlots.push(slotTime);
                        }
                    }
                });

                this.availableSlots = allSlots.sort();
                console.log('Slots disponibles:', this.availableSlots);
                
            } catch (error) {
                console.error('Error completo:', error);
                this.errorMessage = 'Error al cargar la disponibilidad: ' + error.message;
            } finally {
                this.loadingAvailability = false;
            }
        },

        parseTime(timeStr) {
            const [hours, minutes] = timeStr.split(':').map(Number);
            return hours * 60 + minutes;
        },

        formatTime(minutes) {
            const hours = Math.floor(minutes / 60);
            const mins = minutes % 60;
            return `${String(hours).padStart(2, '0')}:${String(mins).padStart(2, '0')}`;
        },

        async submitBooking() {
            if (!this.selectedDate) {
                this.errorMessage = 'Por favor selecciona una fecha';
                return;
            }

            this.loading = true;
            this.errorMessage = '';
            this.successMessage = '';

            try {
                // Crear un formulario y enviarlo
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/bookings';

                // CSRF Token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
                form.appendChild(csrfInput);

                // Service ID
                const serviceInput = document.createElement('input');
                serviceInput.type = 'hidden';
                serviceInput.name = 'service_id';
                serviceInput.value = this.serviceId;
                form.appendChild(serviceInput);

                // DateTime
                const datetimeInput = document.createElement('input');
                datetimeInput.type = 'hidden';
                datetimeInput.name = 'datetime';
                datetimeInput.value = this.selectedDate + (this.selectedTime ? ' ' + this.selectedTime + ':00' : ' 09:00:00');
                form.appendChild(datetimeInput);

                // Address (from description or default)
                const addressInput = document.createElement('input');
                addressInput.type = 'hidden';
                addressInput.name = 'address';
                addressInput.value = this.description || 'Dirección a confirmar';
                form.appendChild(addressInput);

                // Total Price (from service price)
                const priceInput = document.createElement('input');
                priceInput.type = 'hidden';
                priceInput.name = 'total_price';
                priceInput.value = {{ $service->price ?? 0 }};
                form.appendChild(priceInput);

                document.body.appendChild(form);
                form.submit();

            } catch (error) {
                console.error('Error:', error);
                this.errorMessage = 'Error al procesar la solicitud. Inténtalo de nuevo.';
                this.loading = false;
            }
        }
    }));
});
</script>
