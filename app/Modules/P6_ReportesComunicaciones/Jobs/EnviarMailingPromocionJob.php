<?php

namespace App\Modules\P6_ReportesComunicaciones\Jobs;

use App\Modules\P4_GestionServiciosCitas\Models\Promocion;
use App\Modules\P2_GestionPersonalClientes\Models\Cliente;
use App\Modules\P6_ReportesComunicaciones\Mail\PromocionMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EnviarMailingPromocionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $promocion;

    /**
     * Create a new job instance.
     */
    public function __construct(Promocion $promocion)
    {
        $this->promocion = $promocion;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Iniciando envío masivo de Promoción: {$this->promocion->nombre}");

        // Obtener clientes activos con correo electrónico
        Cliente::activos()->whereNotNull('email')->where('email', '!=', '')->chunk(100, function ($clientes) {
            foreach ($clientes as $cliente) {
                try {
                    Mail::to($cliente->email)->send(new PromocionMail($this->promocion, $cliente));
                } catch (\Exception $e) {
                    Log::error("Error al enviar promoción a cliente {$cliente->email}: " . $e->getMessage());
                }
            }
        });

        Log::info("Envío masivo finalizado para: {$this->promocion->nombre}");
    }
}
