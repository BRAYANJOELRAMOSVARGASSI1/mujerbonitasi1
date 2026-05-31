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
        Schema::create('herramienta_incidentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('herramienta_id')->constrained('herramientas')->onDelete('cascade');
            $table->foreignId('estilista_id')->constrained('estilistas')->onDelete('cascade');
            $table->enum('tipo', ['dano', 'perdida', 'fallo']);
            $table->text('descripcion');
            $table->enum('estado', ['pendiente', 'autorizado', 'rechazado'])->default('pendiente');
            $table->text('resolucion')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('herramienta_incidentes');
    }
};
