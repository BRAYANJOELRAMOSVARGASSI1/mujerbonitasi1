<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('herramientas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();
            $table->string('categoria', 80)->comment('Cabello, Uñas, Cejas y Pestañas, Maquillaje, General');
            $table->string('numero_serie', 100)->nullable()->unique();
            $table->string('area_asignada', 80)->nullable()->comment('Área del salón donde se usa');
            $table->enum('estado', ['disponible', 'en_uso', 'mantenimiento', 'baja'])->default('disponible');
            $table->date('fecha_adquisicion')->nullable();
            $table->decimal('costo', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('herramientas');
    }
};
