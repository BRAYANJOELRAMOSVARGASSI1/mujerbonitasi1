<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiamos la caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Crear permisos (Añadimos 'ver bitacora')
        Permission::firstOrCreate(['name' => 'ver usuarios']);
        Permission::firstOrCreate(['name' => 'crear usuarios']);
        Permission::firstOrCreate(['name' => 'editar usuarios']);
        Permission::firstOrCreate(['name' => 'eliminar usuarios']);
        Permission::firstOrCreate(['name' => 'ver bitacora']);

        // 2. Crear roles
        $roleSuper = Role::firstOrCreate(['name' => 'super-admin']);
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleRecep = Role::firstOrCreate(['name' => 'recepcionista']);
        $roleCliente = Role::firstOrCreate(['name' => 'cliente']);

        // El Super Admin tiene todos los permisos por defecto (Gate en AppServiceProvider lo permite)
        
        // Admin (Magaly) puede ver bitácora y administrar usuarios
        $roleAdmin->syncPermissions(['ver usuarios', 'crear usuarios', 'editar usuarios', 'eliminar usuarios', 'ver bitacora']);

        // 3. Crear Usuarios Iniciales Seguros

        // El Dueño Técnico del Software (TÚ)
        $superUser = User::firstOrCreate(
            ['email' => 'super@gmail.com'], 
            [
                'name' => 'Soporte Tecnico',
                'password' => '12345',
                'email_verified_at' => now(),
                'status' => 'activo'
            ]
        );
        if (!$superUser->hasRole('super-admin')) { $superUser->assignRole($roleSuper); }

        // La Dueña del Salón (MAGALY)
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@gmail.com'], 
            [
                'name' => 'Magaly (Admin)',
                'password' => '12345',
                'email_verified_at' => now(),
                'status' => 'activo'
            ]
        );
        if (!$adminUser->hasRole('admin')) { $adminUser->assignRole($roleAdmin); }

        // Recepcionista Genérico
        $recepUser = User::firstOrCreate(
            ['email' => 'recepcionista@recepcionista.com'], 
            [
                'name' => 'Recepcionista',
                'password' => '12345',
                'email_verified_at' => now(),
                'status' => 'activo'
            ]
        );
        if (!$recepUser->hasRole('recepcionista')) { $recepUser->assignRole($roleRecep); }

        // Cliente Genérico
        $clienteUser = User::firstOrCreate(
            ['email' => 'cliente@cliente.com'], 
            [
                'name' => 'Cliente Feliz',
                'password' => '12345',
                'email_verified_at' => now(),
                'status' => 'activo'
            ]
        );
        if (!$clienteUser->hasRole('cliente')) { $clienteUser->assignRole($roleCliente); }
    }
}
