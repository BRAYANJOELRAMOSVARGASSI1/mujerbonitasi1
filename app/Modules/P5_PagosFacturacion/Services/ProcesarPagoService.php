<?php

namespace App\Modules\P5_PagosFacturacion\Services;

use App\Modules\P5_PagosFacturacion\Contracts\PaymentGatewayInterface;
use App\Modules\P5_PagosFacturacion\Models\Pago;
use App\Modules\P4_GestionServiciosCitas\Models\Cita;

class ProcesarPagoService
{
    protected $paymentGateway;

    public function __construct(PaymentGatewayInterface $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    /**
     * Inicia el proceso de pago para una cita.
     * Crea un registro de pago "pendiente" y devuelve la URL del Gateway.
     */
    public function iniciarPago(Cita $cita): string
    {
        // 1. Validar si la cita ya está pagada
        // En una app real podríamos verificar si ya existe un pago completado para esta cita
        $pagoExistente = Pago::where('cita_id', $cita->id)
                            ->where('estado_pago', 'completado')
                            ->first();
                            
        if ($pagoExistente) {
            throw new \Exception("La cita ya se encuentra pagada.");
        }

        $monto = $cita->precio_total;
        $moneda = 'bob'; // Moneda base (Bolivianos)

        // 2. Generar sesión en el Gateway
        $redirectUrl = $this->paymentGateway->createCheckoutSession($cita, (float)$monto, $moneda);

        // 3. Crear el registro del Pago en base de datos
        Pago::updateOrCreate(
            ['cita_id' => $cita->id, 'estado_pago' => 'pendiente'],
            [
                'monto' => $monto,
                'metodo' => 'stripe',
                // El ID de transacción se llenará al completar vía webhook si es necesario
            ]
        );

        return $redirectUrl;
    }
}
