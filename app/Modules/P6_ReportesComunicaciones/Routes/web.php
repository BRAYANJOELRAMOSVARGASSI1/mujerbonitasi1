<?php

use Illuminate\Support\Facades\Route;
use App\Modules\P6_ReportesComunicaciones\Controllers\ReportesController;

/*
|--------------------------------------------------------------------------
| P6 — REPORTES Y ANÁLISIS DEL NEGOCIO
|--------------------------------------------------------------------------
| CU15 — Reporte de Servicios
| CU16 — Reporte de Ingresos
| CU17 — Reporte de Clientes
| Acceso exclusivo: admin / super-admin
|--------------------------------------------------------------------------
*/

Route::prefix('reportes')->name('reportes.')->group(function () {

    // Dashboard principal con filtros
    Route::get('/', [ReportesController::class, 'index'])->name('index');

    // Exportación PDF por tipo
    Route::get('/pdf/{tipo}', [ReportesController::class, 'exportarPdf'])
        ->name('pdf')
        ->where('tipo', 'ventas|clientes|inventario|servicios|promociones|general');

    // Exportación Excel por tipo
    Route::get('/excel/{tipo}', [ReportesController::class, 'exportarExcel'])
        ->name('excel')
        ->where('tipo', 'ventas|clientes|inventario|servicios|promociones');
});
