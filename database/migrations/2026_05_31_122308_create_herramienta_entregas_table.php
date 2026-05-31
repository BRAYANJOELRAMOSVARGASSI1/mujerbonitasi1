<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('herramienta_entregas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('herramienta_id')->constrained('herramientas')->onDelete('cascade');
            $table->foreignId('estilista_id')->constrained('estilistas')->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->date('fecha_entrega');
            $table->date('fecha_devolucion_esperada')->nullable();
            $table->date('fecha_devolucion_real')->nullable();
            $table->enum('estado', ['entregada', 'devuelta'])->default('entregada');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('herramienta_entregas');
    }
};
