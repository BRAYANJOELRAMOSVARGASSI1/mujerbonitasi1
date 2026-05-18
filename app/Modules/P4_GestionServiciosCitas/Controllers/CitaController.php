<?php

namespace App\Modules\P4_GestionServiciosCitas\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Modules\P2_GestionPersonalClientes\Models\Cliente;
use App\Modules\P2_GestionPersonalClientes\Models\Estilista;
use App\Modules\P2_GestionPersonalClientes\Models\Horario;
use App\Modules\P2_GestionPersonalClientes\Models\HorarioExcepcion;
use App\Modules\P4_GestionServiciosCitas\Models\Cita;
use App\Modules\P4_GestionServiciosCitas\Models\Servicio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * [CU8]  — Agendar Cita
 * [CU9]  — Asignar Estilista a Servicio (integrado en el flujo de agendamiento)
 *
 * Actores:
 *  - Recepcionista / Admin: pueden agendar citas para cualquier cliente.
 *  - Cliente: puede agendar citas solo para sí mismo.
 *  - Estilista: solo puede ver las citas asignadas a él/ella.
 */
class CitaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver citas')->only(['index', 'show']);
        $this->middleware('permission:crear citas')->only(['create', 'store']);
        $this->middleware('permission:editar citas')->only(['edit', 'update']);
        $this->middleware('permission:cancelar citas')->only(['destroy']);
    }

    /**
     * Listar citas con filtros.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Cita::with(['cliente', 'estilista', 'servicio'])
            ->when($request->filled('fecha'), fn($q) => $q->whereDate('fecha', $request->fecha))
            ->when($request->filled('estado'), fn($q) => $q->where('estado', $request->estado))
            ->when($request->filled('estilista_id'), fn($q) => $q->where('estilista_id', $request->estilista_id))
            ->when($request->filled('buscar'), function ($q) use ($request) {
                $q->whereHas('cliente', function ($sub) use ($request) {
                    $sub->where('nombre', 'like', "%{$request->buscar}%")
                        ->orWhere('apellido', 'like', "%{$request->buscar}%");
                });
            });

        // Restricción por rol
        if ($user->hasRole('estilista')) {
            $estilistaId = Estilista::where('user_id', $user->id)->value('id');
            $query->where('estilista_id', $estilistaId);
        } elseif ($user->hasRole('cliente')) {
            $clienteId = Cliente::where('email', $user->email)->value('id');
            $query->where('cliente_id', $clienteId);
        }

        $citas = $query->orderByDesc('fecha')->orderByDesc('hora_inicio')
            ->paginate(12)
            ->withQueryString();

        $estilistas = Estilista::activos()->orderBy('nombre')->get();

        return view('modules.servicios.citas.index', compact('citas', 'estilistas'));
    }

    /**
     * Formulario de agendamiento.
     */
    public function create()
    {
        $user = Auth::user();

        // Clientes disponibles según rol
        if ($user->hasRole('cliente')) {
            $clientes = Cliente::where('email', $user->email)->get();
        } else {
            $clientes = Cliente::activos()->orderBy('nombre')->get();
        }

        $servicios  = Servicio::activos()->orderBy('categoria')->orderBy('nombre')->get();
        $estilistas = Estilista::activos()->orderBy('nombre')->get();

        return view('modules.servicios.citas.create', compact('clientes', 'servicios', 'estilistas'));
    }

    /**
     * [CU9 Integrado] — Endpoint AJAX: devuelve estilistas disponibles
     * según servicio, fecha y hora seleccionados.
     *
     * Lógica de disponibilidad:
     * 1. Calcular hora_fin = hora_inicio + duración del servicio
     * 2. Determinar dia_semana de la fecha
     * 3. Filtrar estilistas que tengan horario activo ese día cubriendo el rango
     * 4. Excluir estilistas con excepciones de horario en esa fecha
     * 5. Excluir estilistas con citas solapadas
     */
    public function getEstilistasDisponibles(Request $request)
    {
        $request->validate([
            'servicio_id' => ['required', 'exists:servicios,id'],
            'fecha'       => ['required', 'date', 'after_or_equal:today'],
            'hora_inicio' => ['required', 'date_format:H:i'],
        ]);

        $servicio   = Servicio::findOrFail($request->servicio_id);
        $fecha      = Carbon::parse($request->fecha);
        $horaInicio = $request->hora_inicio . ':00';
        $horaFin    = Carbon::parse($request->hora_inicio)
            ->addMinutes($servicio->duracion_minutos)
            ->format('H:i:s');

        // Mapeo de Carbon dayOfWeek a nuestro enum
        $diasMap = [1 => 'lunes', 2 => 'martes', 3 => 'miercoles', 4 => 'jueves', 5 => 'viernes', 6 => 'sabado', 0 => 'domingo'];
        $diaSemana = $diasMap[$fecha->dayOfWeek];

        // 1. Estilistas con horario activo que cubre el rango
        $estilistasConHorario = Horario::where('dia_semana', $diaSemana)
            ->where('estado', 'activo')
            ->where('hora_inicio', '<=', $horaInicio)
            ->where('hora_fin', '>=', $horaFin)
            ->pluck('estilista_id');

        // 2. Excluir estilistas con excepciones de horario en esa fecha
        $estilistasConExcepcion = HorarioExcepcion::where('fecha', $fecha->toDateString())
            ->where('estado', 'activo')
            ->where(function ($q) use ($horaInicio, $horaFin) {
                $q->where(function ($sub) use ($horaInicio, $horaFin) {
                    $sub->where('hora_inicio', '<', $horaFin)
                        ->where('hora_fin', '>', $horaInicio);
                });
            })
            ->pluck('estilista_id');

        // 3. Excluir estilistas con citas solapadas
        $estilistasOcupados = Cita::where('fecha', $fecha->toDateString())
            ->whereIn('estado', ['pendiente', 'en_curso'])
            ->where(function ($q) use ($horaInicio, $horaFin) {
                $q->where(function ($sub) use ($horaInicio, $horaFin) {
                    $sub->where('hora_inicio', '<', $horaFin)
                        ->where('hora_fin', '>', $horaInicio);
                });
            })
            ->pluck('estilista_id');

        // 4. Filtrar
        $disponibles = Estilista::activos()
            ->whereIn('id', $estilistasConHorario)
            ->whereNotIn('id', $estilistasConExcepcion)
            ->whereNotIn('id', $estilistasOcupados)
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'apellido', 'especialidad']);

        return response()->json([
            'estilistas' => $disponibles,
            'hora_fin'   => Carbon::parse($request->hora_inicio)->addMinutes($servicio->duracion_minutos)->format('H:i'),
            'duracion'   => $servicio->duracion_minutos,
            'precio'     => $servicio->precio,
        ]);
    }

    /**
     * Almacenar nueva cita — Validación completa de disponibilidad.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id'   => ['required', 'exists:clientes,id'],
            'servicio_id'  => ['required', 'exists:servicios,id'],
            'estilista_id' => ['required', 'exists:estilistas,id'],
            'fecha'        => ['required', 'date', 'after_or_equal:today'],
            'hora_inicio'  => ['required', 'date_format:H:i'],
            'notas'        => ['nullable', 'string', 'max:1000'],
        ]);

        $user = Auth::user();

        // Restricción de cliente: solo puede agendar para sí mismo
        if ($user->hasRole('cliente')) {
            $clienteId = Cliente::where('email', $user->email)->value('id');
            if (!$clienteId || $data['cliente_id'] != $clienteId) {
                return back()->withErrors(['cliente_id' => 'Solo puedes agendar citas para tu propio perfil.'])->withInput();
            }
        }

        $servicio   = Servicio::findOrFail($data['servicio_id']);
        $horaInicio = $data['hora_inicio'] . ':00';
        $horaFin    = Carbon::parse($data['hora_inicio'])
            ->addMinutes($servicio->duracion_minutos)
            ->format('H:i:s');

        // Validar que no haya conflicto con otra cita del mismo estilista
        $conflictoEstilista = Cita::where('estilista_id', $data['estilista_id'])
            ->where('fecha', $data['fecha'])
            ->whereIn('estado', ['pendiente', 'en_curso'])
            ->where(function ($q) use ($horaInicio, $horaFin) {
                $q->where('hora_inicio', '<', $horaFin)
                  ->where('hora_fin', '>', $horaInicio);
            })->exists();

        if ($conflictoEstilista) {
            return back()->withErrors(['hora_inicio' => 'La hora seleccionada ya está ocupada para este estilista.'])->withInput();
        }

        // Validar que no haya conflicto con otra cita del mismo cliente
        $conflictoCliente = Cita::where('cliente_id', $data['cliente_id'])
            ->where('fecha', $data['fecha'])
            ->whereIn('estado', ['pendiente', 'en_curso'])
            ->where(function ($q) use ($horaInicio, $horaFin) {
                $q->where('hora_inicio', '<', $horaFin)
                  ->where('hora_fin', '>', $horaInicio);
            })->exists();

        if ($conflictoCliente) {
            return back()->withErrors(['hora_inicio' => 'El cliente ya tiene una cita en ese horario.'])->withInput();
        }

        $cita = Cita::create([
            'cliente_id'   => $data['cliente_id'],
            'estilista_id' => $data['estilista_id'],
            'servicio_id'  => $data['servicio_id'],
            'fecha'        => $data['fecha'],
            'hora_inicio'  => $horaInicio,
            'hora_fin'     => $horaFin,
            'precio_total' => $servicio->precio,
            'estado'       => 'pendiente',
            'notas'        => $data['notas'] ?? null,
        ]);

        $cliente = Cliente::find($data['cliente_id']);

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'Agendar Cita',
            'description' => "Cita #{$cita->id} agendada: {$cliente->nombre_completo} — {$servicio->nombre} el {$cita->fecha->format('d/m/Y')} a las {$data['hora_inicio']}",
            'ip_address'  => $request->ip() ?? 'No disponible',
            'browser'     => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('citas.index')->with('status', 'Cita agendada correctamente.');
    }

    /**
     * Detalle de una cita.
     */
    public function show(Cita $cita)
    {
        $cita->load(['cliente', 'estilista', 'servicio', 'servicioRealizado']);
        return view('modules.servicios.citas.show', compact('cita'));
    }

    /**
     * Formulario de edición (solo citas pendientes).
     */
    public function edit(Cita $cita)
    {
        if ($cita->estado === 'completada' || $cita->estado === 'cancelada') {
            return back()->with('error', 'No se puede editar una cita completada o cancelada.');
        }

        $clientes   = Cliente::activos()->orderBy('nombre')->get();
        $servicios  = Servicio::activos()->orderBy('categoria')->orderBy('nombre')->get();
        $estilistas = Estilista::activos()->orderBy('nombre')->get();

        return view('modules.servicios.citas.edit', compact('cita', 'clientes', 'servicios', 'estilistas'));
    }

    /**
     * Actualizar cita.
     */
    public function update(Request $request, Cita $cita)
    {
        if ($cita->estado === 'completada' || $cita->estado === 'cancelada') {
            return back()->with('error', 'No se puede editar una cita completada o cancelada.');
        }

        $data = $request->validate([
            'cliente_id'   => ['required', 'exists:clientes,id'],
            'servicio_id'  => ['required', 'exists:servicios,id'],
            'estilista_id' => ['required', 'exists:estilistas,id'],
            'fecha'        => ['required', 'date'],
            'hora_inicio'  => ['required', 'date_format:H:i'],
            'notas'        => ['nullable', 'string', 'max:1000'],
        ]);

        $servicio   = Servicio::findOrFail($data['servicio_id']);
        $horaInicio = $data['hora_inicio'] . ':00';
        $horaFin    = Carbon::parse($data['hora_inicio'])
            ->addMinutes($servicio->duracion_minutos)
            ->format('H:i:s');

        // Validar conflicto con estilista (excluyendo la cita actual)
        $conflicto = Cita::where('estilista_id', $data['estilista_id'])
            ->where('fecha', $data['fecha'])
            ->where('id', '!=', $cita->id)
            ->whereIn('estado', ['pendiente', 'en_curso'])
            ->where(function ($q) use ($horaInicio, $horaFin) {
                $q->where('hora_inicio', '<', $horaFin)
                  ->where('hora_fin', '>', $horaInicio);
            })->exists();

        if ($conflicto) {
            return back()->withErrors(['hora_inicio' => 'La hora seleccionada ya está ocupada.'])->withInput();
        }

        $cita->update([
            'cliente_id'   => $data['cliente_id'],
            'estilista_id' => $data['estilista_id'],
            'servicio_id'  => $data['servicio_id'],
            'fecha'        => $data['fecha'],
            'hora_inicio'  => $horaInicio,
            'hora_fin'     => $horaFin,
            'precio_total' => $servicio->precio,
            'notas'        => $data['notas'] ?? null,
        ]);

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'Actualización de Cita',
            'description' => "Cita #{$cita->id} actualizada.",
            'ip_address'  => $request->ip() ?? 'No disponible',
            'browser'     => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('citas.index')->with('status', 'Cita actualizada correctamente.');
    }

    /**
     * Cancelar cita.
     */
    public function destroy(Request $request, Cita $cita)
    {
        $user = Auth::user();

        // Cliente solo puede cancelar sus propias citas
        if ($user->hasRole('cliente')) {
            $clienteId = Cliente::where('email', $user->email)->value('id');
            if ($cita->cliente_id != $clienteId) {
                return back()->with('error', 'No puedes cancelar citas de otros clientes.');
            }
        }

        if ($cita->estado === 'completada') {
            return back()->with('error', 'No se puede cancelar una cita ya completada.');
        }

        $cita->update(['estado' => 'cancelada']);

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'Cancelación de Cita',
            'description' => "Cita #{$cita->id} cancelada ({$cita->cliente->nombre_completo} — {$cita->servicio->nombre})",
            'ip_address'  => $request->ip() ?? 'No disponible',
            'browser'     => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('citas.index')->with('status', 'Cita cancelada correctamente.');
    }
}
