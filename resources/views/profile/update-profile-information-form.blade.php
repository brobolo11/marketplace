<div>
    <form wire:submit="updateProfileInformation">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">
                <i class="fas fa-user text-blue-600 mr-2"></i>
                Información del Perfil
            </h2>
            <p class="text-gray-600">Actualiza la información de tu cuenta y dirección de correo electrónico.</p>
        </div>

        <div class="space-y-6">
            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                <div x-data="{photoName: null, photoPreview: null}">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto de Perfil</label>
                    <input type="file" id="photo" class="hidden" wire:model.live="photo" x-ref="photo" x-on:change="photoName = $refs.photo.files[0].name; const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL($refs.photo.files[0]);" />
                    <div class="flex items-center gap-4">
                        <div x-show="! photoPreview">
                            <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full w-20 h-20 object-cover border-4 border-gray-200">
                        </div>
                        <div x-show="photoPreview" style="display: none;">
                            <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center border-4 border-blue-200" x-bind:style="'background-image: url(\'' + photoPreview + '\');'"></span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <button type="button" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors" x-on:click.prevent="$refs.photo.click()">
                                <i class="fas fa-upload mr-2"></i>Seleccionar Nueva Foto
                            </button>
                            @if ($this->user->profile_photo_path)
                                <button type="button" class="px-4 py-2 bg-white border border-red-300 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 transition-colors" wire:click="deleteProfilePhoto">
                                    <i class="fas fa-trash mr-2"></i>Eliminar Foto
                                </button>
                            @endif
                        </div>
                    </div>
                    @error('photo')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endif
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                <input id="name" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" wire:model="state.name" required autocomplete="name" placeholder="Tu nombre completo" />
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                <input id="email" type="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" wire:model="state.email" required autocomplete="username" placeholder="tu@email.com" />
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                    <div class="mt-3 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-400 mt-1 mr-3"></i>
                            <div>
                                <p class="text-sm text-yellow-700">Tu correo electrónico no está verificado.</p>
                                <button type="button" class="mt-2 text-sm text-yellow-700 underline hover:text-yellow-900" wire:click.prevent="sendEmailVerification">
                                    Haz clic aquí para reenviar el correo de verificación.
                                </button>
                                @if ($this->verificationLinkSent)
                                    <p class="mt-2 text-sm text-green-600 font-medium">
                                        <i class="fas fa-check-circle mr-1"></i>Se ha enviado un nuevo enlace de verificación a tu correo.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
            @if (session('saved'))
                <p class="text-sm text-green-600 font-medium">
                    <i class="fas fa-check-circle mr-1"></i>Guardado exitosamente.
                </p>
            @endif
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-medium hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl disabled:opacity-50" wire:loading.attr="disabled" wire:target="photo">
                <span wire:loading.remove wire:target="updateProfileInformation">
                    <i class="fas fa-save mr-2"></i>Guardar Cambios
                </span>
                <span wire:loading wire:target="updateProfileInformation">
                    <i class="fas fa-spinner fa-spin mr-2"></i>Guardando...
                </span>
            </button>
        </div>
    </form>
</div>
