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
        Schema::create('horario_excepciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estilista_id')->constrained('estilistas')->onDelete('cascade');
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->string('tipo')->default('permiso'); // permiso, vacaciones, bloqueo_manual
            $table->string('motivo')->nullable();
            $table->string('estado')->default('activo'); // activo, inactivo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horario_excepciones');
    }
};
