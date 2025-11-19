@extends('layouts.marketplace')

@section('title', 'Gestionar Perfil - HouseFixes')

@section('content')
    <section class="py-8 bg-gray-50 min-h-screen">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    {{-- Sidebar de Navegación --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">
                                <i class="fas fa-cog text-blue-600 mr-2"></i>
                                Configuración
                            </h3>
                            <nav class="space-y-2">
                                <a href="#perfil" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-user w-5"></i>
                                    <span>Información Personal</span>
                                </a>
                                <a href="#password" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-lock w-5"></i>
                                    <span>Contraseña</span>
                                </a>
                                @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                                <a href="#two-factor" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-shield-alt w-5"></i>
                                    <span>Autenticación 2FA</span>
                                </a>
                                @endif
                                <a href="#sessions" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-desktop w-5"></i>
                                    <span>Sesiones Activas</span>
                                </a>
                                <a href="#delete" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="fas fa-trash-alt w-5"></i>
                                    <span>Eliminar Cuenta</span>
                                </a>
                            </nav>
                        </div>
                    </div>

                    {{-- Contenido Principal --}}
                    <div class="lg:col-span-3 space-y-6">
                        @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                            <div id="perfil" class="bg-white rounded-xl shadow-lg p-6 md:p-8 scroll-mt-24">
                                @livewire('profile.update-profile-information-form')
                            </div>
                        @endif

                        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                            <div id="password" class="bg-white rounded-xl shadow-lg p-6 md:p-8 scroll-mt-24">
                                @livewire('profile.update-password-form')
                            </div>
                        @endif

                        @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                            <div id="two-factor" class="bg-white rounded-xl shadow-lg p-6 md:p-8 scroll-mt-24">
                                @livewire('profile.two-factor-authentication-form')
                            </div>
                        @endif

                        <div id="sessions" class="bg-white rounded-xl shadow-lg p-6 md:p-8 scroll-mt-24">
                            @livewire('profile.logout-other-browser-sessions-form')
                        </div>

                        @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                            <div id="delete" class="bg-white rounded-xl shadow-lg p-6 md:p-8 scroll-mt-24">
                                @livewire('profile.delete-user-form')
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- JavaScript para navegación activa --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('nav a[href^="#"]');
            
            // Smooth scroll
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const targetElement = document.getElementById(targetId);
                    
                    if (targetElement) {
                        targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        
                        // Actualizar enlace activo
                        navLinks.forEach(l => l.classList.remove('bg-blue-50', 'text-blue-600'));
                        this.classList.add('bg-blue-50', 'text-blue-600');
                    }
                });
            });

            // Activar enlace según scroll
            const sections = ['perfil', 'password', 'two-factor', 'sessions', 'delete'];
            window.addEventListener('scroll', function() {
                let current = '';
                
                sections.forEach(sectionId => {
                    const section = document.getElementById(sectionId);
                    if (section) {
                        const sectionTop = section.offsetTop;
                        const sectionHeight = section.clientHeight;
                        if (pageYOffset >= (sectionTop - 150)) {
                            current = sectionId;
                        }
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('bg-blue-50', 'text-blue-600');
                    if (link.getAttribute('href') === '#' + current) {
                        link.classList.add('bg-blue-50', 'text-blue-600');
                    }
                });
            });
        });
    </script>
@endsection
