<?php

namespace App\Modules\P4_GestionServiciosCitas\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Modules\P2_GestionPersonalClientes\Models\Estilista;
use App\Modules\P4_GestionServiciosCitas\Models\Cita;
use App\Modules\P4_GestionServiciosCitas\Models\ServicioRealizado;
use App\Modules\P3_GestionInventarioHerramientas\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * [CU14] — Registrar Servicio Realizado
 *
 * Actores:
 *  - Estilista: puede registrar servicios de sus propias citas.
 *  - Recepcionista / Admin: puede registrar servicios de cualquier cita.
 *
 * El registro incluye observaciones, duración real, productos utilizados
 * y calcula automáticamente la comisión de la estilista.
 */
class ServicioRealizadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver servicios realizados')->only(['index', 'show']);
        $this->middleware('permission:registrar servicio realizado')->only(['create', 'store']);
    }

    /**
     * Listar servicios realizados con filtros.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = ServicioRealizado::with(['cita', 'estilista', 'servicio', 'cliente'])
            ->when($request->filled('estilista_id'), fn($q) => $q->where('estilista_id', $request->estilista_id))
            ->when($request->filled('fecha_desde'), fn($q) => $q->whereDate('fecha_realizacion', '>=', $request->fecha_desde))
            ->when($request->filled('fecha_hasta'), fn($q) => $q->whereDate('fecha_realizacion', '<=', $request->fecha_hasta))
            ->when($request->filled('buscar'), function ($q) use ($request) {
                $q->whereHas('cliente', fn($sub) =>
                    $sub->where('nombre', 'like', "%{$request->buscar}%")
                        ->orWhere('apellido', 'like', "%{$request->buscar}%")
                );
            });

        // Restricción por rol: estilista solo ve sus propios registros
        if ($user->hasRole('estilista')) {
            $estilistaId = Estilista::where('user_id', $user->id)->value('id');
            $query->where('estilista_id', $estilistaId);
        }

        $registros = $query->orderByDesc('fecha_realizacion')
            ->paginate(12)
            ->withQueryString();

        $estilistas = Estilista::activos()->orderBy('nombre')->get();

        // Estadísticas rápidas
        $statsQuery = ServicioRealizado::query();
        if ($user->hasRole('estilista')) {
            $estilistaId = Estilista::where('user_id', $user->id)->value('id');
            $statsQuery->where('estilista_id', $estilistaId);
        }

        $stats = [
            'total_registros'  => $statsQuery->count(),
            'total_ingresos'   => $statsQuery->sum('precio_cobrado'),
            'total_comisiones' => $statsQuery->sum('comision_monto'),
        ];

        return view('modules.servicios.realizados.index', compact('registros', 'estilistas', 'stats'));
    }

    /**
     * Formulario para registrar un servicio como realizado.
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        // Citas pendientes de realización
        $query = Cita::with(['cliente', 'estilista', 'servicio'])
            ->pendienteRealizacion();

        // Restricción por rol
        if ($user->hasRole('estilista')) {
            $estilistaId = Estilista::where('user_id', $user->id)->value('id');
            $query->where('estilista_id', $estilistaId);
        }

        $citasPendientes = $query->orderByDesc('fecha')->orderByDesc('hora_inicio')->get();

        // Pre-seleccionar cita si viene por parámetro
        $citaSeleccionada = null;
        if ($request->filled('cita_id')) {
            $citaSeleccionada = Cita::with(['cliente', 'estilista', 'servicio'])->find($request->cita_id);
        }

        // Productos disponibles para seleccionar
        $productos = [];
        $productoModel = 'App\\Modules\\P3_GestionInventarioHerramientas\\Models\\Producto';
        if (class_exists($productoModel)) {
            $productos = $productoModel::where('stock_actual', '>', 0)->orderBy('nombre')->get();
        }

        return view('modules.servicios.realizados.create', compact('citasPendientes', 'citaSeleccionada', 'productos'));
    }

    /**
     * Registrar servicio realizado.
     * Cambia el estado de la cita a "completada" y crea el registro detallado.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'cita_id'               => ['required', 'exists:citas,id'],
            'observaciones'         => ['nullable', 'string', 'max:2000'],
            'duracion_real_minutos' => ['nullable', 'integer', 'min:1', 'max:600'],
            'productos_utilizados'  => ['nullable', 'string', 'max:2000'],
        ]);

        $cita = Cita::with(['estilista', 'servicio', 'cliente'])->findOrFail($data['cita_id']);

        // Verificar que no esté ya registrada
        if ($cita->is_realizada) {
            return back()->withErrors(['cita_id' => 'Este servicio ya fue registrado como realizado.'])->withInput();
        }

        // Verificar que la cita no esté cancelada
        if ($cita->estado === 'cancelada') {
            return back()->withErrors(['cita_id' => 'No se puede registrar un servicio de una cita cancelada.'])->withInput();
        }

        // Restricción de estilista: solo puede registrar sus propias citas
        $user = Auth::user();
        if ($user->hasRole('estilista')) {
            $estilistaId = Estilista::where('user_id', $user->id)->value('id');
            if ($cita->estilista_id != $estilistaId) {
                return back()->with('error', 'No puedes registrar servicios de citas que no te corresponden.');
            }
        }

        // Crear registro de servicio realizado (la comisión se calcula automáticamente en el model boot)
        $registro = ServicioRealizado::create([
            'cita_id'               => $cita->id,
            'estilista_id'          => $cita->estilista_id,
            'servicio_id'           => $cita->servicio_id,
            'cliente_id'            => $cita->cliente_id,
            'observaciones'         => $data['observaciones'] ?? null,
            'duracion_real_minutos' => $data['duracion_real_minutos'] ?? $cita->servicio->duracion_minutos,
            'productos_utilizados'  => $data['productos_utilizados'] ?? null,
            'precio_cobrado'        => $cita->precio_total,
            'comision_porcentaje'   => $cita->estilista->porcentaje_comision,
            'comision_monto'        => 0, // Se calcula en el boot del modelo
            'fecha_realizacion'     => now(),
        ]);

        // Cambiar estado de la cita a completada
        $cita->update(['estado' => 'completada']);

        // Bitácora
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'Registro de Servicio Realizado',
            'description' => "Servicio realizado #{$registro->id}: {$cita->servicio->nombre} para {$cita->cliente->nombre_completo} por {$cita->estilista->nombre_completo}. Comisión: Bs. {$registro->comision_monto}",
            'ip_address'  => $request->ip() ?? 'No disponible',
            'browser'     => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('servicios-realizados.index')->with('status', 'Servicio registrado como realizado correctamente.');
    }

    /**
     * Detalle de un servicio realizado.
     */
    public function show(ServicioRealizado $servicios_realizado)
    {
        $servicios_realizado->load(['cita', 'estilista', 'servicio', 'cliente']);
        return view('modules.servicios.realizados.show', compact('servicios_realizado'));
    }
}
