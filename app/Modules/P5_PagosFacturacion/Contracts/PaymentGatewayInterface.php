<?php

namespace App\Modules\P5_PagosFacturacion\Contracts;

use App\Modules\P4_GestionServiciosCitas\Models\Cita;

interface PaymentGatewayInterface
{
    /**
     * Crea una sesión de checkout y retorna la URL para redirigir al usuario.
     *
     * @param Cita $cita
     * @param float $amount Monto total a cobrar.
     * @param string $currency Moneda (ej. 'bob', 'usd').
     * @return string URL de redirección.
     */
    public function createCheckoutSession(Cita $cita, float $amount, string $currency): string;
}
