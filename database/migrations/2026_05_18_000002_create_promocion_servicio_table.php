<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla pivote: Promociones ↔ Servicios (CU24).
     */
    public function up(): void
    {
        Schema::create('promocion_servicio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promocion_id')->constrained('promociones')->cascadeOnDelete();
            $table->foreignId('servicio_id')->constrained('servicios')->cascadeOnDelete();
            $table->unique(['promocion_id', 'servicio_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promocion_servicio');
    }
};
