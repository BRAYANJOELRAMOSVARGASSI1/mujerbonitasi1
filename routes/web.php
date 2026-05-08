<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas Web — Sistema MUJER BONITA
|--------------------------------------------------------------------------
| Las rutas de los módulos C2 y P1 se cargan automáticamente desde
| app/Modules/{Modulo}/Routes/web.php via ModuleServiceProvider.
|--------------------------------------------------------------------------
*/

// Página de bienvenida (pública)
Route::get('/', function () {
    return view('welcome');
});

// ═══════════════════════════════════════════════════════════════
// AUTENTICACIÓN Y PERFIL BASE
// ═══════════════════════════════════════════════════════════════
// [CU20] Iniciar Sesión / [CU21] Cerrar Sesión
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    // Perfil de Usuario
    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});
