<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Registro detallado de servicios completados (CU14).
     * Vincula cita + estilista + servicio + cliente con datos de ejecución.
     */
    public function up(): void
    {
        Schema::create('servicios_realizados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')->constrained('citas')->cascadeOnDelete();
            $table->foreignId('estilista_id')->constrained('estilistas')->cascadeOnDelete();
            $table->foreignId('servicio_id')->constrained('servicios')->cascadeOnDelete();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->text('observaciones')->nullable();
            $table->integer('duracion_real_minutos')->nullable();
            $table->text('productos_utilizados')->nullable();     // JSON string con lista de productos
            $table->decimal('precio_cobrado', 10, 2);
            $table->decimal('comision_porcentaje', 5, 2);         // Snapshot del % vigente
            $table->decimal('comision_monto', 10, 2);             // precio_cobrado * comision_porcentaje / 100
            $table->dateTime('fecha_realizacion');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicios_realizados');
    }
};
