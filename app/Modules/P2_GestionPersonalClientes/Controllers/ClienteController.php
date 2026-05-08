<?php

namespace App\Modules\P2_GestionPersonalClientes\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\P2_GestionPersonalClientes\Models\Cliente;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * [CU4]  — Registrar Cliente
 * [CU10] — Buscar Cliente
 */
class ClienteController extends Controller
{
    /**
     * [CU10] Listado + Búsqueda de Clientes.
     */
    public function index(Request $request)
    {
        $clientes = Cliente::query()
            ->buscar($request->input('buscar'))
            ->when($request->filled('estado'), fn($q) => $q->where('estado', $request->estado))
            ->orderBy('nombre')
            ->paginate(12)
            ->withQueryString();

        return view('modules.personal.clientes.index', compact('clientes'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        return view('modules.personal.clientes.create');
    }

    /**
     * [CU4] Almacenar nuevo cliente.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'           => ['required', 'string', 'max:100'],
            'apellido'         => ['required', 'string', 'max:100'],
            'telefono'         => ['required', 'string', 'max:20'],
            'email'            => ['nullable', 'email', 'max:255', 'unique:clientes,email'],
            'direccion'        => ['nullable', 'string', 'max:500'],
            'fecha_nacimiento' => ['nullable', 'date', 'before:today'],
            'notas'            => ['nullable', 'string', 'max:1000'],
        ]);

        $cliente = Cliente::create($data);

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Registro de cliente',
            'description'=> "Cliente registrado: {$cliente->nombre_completo} (Tel: {$cliente->telefono})",
            'ip_address' => $request->ip() ?? 'No disponible',
            'browser'    => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('clientes.index')
            ->with('status', 'Cliente registrado correctamente.');
    }

    /**
     * Detalle del cliente.
     */
    public function show(Cliente $cliente)
    {
        return view('modules.personal.clientes.show', compact('cliente'));
    }

    /**
     * Formulario de edición.
     */
    public function edit(Cliente $cliente)
    {
        return view('modules.personal.clientes.edit', compact('cliente'));
    }

    /**
     * Actualizar cliente.
     */
    public function update(Request $request, Cliente $cliente)
    {
        $data = $request->validate([
            'nombre'           => ['required', 'string', 'max:100'],
            'apellido'         => ['required', 'string', 'max:100'],
            'telefono'         => ['required', 'string', 'max:20'],
            'email'            => ['nullable', 'email', 'max:255', 'unique:clientes,email,' . $cliente->id],
            'direccion'        => ['nullable', 'string', 'max:500'],
            'fecha_nacimiento' => ['nullable', 'date', 'before:today'],
            'notas'            => ['nullable', 'string', 'max:1000'],
            'estado'           => ['nullable', 'in:activo,inactivo'],
        ]);

        $cliente->update($data);

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Actualización de cliente',
            'description'=> "Cliente actualizado: {$cliente->nombre_completo}",
            'ip_address' => $request->ip() ?? 'No disponible',
            'browser'    => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('clientes.index')
            ->with('status', 'Cliente actualizado correctamente.');
    }

    /**
     * Eliminar cliente.
     */
    public function destroy(Request $request, Cliente $cliente)
    {
        $nombre = $cliente->nombre_completo;
        $cliente->delete();

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Eliminación de cliente',
            'description'=> "Cliente eliminado: {$nombre}",
            'ip_address' => $request->ip() ?? 'No disponible',
            'browser'    => $request->header('user-agent') ?? 'No disponible',
        ]);

        return redirect()->route('clientes.index')
            ->with('status', 'Cliente eliminado correctamente.');
    }
}
