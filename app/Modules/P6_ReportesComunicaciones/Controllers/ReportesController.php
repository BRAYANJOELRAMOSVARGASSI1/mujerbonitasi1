<?php

namespace App\Modules\P6_ReportesComunicaciones\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

// Modelos del sistema
use App\Modules\P2_GestionPersonalClientes\Models\Cliente;
use App\Modules\P2_GestionPersonalClientes\Models\Estilista;
use App\Modules\P3_GestionInventarioHerramientas\Models\Producto;
use App\Modules\P4_GestionServiciosCitas\Models\Cita;
use App\Modules\P4_GestionServiciosCitas\Models\Servicio;
use App\Modules\P4_GestionServiciosCitas\Models\ServicioRealizado;
use App\Modules\P4_GestionServiciosCitas\Models\Promocion;
use App\Modules\P5_PagosFacturacion\Models\Comision;

// Export classes
use App\Modules\P6_ReportesComunicaciones\Exports\VentasExport;
use App\Modules\P6_ReportesComunicaciones\Exports\ClientesExport;
use App\Modules\P6_ReportesComunicaciones\Exports\InventarioExport;
use App\Modules\P6_ReportesComunicaciones\Exports\ServiciosExport;
use App\Modules\P6_ReportesComunicaciones\Exports\PromocionesExport;

/**
 * ReportesController — P6
 *
 * Módulo de reportes y análisis del negocio.
 * Acceso exclusivo para administradores del sistema.
 */
class ReportesController extends Controller
{
    /**
     * Constructor: aplica middleware de roles.
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            if (! auth()->user()->hasAnyRole(['admin', 'super-admin'])) {
                abort(403, 'Solo los administradores pueden acceder a los reportes.');
            }
            return $next($request);
        });
    }

    // ─────────────────────────────────────────────────────────
    // DASHBOARD PRINCIPAL
    // ─────────────────────────────────────────────────────────

    /**
     * Dashboard principal de reportes con KPIs y datos filtrados.
     */
    public function index(Request $request)
    {
        // Validar y obtener rango de fechas (default: mes actual)
        $fechaInicio = $request->filled('fecha_inicio')
            ? Carbon::parse($request->fecha_inicio)->startOfDay()
            : Carbon::now()->startOfMonth();

        $fechaFin = $request->filled('fecha_fin')
            ? Carbon::parse($request->fecha_fin)->endOfDay()
            : Carbon::now()->endOfDay();

        $estilistaId = $request->input('estilista_id');
        $clienteId   = $request->input('cliente_id');

        // ── KPIs GENERALES ──────────────────────────────────────
        $kpis = $this->buildKpis($fechaInicio, $fechaFin, $estilistaId, $clienteId);

        // ── SECCIÓN VENTAS ───────────────────────────────────────
        $ventas = $this->buildVentas($fechaInicio, $fechaFin, $estilistaId, $clienteId);

        // ── SECCIÓN CLIENTES ─────────────────────────────────────
        $clientes = $this->buildClientes($fechaInicio, $fechaFin);

        // ── SECCIÓN INVENTARIO ───────────────────────────────────
        $inventario = $this->buildInventario();

        // ── SECCIÓN SERVICIOS & CITAS ────────────────────────────
        $servicios = $this->buildServicios($fechaInicio, $fechaFin, $estilistaId, $clienteId);

        // ── SECCIÓN PAGOS ONLINE/MANUALES ────────────────────────
        $pagos = $this->buildPagos($fechaInicio, $fechaFin, $estilistaId, $clienteId);

        // ── SECCIÓN PROMOCIONES & COMISIONES ─────────────────────
        $promociones = $this->buildPromociones($fechaInicio, $fechaFin, $estilistaId);

        // ── DATOS PARA FILTROS (selects) ─────────────────────────
        $listaEstilistas = Estilista::activos()->orderBy('nombre')->get();
        $listaClientes   = Cliente::activos()->orderBy('nombre')->get();

        // ── DATOS PARA GRÁFICA (Chart.js) ────────────────────────
        $graficaIngresosMes   = $this->graficaIngresosPorMes();
        $graficaTopServicios  = $this->graficaTopServicios($fechaInicio, $fechaFin);
        $graficaCitasEstado   = $this->graficaCitasPorEstado($fechaInicio, $fechaFin);

        return view('modules.reportes.index', compact(
            'kpis',
            'ventas',
            'clientes',
            'inventario',
            'servicios',
            'pagos',
            'promociones',
            'listaEstilistas',
            'listaClientes',
            'graficaIngresosMes',
            'graficaTopServicios',
            'graficaCitasEstado',
            'fechaInicio',
            'fechaFin',
            'estilistaId',
            'clienteId'
        ));
    }

    // ─────────────────────────────────────────────────────────
    // EXPORTACIÓN PDF
    // ─────────────────────────────────────────────────────────

    /**
     * Exporta un reporte en PDF según el tipo solicitado.
     */
    public function exportarPdf(Request $request, string $tipo)
    {
        $fechaInicio = $request->filled('fecha_inicio')
            ? Carbon::parse($request->fecha_inicio)->startOfDay()
            : Carbon::now()->startOfMonth();

        $fechaFin = $request->filled('fecha_fin')
            ? Carbon::parse($request->fecha_fin)->endOfDay()
            : Carbon::now()->endOfDay();

        $datos = match ($tipo) {
            'ventas'      => $this->buildVentas($fechaInicio, $fechaFin, $request->estilista_id, $request->cliente_id),
            'clientes'    => $this->buildClientes($fechaInicio, $fechaFin),
            'inventario'  => $this->buildInventario(),
            'servicios'   => $this->buildServicios($fechaInicio, $fechaFin, $request->estilista_id, $request->cliente_id),
            'promociones' => $this->buildPromociones($fechaInicio, $fechaFin, $request->estilista_id),
            'general'     => $this->buildGeneral($fechaInicio, $fechaFin, $request->estilista_id, $request->cliente_id),
            default       => abort(404, 'Tipo de reporte no válido'),
        };

        $view = view("modules.reportes.pdf.{$tipo}", compact('datos', 'fechaInicio', 'fechaFin'));

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($view->render());
        $pdf->setPaper('A4', 'landscape');

        $nombreArchivo = "reporte_{$tipo}_" . now()->format('Y-m-d_His') . '.pdf';

        return $pdf->stream($nombreArchivo);
    }

    // ─────────────────────────────────────────────────────────
    // EXPORTACIÓN EXCEL
    // ─────────────────────────────────────────────────────────

    /**
     * Exporta un reporte en Excel según el tipo solicitado.
     */
    public function exportarExcel(Request $request, string $tipo)
    {
        $fechaInicio = $request->filled('fecha_inicio')
            ? Carbon::parse($request->fecha_inicio)->startOfDay()
            : Carbon::now()->startOfMonth();

        $fechaFin = $request->filled('fecha_fin')
            ? Carbon::parse($request->fecha_fin)->endOfDay()
            : Carbon::now()->endOfDay();

        $nombreArchivo = "reporte_{$tipo}_" . now()->format('Y-m-d_His') . '.xlsx';

        $export = match ($tipo) {
            'ventas'      => new VentasExport($fechaInicio, $fechaFin),
            'clientes'    => new ClientesExport($fechaInicio, $fechaFin),
            'inventario'  => new InventarioExport(),
            'servicios'   => new ServiciosExport($fechaInicio, $fechaFin),
            'promociones' => new PromocionesExport($fechaInicio, $fechaFin),
            default       => abort(404, 'Tipo de reporte no válido'),
        };

        return \Maatwebsite\Excel\Facades\Excel::download($export, $nombreArchivo);
    }

    // ─────────────────────────────────────────────────────────
    // BUILDERS DE DATOS
    // ─────────────────────────────────────────────────────────

    /** KPIs generales del negocio. */
    private function buildKpis(Carbon $inicio, Carbon $fin, $estilistaId = null, $clienteId = null): array
    {
        $ingresosQuery = ServicioRealizado::whereBetween('fecha_realizacion', [$inicio, $fin]);
        if ($estilistaId) $ingresosQuery->where('estilista_id', $estilistaId);
        if ($clienteId) $ingresosQuery->where('cliente_id', $clienteId);
        $ingresosPeriodo = $ingresosQuery->sum('precio_cobrado');

        $pagosAdelantadosQuery = \App\Modules\P5_PagosFacturacion\Models\Pago::where('estado_pago', 'completado')
            ->whereBetween('updated_at', [$inicio, $fin])
            ->whereHas('cita', function($q) use ($estilistaId, $clienteId) {
                $q->whereDoesntHave('servicioRealizado');
                if ($estilistaId) $q->where('estilista_id', $estilistaId);
                if ($clienteId) $q->where('cliente_id', $clienteId);
            });
        $pagosAdelantados = $pagosAdelantadosQuery->sum('monto');
            
        $ingresosPeriodo += $pagosAdelantados;

        $serviciosRealizadosPeriodo = $ingresosQuery->count();

        $citasQuery = Cita::whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()]);
        if ($estilistaId) $citasQuery->where('estilista_id', $estilistaId);
        if ($clienteId) $citasQuery->where('cliente_id', $clienteId);
        $citasPeriodo = $citasQuery->count();

        $clientesTotales  = Cliente::count();
        $clientesActivos  = Cliente::activos()->count();
        $estilistasTotales = Estilista::activos()->count();
        $productosBajoStock = Producto::stockBajo()->count();

        // Validar que venga audio o texto
        request()->validate([
            'audio' => 'nullable|file',
            'texto' => 'nullable|string'
        ]);

        return compact(
            'ingresosPeriodo',
            'serviciosRealizadosPeriodo',
            'citasPeriodo',
            'clientesTotales',
            'clientesActivos',
            'estilistasTotales',
            'productosBajoStock'
        );
    }

    /** Datos para la sección de Ventas/Ingresos. */
    private function buildVentas(Carbon $inicio, Carbon $fin, $estilistaId = null, $clienteId = null): array
    {
        $query = ServicioRealizado::with(['estilista', 'servicio', 'cliente'])
            ->whereBetween('fecha_realizacion', [$inicio, $fin]);

        if ($estilistaId) {
            $query->where('estilista_id', $estilistaId);
        }
        if ($clienteId) {
            $query->where('cliente_id', $clienteId);
        }

        $registros = $query->orderByDesc('fecha_realizacion')->get();

        $totalIngresos    = $registros->sum('precio_cobrado');
        $totalComisiones  = $registros->sum('comision_monto');

        // Agregar ingresos de pagos adelantados
        $pagosAdelantadosQuery = \App\Modules\P5_PagosFacturacion\Models\Pago::where('estado_pago', 'completado')
            ->whereBetween('updated_at', [$inicio, $fin])
            ->whereHas('cita', function($q) use ($estilistaId, $clienteId) {
                $q->whereDoesntHave('servicioRealizado');
                if ($estilistaId) {
                    $q->where('estilista_id', $estilistaId);
                }
                if ($clienteId) {
                    $q->where('cliente_id', $clienteId);
                }
            });
            
        $ingresosAdelantados = $pagosAdelantadosQuery->sum('monto');
        $totalIngresos += $ingresosAdelantados;

        $totalOperaciones = $registros->count() + $pagosAdelantadosQuery->count();
        $ticketPromedio   = $totalOperaciones > 0 ? $totalIngresos / $totalOperaciones : 0;

        // Top 5 estilistas por ingresos
        $topEstilistas = ServicioRealizado::with('estilista')
            ->whereBetween('fecha_realizacion', [$inicio, $fin])
            ->select('estilista_id', DB::raw('SUM(precio_cobrado) as total'), DB::raw('COUNT(*) as cantidad'))
            ->groupBy('estilista_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return compact('registros', 'totalIngresos', 'totalComisiones', 'ticketPromedio', 'topEstilistas');
    }

    /** Datos para la sección de Clientes. */
    private function buildClientes(Carbon $inicio, Carbon $fin): array
    {
        $clientesList = Cliente::withCount([
            'citas as total_citas',
        ])->orderBy('nombre')->paginate(15, ['*'], 'page_clientes');

        // Clientes nuevos en el período
        $clientesNuevos = Cliente::whereBetween('created_at', [$inicio, $fin])->count();

        // Top clientes por cantidad de citas
        $topClientes = Cliente::withCount('citas')
            ->orderByDesc('citas_count')
            ->limit(5)
            ->get();

        $totalActivos   = Cliente::activos()->count();
        $totalInactivos = Cliente::where('estado', '!=', 'activo')->count();

        return compact('clientesList', 'clientesNuevos', 'topClientes', 'totalActivos', 'totalInactivos');
    }

    /** Datos para la sección de Inventario. */
    private function buildInventario(): array
    {
        $productos = Producto::orderBy('categoria')->orderBy('nombre')->get();

        $totalProductos   = $productos->count();
        $productosBajo    = $productos->filter(fn($p) => $p->stock_actual <= $p->stock_minimo && $p->stock_actual > 0)->count();
        $productosCritico = $productos->filter(fn($p) => $p->stock_actual === 0)->count();
        $productosOk      = $totalProductos - $productosBajo - $productosCritico;

        $valorInventario  = $productos->sum(fn($p) => $p->stock_actual * $p->precio_compra);
        $valorVentaTotal  = $productos->sum(fn($p) => $p->stock_actual * $p->precio_venta);

        // Agrupados por categoría
        $porCategoria = $productos->groupBy('categoria')->map(fn($grupo) => [
            'cantidad'  => $grupo->count(),
            'stock'     => $grupo->sum('stock_actual'),
            'valor'     => $grupo->sum(fn($p) => $p->stock_actual * $p->precio_compra),
        ]);

        return compact(
            'productos',
            'totalProductos',
            'productosBajo',
            'productosCritico',
            'productosOk',
            'valorInventario',
            'valorVentaTotal',
            'porCategoria'
        );
    }

    /** Datos para la sección de Servicios & Citas. */
    private function buildServicios(Carbon $inicio, Carbon $fin, $estilistaId = null, $clienteId = null): array
    {
        $query = Cita::with(['cliente', 'estilista', 'servicio'])
            ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()]);

        if ($estilistaId) $query->where('estilista_id', $estilistaId);
        if ($clienteId) $query->where('cliente_id', $clienteId);

        $citas = (clone $query)->orderByDesc('fecha')
            ->paginate(15, ['*'], 'page_citas');

        // Distribución por estado
        $porEstado = (clone $query)
            ->select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado');

        // Top servicios más solicitados
        $topServiciosQuery = Servicio::withCount(['citas as total_citas' => function ($q) use ($inicio, $fin, $estilistaId, $clienteId) {
            $q->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()]);
            if ($estilistaId) $q->where('estilista_id', $estilistaId);
            if ($clienteId) $q->where('cliente_id', $clienteId);
        }]);
        
        $topServicios = $topServiciosQuery->orderByDesc('total_citas')->limit(5)->get();

        $totalCitas    = (clone $query)->count();
        $citasCompletadas = $porEstado['completada'] ?? 0;
        $citasCanceladas  = $porEstado['cancelada'] ?? 0;
        $citasPendientes  = $porEstado['pendiente'] ?? 0;

        return compact('citas', 'porEstado', 'topServicios', 'totalCitas', 'citasCompletadas', 'citasCanceladas', 'citasPendientes');
    }

    /** Datos para la sección de Promociones & Comisiones. */
    private function buildPromociones(Carbon $inicio, Carbon $fin, $estilistaId = null): array
    {
        $promociones = Promocion::with('servicios')
            ->orderByDesc('fecha_inicio')
            ->get();

        $activas  = $promociones->filter(fn($p) => $p->is_vigente)->count();
        $vencidas = $promociones->filter(fn($p) => $p->fecha_fin < now())->count();
        $futuras  = $promociones->filter(fn($p) => $p->fecha_inicio > now())->count();

        $comisionesQuery = Comision::with('estilista')
            ->where(function ($q) use ($inicio, $fin) {
                $q->whereBetween('periodo_inicio', [$inicio, $fin])
                  ->orWhereBetween('periodo_fin', [$inicio, $fin]);
            });

        if ($estilistaId) {
            $comisionesQuery->where('estilista_id', $estilistaId);
        }

        $comisiones = $comisionesQuery->orderByDesc('periodo_fin')->get();

        $totalComisionesPagadas  = $comisiones->where('estado', 'aprobada')->sum('total_comision');
        $totalComisionesPendientes = $comisiones->where('estado', 'pendiente')->sum('total_comision');

        return compact(
            'promociones',
            'activas',
            'vencidas',
            'futuras',
            'comisiones',
            'totalComisionesPagadas',
            'totalComisionesPendientes'
        );
    }

    /** Datos para la sección de Pagos. */
    private function buildPagos(Carbon $inicio, Carbon $fin, $estilistaId = null, $clienteId = null): array
    {
        $pagosQuery = \App\Modules\P5_PagosFacturacion\Models\Pago::with(['cita.cliente', 'cita.servicio'])
            ->whereBetween('updated_at', [$inicio, $fin])
            ->whereHas('cita', function($q) use ($estilistaId, $clienteId) {
                if ($estilistaId) $q->where('estilista_id', $estilistaId);
                if ($clienteId) $q->where('cliente_id', $clienteId);
            })
            ->orderByDesc('updated_at');

        $listaPagos = $pagosQuery->get();

        $totalOnline   = $listaPagos->where('metodo', 'stripe')->where('estado_pago', 'completado')->sum('monto');
        $totalEfectivo = $listaPagos->where('metodo', 'efectivo')->where('estado_pago', 'completado')->sum('monto');
        $totalTarjeta  = $listaPagos->where('metodo', 'tarjeta_presencial')->where('estado_pago', 'completado')->sum('monto');
        $pendientes    = $listaPagos->where('estado_pago', 'pendiente')->sum('monto');

        $porMetodo = $listaPagos->where('estado_pago', 'completado')->groupBy('metodo')->map->count();

        return compact('listaPagos', 'totalOnline', 'totalEfectivo', 'totalTarjeta', 'pendientes', 'porMetodo');
    }

    /** Datos completos para reporte general. */
    private function buildGeneral(Carbon $inicio, Carbon $fin, $estilistaId = null, $clienteId = null): array
    {
        return [
            'kpis'       => $this->buildKpis($inicio, $fin, $estilistaId, $clienteId),
            'ventas'     => $this->buildVentas($inicio, $fin, $estilistaId, $clienteId),
            'clientes'   => $this->buildClientes($inicio, $fin),
            'inventario' => $this->buildInventario(),
            'servicios'  => $this->buildServicios($inicio, $fin, $estilistaId, $clienteId),
        ];
    }

    // ─────────────────────────────────────────────────────────
    // DATOS PARA GRÁFICAS (Chart.js)
    // ─────────────────────────────────────────────────────────

    /** Ingresos por mes (últimos 12 meses). */
    private function graficaIngresosPorMes(): array
    {
        $datos = ServicioRealizado::select(
            DB::raw("DATE_FORMAT(fecha_realizacion, '%Y-%m') as mes"),
            DB::raw('SUM(precio_cobrado) as total')
        )
        ->where('fecha_realizacion', '>=', Carbon::now()->subMonths(11)->startOfMonth())
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();

        return [
            'labels' => $datos->pluck('mes')->toArray(),
            'data'   => $datos->pluck('total')->map(fn($v) => (float)$v)->toArray(),
        ];
    }

    /** Top 5 servicios por ingresos en el período. */
    private function graficaTopServicios(Carbon $inicio, Carbon $fin): array
    {
        $datos = ServicioRealizado::with('servicio')
            ->whereBetween('fecha_realizacion', [$inicio, $fin])
            ->select('servicio_id', DB::raw('SUM(precio_cobrado) as total'), DB::raw('COUNT(*) as cantidad'))
            ->groupBy('servicio_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return [
            'labels' => $datos->map(fn($r) => $r->servicio?->nombre ?? 'Desconocido')->toArray(),
            'data'   => $datos->pluck('total')->map(fn($v) => (float)$v)->toArray(),
        ];
    }

    /** Distribución de citas por estado. */
    private function graficaCitasPorEstado(Carbon $inicio, Carbon $fin): array
    {
        $datos = Cita::whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])
            ->select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->get();

        return [
            'labels' => $datos->pluck('estado')->toArray(),
            'data'   => $datos->pluck('total')->toArray(),
        ];
    }

    // ─────────────────────────────────────────────────────────
    // GENERACIÓN DE REPORTES POR VOZ (API GROQ)
    // ─────────────────────────────────────────────────────────
    
    /**
     * Procesa el audio grabado o texto directo, y obtiene los
     * parámetros del reporte con LLama 3.1 via Groq.
     * Acepta:
     *   - audio (file): transcribe con Whisper primero
     *   - texto (string): procesa directamente con Llama
     */
    public function procesarAudioReporte(Request $request)
    {
        $tieneTexto = $request->filled('texto');
        $tieneAudio = $request->hasFile('audio');

        if (!$tieneTexto && !$tieneAudio) {
            return response()->json(['error' => 'Debes enviar texto o un archivo de audio.'], 422);
        }

        $groqApiKey = config('services.groq.key');
        if (!$groqApiKey) {
            return response()->json(['error' => 'API Key de Groq no configurada.'], 500);
        }

        try {
            // ── Paso 1: Obtener el texto ───────────────────────────
            if ($tieneTexto) {
                // Texto plano enviado directamente desde el modal
                $textoTranscrito = trim($request->input('texto'));
            } else {
                // Audio → transcripción con Whisper (Groq)
                $request->validate([
                    'audio' => 'required|file|max:10240',
                ]);

                $audioFile = $request->file('audio');
                $audioPath = $audioFile->getPathname();
                $audioName = $audioFile->getClientOriginalName();
                if (!str_contains($audioName, '.')) {
                    $audioName .= '.webm';
                }

                $responseWhisper = Http::withToken($groqApiKey)
                    ->attach('file', file_get_contents($audioPath), $audioName)
                    ->post('https://api.groq.com/openai/v1/audio/transcriptions', [
                        'model'    => 'whisper-large-v3',
                        'language' => 'es',
                    ]);

                if (!$responseWhisper->successful()) {
                    Log::error('Error en Whisper', ['res' => $responseWhisper->json()]);
                    return response()->json(['error' => 'Error al transcribir el audio.'], 500);
                }

                $textoTranscrito = $responseWhisper->json('text');

                if (empty($textoTranscrito)) {
                    return response()->json(['error' => 'No se detectó voz o el audio está vacío.'], 400);
                }
            }

            // ── Paso 2: Extraer intención con Llama 3.1 (Groq) ────
            $hoy         = Carbon::today()->format('Y-m-d');
            $inicioMes   = Carbon::now()->startOfMonth()->format('Y-m-d');
            $finMes      = Carbon::now()->endOfMonth()->format('Y-m-d');
            $inicioSemana = Carbon::now()->startOfWeek()->format('Y-m-d');
            $finSemana    = Carbon::now()->endOfWeek()->format('Y-m-d');
            $inicioMesPasado = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
            $finMesPasado    = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');

            $prompt = "Eres un asistente inteligente para un sistema de peluquería/spa llamado Mujer Bonita. " .
                      "Analiza el siguiente texto y extrae la intención de reporte solicitada. " .
                      "Tipos de reporte válidos: 'ventas', 'clientes', 'inventario', 'servicios', 'promociones', 'general'. " .
                      "Si no especifica tipo, usa 'general'. " .
                      "Extrae fecha_inicio y fecha_fin en formato YYYY-MM-DD. " .
                      "Fechas de referencia: hoy=$hoy, inicio_mes=$inicioMes, fin_mes=$finMes, " .
                      "inicio_semana=$inicioSemana, fin_semana=$finSemana, " .
                      "inicio_mes_pasado=$inicioMesPasado, fin_mes_pasado=$finMesPasado. " .
                      "Si no menciona fechas, usa inicio_mes y fin_mes. " .
                      "Responde ÚNICAMENTE con JSON, sin markdown: " .
                      "{\"tipo\": \"ventas\", \"fecha_inicio\": \"2024-06-01\", \"fecha_fin\": \"2024-06-30\"}";

                $responseLlama = Http::withToken($groqApiKey)
                    ->timeout(30)
                    ->post('https://api.groq.com/openai/v1/chat/completions', [
                        'model'           => 'llama-3.3-70b-versatile',
                        'messages'        => [
                            ['role' => 'system', 'content' => $prompt],
                            ['role' => 'user',   'content' => $textoTranscrito]
                        ],
                        'temperature'     => 0.1,
                        'response_format' => ['type' => 'json_object']
                    ]);

            if (!$responseLlama->successful()) {
                Log::error('Error en Llama', ['res' => $responseLlama->json()]);
                return response()->json(['error' => 'Error al analizar el comando.'], 500);
            }

            $contenido       = $responseLlama->json('choices.0.message.content');
            $datosParseados  = json_decode($contenido, true);

            if (!$datosParseados || !isset($datosParseados['tipo'])) {
                return response()->json(['error' => 'No se pudo entender el tipo de reporte solicitado.'], 400);
            }

            // Validar tipo
            $tiposValidos = ['ventas', 'clientes', 'inventario', 'servicios', 'promociones', 'general'];
            if (!in_array($datosParseados['tipo'], $tiposValidos)) {
                $datosParseados['tipo'] = 'general';
            }

            return response()->json([
                'success' => true,
                'texto'   => $textoTranscrito,
                'reporte' => $datosParseados
            ]);

        } catch (\Exception $e) {
            Log::error('Error procesando reporte IA', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Ocurrió un error inesperado.'], 500);
        }
    }
}
