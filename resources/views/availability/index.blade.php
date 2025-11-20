@extends('layouts.marketplace')

@section('title', 'Gestionar Disponibilidad - HouseFixes')

@section('content')
    <section class="py-8 bg-gray-50 min-h-screen">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-calendar-alt text-blue-600 mr-3"></i>
                        Gestionar Disponibilidad
                    </h1>
                    <p class="text-gray-600">Configura tus horarios de trabajo y d�as disponibles para recibir reservas</p>
                </div>

                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">
                                <i class="fas fa-clock text-blue-600 mr-2"></i>
                                Horario Semanal
                            </h2>
                            <p class="text-sm text-gray-600 mb-6">Define tus horas de trabajo para cada d�a de la semana</p>
                            
                            <div class="space-y-4">
                                @foreach([1 => 'Lunes', 2 => 'Martes', 3 => 'Mi�rcoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'S�bado', 0 => 'Domingo'] as $day => $dayName)
                                    @php
                                        $daySlots = $weeklyAvailability->get($day, collect());
                                    @endphp
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <h3 class="font-semibold text-gray-900">{{ $dayName }}</h3>
                                            <button type="button" onclick="addTimeSlot({{ $day }}, '{{ $dayName }}')" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                                <i class="fas fa-plus-circle mr-1"></i>
                                                Agregar horario
                                            </button>
                                        </div>
                                        
                                        <div id="slots-{{ $day }}" class="space-y-2">
                                            @forelse($daySlots as $slot)
                                                <div class="flex items-center gap-3 bg-gray-50 p-3 rounded-lg">
                                                    <i class="fas fa-clock text-gray-400"></i>
                                                    <span class="flex-1 text-sm text-gray-700">
                                                        {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                                    </span>
                                                    <form action="{{ route('availability.destroy', $slot->id) }}" method="POST" class="inline" onsubmit="return confirm('�Eliminar este horario?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-700 text-sm">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @empty
                                                <p class="text-sm text-gray-500 italic">No disponible</p>
                                            @endforelse
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">
                                <i class="fas fa-ban text-red-600 mr-2"></i>
                                Bloqueos Espec�ficos
                            </h2>
                            <p class="text-sm text-gray-600 mb-4">Marca d�as espec�ficos como no disponibles (vacaciones, festivos, etc.)</p>
                            
                            <form id="blockForm" class="mb-6">
                                @csrf
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                                    <input type="date" id="blockDate" name="specific_date" min="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                                </div>
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Raz�n (opcional)</label>
                                    <input type="text" id="blockReason" name="reason" placeholder="Ej: Vacaciones" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>
                                <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition">
                                    <i class="fas fa-plus-circle mr-2"></i>
                                    Agregar Bloqueo
                                </button>
                            </form>

                            <div class="space-y-2">
                                <h3 class="font-semibold text-gray-900 text-sm mb-2">D�as Bloqueados:</h3>
                                <div id="blocksList" class="space-y-2">
                                    @forelse($specificBlocks as $block)
                                        <div class="flex items-center justify-between bg-red-50 p-3 rounded-lg">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ \Carbon\Carbon::parse($block->specific_date)->isoFormat('D MMM YYYY') }}
                                                </p>
                                                @if($block->reason)
                                                    <p class="text-xs text-gray-600">{{ $block->reason }}</p>
                                                @endif
                                            </div>
                                            <button onclick="deleteBlock({{ $block->id }})" class="text-red-600 hover:text-red-700">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500 italic">Sin bloqueos</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="timeSlotModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md">
            <h3 class="text-xl font-bold text-gray-900 mb-4">
                Agregar Horario - <span id="modalDayName"></span>
            </h3>
            <form id="timeSlotForm" method="POST" action="{{ route('availability.store') }}">
                @csrf
                <input type="hidden" id="modalWeekday" name="weekday">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hora de Inicio</label>
                    <input type="time" name="start_time" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hora de Fin</label>
                    <input type="time" name="end_time" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="flex gap-3">
                    <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function addTimeSlot(weekday, dayName) {
            document.getElementById('modalWeekday').value = weekday;
            document.getElementById('modalDayName').textContent = dayName;
            document.getElementById('timeSlotModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('timeSlotModal').classList.add('hidden');
        }

        document.getElementById('blockForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            try {
                const response = await fetch('{{ route('availability.block') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        specific_date: formData.get('specific_date'),
                        reason: formData.get('reason')
                    })
                });

                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('Error al crear el bloqueo');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al crear el bloqueo');
            }
        });

        async function deleteBlock(blockId) {
            if (!confirm('�Eliminar este bloqueo?')) return;
            
            try {
                const response = await fetch(`/availability/block/${blockId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('Error al eliminar el bloqueo');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al eliminar el bloqueo');
            }
        }
    </script>
@endsection
