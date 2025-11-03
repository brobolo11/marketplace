{{-- 
    Vista: Listado de Categorías
    Descripción: Muestra todas las categorías disponibles en grid
    Ruta: GET /categories
--}}
@extends('layouts.marketplace')

@section('title', 'Todas las Categorías - Servicios Pro')

@section('content')
    {{-- Header --}}
    <section class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl font-bold mb-4">
                    Todas las Categorías
                </h1>
                <p class="text-xl text-blue-100">
                    Explora nuestros {{ $categories->count() }} servicios profesionales para tu hogar
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
                       class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition duration-300 text-center group">
                        {{-- Icono --}}
                        <div class="text-5xl mb-4 group-hover:scale-110 transition duration-200">
                            <i class="fas fa-{{ $category->icon }}"></i>
                        </div>
                        
                        {{-- Nombre --}}
                        <h3 class="font-semibold text-gray-800 group-hover:text-blue-600 transition duration-200 mb-2">
                            {{ $category->name }}
                        </h3>
                        
                        {{-- Contador de servicios --}}
                        <p class="text-sm text-gray-500">
                            {{ $category->services_count }} 
                            {{ $category->services_count === 1 ? 'servicio' : 'servicios' }}
                        </p>
                        
                        {{-- Descripción (si existe) --}}
                        @if($category->description)
                            <p class="text-xs text-gray-400 mt-2 line-clamp-2">
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
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">
                ¿Eres un Profesional?
            </h2>
            <p class="text-gray-600 mb-8 max-w-2xl mx-auto">
                Únete a nuestra plataforma y comienza a ofrecer tus servicios a miles de clientes potenciales
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @guest
                    <a href="{{ route('register') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition duration-200">
                        Registrarse como Profesional
                    </a>
                @else
                    @if(Auth::user()->isPro())
                        <a href="{{ route('services.create') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition duration-200">
                            Crear Nuevo Servicio
                        </a>
                    @endif
                @endguest
            </div>
        </div>
    </section>
@endsection
