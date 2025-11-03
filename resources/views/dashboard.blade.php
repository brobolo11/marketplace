<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Control') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Información del usuario --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <h3 class="text-2xl font-bold mb-4">Bienvenido, {{ Auth::user()->name }}!</h3>
                <p class="text-gray-600 mb-4">
                    @if(Auth::user()->isAdmin())
                        Eres un <span class="font-semibold text-red-600">Administrador</span>. 
                        Tienes acceso completo al sistema.
                    @elseif(Auth::user()->isPro())
                        Eres un <span class="font-semibold text-blue-600">Profesional</span>. 
                        Gestiona tus servicios, reservas y disponibilidad desde aquí.
                    @else
                        Eres un <span class="font-semibold text-green-600">Cliente</span>. 
                        Busca profesionales y reserva servicios fácilmente.
                    @endif
                </p>
            </div>

            {{-- Acciones rápidas --}}
            <div class="grid md:grid-cols-3 gap-6">
                @if(Auth::user()->isAdmin())
                    {{-- Panel Admin --}}
                    <a href="{{ route('admin.dashboard') }}" class="block bg-gradient-to-r from-red-500 to-pink-600 p-6 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-chart-line text-2xl" style="color: white;"></i>
                            </div>
                            <h4 class="text-lg font-semibold" style="color: white;">Dashboard Admin</h4>
                        </div>
                        <p style="color: rgba(255, 255, 255, 0.9);">Ver estadísticas del sistema</p>
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="block bg-gradient-to-r from-purple-500 to-indigo-600 p-6 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-users-cog text-2xl" style="color: white;"></i>
                            </div>
                            <h4 class="text-lg font-semibold" style="color: white;">Gestión Usuarios</h4>
                        </div>
                        <p style="color: rgba(255, 255, 255, 0.9);">Administrar usuarios del sistema</p>
                    </a>

                    <a href="{{ route('categories.index') }}" class="block bg-gradient-to-r from-blue-500 to-cyan-600 p-6 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-th-large text-2xl" style="color: white;"></i>
                            </div>
                            <h4 class="text-lg font-semibold" style="color: white;">Categorías</h4>
                        </div>
                        <p style="color: rgba(255, 255, 255, 0.9);">Gestionar categorías de servicios</p>
                    </a>

                @elseif(Auth::user()->isPro())
                    <a href="{{ route('services.create') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-plus text-blue-600 text-xl"></i>
                            </div>
                            <h4 class="text-lg font-semibold">Nuevo Servicio</h4>
                        </div>
                        <p class="text-gray-600">Crea un nuevo servicio para ofrecer</p>
                    </a>

                    <a href="{{ route('professionals.services', Auth::user()) }}" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-briefcase text-green-600 text-xl"></i>
                            </div>
                            <h4 class="text-lg font-semibold">Mis Servicios</h4>
                        </div>
                        <p class="text-gray-600">Gestiona tus servicios activos</p>
                    </a>

                    <a href="{{ route('professionals.availability', Auth::user()) }}" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-calendar text-purple-600 text-xl"></i>
                            </div>
                            <h4 class="text-lg font-semibold">Disponibilidad</h4>
                        </div>
                        <p class="text-gray-600">Configura tu horario</p>
                    </a>
                @else
                    <a href="{{ route('services.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-search text-blue-600 text-xl"></i>
                            </div>
                            <h4 class="text-lg font-semibold">Buscar Servicios</h4>
                        </div>
                        <p class="text-gray-600">Encuentra el servicio que necesitas</p>
                    </a>

                    <a href="{{ route('professionals.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-users text-green-600 text-xl"></i>
                            </div>
                            <h4 class="text-lg font-semibold">Profesionales</h4>
                        </div>
                        <p class="text-gray-600">Explora profesionales verificados</p>
                    </a>

                    <a href="{{ route('categories.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-th text-purple-600 text-xl"></i>
                            </div>
                            <h4 class="text-lg font-semibold">Categorías</h4>
                        </div>
                        <p class="text-gray-600">Navega por categorías</p>
                    </a>
                @endif
            </div>

            {{-- Reservas recientes --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mt-6">
                <h3 class="text-xl font-bold mb-4">Mis Reservas Recientes</h3>
                <a href="{{ route('bookings.index') }}" class="text-blue-600 hover:text-blue-800">
                    Ver todas las reservas →
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
