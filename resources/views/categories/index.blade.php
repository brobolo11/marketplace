{{-- 
    Vista: Listado de Categorías
    Descripción: Muestra todas las categorías disponibles en grid
    Ruta: GET /categories
--}}
@extends('layouts.marketplace')

@section('title', 'Todas las Categorías - HouseFixes')

@section('content')
    {{-- Header --}}
    <section class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <div class="mb-6">
                    <i class="fas fa-th-large text-6xl mb-4 opacity-90"></i>
                </div>
                <h1 class="text-5xl font-bold mb-6">
                    Todas las Categorías
                </h1>
                <p class="text-xl text-blue-100 leading-relaxed">
                    Explora nuestras <span class="font-bold text-white">{{ $categories->count() }}</span> categorías de HouseFixesfesionales para tu hogar y negocio
                </p>
            </div>
        </div>
    </section>

    {{-- Grid de Categorías --}}
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
                @forelse($categories as $category)
                    <a href="{{ route('categories.show', $category) }}" 
                       class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 text-center group border border-gray-100 hover:border-blue-200 transform hover:-translate-y-2">
                        {{-- Icono --}}
                        <div class="text-5xl mb-5 group-hover:scale-110 transition-transform duration-200 text-blue-600">
                            <i class="fas fa-{{ $category->icon }}"></i>
                        </div>
                        
                        {{-- Nombre --}}
                        <h3 class="font-bold text-gray-800 group-hover:text-blue-600 transition duration-200 mb-3 text-lg">
                            {{ $category->name }}
                        </h3>
                        
                        {{-- Contador de servicios --}}
                        <p class="text-sm font-medium text-gray-600 bg-gray-100 rounded-full py-1 px-3 inline-block">
                            {{ $category->services_count }} 
                            {{ $category->services_count === 1 ? 'servicio' : 'servicios' }}
                        </p>
                        
                        {{-- Descripción (si existe) --}}
                        @if($category->description)
                            <p class="text-xs text-gray-500 mt-3 line-clamp-2 leading-relaxed">
                                {{ $category->description }}
                            </p>
                        @endif
                    </a>
                @empty
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">No hay categorías disponibles</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-3xl mx-auto">
                <div class="mb-6">
                    <i class="fas fa-user-tie text-6xl mb-4 opacity-90"></i>
                </div>
                <h2 class="text-4xl font-bold mb-6">
                    ¿Eres un Profesional?
                </h2>
                <p class="text-xl text-blue-100 mb-10 leading-relaxed">
                    Únete a nuestra plataforma y comienza a ofrecer tus servicios a miles de clientes potenciales. 
                    Gestiona tus reservas, horarios y pagos desde un solo lugar.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @guest
                        <a href="{{ route('register') }}" 
                           class="bg-white text-blue-600 hover:bg-gray-100 px-10 py-4 rounded-lg font-bold text-lg transition duration-200 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                            <i class="fas fa-user-plus mr-2"></i>
                            Únete a Nosotros
                        </a>
                        <a href="{{ route('professionals.index') }}" 
                           class="bg-transparent border-2 border-white hover:bg-white hover:text-blue-600 px-10 py-4 rounded-lg font-semibold text-lg transition duration-200">
                            <i class="fas fa-users mr-2"></i>
                            Ver Profesionales
                        </a>
                    @else
                        @if(Auth::user()->isPro())
                            <a href="{{ route('services.create') }}" 
                               class="bg-white text-blue-600 hover:bg-gray-100 px-10 py-4 rounded-lg font-bold text-lg transition duration-200 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                                <i class="fas fa-plus-circle mr-2"></i>
                                Crear Nuevo Servicio
                            </a>
                        @else
                            <a href="{{ route('professionals.index') }}" 
                               class="bg-white text-blue-600 hover:bg-gray-100 px-10 py-4 rounded-lg font-bold text-lg transition duration-200 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                                <i class="fas fa-search mr-2"></i>
                                Buscar Profesionales
                            </a>
                        @endif
                    @endguest
                </div>
            </div>
        </div>
    </section>
@endsection
