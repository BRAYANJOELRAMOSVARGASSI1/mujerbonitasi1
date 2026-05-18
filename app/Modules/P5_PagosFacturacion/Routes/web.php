<?php

use Illuminate\Support\Facades\Route;
use App\Modules\P5_PagosFacturacion\Controllers\ComisionController;

/*
|--------------------------------------------------------------------------
| P5 — PAGOS Y FACTURACIÓN
|--------------------------------------------------------------------------
| CU25 — Calcular Comisión Estilista     ← IMPLEMENTADO C3
| CU18 — Generar Factura/Ticket          (pendiente)
| CU26 — Procesar Pago                   (pendiente)
|--------------------------------------------------------------------------
*/

// CU25 — Calcular Comisión Estilista
Route::get('comisiones', [ComisionController::class, 'index'])->name('comisiones.index');
Route::post('comisiones/calcular', [ComisionController::class, 'calcular'])->name('comisiones.calcular');
Route::get('comisiones/{comision}', [ComisionController::class, 'show'])->name('comisiones.show');
Route::post('comisiones/{comision}/aprobar', [ComisionController::class, 'aprobar'])->name('comisiones.aprobar');
