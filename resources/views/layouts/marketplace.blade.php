<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Marketplace de Servicios'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-50">
    
    {{-- Navegación Principal --}}
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                {{-- Logo --}} 
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <img src="{{ asset('logo.png') }}" alt="HouseFixes" class="h-16 w-auto object-contain group-hover:scale-105 transition-transform duration-200">
                        <span class="text-2xl md:text-3xl font-bold text-blue-600 group-hover:text-blue-700 transition-colors">HouseFixes</span>
                    </a>
                </div>

                {{-- Menú Desktop --}}
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('categories.index') }}" class="text-gray-700 hover:text-blue-600 transition">
                        Categorías
                    </a>
                    <a href="{{ route('services.index') }}" class="text-gray-700 hover:text-blue-600 transition">
                        Servicios
                    </a>
                    <a href="{{ route('professionals.index') }}" class="text-gray-700 hover:text-blue-600 transition">
                        Profesionales
                    </a>

                    @auth
                        {{-- Dropdown de usuario autenticado --}}
                        <x-user-dropdown />
                    @else
                        {{-- Menú para invitados --}}
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition">
                            Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                            Registrarse
                        </a>
                    @endauth
                </div>

                {{-- Menú Móvil Toggle --}}
                <div class="md:hidden">
                    <button x-data @click="$dispatch('toggle-mobile-menu')" class="text-gray-700">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Menú Móvil --}}
        <div x-data="{ open: false }" 
             @toggle-mobile-menu.window="open = !open"
             x-show="open" 
             x-transition
             class="md:hidden bg-white border-t">
            <div class="px-4 py-2 space-y-2">
                <a href="{{ route('categories.index') }}" class="block py-2 text-gray-700 hover:text-blue-600">
                    Categorías
                </a>
                <a href="{{ route('services.index') }}" class="block py-2 text-gray-700 hover:text-blue-600">
                    Servicios
                </a>
                <a href="{{ route('professionals.index') }}" class="block py-2 text-gray-700 hover:text-blue-600">
                    Profesionales
                </a>

                @auth
                    <div class="border-t pt-2 mt-2">
                        <a href="{{ route('bookings.index') }}" class="block py-2 text-gray-700 hover:text-blue-600">
                            <i class="fas fa-calendar-check mr-2"></i> Mis Reservas
                        </a>
                        <a href="{{ route('messages.index') }}" class="block py-2 text-gray-700 hover:text-blue-600">
                            <i class="fas fa-envelope mr-2"></i> Mensajes
                        </a>
                        <a href="{{ route('profile.show') }}" class="block py-2 text-gray-700 hover:text-blue-600">
                            <i class="fas fa-cog mr-2"></i> Configuración
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left py-2 text-gray-700 hover:text-blue-600">
                                <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                            </button>
                        </form>
                    </div>
                @else
                    <div class="border-t pt-2 mt-2">
                        <a href="{{ route('login') }}" class="block py-2 text-gray-700 hover:text-blue-600">
                            Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}" class="block py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700">
                            Registrarse
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Mensajes Flash --}}
    @if (session('success'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    {{-- Contenido Principal --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-800 text-white mt-16">
        <div class="container mx-auto px-4 py-12">
            <div class="grid md:grid-cols-4 gap-8">
                {{-- Columna 1: Sobre Nosotros --}}
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <img src="{{ asset('logo.png') }}" alt="HouseFixes" class="h-8 w-8 object-contain">
                        <h3 class="text-lg font-bold">HouseFixes</h3>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        La plataforma líder para conectar clientes con profesionales de confianza para servicios del hogar.
                    </p>
                </div>

                {{-- Columna 2: Enlaces Rápidos --}}
                <div>
                    <h4 class="text-lg font-semibold mb-4">Enlaces Rápidos</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('categories.index') }}" class="text-gray-400 hover:text-white transition">Categorías</a></li>
                        <li><a href="{{ route('services.index') }}" class="text-gray-400 hover:text-white transition">Servicios</a></li>
                        <li><a href="{{ route('professionals.index') }}" class="text-gray-400 hover:text-white transition">Profesionales</a></li>
                    </ul>
                </div>

                {{-- Columna 3: Para Profesionales --}}
                <div>
                    <h4 class="text-lg font-semibold mb-4">Para Profesionales</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white transition">Únete Como Pro</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Centro de Ayuda</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Términos y Condiciones</a></li>
                    </ul>
                </div>

                {{-- Columna 4: Contacto --}}
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contacto</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-envelope mr-2"></i> info@serviciospro.com</li>
                        <li><i class="fas fa-phone mr-2"></i> +34 900 123 456</li>
                        <li class="flex space-x-4 pt-4">
                            <a href="#" class="text-gray-400 hover:text-white transition">
                                <i class="fab fa-facebook text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition">
                                <i class="fab fa-twitter text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition">
                                <i class="fab fa-instagram text-xl"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} HouseFixes. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

</body>
</html>
