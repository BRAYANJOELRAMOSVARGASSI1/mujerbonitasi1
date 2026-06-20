<?php

use Illuminate\Support\Facades\Route;
use App\Modules\P4_GestionServiciosCitas\Controllers\ServicioController;
use App\Modules\P4_GestionServiciosCitas\Controllers\CitaController;
use App\Modules\P4_GestionServiciosCitas\Controllers\ServicioRealizadoController;
use App\Modules\P4_GestionServiciosCitas\Controllers\PromocionController;

/*
|--------------------------------------------------------------------------
| P4 — GESTIÓN DE SERVICIOS Y CITAS
|--------------------------------------------------------------------------
| CU8  — Agendar Cita                    ← IMPLEMENTADO C3
| CU9  — Asignar Estilista a Servicio    ← INTEGRADO EN CU8 (AJAX)
| CU11 — Gestionar Servicios             ← IMPLEMENTADO C2
| CU14 — Registrar Servicio Realizado    ← IMPLEMENTADO C3
| CU24 — Gestionar Promociones           ← IMPLEMENTADO C3
|--------------------------------------------------------------------------
*/

// CU11 — Gestionar Servicios
Route::resource('servicios', ServicioController::class);

// CU8 + CU9 — Agendar Cita (con Asignación de Estilista integrada)
Route::get('citas/estilistas-disponibles', [CitaController::class, 'getEstilistasDisponibles'])
    ->name('citas.estilistas-disponibles');
Route::resource('citas', CitaController::class);

// CU14 — Registrar Servicio Realizado
Route::resource('servicios-realizados', ServicioRealizadoController::class)
    ->only(['index', 'create', 'store', 'show']);

// CU24 — Gestionar Promociones
Route::resource('promociones', PromocionController::class)->parameters(['promociones' => 'promocion']);
Route::post('promociones/{promocion}/enviar', [PromocionController::class, 'enviarCorreos'])->name('promociones.enviar');
