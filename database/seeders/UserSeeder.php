<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Seed: Roles, Permisos y Usuarios iniciales del sistema MUJER BONITA.
     */
    public function run(): void
    {
        // Limpiamos la caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ═══════════════════════════════════════════════════
        // 1. PERMISOS — Ciclo 1 (P1: Usuarios y Seguridad)
        // ═══════════════════════════════════════════════════
        Permission::firstOrCreate(['name' => 'ver usuarios']);
        Permission::firstOrCreate(['name' => 'crear usuarios']);
        Permission::firstOrCreate(['name' => 'editar usuarios']);
        Permission::firstOrCreate(['name' => 'eliminar usuarios']);
        Permission::firstOrCreate(['name' => 'ver bitacora']);
        Permission::firstOrCreate(['name' => 'ver roles']);
        Permission::firstOrCreate(['name' => 'ver permisos']);

        // ═══════════════════════════════════════════════════
        // 2. PERMISOS — Ciclo 2 (P2: Personal y Clientes)
        // ═══════════════════════════════════════════════════
        Permission::firstOrCreate(['name' => 'ver clientes']);
        Permission::firstOrCreate(['name' => 'crear clientes']);
        Permission::firstOrCreate(['name' => 'editar clientes']);
        Permission::firstOrCreate(['name' => 'eliminar clientes']);
        Permission::firstOrCreate(['name' => 'buscar clientes']);

        Permission::firstOrCreate(['name' => 'ver estilistas']);
        Permission::firstOrCreate(['name' => 'crear estilistas']);
        Permission::firstOrCreate(['name' => 'editar estilistas']);
        Permission::firstOrCreate(['name' => 'eliminar estilistas']);

        Permission::firstOrCreate(['name' => 'ver horarios']);
        Permission::firstOrCreate(['name' => 'crear horarios']);
        Permission::firstOrCreate(['name' => 'editar horarios']);
        Permission::firstOrCreate(['name' => 'eliminar horarios']);

        // ═══════════════════════════════════════════════════
        // 3. PERMISOS — Ciclo 2 (P3: Inventario y Herramientas)
        // ═══════════════════════════════════════════════════
        Permission::firstOrCreate(['name' => 'ver productos']);
        Permission::firstOrCreate(['name' => 'crear productos']);
        Permission::firstOrCreate(['name' => 'editar productos']);
        Permission::firstOrCreate(['name' => 'eliminar productos']);
        Permission::firstOrCreate(['name' => 'ver stock']);

        Permission::firstOrCreate(['name' => 'ver herramientas']);
        Permission::firstOrCreate(['name' => 'crear herramientas']);
        Permission::firstOrCreate(['name' => 'editar herramientas']);
        Permission::firstOrCreate(['name' => 'eliminar herramientas']);
        Permission::firstOrCreate(['name' => 'consultar herramientas']);

        // ═══════════════════════════════════════════════════
        // 4. PERMISOS — Ciclo 2 (P4: Servicios)
        // ═══════════════════════════════════════════════════
        Permission::firstOrCreate(['name' => 'ver servicios']);
        Permission::firstOrCreate(['name' => 'crear servicios']);
        Permission::firstOrCreate(['name' => 'editar servicios']);
        Permission::firstOrCreate(['name' => 'eliminar servicios']);

        // ═══════════════════════════════════════════════════
        // 5. ROLES
        // ═══════════════════════════════════════════════════
        $roleSuper    = Role::firstOrCreate(['name' => 'super-admin']);
        $roleAdmin    = Role::firstOrCreate(['name' => 'admin']);
        $roleRecep    = Role::firstOrCreate(['name' => 'recepcionista']);
        $roleEstilista = Role::firstOrCreate(['name' => 'estilista']);
        $roleCliente  = Role::firstOrCreate(['name' => 'cliente']);

        // Admin: todo excepto configuración técnica
        $roleAdmin->syncPermissions([
            // C1
            'ver usuarios', 'crear usuarios', 'editar usuarios', 'eliminar usuarios', 'ver bitacora',
            'ver roles', 'ver permisos',
            // C2 - Personal
            'ver clientes', 'crear clientes', 'editar clientes', 'eliminar clientes', 'buscar clientes',
            'ver estilistas', 'crear estilistas', 'editar estilistas', 'eliminar estilistas',
            'ver horarios', 'crear horarios', 'editar horarios', 'eliminar horarios',
            // C2 - Inventario
            'ver productos', 'crear productos', 'editar productos', 'eliminar productos', 'ver stock',
            'ver herramientas', 'crear herramientas', 'editar herramientas', 'eliminar herramientas', 'consultar herramientas',
            // C2 - Servicios
            'ver servicios', 'crear servicios', 'editar servicios', 'eliminar servicios',
        ]);

        // Recepcionista: gestión de clientes, consultas
        $roleRecep->syncPermissions([
            'ver clientes', 'crear clientes', 'editar clientes', 'buscar clientes',
            'ver estilistas',
            'ver horarios',
            'ver productos', 'ver stock',
            'ver herramientas', 'consultar herramientas',
            'ver servicios',
        ]);

        // Estilista: solo consulta
        $roleEstilista->syncPermissions([
            'ver horarios',
            'ver herramientas', 'consultar herramientas',
            'ver servicios',
        ]);

        // Cliente: solo consulta de servicios
        $roleCliente->syncPermissions([
            'ver servicios',
        ]);

        // ═══════════════════════════════════════════════════
        // 6. USUARIOS INICIALES
        // ═══════════════════════════════════════════════════

        // Soporte Técnico (super-admin)
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

        // Magaly — Dueña del Salón (admin)
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

        // Recepcionista
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

        // Estilista de ejemplo
        $estilistaUser = User::firstOrCreate(
            ['email' => 'estilista@estilista.com'],
            [
                'name' => 'Estilista Demo',
                'password' => '12345',
                'email_verified_at' => now(),
                'status' => 'activo'
            ]
        );
        if (!$estilistaUser->hasRole('estilista')) { $estilistaUser->assignRole($roleEstilista); }

        // Cliente
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
