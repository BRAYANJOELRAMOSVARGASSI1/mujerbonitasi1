<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estilista_id')->constrained('estilistas')->cascadeOnDelete();
            $table->enum('dia_semana', ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo']);
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();

            // Evitar horarios duplicados para el mismo estilista en el mismo día
            $table->unique(['estilista_id', 'dia_semana', 'hora_inicio', 'hora_fin'], 'horario_unico');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
