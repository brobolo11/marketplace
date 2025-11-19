<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class RedirectUserAfterLogin
{
    /**
     * Maneja el evento de login para redirigir según el rol del usuario.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event): void
    {
        $user = $event->user;
        
        // Establece la URL de destino según el rol del usuario
        if ($user->isAdmin()) {
            // Admin va al panel administrativo
            session()->put('url.intended', route('admin.dashboard'));
        } elseif ($user->isPro()) {
            // Profesional va al dashboard
            session()->put('url.intended', route('dashboard'));
        } else {
            // Cliente va al home para buscar servicios
            session()->put('url.intended', route('home'));
        }
    }
}
