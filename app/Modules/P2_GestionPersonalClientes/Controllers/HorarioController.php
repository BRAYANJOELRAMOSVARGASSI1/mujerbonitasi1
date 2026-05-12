<?php

namespace App\Modules\P2_GestionPersonalClientes\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\P2_GestionPersonalClientes\Models\Horario;
use App\Modules\P2_GestionPersonalClientes\Models\Estilista;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * [CU22] — Gestionar Horarios
 * [CU23] — Consultar Horarios
 */
class HorarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver horarios')->only(['index', 'show']);
        $this->middleware('permission:crear horarios')->only(['create', 'store']);
        $this->middleware('permission:editar horarios')->only(['edit', 'update']);
        $this->middleware('permission:eliminar horarios')->only(['destroy']);
    }

    /**
     * [CU23] Consultar horarios con filtros.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Horario::with('estilista')
            ->when($request->filled('estilista_id'), fn($q) => $q->where('estilista_id', $request->estilista_id))
            ->when($request->filled('dia_semana'), fn($q) => $q->where('dia_semana', $request->dia_semana))
            ->activos();

        // Restricción por Rol: El estilista solo ve sus propios horarios en la gestión
        if ($user->hasRole('estilista')) {
            $estilistaId = Estilista::where('user_id', $user->id)->value('id');
            $query->where('estilista_id', $estilistaId);
        }

        $horarios = $query->orderByRaw("FIELD(dia_semana, 'lunes','martes','miercoles','jueves','viernes','sabado','domingo')")
            ->orderBy('hora_inicio')
            ->paginate(15)
            ->withQueryString();

        $estilistas = Estilista::activos()->orderBy('nombre')->get();
        $diasSemana = Horario::DIAS_SEMANA;

        return view('modules.personal.horarios.index', compact('horarios', 'estilistas', 'diasSemana'));
    }

    /**
     * [CU23] Vista de Calendario Visual.
     */
    public function consultar(Request $request)
    {
        $user = Auth::user();
        
        // 1. Lógica de Fechas (Semana)
        $dateStr = $request->get('date', Carbon::now()->toDateString());
        $currentDate = Carbon::parse($dateStr);
        $startOfWeek = $currentDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $currentDate->copy()->endOfWeek(Carbon::SUNDAY);
        
        // 2. Filtros por Rol
        $queryEstilistas = Estilista::activos()->orderBy('nombre');
        
        if ($user->hasRole('estilista')) {
            $estilistaId = Estilista::where('user_id', $user->id)->value('id');
            $queryEstilistas->where('id', $estilistaId);
            $selectedEstilistaId = $estilistaId;
        } else {
            $selectedEstilistaId = $request->get('estilista_id');
        }

        $estilistas = $queryEstilistas->get();

        // 3. Obtener Horarios Base
        $horarios = Horario::with('estilista')
            ->activos()
            ->when($selectedEstilistaId, fn($q) => $q->where('estilista_id', $selectedEstilistaId))
            ->get();

        // 4. Obtener Excepciones (Vacaciones/Permisos)
        $excepciones = \App\Modules\P2_GestionPersonalClientes\Models\HorarioExcepcion::activos()
            ->whereBetween('fecha', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->when($selectedEstilistaId, fn($q) => $q->where('estilista_id', $selectedEstilistaId))
            ->get();

        // 5. Obtener Citas Reales (P4)
        $citas = \App\Modules\P4_GestionServiciosCitas\Models\Cita::with(['cliente', 'servicio'])
            ->whereBetween('fecha', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->when($selectedEstilistaId, fn($q) => $q->where('estilista_id', $selectedEstilistaId))
            ->get();

        // 6. Registrar en Bitácora
        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Consulta de Horarios',
            'description'=> "Consultó el calendario semanal para la fecha {$dateStr}",
            'ip_address' => $request->ip() ?? 'No disponible',
            'browser'    => $request->header('user-agent') ?? 'No disponible',
        ]);

        $diasSemana = Horario::DIAS_SEMANA;

        return view('modules.personal.horarios.consultar', compact(
            'estilistas', 
            'horarios', 
            'excepciones',
            'citas',
            'startOfWeek', 
            'endOfWeek', 
            'currentDate',
            'diasSemana',
            'selectedEstilistaId'
        ));
    }

    /**
     * [CU24] Cambiar estado de una cita (Finalizar trabajo).
     */
    public function finalizarCita(Request $request, $id)
    {
        $cita = \App\Modules\P4_GestionServiciosCitas\Models\Cita::findOrFail($id);
        
        // Validación de seguridad básica
        if (Auth::user()->hasRole('estilista')) {
            $estilistaId = Estilista::where('user_id', Auth::id())->value('id');
            if ($cita->estilista_id != $estilistaId) {
                return back()->with('error', 'No puedes finalizar una cita que no te corresponde.');
            }
        }

        $cita->update(['estado' => 'completada']);

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Cita Finalizada',
            'description'=> "Se marcó como completada la cita #{$cita->id} de {$cita->cliente->nombre_completo}",
            'ip_address' => $request->ip() ?? 'No disponible',
            'browser'    => $request->header('user-agent') ?? 'No disponible',
        ]);

        return back()->with('status', 'Trabajo finalizado correctamente.');
    }

    /**
     * Formulario para crear horario.
     */
    public function create()
    {
        $estilistas  = Estilista::activos()->orderBy('nombre')->get();
        $diasSemana  = Horario::DIAS_SEMANA;
        return view('modules.personal.horarios.create', compact('estilistas', 'diasSemana'));
    }

    /**
     * [CU22] Almacenar nuevo horario.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'estilista_id' => ['required', 'exists:estilistas,id'],
            'dia_semana'   => ['required', 'in:lunes,martes,miercoles,jueves,viernes,sabado,domingo'],
            'hora_inicio'  => ['required', 'date_format:H:i'],
            'hora_fin'     => ['required', 'date_format:H:i', 'after:hora_inicio'],
        ]);

        // Validar solapamiento de horarios
        $solapamiento = Horario::where('estilista_id', $data['estilista_id'])
            ->where('dia_semana', $data['dia_semana'])
            ->where('estado', 'activo')
            ->where(function ($q) use ($data) {
                $q->whereBetween('hora_inicio', [$data['hora_inicio'], $data['hora_fin']])
                  ->orWhereBetween('hora_fin', [$data['hora_inicio'], $data['hora_fin']])
                  ->orWhere(function ($sub) use ($data) {
                      $sub->where('hora_inicio', '<=', $data['hora_inicio'])
                          ->where('hora_fin', '>=', $data['hora_fin']);
                  });
            })->exists();

        if ($solapamiento) {
            return back()->withErrors(['hora_inicio' => 'Ya existe un horario que se solapa con el rango seleccionado.'])->withInput();
        }

        $horario = Horario::create($data);
        $estilista = Estilista::find($data['estilista_id']);

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Creación de horario',
            'description'=> "Horario creado para {$estilista->nombre_completo}: {$horario->dia_semana} {$horario->hora_inicio}-{$horario->hora_fin}",
            'ip_address' => $request->ip() ?? 'No disponible',
            'browser'    => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('horarios.index')
            ->with('status', 'Horario creado correctamente.');
    }

    /**
     * Formulario de edición.
     */
    public function edit(Horario $horario)
    {
        $estilistas = Estilista::activos()->orderBy('nombre')->get();
        $diasSemana = Horario::DIAS_SEMANA;
        return view('modules.personal.horarios.edit', compact('horario', 'estilistas', 'diasSemana'));
    }

    /**
     * Actualizar horario.
     */
    public function update(Request $request, Horario $horario)
    {
        $data = $request->validate([
            'estilista_id' => ['required', 'exists:estilistas,id'],
            'dia_semana'   => ['required', 'in:lunes,martes,miercoles,jueves,viernes,sabado,domingo'],
            'hora_inicio'  => ['required', 'date_format:H:i'],
            'hora_fin'     => ['required', 'date_format:H:i', 'after:hora_inicio'],
        ]);

        // Validar solapamiento excluyendo el horario actual
        $solapamiento = Horario::where('estilista_id', $data['estilista_id'])
            ->where('dia_semana', $data['dia_semana'])
            ->where('estado', 'activo')
            ->where('id', '!=', $horario->id)
            ->where(function ($q) use ($data) {
                $q->whereBetween('hora_inicio', [$data['hora_inicio'], $data['hora_fin']])
                  ->orWhereBetween('hora_fin', [$data['hora_inicio'], $data['hora_fin']])
                  ->orWhere(function ($sub) use ($data) {
                      $sub->where('hora_inicio', '<=', $data['hora_inicio'])
                          ->where('hora_fin', '>=', $data['hora_fin']);
                  });
            })->exists();

        if ($solapamiento) {
            return back()->withErrors(['hora_inicio' => 'Ya existe un horario que se solapa con el rango seleccionado.'])->withInput();
        }

        $horario->update($data);

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Actualización de horario',
            'description'=> "Horario actualizado: {$horario->estilista->nombre_completo} - {$horario->dia_semana}",
            'ip_address' => $request->ip() ?? 'No disponible',
            'browser'    => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('horarios.index')
            ->with('status', 'Horario actualizado correctamente.');
    }

    /**
     * Eliminar horario.
     */
    public function destroy(Request $request, Horario $horario)
    {
        $desc = "{$horario->estilista->nombre_completo} - {$horario->dia_semana} {$horario->hora_inicio}-{$horario->hora_fin}";
        $horario->delete();

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Eliminación de horario',
            'description'=> "Horario eliminado: {$desc}",
            'ip_address' => $request->ip() ?? 'No disponible',
            'browser'    => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('horarios.index')
            ->with('status', 'Horario eliminado correctamente.');
    }
}
