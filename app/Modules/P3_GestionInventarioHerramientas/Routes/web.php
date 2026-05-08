<?php

use Illuminate\Support\Facades\Route;
use App\Modules\P3_GestionInventarioHerramientas\Controllers\ProductoController;
use App\Modules\P3_GestionInventarioHerramientas\Controllers\HerramientaController;

/*
|--------------------------------------------------------------------------
| P3 — GESTIÓN DE INVENTARIO Y HERRAMIENTAS
|--------------------------------------------------------------------------
| CU6  — Registrar Producto
| CU7  — Registrar Herramienta
| CU12 — Consultar Stock (integrado en ProductoController@index)
| CU13 — Consultar Herramientas (integrado en HerramientaController@index)
| CU27 — Generar Alertas Stock Bajo (integrado en ProductoController — accessor nivel_alerta)
|--------------------------------------------------------------------------
*/

Route::resource('productos', ProductoController::class);
Route::resource('herramientas', HerramientaController::class);
