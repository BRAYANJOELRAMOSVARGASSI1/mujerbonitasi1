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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')->constrained('citas')->onDelete('cascade');
            $table->decimal('monto', 10, 2);
            $table->enum('estado_pago', ['pendiente', 'completado', 'fallido'])->default('pendiente');
            $table->string('metodo')->default('stripe');
            $table->string('transaccion_id')->nullable()->comment('ID de la transacción en el Gateway (ej. Stripe)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
