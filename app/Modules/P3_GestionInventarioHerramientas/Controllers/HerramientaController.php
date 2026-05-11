<?php

namespace App\Modules\P3_GestionInventarioHerramientas\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\P3_GestionInventarioHerramientas\Models\Herramienta;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HerramientaController extends Controller
{
    public function index(Request $request)
    {
        $herramientas = Herramienta::query()
            ->when($request->filled('buscar'), function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('nombre', 'like', "%{$request->buscar}%")
                        ->orWhere('numero_serie', 'like', "%{$request->buscar}%");
                });
            })
            ->when($request->filled('categoria'), fn($q) => $q->where('categoria', $request->categoria))
            ->when($request->filled('estado'), fn($q) => $q->where('estado', $request->estado))
            ->when($request->filled('area'), fn($q) => $q->where('area_asignada', $request->area))
            ->orderBy('nombre')
            ->paginate(12)
            ->withQueryString();

        $categorias = Herramienta::CATEGORIAS;
        $estados    = Herramienta::ESTADOS;
        $areas      = Herramienta::AREAS;

        return view('modules.inventario.herramientas.index', compact('herramientas', 'categorias', 'estados', 'areas'));
    }

    public function create()
    {
        $categorias = Herramienta::CATEGORIAS;
        $areas      = Herramienta::AREAS;
        return view('modules.inventario.herramientas.create', compact('categorias', 'areas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'            => ['required', 'string', 'max:150'],
            'descripcion'       => ['nullable', 'string', 'max:1000'],
            'categoria'         => ['required', 'string', 'max:80'],
            'numero_serie'      => ['nullable', 'string', 'max:100', 'unique:herramientas,numero_serie'],
            'area_asignada'     => ['nullable', 'string', 'max:80'],
            'estado'            => ['nullable', 'in:disponible,en_uso,mantenimiento,baja'],
            'fecha_adquisicion' => ['nullable', 'date'],
            'costo'             => ['nullable', 'numeric', 'min:0'],
        ]);

        $herramienta = Herramienta::create($data);

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Registro de herramienta',
            'description'=> "Herramienta registrada: {$herramienta->nombre} (Cat: {$herramienta->categoria})",
            'ip_address' => $request->ip() ?? 'No disponible',
            'browser'    => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('herramientas.index')->with('status', 'Herramienta registrada correctamente.');
    }

    public function show(Herramienta $herramienta)
    {
        return view('modules.inventario.herramientas.show', compact('herramienta'));
    }

    public function edit(Herramienta $herramienta)
    {
        $categorias = Herramienta::CATEGORIAS;
        $estados    = Herramienta::ESTADOS;
        $areas      = Herramienta::AREAS;
        return view('modules.inventario.herramientas.edit', compact('herramienta', 'categorias', 'estados', 'areas'));
    }

    public function update(Request $request, Herramienta $herramienta)
    {
        $data = $request->validate([
            'nombre'            => ['required', 'string', 'max:150'],
            'descripcion'       => ['nullable', 'string', 'max:1000'],
            'categoria'         => ['required', 'string', 'max:80'],
            'numero_serie'      => ['nullable', 'string', 'max:100', 'unique:herramientas,numero_serie,' . $herramienta->id],
            'area_asignada'     => ['nullable', 'string', 'max:80'],
            'estado'            => ['nullable', 'in:disponible,en_uso,mantenimiento,baja'],
            'fecha_adquisicion' => ['nullable', 'date'],
            'costo'             => ['nullable', 'numeric', 'min:0'],
        ]);

        $herramienta->update($data);

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Actualización de herramienta',
            'description'=> "Herramienta actualizada: {$herramienta->nombre}",
            'ip_address' => $request->ip() ?? 'No disponible',
            'browser'    => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('herramientas.index')->with('status', 'Herramienta actualizada correctamente.');
    }

    public function destroy(Request $request, Herramienta $herramienta)
    {
        $nombre = $herramienta->nombre;
        $herramienta->delete();

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Eliminación de herramienta',
            'description'=> "Herramienta eliminada: {$nombre}",
            'ip_address' => $request->ip() ?? 'No disponible',
            'browser'    => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('herramientas.index')->with('status', 'Herramienta eliminada correctamente.');
    }
}
