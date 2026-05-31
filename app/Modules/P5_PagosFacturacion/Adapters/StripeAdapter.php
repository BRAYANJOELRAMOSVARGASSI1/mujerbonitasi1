<?php

namespace App\Modules\P5_PagosFacturacion\Adapters;

use App\Modules\P5_PagosFacturacion\Contracts\PaymentGatewayInterface;
use App\Modules\P4_GestionServiciosCitas\Models\Cita;
use Stripe\StripeClient;
use Exception;

class StripeAdapter implements PaymentGatewayInterface
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function createCheckoutSession(Cita $cita, float $amount, string $currency): string
    {
        try {
            // Stripe requiere el monto en la unidad más pequeña (centavos si es aplicable).
            // Para Bolivianos (bob) y USD (usd) es por 100.
            $unitAmount = intval(round($amount * 100));

            $session = $this->stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => $currency,
                        'product_data' => [
                            'name' => 'Pago por Servicios en Magy Makeup (Cita #' . $cita->id . ')',
                        ],
                        'unit_amount' => $unitAmount,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                // Incluimos el ID de la cita en los metadatos para recuperarlo en el webhook
                'metadata' => [
                    'cita_id' => $cita->id,
                ],
                'success_url' => route('pagos.stripe.success', ['cita_id' => $cita->id]) . '&session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('pagos.stripe.cancel', ['cita_id' => $cita->id]),
            ]);

            return $session->url;
        } catch (Exception $e) {
            throw new Exception("Error al crear sesión de Stripe: " . $e->getMessage());
        }
    }
}
