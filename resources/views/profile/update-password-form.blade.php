<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">
            <i class="fas fa-lock text-blue-600 mr-2"></i>
            Actualizar Contraseña
        </h2>
        <p class="text-gray-600">Asegúrate de usar una contraseña larga y segura para proteger tu cuenta.</p>
    </div>
    <form wire:submit="updatePassword">
        <div class="space-y-6">
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                    Contraseña Actual
                </label>
                <div class="relative">
                    <input id="current_password" type="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all pr-10" wire:model="state.current_password" autocomplete="current-password" placeholder="Tu contraseña actual" />
                    <i class="fas fa-key absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                @error('current_password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Nueva Contraseña
                </label>
                <div class="relative">
                    <input id="password" type="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all pr-10" wire:model="state.password" autocomplete="new-password" placeholder="Mínimo 8 caracteres" />
                    <i class="fas fa-lock absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Confirmar Nueva Contraseña
                </label>
                <div class="relative">
                    <input id="password_confirmation" type="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all pr-10" wire:model="state.password_confirmation" autocomplete="new-password" placeholder="Repite la nueva contraseña" />
                    <i class="fas fa-lock-open absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                @error('password_confirmation')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start gap-2">
                    <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-2">Requisitos de contraseña:</p>
                        <ul class="space-y-1">
                            <li><i class="fas fa-check text-green-600 mr-2"></i>Mínimo 8 caracteres</li>
                            <li><i class="fas fa-check text-green-600 mr-2"></i>Combina letras y números</li>
                            <li><i class="fas fa-check text-green-600 mr-2"></i>Usa mayúsculas y minúsculas</li>
                            <li><i class="fas fa-check text-green-600 mr-2"></i>Incluye caracteres especiales (@, #, $, etc.)</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                @if (session('status') === 'password-updated')
                    <div class="text-sm text-green-600 font-medium">
                        <i class="fas fa-check-circle mr-1"></i>
                        Contraseña actualizada correctamente
                    </div>
                @endif
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-medium hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl" wire:loading.attr="disabled">
                    <span wire:loading.remove>
                        <i class="fas fa-save mr-2"></i>
                        Actualizar Contraseña
                    </span>
                    <span wire:loading>
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Actualizando...
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>
