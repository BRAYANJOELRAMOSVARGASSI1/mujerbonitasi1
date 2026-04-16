<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Failed;
use App\Models\ActivityLog;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });

        // 1. Bitácora de Logeo Exitoso
        Event::listen(function (Login $event) {
            ActivityLog::create([
                'user_id' => $event->user->id,
                'action' => 'Login exitoso',
                'description' => 'Inicio de sesión correcto en el sistema.',
                'ip_address' => request()->ip() ?? 'No disponible',
                'browser' => request()->header('user-agent') ?? 'No disponible',
            ]);
        });

        // 2. Bitácora de Intents Fallidos
        Event::listen(function (Failed $event) {
            $isAlerta = $event->user && $event->user->failed_logins >= 5;
            $accion = $isAlerta ? '⚠️ ALERTA DE SEGURIDAD' : 'Login fallido';

            ActivityLog::create([
                'user_id' => $event->user ? $event->user->id : null,
                'action' => $accion,
                'description' => 'Intento fallido para: ' . ($event->credentials['email'] ?? 'Desconocido'),
                'ip_address' => request()->ip() ?? 'No disponible',
                'browser' => request()->header('user-agent') ?? 'No disponible',
            ]);
        });
    }
}
