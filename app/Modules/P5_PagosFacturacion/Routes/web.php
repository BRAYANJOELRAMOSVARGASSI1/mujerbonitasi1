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

// CU26 — Procesar Pago
use App\Modules\P5_PagosFacturacion\Controllers\PagoController;

Route::get('pagos/checkout/{cita_id}', [PagoController::class, 'checkout'])->name('pagos.checkout');
Route::post('pagos/stripe/iniciar/{cita_id}', [PagoController::class, 'iniciarPago'])->name('pagos.stripe.iniciar');
Route::get('pagos/stripe/success', [PagoController::class, 'success'])->name('pagos.stripe.success');
Route::get('pagos/stripe/cancel', [PagoController::class, 'cancel'])->name('pagos.stripe.cancel');
