<?php

use Illuminate\Support\Facades\Route;
use App\Modules\P1_GestionUsuarioSeguridad\Controllers\UserController;
use App\Modules\P1_GestionUsuarioSeguridad\Controllers\RoleController;
use App\Modules\P1_GestionUsuarioSeguridad\Controllers\PermissionController;

/*
|--------------------------------------------------------------------------
| P1 — GESTIÓN DE USUARIOS Y SEGURIDAD
|--------------------------------------------------------------------------
| CU1  — Gestionar Usuarios
| CU2  — Definir Roles y Permisos
| CU3  — Consultar Bitácora
|--------------------------------------------------------------------------
| Las rutas de autenticación y perfil permanecen en routes/web.php
| por compatibilidad con el framework.
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // [CU1] Gestionar Usuarios
    Route::resource('users', UserController::class);

    // Rutas adicionales de gestión de usuarios
    Route::get('users/{user}/roles-permissions', [UserController::class, 'editRoles'])
        ->name('users.roles.edit');
    Route::put('users/{user}/roles-permissions', [UserController::class, 'updateRoles'])
        ->name('users.roles.update');
    Route::get('users/{user}/roles/data', [UserController::class, 'getRolesData'])
        ->name('users.roles.data');
    Route::post('users/{user}/unblock', [UserController::class, 'unblock'])
        ->name('users.unblock');

    // [CU3] Consultar Bitácora
    Route::get('bitacora', [UserController::class, 'bitacora'])->name('bitacora.index');

    // [CU2] Definir Roles y Permisos
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
});
