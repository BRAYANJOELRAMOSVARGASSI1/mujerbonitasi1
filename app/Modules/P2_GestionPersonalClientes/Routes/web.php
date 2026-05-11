<?php

use Illuminate\Support\Facades\Route;
use App\Modules\P2_GestionPersonalClientes\Controllers\ClienteController;
use App\Modules\P2_GestionPersonalClientes\Controllers\EstilistaController;
use App\Modules\P2_GestionPersonalClientes\Controllers\HorarioController;
use App\Modules\P2_GestionPersonalClientes\Controllers\RecepcionistaController;

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

Route::middleware(['auth'])->group(function () {
    Route::resource('clientes', ClienteController::class);
    Route::resource('estilistas', EstilistaController::class);
    Route::resource('recepcionistas', RecepcionistaController::class);
    
    // CU23 - Consultar Horarios (Vista Visual)
    Route::get('horarios/consultar', [HorarioController::class, 'consultar'])->name('horarios.consultar');
    Route::resource('horarios', HorarioController::class)->except(['show']);
});
