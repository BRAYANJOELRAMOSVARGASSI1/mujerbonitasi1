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

Route::prefix('pagos')->name('pagos.')->group(function () {
    // CU26 — Procesar Pago (Listado y Manual)
    Route::get('/', [PagoController::class, 'index'])->name('index')->middleware('role:admin|recepcionista|super-admin');
    Route::post('/manual/{cita_id}', [PagoController::class, 'processManual'])->name('manual')->middleware('role:admin|recepcionista|super-admin');

    // Checkout de cliente
    Route::get('/checkout/{cita_id}', [PagoController::class, 'checkout'])->name('checkout');
    Route::post('/stripe/iniciar/{cita_id}', [PagoController::class, 'iniciarPago'])->name('stripe.iniciar');
    Route::get('/stripe/success', [PagoController::class, 'success'])->name('stripe.success');
    Route::get('/stripe/cancel', [PagoController::class, 'cancel'])->name('stripe.cancel');

    // CU18 — Generar Factura/Ticket
    Route::get('/{pago}/factura', [PagoController::class, 'factura'])->name('factura');
    Route::get('/{pago}/factura/pdf', [PagoController::class, 'facturaDownload'])->name('factura.pdf');
});
