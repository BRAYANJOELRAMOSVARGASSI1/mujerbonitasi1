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
     * Muestra la lista de pagos para administradores y recepcionistas (CU26).
     */
    public function index(Request $request)
    {
        $pagos = Pago::with(['cita.cliente', 'cita.servicio', 'cita.estilista'])
            ->when($request->filled('estado'), function ($q) use ($request) {
                $q->where('estado_pago', $request->estado);
            })
            ->when($request->filled('metodo'), function ($q) use ($request) {
                $q->where('metodo', $request->metodo);
            })
            ->orderByDesc('updated_at')
            ->paginate(15)
            ->withQueryString();

        $kpis = [
            'total_completado' => Pago::where('estado_pago', 'completado')->sum('monto'),
            'total_pendiente'  => Pago::where('estado_pago', 'pendiente')->sum('monto'),
            'pagos_online'     => Pago::where('estado_pago', 'completado')->where('metodo', 'stripe')->sum('monto'),
            'pagos_efectivo'   => Pago::where('estado_pago', 'completado')->where('metodo', 'efectivo')->sum('monto'),
        ];

        return view('modules.pagos.index', compact('pagos', 'kpis'));
    }

    /**
     * Procesa un pago manual (Efectivo / Tarjeta Presencial) para una cita pendiente.
     */
    public function processManual(Request $request, $cita_id)
    {
        $request->validate([
            'metodo' => 'required|in:efectivo,tarjeta_presencial',
            'monto'  => 'required|numeric|min:0'
        ]);

        $cita = Cita::findOrFail($cita_id);
        
        $pago = Pago::where('cita_id', $cita->id)->first();
        if (!$pago) {
            $pago = Pago::create([
                'cita_id' => $cita->id,
                'monto' => $cita->precio_total,
                'estado_pago' => 'pendiente',
                'metodo' => 'stripe' // Default until paid manually
            ]);
        }

        if ($pago->estado_pago === 'completado') {
            return back()->with('error', 'Esta cita ya ha sido pagada.');
        }

        $pago->update([
            'estado_pago' => 'completado',
            'metodo'      => $request->metodo,
            'monto'       => $request->monto,
            'transaccion_id' => 'MANUAL-' . strtoupper(uniqid())
        ]);

        return redirect()->route('pagos.index')->with('status', 'Pago registrado manualmente con éxito.');
    }

    /**
     * Muestra la factura/ticket web de un pago (CU18).
     */
    public function factura($pago_id)
    {
        $pago = Pago::with(['cita.cliente', 'cita.servicio', 'cita.estilista'])->findOrFail($pago_id);
        
        if ($pago->estado_pago !== 'completado') {
            return back()->with('error', 'Solo se pueden generar facturas de pagos completados.');
        }

        return view('modules.pagos.factura', compact('pago'));
    }

    /**
     * Descarga la factura/ticket en PDF (CU18).
     */
    public function facturaDownload($pago_id)
    {
        $pago = Pago::with(['cita.cliente', 'cita.servicio', 'cita.estilista'])->findOrFail($pago_id);
        
        if ($pago->estado_pago !== 'completado') {
            return back()->with('error', 'Solo se pueden generar facturas de pagos completados.');
        }

        $pdf = app('dompdf.wrapper');
        $view = view('modules.pagos.pdf.factura', compact('pago'));
        $pdf->loadHTML($view->render());
        
        return $pdf->download("Factura_{$pago->transaccion_id}.pdf");
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
        $pago = Pago::where('cita_id', $cita_id)->where('estado_pago', 'completado')->first();
        
        return view('modules.pagos.success', compact('cita', 'pago'));
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
