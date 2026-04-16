<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])->orderBy('name')->paginate(10);
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::orderBy('name')->get();
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::create(['name' => $data['name']]);

        if (isset($data['permissions'])) {
            $permissions = Permission::whereIn('id', $data['permissions'])->get();
            $role->givePermissionTo($permissions);
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Creación de rol',
            'description' => 'Rol creado: "' . $role->name . '"',
            'ip_address' => $request->ip() ?? 'No disponible',            // Guardar IP (con valor predeterminado)
            'browser' => $request->header('user-agent') ?? 'No disponible', // Guardar navegador (con valor predeterminado)
        ]);

        return redirect()->route('roles.index')
            ->with('status', 'Rol creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $role->load('permissions', 'users');
        return view('roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get();
        $rolePermissionIds = $role->permissions->pluck('id');
        
        return view('roles.edit', compact('role', 'permissions', 'rolePermissionIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role->update(['name' => $data['name']]);

        // Sincronizar permisos
        if (isset($data['permissions'])) {
            $permissions = Permission::whereIn('id', $data['permissions'])->get();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Actualización de rol',
            'description' => 'Rol actualizado: "' . $role->name . '"',
            'ip_address' => $request->ip() ?? 'No disponible',            // Guardar IP (con valor predeterminado)
            'browser' => $request->header('user-agent') ?? 'No disponible', // Guardar navegador (con valor predeterminado)
        ]);

        return redirect()->route('roles.index')
            ->with('status', 'Rol actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Role $role)
    {
        // Verificar si el rol tiene usuarios asignados
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'No se puede eliminar el rol porque tiene usuarios asignados.');
        }

        // No permitir eliminar si es super-admin
        if ($role->name === 'super-admin') {
            return redirect()->route('roles.index')
                ->with('error', 'No se puede eliminar el rol Super Admin.');
        }

        $roleName = $role->name;
        $role->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Eliminación de rol',
            'description' => 'Rol eliminado: "' . $roleName . '"',
            'ip_address' => $request->ip() ?? 'No disponible',            // Guardar IP (con valor predeterminado)
            'browser' => $request->header('user-agent') ?? 'No disponible', // Guardar navegador (con valor predeterminado)
        ]);

        return redirect()->route('roles.index')
            ->with('status', 'Rol eliminado correctamente.');
    }
}