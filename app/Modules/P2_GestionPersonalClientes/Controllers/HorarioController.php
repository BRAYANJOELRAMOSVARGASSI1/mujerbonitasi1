<?php

namespace App\Modules\P2_GestionPersonalClientes\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\P2_GestionPersonalClientes\Models\Horario;
use App\Modules\P2_GestionPersonalClientes\Models\Estilista;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * [CU22] — Gestionar Horarios
 * [CU23] — Consultar Horarios
 */
class HorarioController extends Controller
{
    /**
     * [CU23] Consultar horarios con filtros.
     */
    public function index(Request $request)
    {
        $estilistas = Estilista::activos()->orderBy('nombre')->get();

        $horarios = Horario::with('estilista')
            ->when($request->filled('estilista_id'), fn($q) => $q->where('estilista_id', $request->estilista_id))
            ->when($request->filled('dia_semana'), fn($q) => $q->where('dia_semana', $request->dia_semana))
            ->activos()
            ->orderByRaw("FIELD(dia_semana, 'lunes','martes','miercoles','jueves','viernes','sabado','domingo')")
            ->orderBy('hora_inicio')
            ->paginate(15)
            ->withQueryString();

        $diasSemana = Horario::DIAS_SEMANA;

        return view('modules.personal.horarios.index', compact('horarios', 'estilistas', 'diasSemana'));
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
