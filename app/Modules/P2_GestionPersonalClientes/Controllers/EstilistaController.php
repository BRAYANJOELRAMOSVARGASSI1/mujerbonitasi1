<?php

namespace App\Modules\P2_GestionPersonalClientes\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\P2_GestionPersonalClientes\Models\Estilista;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * [CU5] — Registrar Estilista
 */
class EstilistaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:crear estilistas')->only(['create', 'store']);
        $this->middleware('permission:editar estilistas')->only(['edit', 'update']);
        $this->middleware('permission:eliminar estilistas')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $estilistas = Estilista::query()
            ->when($request->filled('especialidad'), fn($q) => $q->where('especialidad', $request->especialidad))
            ->when($request->filled('estado'), fn($q) => $q->where('estado', $request->estado))
            ->when($request->filled('buscar'), function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('nombre', 'like', "%{$request->buscar}%")
                        ->orWhere('apellido', 'like', "%{$request->buscar}%")
                        ->orWhere('telefono', 'like', "%{$request->buscar}%");
                });
            })
            ->orderBy('nombre')
            ->paginate(12)
            ->withQueryString();

        return view('modules.personal.estilistas.index', compact('estilistas'));
    }

    public function create()
    {
        $especialidades = Estilista::ESPECIALIDADES;
        return view('modules.personal.estilistas.create', compact('especialidades'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'               => ['required', 'string', 'max:100'],
            'apellido'             => ['required', 'string', 'max:100'],
            'telefono'             => ['required', 'string', 'max:20'],
            'email'                => ['nullable', 'email', 'max:255'],
            'especialidad'         => ['required', 'string', 'max:100'],
            'porcentaje_comision'  => ['required', 'numeric', 'min:0', 'max:100'],
            'fecha_contratacion'   => ['required', 'date'],
        ]);

        $estilista = Estilista::create($data);

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Registro de estilista',
            'description'=> "Estilista registrada: {$estilista->nombre_completo} (Esp: {$estilista->especialidad}, Comisión: {$estilista->porcentaje_comision}%)",
            'ip_address' => $request->ip() ?? 'No disponible',
            'browser'    => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('estilistas.index')
            ->with('status', 'Estilista registrada correctamente.');
    }

    public function show(Estilista $estilista)
    {
        $estilista->load('horarios');
        return view('modules.personal.estilistas.show', compact('estilista'));
    }

    public function edit(Estilista $estilista)
    {
        $especialidades = Estilista::ESPECIALIDADES;
        return view('modules.personal.estilistas.edit', compact('estilista', 'especialidades'));
    }

    public function update(Request $request, Estilista $estilista)
    {
        $data = $request->validate([
            'nombre'               => ['required', 'string', 'max:100'],
            'apellido'             => ['required', 'string', 'max:100'],
            'telefono'             => ['required', 'string', 'max:20'],
            'email'                => ['nullable', 'email', 'max:255'],
            'especialidad'         => ['required', 'string', 'max:100'],
            'porcentaje_comision'  => ['required', 'numeric', 'min:0', 'max:100'],
            'fecha_contratacion'   => ['required', 'date'],
            'estado'               => ['nullable', 'in:activo,inactivo'],
        ]);

        $estilista->update($data);

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Actualización de estilista',
            'description'=> "Estilista actualizada: {$estilista->nombre_completo}",
            'ip_address' => $request->ip() ?? 'No disponible',
            'browser'    => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('estilistas.index')
            ->with('status', 'Estilista actualizada correctamente.');
    }

    public function destroy(Request $request, Estilista $estilista)
    {
        $nombre = $estilista->nombre_completo;
        $estilista->delete();

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Eliminación de estilista',
            'description'=> "Estilista eliminada: {$nombre}",
            'ip_address' => $request->ip() ?? 'No disponible',
            'browser'    => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('estilistas.index')
            ->with('status', 'Estilista eliminada correctamente.');
    }
}
