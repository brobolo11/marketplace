@props([
    'service',
    'professional'
])

<div x-data="bookingCalendar()" x-show="open" x-cloak
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
                        Selecciona las fechas de servicio
                    </label>
                    <div id="calendar" class="border border-gray-200 rounded-lg p-4"></div>
                </div>

                <!-- Selected Dates Display -->
                <div x-show="selectedDates.length > 0" class="mb-4 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm font-medium text-blue-900 mb-2">Fechas seleccionadas:</p>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="date in selectedDates" :key="date">
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-600 text-white text-sm rounded-full">
                                <span x-text="formatDate(date)"></span>
                                <button @click="removeDate(date)" type="button" class="hover:bg-blue-700 rounded-full p-0.5">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </span>
                        </template>
                    </div>
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
                    :disabled="loading || selectedDates.length === 0"
                    :class="{'opacity-50 cursor-not-allowed': loading || selectedDates.length === 0}"
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
</div>

@push('scripts')
<script>
function bookingCalendar() {
    return {
        open: false,
        calendar: null,
        selectedDates: [],
        description: '',
        loading: false,
        errorMessage: '',
        successMessage: '',
        serviceId: {{ $service->id }},
        professionalId: {{ $professional->id }},

        init() {
            // Escuchar evento para abrir modal
            this.$watch('open', (value) => {
                if (value) {
                    this.$nextTick(() => {
                        this.initCalendar();
                    });
                }
            });
        },

        initCalendar() {
            if (this.calendar) {
                this.calendar.destroy();
            }

            const { Calendar } = window.FullCalendar;
            const calendarEl = document.getElementById('calendar');

            this.calendar = new Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                selectable: true,
                selectMirror: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth'
                },
                validRange: {
                    start: new Date()
                },
                dateClick: (info) => {
                    this.toggleDate(info.dateStr);
                },
                events: async (fetchInfo, successCallback, failureCallback) => {
                    try {
                        // Aquí se cargarán los días ocupados desde la API
                        const response = await fetch(`/api/availability/{{ $professional->id }}`);
                        const data = await response.json();
                        successCallback(data.events || []);
                    } catch (error) {
                        console.error('Error loading availability:', error);
                        failureCallback(error);
                    }
                }
            });

            this.calendar.render();
        },

        toggleDate(dateStr) {
            const index = this.selectedDates.indexOf(dateStr);
            if (index > -1) {
                this.selectedDates.splice(index, 1);
            } else {
                this.selectedDates.push(dateStr);
            }
            this.updateCalendarHighlights();
        },

        removeDate(date) {
            const index = this.selectedDates.indexOf(date);
            if (index > -1) {
                this.selectedDates.splice(index, 1);
            }
            this.updateCalendarHighlights();
        },

        updateCalendarHighlights() {
            if (!this.calendar) return;
            
            // Remover highlights anteriores
            const highlighted = document.querySelectorAll('.fc-day.selected-date');
            highlighted.forEach(el => el.classList.remove('selected-date', 'bg-blue-100'));

            // Agregar nuevos highlights
            this.selectedDates.forEach(date => {
                const dayEl = document.querySelector(`[data-date="${date}"]`);
                if (dayEl) {
                    dayEl.classList.add('selected-date', 'bg-blue-100');
                }
            });
        },

        formatDate(dateStr) {
            const date = new Date(dateStr + 'T00:00:00');
            return date.toLocaleDateString('es-ES', { 
                day: 'numeric', 
                month: 'short',
                year: 'numeric'
            });
        },

        async submitBooking() {
            if (this.selectedDates.length === 0) {
                this.errorMessage = 'Por favor selecciona al menos una fecha';
                return;
            }

            this.loading = true;
            this.errorMessage = '';
            this.successMessage = '';

            try {
                const response = await fetch('/api/bookings', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        service_id: this.serviceId,
                        professional_id: this.professionalId,
                        dates: this.selectedDates,
                        description: this.description
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    this.successMessage = '¡Solicitud enviada! El profesional revisará tu petición.';
                    setTimeout(() => {
                        window.location.href = '/bookings';
                    }, 2000);
                } else {
                    this.errorMessage = data.message || 'Error al crear la reserva';
                }
            } catch (error) {
                console.error('Error:', error);
                this.errorMessage = 'Error al procesar la solicitud. Inténtalo de nuevo.';
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endpush

<style>
.fc-day.selected-date {
    background-color: rgba(59, 130, 246, 0.1) !important;
}
.fc-day.selected-date .fc-daygrid-day-number {
    background-color: #3b82f6;
    color: white;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
