<?php

namespace App\Modules\P4_GestionServiciosCitas\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Modules\P4_GestionServiciosCitas\Models\Promocion;
use App\Modules\P4_GestionServiciosCitas\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * [CU24] — Gestionar Promociones
 *
 * Actores:
 *  - Administradora (Admin): CRUD completo de promociones.
 *  - Recepcionista / Estilista / Cliente: solo pueden ver promociones activas.
 */
class PromocionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver promociones')->only(['index', 'show']);
        $this->middleware('permission:crear promociones')->only(['create', 'store']);
        $this->middleware('permission:editar promociones')->only(['edit', 'update']);
        $this->middleware('permission:eliminar promociones')->only(['destroy']);
    }

    /**
     * Listar promociones activas e históricas.
     */
    public function index(Request $request)
    {
        $promociones = Promocion::with('servicios')
            ->when($request->filled('estado'), fn($q) => $q->where('estado', $request->estado))
            ->when($request->filled('buscar'), fn($q) => $q->where('nombre', 'like', "%{$request->buscar}%"))
            ->orderByDesc('fecha_inicio')
            ->paginate(12)
            ->withQueryString();

        return view('modules.servicios.promociones.index', compact('promociones'));
    }

    /**
     * Formulario de nueva promoción.
     */
    public function create()
    {
        $servicios = Servicio::activos()->orderBy('categoria')->orderBy('nombre')->get();
        return view('modules.servicios.promociones.create', compact('servicios'));
    }

    /**
     * Almacenar nueva promoción.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'               => ['required', 'string', 'max:150'],
            'descripcion'          => ['nullable', 'string', 'max:2000'],
            'porcentaje_descuento' => ['required', 'numeric', 'min:1', 'max:100'],
            'fecha_inicio'         => ['required', 'date'],
            'fecha_fin'            => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'servicios'            => ['required', 'array', 'min:1'],
            'servicios.*'          => ['exists:servicios,id'],
        ]);

        $promocion = Promocion::create([
            'nombre'               => $data['nombre'],
            'descripcion'          => $data['descripcion'] ?? null,
            'porcentaje_descuento' => $data['porcentaje_descuento'],
            'fecha_inicio'         => $data['fecha_inicio'],
            'fecha_fin'            => $data['fecha_fin'],
            'estado'               => 'activa',
        ]);

        // Asociar servicios
        $promocion->servicios()->attach($data['servicios']);

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'Creación de Promoción',
            'description' => "Promoción creada: {$promocion->nombre} ({$promocion->porcentaje_descuento}% dto.) — Servicios: " . count($data['servicios']),
            'ip_address'  => $request->ip() ?? 'No disponible',
            'browser'     => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('promociones.index')->with('status', 'Promoción creada correctamente.');
    }

    /**
     * Detalle de una promoción.
     */
    public function show(Promocion $promocion)
    {
        $promocion->load('servicios');
        return view('modules.servicios.promociones.show', compact('promocion'));
    }

    /**
     * Formulario de edición.
     */
    public function edit(Promocion $promocion)
    {
        $servicios = Servicio::activos()->orderBy('categoria')->orderBy('nombre')->get();
        $promocion->load('servicios');
        return view('modules.servicios.promociones.edit', compact('promocion', 'servicios'));
    }

    /**
     * Actualizar promoción.
     */
    public function update(Request $request, Promocion $promocion)
    {
        $data = $request->validate([
            'nombre'               => ['required', 'string', 'max:150'],
            'descripcion'          => ['nullable', 'string', 'max:2000'],
            'porcentaje_descuento' => ['required', 'numeric', 'min:1', 'max:100'],
            'fecha_inicio'         => ['required', 'date'],
            'fecha_fin'            => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'estado'               => ['nullable', 'in:activa,inactiva,expirada'],
            'servicios'            => ['required', 'array', 'min:1'],
            'servicios.*'          => ['exists:servicios,id'],
        ]);

        $promocion->update([
            'nombre'               => $data['nombre'],
            'descripcion'          => $data['descripcion'] ?? null,
            'porcentaje_descuento' => $data['porcentaje_descuento'],
            'fecha_inicio'         => $data['fecha_inicio'],
            'fecha_fin'            => $data['fecha_fin'],
            'estado'               => $data['estado'] ?? $promocion->estado,
        ]);

        // Sincronizar servicios
        $promocion->servicios()->sync($data['servicios']);

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'Actualización de Promoción',
            'description' => "Promoción actualizada: {$promocion->nombre}",
            'ip_address'  => $request->ip() ?? 'No disponible',
            'browser'     => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('promociones.index')->with('status', 'Promoción actualizada correctamente.');
    }

    /**
     * Eliminar promoción.
     */
    public function destroy(Request $request, Promocion $promocion)
    {
        $nombre = $promocion->nombre;
        $promocion->servicios()->detach(); // Limpiar pivote
        $promocion->delete();

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'Eliminación de Promoción',
            'description' => "Promoción eliminada: {$nombre}",
            'ip_address'  => $request->ip() ?? 'No disponible',
            'browser'     => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('promociones.index')->with('status', 'Promoción eliminada correctamente.');
    }
}
