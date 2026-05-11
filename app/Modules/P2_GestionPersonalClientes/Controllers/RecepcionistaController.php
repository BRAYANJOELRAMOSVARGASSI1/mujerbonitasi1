<?php

namespace App\Modules\P2_GestionPersonalClientes\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\P2_GestionPersonalClientes\Models\Recepcionista;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * [CU] — Gestionar Recepcionistas (Tabla Independiente)
 */
class RecepcionistaController extends Controller
{
    public function index(Request $request)
    {
        $recepcionistas = Recepcionista::query()
            ->when($request->filled('buscar'), function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('nombre', 'like', "%{$request->buscar}%")
                        ->orWhere('apellido', 'like', "%{$request->buscar}%")
                        ->orWhere('telefono', 'like', "%{$request->buscar}%");
                });
            })
            ->when($request->filled('estado'), fn($q) => $q->where('estado', $request->estado))
            ->orderBy('nombre')
            ->paginate(12)
            ->withQueryString();

        return view('modules.personal.recepcionistas.index', compact('recepcionistas'));
    }

    public function create()
    {
        return view('modules.personal.recepcionistas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'             => ['required', 'string', 'max:100'],
            'apellido'           => ['required', 'string', 'max:100'],
            'telefono'           => ['required', 'string', 'max:20'],
            'email'              => ['nullable', 'email', 'max:255'],
            'fecha_contratacion' => ['required', 'date'],
        ]);

        $recepcionista = Recepcionista::create($data);

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Registro de recepcionista',
            'description'=> "Recepcionista registrada: {$recepcionista->nombre_completo}",
            'ip_address' => $request->ip() ?? 'No disponible',
            'browser'    => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('recepcionistas.index')
            ->with('status', 'Recepcionista registrada correctamente.');
    }

    public function show(Recepcionista $recepcionista)
    {
        return view('modules.personal.recepcionistas.show', compact('recepcionista'));
    }

    public function edit(Recepcionista $recepcionista)
    {
        return view('modules.personal.recepcionistas.edit', compact('recepcionista'));
    }

    public function update(Request $request, Recepcionista $recepcionista)
    {
        $data = $request->validate([
            'nombre'             => ['required', 'string', 'max:100'],
            'apellido'           => ['required', 'string', 'max:100'],
            'telefono'           => ['required', 'string', 'max:20'],
            'email'              => ['nullable', 'email', 'max:255'],
            'fecha_contratacion' => ['required', 'date'],
            'estado'             => ['nullable', 'in:activo,inactivo'],
        ]);

        $recepcionista->update($data);

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Actualización de recepcionista',
            'description'=> "Recepcionista actualizada: {$recepcionista->nombre_completo}",
            'ip_address' => $request->ip() ?? 'No disponible',
            'browser'    => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('recepcionistas.index')
            ->with('status', 'Recepcionista actualizada correctamente.');
    }

    public function destroy(Request $request, Recepcionista $recepcionista)
    {
        $nombre = $recepcionista->nombre_completo;
        $recepcionista->delete();

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Eliminación de recepcionista',
            'description'=> "Recepcionista eliminada: {$nombre}",
            'ip_address' => $request->ip() ?? 'No disponible',
            'browser'    => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('recepcionistas.index')
            ->with('status', 'Recepcionista eliminada correctamente.');
    }
}
