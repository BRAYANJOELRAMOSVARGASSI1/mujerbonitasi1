<?php

namespace App\Modules\P5_PagosFacturacion\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\P4_GestionServiciosCitas\Models\Cita;
use App\Modules\P5_PagosFacturacion\Services\ProcesarPagoService;
use App\Modules\P5_PagosFacturacion\Models\Pago;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    protected $procesarPagoService;

    public function __construct(ProcesarPagoService $procesarPagoService)
    {
        $this->procesarPagoService = $procesarPagoService;
    }

    /**
     * Muestra la pantalla de checkout para una cita.
     */
    public function checkout($cita_id)
    {
        $cita = Cita::with('servicio')->findOrFail($cita_id);

        // Verificamos si ya está pagada
        $pago = Pago::where('cita_id', $cita->id)->where('estado_pago', 'completado')->first();
        if ($pago) {
            return redirect()->route('pagos.stripe.success', ['cita_id' => $cita->id])
                             ->with('info', 'Esta cita ya ha sido pagada.');
        }

        return view('modules.pagos.checkout', compact('cita'));
    }

    /**
     * Inicia el proceso de pago y redirige a Stripe.
     */
    public function iniciarPago(Request $request, $cita_id)
    {
        try {
            $cita = Cita::findOrFail($cita_id);
            $redirectUrl = $this->procesarPagoService->iniciarPago($cita);
            
            return redirect()->away($redirectUrl);
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo iniciar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Pantalla de éxito tras el pago.
     */
    public function success(Request $request)
    {
        $cita_id = $request->query('cita_id');
        $session_id = $request->query('session_id');
        $cita = Cita::findOrFail($cita_id);

        if ($session_id) {
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            try {
                $session = $stripe->checkout->sessions->retrieve($session_id);
                if ($session->payment_status === 'paid') {
                    $pago = Pago::where('cita_id', $cita_id)
                                ->where('estado_pago', 'pendiente')
                                ->first();
                    if ($pago) {
                        $pago->update([
                            'estado_pago' => 'completado',
                            'transaccion_id' => $session->payment_intent
                        ]);
                    }
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error validando pago en success: ' . $e->getMessage());
            }
        }
        
        return view('modules.pagos.success', compact('cita'));
    }

    /**
     * Pantalla de cancelación.
     */
    public function cancel(Request $request)
    {
        $cita_id = $request->query('cita_id');
        return redirect()->route('pagos.checkout', ['cita_id' => $cita_id])
                         ->with('error', 'El pago ha sido cancelado.');
    }
}
