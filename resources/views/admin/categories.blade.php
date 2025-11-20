@extends('layouts.marketplace')

@section('title', 'Gestión de Categorías - Admin')

@section('content')
<section class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-8">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">
                    <i class="fas fa-layer-group mr-2"></i> Gestión de Categorías
                </h1>
                <p class="text-purple-100">Administrar categorías de servicios</p>
            </div>
            <div class="flex gap-3">
                <button onclick="document.getElementById('modalCreate').classList.remove('hidden')" class="bg-white text-purple-600 hover:bg-purple-50 px-4 py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-plus mr-2"></i> Nueva Categoría
                </button>
                <a href="{{ route('admin.dashboard') }}" class="bg-purple-700 hover:bg-purple-800 text-white px-4 py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
            </div>
        </div>
    </div>
</section>

<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-4">
        @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
        @endif

        @if($errors->any())
        <x-alert type="error">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
        @endif

        {{-- Grid de Categorías --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($categories as $category)
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center text-white text-2xl">
                        <i class="fas fa-{{ $category->icon }}"></i>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="editCategory({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->description ?? '') }}', '{{ $category->icon }}')" class="text-blue-600 hover:text-blue-900">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" onclick="confirmDelete({{ $category->id }}, '{{ addslashes($category->name) }}', {{ $category->services_count }})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $category->name }}</h3>
                <p class="text-sm text-gray-600 mb-3">{{ $category->description ?? 'Sin descripción' }}</p>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">{{ $category->services_count }} servicios</span>
                    <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded-full text-xs font-semibold">
                        {{ $category->icon }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Modal Crear Categoría --}}
<div id="modalCreate" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800">
                <i class="fas fa-plus-circle mr-2 text-purple-600"></i>
                Nueva Categoría
            </h3>
            <button onclick="document.getElementById('modalCreate').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Icono (FontAwesome)</label>
                    <input type="text" name="icon" required placeholder="home, wrench, paint-brush..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <p class="text-xs text-gray-500 mt-1">Ejemplos: home, wrench, paint-brush, plug, hammer, broom, leaf, car</p>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-save mr-2"></i> Crear
                </button>
                <button type="button" onclick="document.getElementById('modalCreate').classList.add('hidden')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold transition">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Editar Categoría --}}
<div id="modalEdit" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800">
                <i class="fas fa-edit mr-2 text-blue-600"></i>
                Editar Categoría
            </h3>
            <button onclick="document.getElementById('modalEdit').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="formEdit" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                    <input type="text" name="name" id="editName" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                    <textarea name="description" id="editDescription" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Icono (FontAwesome)</label>
                    <input type="text" name="icon" id="editIcon" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-save mr-2"></i> Guardar
                </button>
                <button type="button" onclick="document.getElementById('modalEdit').classList.add('hidden')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold transition">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Eliminar Categoría --}}
<div id="modalDelete" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800">
                <i class="fas fa-exclamation-triangle mr-2 text-red-600"></i>
                Confirmar Eliminación
            </h3>
            <button onclick="document.getElementById('modalDelete').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="mb-6">
            <p class="text-gray-700 mb-2">¿Estás seguro de eliminar la categoría <strong id="deleteCategoryName"></strong>?</p>
            <p class="text-sm text-gray-600" id="deleteWarning"></p>
        </div>
        <form id="formDelete" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-trash mr-2"></i> Sí, eliminar
                </button>
                <button type="button" onclick="document.getElementById('modalDelete').classList.add('hidden')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold transition">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function editCategory(id, name, description, icon) {
    document.getElementById('editName').value = name;
    document.getElementById('editDescription').value = description || '';
    document.getElementById('editIcon').value = icon;
    document.getElementById('formEdit').action = `{{ url('admin/categories') }}/${id}`;
    document.getElementById('modalEdit').classList.remove('hidden');
}

function confirmDelete(id, name, servicesCount) {
    document.getElementById('deleteCategoryName').textContent = name;
    const warning = document.getElementById('deleteWarning');
    
    if (servicesCount > 0) {
        warning.innerHTML = `<i class="fas fa-exclamation-circle text-red-600"></i> Esta categoría tiene <strong>${servicesCount} servicio(s)</strong> asociado(s) y no se puede eliminar.`;
        document.getElementById('formDelete').querySelector('button[type="submit"]').disabled = true;
        document.getElementById('formDelete').querySelector('button[type="submit"]').classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        warning.innerHTML = '<i class="fas fa-check-circle text-green-600"></i> Esta categoría no tiene servicios asociados y puede eliminarse.';
        document.getElementById('formDelete').querySelector('button[type="submit"]').disabled = false;
        document.getElementById('formDelete').querySelector('button[type="submit"]').classList.remove('opacity-50', 'cursor-not-allowed');
    }
    
    document.getElementById('formDelete').action = `{{ url('admin/categories') }}/${id}`;
    document.getElementById('modalDelete').classList.remove('hidden');
}
</script>
@endpush
@endsection
