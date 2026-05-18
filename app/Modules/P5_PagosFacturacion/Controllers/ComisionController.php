<?php

namespace App\Modules\P5_PagosFacturacion\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Modules\P2_GestionPersonalClientes\Models\Estilista;
use App\Modules\P4_GestionServiciosCitas\Models\ServicioRealizado;
use App\Modules\P5_PagosFacturacion\Models\Comision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * [CU25] — Calcular Comisión Estilista
 *
 * Actores:
 *  - Sistema: calcula automáticamente las comisiones.
 *  - Administradora (Admin): revisa, aprueba o ajusta el cálculo.
 *  - Estilista: puede ver sus propias comisiones.
 */
class ComisionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver comisiones')->only(['index', 'show']);
        $this->middleware('permission:calcular comisiones')->only(['calcular']);
        $this->middleware('permission:aprobar comisiones')->only(['aprobar']);
    }

    /**
     * Dashboard de comisiones con filtros de período.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Período por defecto: mes actual
        $periodoInicio = $request->get('periodo_inicio', Carbon::now()->startOfMonth()->toDateString());
        $periodoFin    = $request->get('periodo_fin', Carbon::now()->endOfMonth()->toDateString());

        $query = Comision::with('estilista')
            ->when($request->filled('estilista_id'), fn($q) => $q->where('estilista_id', $request->estilista_id))
            ->when($request->filled('estado'), fn($q) => $q->where('estado', $request->estado));

        // Restricción por rol: estilista solo ve sus propias comisiones
        if ($user->hasRole('estilista')) {
            $estilistaId = Estilista::where('user_id', $user->id)->value('id');
            $query->where('estilista_id', $estilistaId);
        }

        $comisiones = $query->orderByDesc('periodo_inicio')
            ->paginate(12)
            ->withQueryString();

        $estilistas = Estilista::activos()->orderBy('nombre')->get();

        // Resumen general del período seleccionado
        $resumenQuery = ServicioRealizado::whereBetween('fecha_realizacion', [$periodoInicio, $periodoFin . ' 23:59:59']);
        if ($user->hasRole('estilista')) {
            $estilistaId = Estilista::where('user_id', $user->id)->value('id');
            $resumenQuery->where('estilista_id', $estilistaId);
        }

        $resumen = [
            'total_servicios'   => $resumenQuery->count(),
            'total_ingresos'    => $resumenQuery->sum('precio_cobrado'),
            'total_comisiones'  => $resumenQuery->sum('comision_monto'),
        ];

        return view('modules.pagos.comisiones.index', compact(
            'comisiones', 'estilistas', 'periodoInicio', 'periodoFin', 'resumen'
        ));
    }

    /**
     * Calcular comisiones para un período.
     * Agrupa servicios realizados por estilista y genera registros de comisión.
     */
    public function calcular(Request $request)
    {
        $data = $request->validate([
            'periodo_inicio' => ['required', 'date'],
            'periodo_fin'    => ['required', 'date', 'after_or_equal:periodo_inicio'],
        ]);

        $periodoInicio = $data['periodo_inicio'];
        $periodoFin    = $data['periodo_fin'];

        // Agrupar servicios realizados por estilista en el período
        $agrupados = ServicioRealizado::whereBetween('fecha_realizacion', [$periodoInicio, $periodoFin . ' 23:59:59'])
            ->selectRaw('estilista_id, SUM(precio_cobrado) as total_servicios, SUM(comision_monto) as total_comision, COUNT(*) as cantidad')
            ->groupBy('estilista_id')
            ->get();

        if ($agrupados->isEmpty()) {
            return back()->with('error', 'No existen servicios finalizados para el período seleccionado.');
        }

        $creadas = 0;
        foreach ($agrupados as $grupo) {
            // Verificar si ya existe una comisión para este estilista en este período
            $existente = Comision::where('estilista_id', $grupo->estilista_id)
                ->where('periodo_inicio', $periodoInicio)
                ->where('periodo_fin', $periodoFin)
                ->first();

            if ($existente) {
                // Actualizar la existente
                $existente->update([
                    'total_servicios'   => $grupo->total_servicios,
                    'total_comision'    => $grupo->total_comision,
                    'cantidad_servicios' => $grupo->cantidad,
                    'estado'            => 'pendiente', // Resetear a pendiente si se recalcula
                ]);
            } else {
                Comision::create([
                    'estilista_id'       => $grupo->estilista_id,
                    'periodo_inicio'     => $periodoInicio,
                    'periodo_fin'        => $periodoFin,
                    'total_servicios'    => $grupo->total_servicios,
                    'total_comision'     => $grupo->total_comision,
                    'cantidad_servicios' => $grupo->cantidad,
                    'estado'             => 'pendiente',
                ]);
                $creadas++;
            }
        }

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'Cálculo de Comisiones',
            'description' => "Comisiones calculadas para período {$periodoInicio} a {$periodoFin}: {$agrupados->count()} estilistas, {$creadas} nuevos registros.",
            'ip_address'  => $request->ip() ?? 'No disponible',
            'browser'     => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('comisiones.index', [
            'periodo_inicio' => $periodoInicio,
            'periodo_fin'    => $periodoFin,
        ])->with('status', "Comisiones calculadas correctamente para {$agrupados->count()} estilistas.");
    }

    /**
     * Detalle de comisión de un estilista.
     */
    public function show(Comision $comision)
    {
        $comision->load('estilista');

        // Obtener el detalle de servicios realizados en el período
        $serviciosDetalle = ServicioRealizado::with(['servicio', 'cliente', 'cita'])
            ->where('estilista_id', $comision->estilista_id)
            ->whereBetween('fecha_realizacion', [$comision->periodo_inicio, $comision->periodo_fin->endOfDay()])
            ->orderByDesc('fecha_realizacion')
            ->get();

        return view('modules.pagos.comisiones.show', compact('comision', 'serviciosDetalle'));
    }

    /**
     * Aprobar comisión (Admin).
     */
    public function aprobar(Request $request, Comision $comision)
    {
        $comision->update([
            'estado' => 'aprobada',
            'notas'  => $request->get('notas', $comision->notas),
        ]);

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'Aprobación de Comisión',
            'description' => "Comisión #{$comision->id} aprobada para {$comision->estilista->nombre_completo}: Bs. {$comision->total_comision}",
            'ip_address'  => $request->ip() ?? 'No disponible',
            'browser'     => $request->header('user-agent') ?? 'No disponible',
        ]);

        return back()->with('status', 'Comisión aprobada correctamente.');
    }
}
