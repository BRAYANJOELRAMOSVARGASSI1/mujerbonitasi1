<?php

namespace App\Modules\P4_GestionServiciosCitas\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\P4_GestionServiciosCitas\Models\Servicio;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServicioController extends Controller
{
    public function index(Request $request)
    {
        $servicios = Servicio::query()
            ->when($request->filled('buscar'), fn($q) => $q->where('nombre', 'like', "%{$request->buscar}%"))
            ->when($request->filled('categoria'), fn($q) => $q->where('categoria', $request->categoria))
            ->when($request->filled('estado'), fn($q) => $q->where('estado', $request->estado))
            ->orderBy('categoria')->orderBy('nombre')
            ->paginate(12)
            ->withQueryString();

        $categorias = Servicio::CATEGORIAS;
        return view('modules.servicios.servicios.index', compact('servicios', 'categorias'));
    }

    public function create()
    {
        $categorias = Servicio::CATEGORIAS;
        return view('modules.servicios.servicios.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'           => ['required', 'string', 'max:150'],
            'descripcion'      => ['nullable', 'string', 'max:1000'],
            'categoria'        => ['required', 'string', 'max:80'],
            'duracion_minutos' => ['required', 'integer', 'min:5', 'max:480'],
            'precio'           => ['required', 'numeric', 'min:0'],
        ]);

        $servicio = Servicio::create($data);

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Registro de servicio',
            'description'=> "Servicio registrado: {$servicio->nombre} (Cat: {$servicio->categoria}, Precio: Bs. {$servicio->precio})",
            'ip_address' => $request->ip() ?? 'No disponible',
            'browser'    => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('servicios.index')->with('status', 'Servicio registrado correctamente.');
    }

    public function show(Servicio $servicio)
    {
        return view('modules.servicios.servicios.show', compact('servicio'));
    }

    public function edit(Servicio $servicio)
    {
        $categorias = Servicio::CATEGORIAS;
        return view('modules.servicios.servicios.edit', compact('servicio', 'categorias'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $data = $request->validate([
            'nombre'           => ['required', 'string', 'max:150'],
            'descripcion'      => ['nullable', 'string', 'max:1000'],
            'categoria'        => ['required', 'string', 'max:80'],
            'duracion_minutos' => ['required', 'integer', 'min:5', 'max:480'],
            'precio'           => ['required', 'numeric', 'min:0'],
            'estado'           => ['nullable', 'in:activo,inactivo'],
        ]);

        $servicio->update($data);

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Actualización de servicio',
            'description'=> "Servicio actualizado: {$servicio->nombre}",
            'ip_address' => $request->ip() ?? 'No disponible',
            'browser'    => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('servicios.index')->with('status', 'Servicio actualizado correctamente.');
    }

    public function destroy(Request $request, Servicio $servicio)
    {
        $nombre = $servicio->nombre;
        $servicio->delete();

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Eliminación de servicio',
            'description'=> "Servicio eliminado: {$nombre}",
            'ip_address' => $request->ip() ?? 'No disponible',
            'browser'    => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('servicios.index')->with('status', 'Servicio eliminado correctamente.');
    }
}
