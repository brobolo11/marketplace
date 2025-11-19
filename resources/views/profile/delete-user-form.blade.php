<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">
            <i class="fas fa-user-times text-red-600 mr-2"></i>
            Eliminar Cuenta
        </h2>
        <p class="text-gray-600">Elimina permanentemente tu cuenta de HouseFixes.</p>
    </div>
    <div class="space-y-6">
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl mt-0.5"></i>
                <div>
                    <h3 class="font-semibold text-red-900 mb-2">Advertencia: Esta acción es permanente</h3>
                    <p class="text-sm text-red-800 mb-3">
                        Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente.
                        Antes de eliminar tu cuenta, por favor descarga cualquier dato o información que desees conservar.
                    </p>
                    <div class="text-sm text-red-700 space-y-1">
                        <p><i class="fas fa-times-circle mr-2"></i>Todos tus servicios serán eliminados</p>
                        <p><i class="fas fa-times-circle mr-2"></i>Todas tus reservas serán canceladas</p>
                        <p><i class="fas fa-times-circle mr-2"></i>Todos tus mensajes serán borrados</p>
                        <p><i class="fas fa-times-circle mr-2"></i>Todas tus reseñas serán eliminadas</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end pt-6 border-t border-gray-200">
            <button type="button" class="px-6 py-3 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-all shadow-lg hover:shadow-xl" wire:click="confirmUserDeletion" wire:loading.attr="disabled">
                <i class="fas fa-trash-alt mr-2"></i>
                Eliminar Cuenta
            </button>
        </div>
    </div>
    <x-dialog-modal wire:model.live="confirmingUserDeletion">
        <x-slot name="title">
            Eliminar Cuenta
        </x-slot>
        <x-slot name="content">
            ¿Estás seguro de que deseas eliminar tu cuenta? Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. Por favor, ingresa tu contraseña para confirmar que deseas eliminar permanentemente tu cuenta.
            <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                <x-input type="password" class="mt-1 block w-3/4" autocomplete="current-password" placeholder="Contraseña" x-ref="password" wire:model="password" wire:keydown.enter="deleteUser" />
                <x-input-error for="password" class="mt-2" />
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                Cancelar
            </x-secondary-button>
            <x-danger-button class="ms-3" wire:click="deleteUser" wire:loading.attr="disabled">
                Eliminar Cuenta
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>
</div>
