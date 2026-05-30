<?php

namespace App\Modules\P5_PagosFacturacion\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Webhook;
use App\Modules\P5_PagosFacturacion\Models\Pago;
use App\Modules\P4_GestionServiciosCitas\Models\Cita;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            Log::error('Stripe Webhook Error: Invalid Payload');
            return response('', 400);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            Log::error('Stripe Webhook Error: Invalid Signature');
            return response('', 400);
        }

        // Manejar el evento
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $this->handleCheckoutSessionCompleted($session);
                break;
            default:
                Log::info('Unhandled event type ' . $event->type);
        }

        return response('', 200);
    }

    protected function handleCheckoutSessionCompleted($session)
    {
        $cita_id = $session->metadata->cita_id ?? null;
        $payment_intent = $session->payment_intent;

        if ($cita_id) {
            // Actualizar pago a completado
            Pago::where('cita_id', $cita_id)
                ->where('estado_pago', 'pendiente')
                ->update([
                    'estado_pago' => 'completado',
                    'transaccion_id' => $payment_intent
                ]);

            // Actualizar estado de la cita si corresponde
            $cita = Cita::find($cita_id);
            if ($cita) {
                // Por ejemplo, podríamos marcar notas u otro estado
                // $cita->update(['estado' => 'pagada']); 
                // Asumiendo que 'pagada' es un estado válido, pero si no lo es, 
                // el pago se valida mediante la relación con `pagos`.
                Log::info("Pago completado para la Cita ID: {$cita_id}");
            }
        }
    }
}
