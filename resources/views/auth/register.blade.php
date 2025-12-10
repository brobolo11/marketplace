<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <!-- Logo y Título -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-3 rounded-2xl shadow-xl">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                </div>
                <h2 class="text-4xl font-extrabold text-gray-900 mb-2">
                    Únete a <span class="text-gray-900">HouseFixes</span>
                </h2>
                <p class="text-gray-600">Crea tu cuenta en segundos</p>
            </div>

            <!-- Card de Registro -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- Nombre -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nombre Completo
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Correo Electrónico
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Contraseña
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        </div>
                    </div>

                    <!-- Confirmar Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                            Confirmar Contraseña
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        </div>
                    </div>

                    <!-- Tipo de Usuario -->
                    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg p-4 border-2 border-purple-200">
                        <label for="is_professional" class="flex items-start cursor-pointer">
                            <input id="is_professional" type="checkbox" name="is_professional" value="1" {{ old('is_professional') ? 'checked' : '' }}
                                   class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500 mt-0.5">
                            <div class="ml-3">
                                <span class="text-sm font-semibold text-gray-900">Registrarme como Profesional</span>
                                <p class="text-xs text-gray-600 mt-1">
                                    Podrás ofrecer servicios, gestionar reservas y generar facturas
                                </p>
                            </div>
                        </label>
                    </div>

                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label for="terms" class="flex items-start cursor-pointer">
                                <input id="terms" type="checkbox" name="terms" required
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-0.5">
                                <div class="ml-3">
                                    <span class="text-xs text-gray-700">
                                        Acepto los <a target="_blank" href="{{ route('terms.show') }}" class="text-blue-600 hover:text-blue-700 font-medium underline">Términos de Servicio</a> y la <a target="_blank" href="{{ route('policy.show') }}" class="text-blue-600 hover:text-blue-700 font-medium underline">Política de Privacidad</a>
                                    </span>
                                </div>
                            </label>
                        </div>
                    @endif

                    <!-- Botón Registrar -->
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition duration-200">
                        Crear Cuenta
                    </button>
                </form>

                <!-- Divider -->
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">¿Ya tienes cuenta?</span>
                        </div>
                    </div>
                </div>

                <!-- Link Login -->
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center w-full px-4 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Iniciar Sesión
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <p class="mt-8 text-center text-sm text-gray-600">
                © 2025 HouseFixes. Todos los derechos reservados.
            </p>
        </div>
    </div>
</x-guest-layout>
