<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

/**
 * ModuleServiceProvider
 * 
 * Carga automáticamente las rutas de cada módulo del monolito modular.
 * Cada módulo en app/Modules/{Modulo}/Routes/web.php es registrado
 * con el middleware 'web' y 'auth'.
 */
class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Módulos registrados en el sistema.
     * Cada entrada mapea: nombre_modulo => namespace_controllers
     */
    protected array $modules = [
        'P1_GestionUsuarioSeguridad'       => 'App\\Modules\\P1_GestionUsuarioSeguridad\\Controllers',
        'P2_GestionPersonalClientes'       => 'App\\Modules\\P2_GestionPersonalClientes\\Controllers',
        'P3_GestionInventarioHerramientas' => 'App\\Modules\\P3_GestionInventarioHerramientas\\Controllers',
        'P4_GestionServiciosCitas'         => 'App\\Modules\\P4_GestionServiciosCitas\\Controllers',
        'P5_PagosFacturacion'              => 'App\\Modules\\P5_PagosFacturacion\\Controllers',
        'P6_ReportesComunicaciones'        => 'App\\Modules\\P6_ReportesComunicaciones\\Controllers',
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->registerModuleRoutes();
    }

    /**
     * Registra las rutas web de cada módulo que tenga archivo Routes/web.php
     */
    protected function registerModuleRoutes(): void
    {
        foreach ($this->modules as $module => $namespace) {
            $routeFile = app_path("Modules/{$module}/Routes/web.php");

            if (file_exists($routeFile)) {
                Route::middleware(['web', 'auth'])
                    ->group($routeFile);
            }
        }
    }
}
