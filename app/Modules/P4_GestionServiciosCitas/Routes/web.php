<?php

use Illuminate\Support\Facades\Route;
use App\Modules\P4_GestionServiciosCitas\Controllers\ServicioController;

/*
|--------------------------------------------------------------------------
| P4 — GESTIÓN DE SERVICIOS Y CITAS
|--------------------------------------------------------------------------
| CU8  — Agendar Cita (pendiente C3)
| CU9  — Asignar Estilista a Servicio (pendiente C3)
| CU11 — Gestionar Servicios ← IMPLEMENTADO
| CU14 — Registrar Servicio Realizado (pendiente C3)
| CU24 — Gestionar Promociones (pendiente C3)
|--------------------------------------------------------------------------
*/

Route::resource('servicios', ServicioController::class);
