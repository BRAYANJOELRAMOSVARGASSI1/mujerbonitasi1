<?php

use Illuminate\Support\Facades\Route;
use App\Modules\P2_GestionPersonalClientes\Controllers\ClienteController;
use App\Modules\P2_GestionPersonalClientes\Controllers\EstilistaController;
use App\Modules\P2_GestionPersonalClientes\Controllers\HorarioController;

/*
|--------------------------------------------------------------------------
| P2 — GESTIÓN DE PERSONAL Y CLIENTES
|--------------------------------------------------------------------------
| CU4  — Registrar Cliente
| CU5  — Registrar Estilista
| CU10 — Buscar Cliente (integrado en ClienteController@index)
| CU22 — Gestionar Horario
| CU23 — Consultar Horario (integrado en HorarioController@index)
|--------------------------------------------------------------------------
*/

Route::resource('clientes', ClienteController::class);
Route::resource('estilistas', EstilistaController::class);
Route::resource('horarios', HorarioController::class)->except(['show']);
