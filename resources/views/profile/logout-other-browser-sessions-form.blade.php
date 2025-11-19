<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">
            <i class="fas fa-desktop text-blue-600 mr-2"></i>
            Sesiones del Navegador
        </h2>
        <p class="text-gray-600">Administra y cierra sesión en tus sesiones activas en otros navegadores y dispositivos.</p>
    </div>
    <div class="space-y-6">
        <div class="text-sm text-gray-600 bg-gray-50 p-4 rounded-lg">
            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
            Si es necesario, puedes cerrar sesión en todas tus otras sesiones de navegador en todos tus dispositivos.
        </div>
        @if (count($this->sessions) > 0)
            <div class="space-y-4">
                @foreach ($this->sessions as $session)
                    <div class="flex items-center gap-4 p-4 bg-white border border-gray-200 rounded-lg">
                        <div class="flex-shrink-0">
                            @if ($session->agent->isDesktop())
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-desktop text-blue-600 text-xl"></i>
                                </div>
                            @else
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-mobile-alt text-purple-600 text-xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $session->agent->platform() ? $session->agent->platform() : 'Desconocido' }} - {{ $session->agent->browser() ? $session->agent->browser() : 'Desconocido' }}
                            </div>
                            <div class="flex items-center gap-3 mt-1">
                                <span class="text-xs text-gray-500">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    {{ $session->ip_address }}
                                </span>
                                @if ($session->is_current_device)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Este dispositivo
                                    </span>
                                @else
                                    <span class="text-xs text-gray-500">
                                        <i class="fas fa-clock mr-1"></i>
                                        Última actividad {{ $session->last_active }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        <div class="flex items-center justify-end pt-6 border-t border-gray-200">
            <button type="button" class="px-6 py-3 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-all shadow-lg hover:shadow-xl" wire:click="confirmLogout" wire:loading.attr="disabled">
                <i class="fas fa-sign-out-alt mr-2"></i>
                Cerrar Otras Sesiones
            </button>
        </div>
    </div>
    <x-dialog-modal wire:model.live="confirmingLogout">
        <x-slot name="title">
            Cerrar Otras Sesiones del Navegador
        </x-slot>
        <x-slot name="content">
            Por favor, ingresa tu contraseña para confirmar que deseas cerrar sesión en tus otras sesiones de navegador en todos tus dispositivos.
            <div class="mt-4" x-data="{}" x-on:confirming-logout-other-browser-sessions.window="setTimeout(() => $refs.password.focus(), 250)">
                <x-input type="password" class="mt-1 block w-3/4" autocomplete="current-password" placeholder="Contraseña" x-ref="password" wire:model="password" wire:keydown.enter="logoutOtherBrowserSessions" />
                <x-input-error for="password" class="mt-2" />
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingLogout')" wire:loading.attr="disabled">
                Cancelar
            </x-secondary-button>
            <x-button class="ms-3" wire:click="logoutOtherBrowserSessions" wire:loading.attr="disabled">
                Cerrar Otras Sesiones
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
