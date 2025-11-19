@props(['service' => null, 'mode' => 'create'])

@php
    $isEdit = $mode === 'edit' && $service;
    $title = $isEdit ? 'Editar Servicio' : 'Crear Nuevo Servicio';
@endphp

<div x-data="serviceModal({{ $service ? $service->toJson() : 'null' }})" 
     x-show="open" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true"
     style="display: none;">
    
    <!-- Overlay -->
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" 
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
        <div class="relative transform overflow-hidden rounded-xl bg-white shadow-2xl transition-all w-full max-w-4xl max-h-[90vh] overflow-y-auto"
             x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             @click.away="open = false">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 px-6 py-5 sticky top-0 z-10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">{{ $title }}</h3>
                            <p class="text-purple-100 text-sm">Completa la información del servicio</p>
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
            <form @submit.prevent="submitForm()" class="p-6 space-y-6">
                
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                        Título del servicio <span class="text-red-500">*</span>
                    </label>
                    <input 
                        x-model="formData.title"
                        type="text" 
                        id="title"
                        required
                        maxlength="100"
                        placeholder="Ej: Reparación de fontanería, Limpieza de hogar..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Máximo 100 caracteres</p>
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Categoría <span class="text-red-500">*</span>
                    </label>
                    <select 
                        x-model="formData.category_id"
                        id="category_id"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Selecciona una categoría</option>
                        @foreach(\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Descripción <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        x-model="formData.description"
                        id="description"
                        required
                        rows="4"
                        maxlength="500"
                        placeholder="Describe detalladamente qué incluye tu servicio, experiencia, materiales..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent resize-none"></textarea>
                    <p class="text-xs text-gray-500 mt-1">
                        <span x-text="formData.description?.length || 0"></span>/500 caracteres
                    </p>
                </div>

                <!-- Price and Duration -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                            Precio (€) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">€</span>
                            <input 
                                x-model="formData.price"
                                type="number" 
                                id="price"
                                required
                                min="0"
                                step="0.01"
                                placeholder="0.00"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label for="duration" class="block text-sm font-semibold text-gray-700 mb-2">
                            Duración (minutos) <span class="text-red-500">*</span>
                        </label>
                        <select 
                            x-model="formData.duration"
                            id="duration"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="30">30 minutos</option>
                            <option value="60">1 hora</option>
                            <option value="90">1.5 horas</option>
                            <option value="120">2 horas</option>
                            <option value="180">3 horas</option>
                            <option value="240">4 horas</option>
                            <option value="480">8 horas (día completo)</option>
                        </select>
                    </div>
                </div>

                <!-- Photos Upload -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Fotos del servicio
                        <span class="text-gray-500 font-normal">(Opcional, máximo 5)</span>
                    </label>
                    
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-purple-400 transition-colors">
                        <input 
                            type="file" 
                            id="photos"
                            accept="image/*"
                            multiple
                            @change="handleFileUpload($event)"
                            class="hidden">
                        
                        <label for="photos" class="cursor-pointer">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-600">
                                <span class="font-semibold text-purple-600 hover:text-purple-500">Haz clic para subir</span>
                                o arrastra las imágenes aquí
                            </p>
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG, WEBP hasta 5MB cada una</p>
                        </label>
                    </div>

                    <!-- Preview de imágenes -->
                    <div x-show="photos.length > 0" class="mt-4 grid grid-cols-2 md:grid-cols-5 gap-3">
                        <template x-for="(photo, index) in photos" :key="index">
                            <div class="relative group">
                                <img :src="photo.url" class="w-full h-24 object-cover rounded-lg border border-gray-200">
                                <button 
                                    @click="removePhoto(index)"
                                    type="button"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Error Message -->
                <div x-show="errorMessage" class="p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm text-red-600" x-text="errorMessage"></p>
                </div>

                <!-- Success Message -->
                <div x-show="successMessage" class="p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-sm text-green-600" x-text="successMessage"></p>
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button 
                        @click="open = false" 
                        type="button"
                        class="px-6 py-2.5 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 transition font-medium">
                        Cancelar
                    </button>
                    <button 
                        type="submit"
                        :disabled="loading"
                        :class="{'opacity-50 cursor-not-allowed': loading}"
                        class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg hover:from-purple-700 hover:to-blue-700 transition font-semibold flex items-center gap-2">
                        <svg x-show="loading" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="loading ? 'Guardando...' : '{{ $isEdit ? 'Actualizar' : 'Crear' }} Servicio'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function serviceModal(existingService = null) {
    return {
        open: false,
        loading: false,
        errorMessage: '',
        successMessage: '',
        photos: [],
        formData: {
            title: existingService?.title || '',
            category_id: existingService?.category_id || '',
            description: existingService?.description || '',
            price: existingService?.price || '',
            duration: existingService?.duration || 60,
        },
        serviceId: existingService?.id || null,

        init() {
            // Cargar fotos existentes si es modo edición
            if (existingService?.photos) {
                existingService.photos.forEach(photo => {
                    this.photos.push({
                        url: photo.url || `/storage/${photo.path}`,
                        isExisting: true,
                        id: photo.id
                    });
                });
            }
        },

        handleFileUpload(event) {
            const files = Array.from(event.target.files);
            
            if (this.photos.length + files.length > 5) {
                this.errorMessage = 'Máximo 5 fotos permitidas';
                setTimeout(() => this.errorMessage = '', 3000);
                return;
            }

            files.forEach(file => {
                if (file.size > 5 * 1024 * 1024) { // 5MB
                    this.errorMessage = `${file.name} excede el tamaño máximo de 5MB`;
                    return;
                }

                const reader = new FileReader();
                reader.onload = (e) => {
                    this.photos.push({
                        url: e.target.result,
                        file: file,
                        isExisting: false
                    });
                };
                reader.readAsDataURL(file);
            });

            // Limpiar input
            event.target.value = '';
        },

        removePhoto(index) {
            this.photos.splice(index, 1);
        },

        async submitForm() {
            this.loading = true;
            this.errorMessage = '';
            this.successMessage = '';

            try {
                const formData = new FormData();
                
                // Agregar campos básicos
                Object.keys(this.formData).forEach(key => {
                    formData.append(key, this.formData[key]);
                });

                // Agregar fotos nuevas
                this.photos.forEach((photo, index) => {
                    if (!photo.isExisting && photo.file) {
                        formData.append(`photos[${index}]`, photo.file);
                    }
                });

                // Agregar IDs de fotos existentes a mantener
                const existingPhotoIds = this.photos
                    .filter(p => p.isExisting)
                    .map(p => p.id);
                formData.append('existing_photos', JSON.stringify(existingPhotoIds));

                const url = this.serviceId 
                    ? `/services/${this.serviceId}` 
                    : '/services';
                
                if (this.serviceId) {
                    formData.append('_method', 'PUT');
                }

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    this.successMessage = data.message || 'Servicio guardado exitosamente';
                    setTimeout(() => {
                        window.location.href = '/services';
                    }, 1500);
                } else {
                    this.errorMessage = data.message || 'Error al guardar el servicio';
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
