{{-- 
    Vista: Editar Servicio
    Descripción: Formulario para que profesionales editen sus servicios
    Ruta: GET /services/{service}/edit
--}}
@extends('layouts.marketplace')

@section('title', 'Editar Servicio - HouseFixes')

@section('content')
    <section class="py-12 bg-gray-50 min-h-screen">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto">
                {{-- Header --}}
                <div class="mb-8">
                    <a href="{{ route('services.show', $service) }}" 
                       class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold mb-4">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver al servicio
                    </a>
                    <h1 class="text-4xl font-bold text-gray-800 mb-2">Editar Servicio</h1>
                    <p class="text-gray-600">Actualiza la información de tu servicio</p>
                </div>

                {{-- Formulario --}}
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <form action="{{ route('services.update', $service) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Categoría --}}
                        <div>
                            <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                Categoría <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" 
                                    id="category_id" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category_id') border-red-500 @enderror">
                                <option value="">Selecciona una categoría</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ (old('category_id', $service->category_id) == $category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Título --}}
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                                Título del servicio <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   required
                                   maxlength="255"
                                   value="{{ old('title', $service->title) }}"
                                   placeholder="Ej: Reparación de tuberías a domicilio"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Descripción --}}
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                Descripción
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="6"
                                      maxlength="1000"
                                      placeholder="Describe tu servicio, qué incluye, tu experiencia, materiales que utilizas, etc."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $service->description) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Máximo 1000 caracteres</p>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Precio por hora --}}
                        <div>
                            <label for="price_hour" class="block text-sm font-semibold text-gray-700 mb-2">
                                Precio por hora (€) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">€</span>
                                <input type="number" 
                                       name="price_hour" 
                                       id="price_hour" 
                                       required
                                       min="0"
                                       max="999999.99"
                                       step="0.01"
                                       value="{{ old('price_hour', $service->price) }}"
                                       placeholder="25.00"
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('price_hour') border-red-500 @enderror">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Indica tu tarifa por hora de trabajo</p>
                            @error('price_hour')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Fotos Existentes --}}
                        @if($service->photos->count() > 0)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Fotos actuales
                                </label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                    @foreach($service->photos as $photo)
                                        <div class="relative group">
                                            <img src="{{ Storage::url($photo->path) }}" 
                                                 alt="Foto del servicio"
                                                 class="w-full h-32 object-cover rounded-lg shadow-md">
                                            <button type="button"
                                                    onclick="deletePhoto({{ $photo->id }})"
                                                    class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white w-8 h-8 rounded-full opacity-0 group-hover:opacity-100 transition duration-200">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Agregar Nuevas Fotos --}}
                        <div>
                            <label for="photos" class="block text-sm font-semibold text-gray-700 mb-2">
                                Agregar más fotos
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition duration-200">
                                <i class="fas fa-cloud-upload-alt text-5xl text-gray-400 mb-4"></i>
                                <input type="file" 
                                       name="photos[]" 
                                       id="photos" 
                                       multiple
                                       accept="image/jpeg,image/png,image/jpg,image/gif"
                                       class="hidden"
                                       onchange="displayFileNames(this)">
                                <label for="photos" class="cursor-pointer">
                                    <span class="text-blue-600 hover:text-blue-700 font-semibold">Haz clic para subir fotos</span>
                                    <span class="text-gray-500"> o arrastra y suelta</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-2">JPG, PNG, GIF hasta 2MB por imagen</p>
                                <div id="file-names" class="mt-4 text-sm text-gray-600"></div>
                            </div>
                            @error('photos.*')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Información adicional --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800 mb-2">Consejos para tu servicio</h4>
                                    <ul class="text-sm text-gray-700 space-y-1">
                                        <li>• Las fotos de calidad atraen más clientes</li>
                                        <li>• Actualiza regularmente tu descripción</li>
                                        <li>• Mantén precios competitivos</li>
                                        <li>• Responde rápido a las solicitudes</li>
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
                        <div class="flex gap-4 pt-6 border-t border-gray-200">
                            <button type="submit" 
                                    class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-4 rounded-lg font-bold text-lg transition duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-save mr-2"></i>
                                Guardar Cambios
                            </button>
                            <a href="{{ route('services.show', $service) }}" 
                               class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-4 rounded-lg font-bold text-lg transition duration-200 text-center">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Zona de Peligro --}}
                <div class="bg-white rounded-xl shadow-lg p-8 mt-8 border-2 border-red-200">
                    <h3 class="text-xl font-bold text-red-600 mb-4">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Zona de Peligro
                    </h3>
                    <p class="text-gray-600 mb-4">
                        Una vez eliminado el servicio, no podrás recuperarlo. Esta acción es irreversible.
                    </p>
                    <form action="{{ route('services.destroy', $service) }}" 
                          method="POST" 
                          onsubmit="return confirm('¿Estás seguro de que deseas eliminar este servicio? Esta acción no se puede deshacer.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                            <i class="fas fa-trash mr-2"></i>
                            Eliminar Servicio
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        function displayFileNames(input) {
            const fileNamesDiv = document.getElementById('file-names');
            if (input.files.length > 0) {
                let fileList = '<p class="font-semibold mb-2">Archivos seleccionados:</p><ul class="list-disc list-inside">';
                for (let i = 0; i < input.files.length; i++) {
                    fileList += `<li>${input.files[i].name} (${(input.files[i].size / 1024).toFixed(2)} KB)</li>`;
                }
                fileList += '</ul>';
                fileNamesDiv.innerHTML = fileList;
            } else {
                fileNamesDiv.innerHTML = '';
            }
        }

        function deletePhoto(photoId) {
            if (confirm('¿Estás seguro de eliminar esta foto?')) {
                fetch(`/services/photos/${photoId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error al eliminar la foto');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar la foto');
                });
            }
        }
    </script>
    @endpush
@endsection
