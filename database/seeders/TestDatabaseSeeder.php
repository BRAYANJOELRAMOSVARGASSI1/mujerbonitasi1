<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

/**
 * TestDatabaseSeeder
 *
 * Genera los usuarios de prueba con credenciales reales,
 * perfiles de estilista/cliente enlazados, productos,
 * herramientas y servicios de demostración para el sistema
 * MUJER BONITA — Ciclos 1 y 2.
 *
 * Contraseña estandarizada: Password123
 * (Cumple: mín. 8 caracteres, 1 mayúscula, 1 minúscula, 1 número)
 */
class TestDatabaseSeeder extends Seeder
{
    /**
     * Contraseña segura estandarizada para todos los usuarios de prueba.
     */
    private const DEFAULT_PASSWORD = 'Password123';

    public function run(): void
    {
        $this->command->info('╔══════════════════════════════════════════════╗');
        $this->command->info('║  MUJER BONITA — Semilla de Datos de Prueba  ║');
        $this->command->info('╚══════════════════════════════════════════════╝');

        $this->seedTestUsers();
        $this->seedProductos();
        $this->seedHerramientas();
        $this->seedEstilistas();
        $this->seedClientes();
        $this->seedServicios();

        $this->command->info('');
        $this->command->info('✅ Todos los datos de prueba fueron creados correctamente.');
        $this->command->info('🔑 Contraseña para todas las cuentas: ' . self::DEFAULT_PASSWORD);
    }

    /**
     * ═══════════════════════════════════════════════════
     * 1. USUARIOS DE PRUEBA CON ROLES REALES
     * ═══════════════════════════════════════════════════
     */
    private function seedTestUsers(): void
    {
        $this->command->info('');
        $this->command->info('👤 Creando usuarios de prueba...');

        // Asegurar que los roles existen
        $roles = ['super-admin', 'admin', 'recepcionista', 'estilista', 'cliente'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        $users = [
            [
                'email'   => 'trabajodt1c0@gmail.com',
                'name'    => 'Soporte Técnico',
                'role'    => 'super-admin',
                'desc'    => 'Soporte técnico, acceso total',
            ],
            [
                'email'   => 'joetoe250@gmail.com',
                'name'    => 'Administradora Mujer Bonita',
                'role'    => 'admin',
                'desc'    => 'Gestión completa de inventario, personal, reportes y clientes',
            ],
            [
                'email'   => 'ramosvargabrayan@gmail.com',
                'name'    => 'Recepcionista Principal',
                'role'    => 'recepcionista',
                'desc'    => 'Crear clientes, ver horarios, realizar ventas/citas',
            ],
            [
                'email'   => 'joelramostrbj@gmail.com',
                'name'    => 'Estilista María López',
                'role'    => 'estilista',
                'desc'    => 'Solo ver sus horarios y herramientas',
            ],
            [
                'email'   => 'fitgo61@gmail.com',
                'name'    => 'Estilista Carmen Flores',
                'role'    => 'estilista',
                'desc'    => 'Solo ver sus horarios y herramientas',
            ],
            [
                'email'   => 'ramosvargasbrayanjoel66@gmail.com',
                'name'    => 'Cliente Ana Pérez',
                'role'    => 'cliente',
                'desc'    => 'Acceso a ver servicios y agendar',
            ],
            [
                'email'   => 'etsech67@gmail.com',
                'name'    => 'Cliente Laura Gutiérrez',
                'role'    => 'cliente',
                'desc'    => 'Acceso a ver servicios y agendar',
            ],
            [
                'email'   => 'xdreicarlos@gmail.com',
                'name'    => 'Cliente Carlos Mendoza',
                'role'    => 'cliente',
                'desc'    => 'Acceso a ver servicios y agendar',
            ],
            [
                'email'   => 'si2psicologiaproy@gmail.com',
                'name'    => 'Cliente Proyecto SI2',
                'role'    => 'cliente',
                'desc'    => 'Acceso a ver servicios y agendar',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name'              => $userData['name'],
                    'password'          => Hash::make(self::DEFAULT_PASSWORD),
                    'email_verified_at' => now(),
                    'status'            => 'activo',
                ]
            );

            // Sincronizar rol (reemplaza cualquier rol anterior)
            $user->syncRoles([$userData['role']]);

            $this->command->info("   ✓ {$userData['email']} → [{$userData['role']}] — {$userData['desc']}");
        }
    }

    /**
     * ═══════════════════════════════════════════════════
     * 2. PRODUCTOS DE PRUEBA (P3)
     * ═══════════════════════════════════════════════════
     */
    private function seedProductos(): void
    {
        $this->command->info('');
        $this->command->info('📦 Creando productos de prueba...');

        $modelClass = 'App\\Modules\\P3_GestionInventarioHerramientas\\Models\\Producto';

        if (!class_exists($modelClass)) {
            $this->command->warn('   ⚠ Modelo Producto no encontrado. Saltando...');
            return;
        }

        $productos = [
            [
                'nombre'         => 'Shampoo Profesional L\'Oréal',
                'descripcion'    => 'Shampoo profesional para uso en salón, fórmula reparadora.',
                'marca'          => 'L\'Oréal Professionnel',
                'categoria'      => 'Cuidado Capilar',
                'unidad_medida'  => 'unidad',
                'precio_compra'  => 85.00,
                'precio_venta'   => 150.00,
                'stock_actual'   => 25,
                'stock_minimo'   => 5,
            ],
            [
                'nombre'         => 'Keratina Brasileña Tratamiento',
                'descripcion'    => 'Tratamiento de keratina para alisado y reconstrucción capilar.',
                'marca'          => 'Kerafruit',
                'categoria'      => 'Cuidado Capilar',
                'unidad_medida'  => 'litro',
                'precio_compra'  => 200.00,
                'precio_venta'   => 450.00,
                'stock_actual'   => 10,
                'stock_minimo'   => 3,
            ],
            [
                'nombre'         => 'Tinte Wella Koleston Perfect',
                'descripcion'    => 'Tinte permanente profesional con tecnología ME+.',
                'marca'          => 'Wella',
                'categoria'      => 'Coloración',
                'unidad_medida'  => 'unidad',
                'precio_compra'  => 45.00,
                'precio_venta'   => 90.00,
                'stock_actual'   => 40,
                'stock_minimo'   => 10,
            ],
            [
                'nombre'         => 'Acondicionador Hidratante',
                'descripcion'    => 'Acondicionador de hidratación profunda para cabello seco.',
                'marca'          => 'Schwarzkopf',
                'categoria'      => 'Cuidado Capilar',
                'unidad_medida'  => 'unidad',
                'precio_compra'  => 65.00,
                'precio_venta'   => 120.00,
                'stock_actual'   => 18,
                'stock_minimo'   => 4,
            ],
            [
                'nombre'         => 'Gel Fijador Extra Fuerte',
                'descripcion'    => 'Gel de fijación extrema para peinados de larga duración.',
                'marca'          => 'Moco de Gorila',
                'categoria'      => 'Insumos Generales',
                'unidad_medida'  => 'unidad',
                'precio_compra'  => 15.00,
                'precio_venta'   => 35.00,
                'stock_actual'   => 30,
                'stock_minimo'   => 8,
            ],
        ];

        foreach ($productos as $data) {
            $modelClass::firstOrCreate(
                ['nombre' => $data['nombre']],
                $data
            );
            $this->command->info("   ✓ Producto: {$data['nombre']}");
        }
    }

    /**
     * ═══════════════════════════════════════════════════
     * 3. HERRAMIENTAS DE PRUEBA (P3)
     * ═══════════════════════════════════════════════════
     */
    private function seedHerramientas(): void
    {
        $this->command->info('');
        $this->command->info('🔧 Creando herramientas de prueba...');

        $modelClass = 'App\\Modules\\P3_GestionInventarioHerramientas\\Models\\Herramienta';

        if (!class_exists($modelClass)) {
            $this->command->warn('   ⚠ Modelo Herramienta no encontrado. Saltando...');
            return;
        }

        $herramientas = [
            [
                'nombre'            => 'Secadora Profesional BaByliss PRO',
                'descripcion'       => 'Secadora iónica de 2200W con difusor y concentrador.',
                'categoria'         => 'Cabello',
                'numero_serie'      => 'SEC-BABYLISS-001',
                'area_asignada'     => 'Cabello',
                'estado'            => 'disponible',
                'fecha_adquisicion' => now()->subMonths(6)->toDateString(),
                'costo'             => 350.00,
            ],
            [
                'nombre'            => 'Plancha GHD Platinum+',
                'descripcion'       => 'Plancha de cerámica con tecnología predictiva de calor.',
                'categoria'         => 'Cabello',
                'numero_serie'      => 'PLA-GHD-002',
                'area_asignada'     => 'Cabello',
                'estado'            => 'disponible',
                'fecha_adquisicion' => now()->subMonths(3)->toDateString(),
                'costo'             => 520.00,
            ],
            [
                'nombre'            => 'Tijeras Profesionales Jaguar',
                'descripcion'       => 'Tijeras de acero inoxidable para corte profesional, 5.5".',
                'categoria'         => 'Cabello',
                'numero_serie'      => 'TIJ-JAGUAR-003',
                'area_asignada'     => 'Cabello',
                'estado'            => 'disponible',
                'fecha_adquisicion' => now()->subMonths(12)->toDateString(),
                'costo'             => 180.00,
            ],
            [
                'nombre'            => 'Cepillo Térmico Olivia Garden',
                'descripcion'       => 'Cepillo redondo cerámico para brushing profesional.',
                'categoria'         => 'Cabello',
                'numero_serie'      => 'CEP-OG-004',
                'area_asignada'     => 'Cabello',
                'estado'            => 'disponible',
                'fecha_adquisicion' => now()->subMonths(2)->toDateString(),
                'costo'             => 45.00,
            ],
            [
                'nombre'            => 'Pinzas de Sección (Set x12)',
                'descripcion'       => 'Set de pinzas profesionales para seccionar cabello.',
                'categoria'         => 'General',
                'numero_serie'      => 'PIN-DIANE-005',
                'area_asignada'     => 'General',
                'estado'            => 'disponible',
                'fecha_adquisicion' => now()->subMonths(1)->toDateString(),
                'costo'             => 25.00,
            ],
        ];

        foreach ($herramientas as $data) {
            $modelClass::firstOrCreate(
                ['nombre' => $data['nombre']],
                $data
            );
            $this->command->info("   ✓ Herramienta: {$data['nombre']}");
        }
    }

    /**
     * ═══════════════════════════════════════════════════
     * 4. PERFILES DE ESTILISTAS (P2)
     * ═══════════════════════════════════════════════════
     */
    private function seedEstilistas(): void
    {
        $this->command->info('');
        $this->command->info('💇 Enlazando perfiles de estilistas...');

        $modelClass = 'App\\Modules\\P2_GestionPersonalClientes\\Models\\Estilista';

        if (!class_exists($modelClass)) {
            $this->command->warn('   ⚠ Modelo Estilista no encontrado. Saltando...');
            return;
        }

        $estilistasData = [
            'joelramostrbj@gmail.com'  => [
                'nombre'       => 'María',
                'apellido'     => 'López',
                'especialidad' => 'Cabello',
                'telefono'     => '70012345',
                'email'        => 'joelramostrbj@gmail.com',
            ],
            'fitgo61@gmail.com'        => [
                'nombre'       => 'Carmen',
                'apellido'     => 'Flores',
                'especialidad' => 'Maquillaje',
                'telefono'     => '70067890',
                'email'        => 'fitgo61@gmail.com',
            ],
        ];

        foreach ($estilistasData as $email => $data) {
            $user = User::where('email', $email)->first();
            if (!$user) {
                $this->command->warn("   ⚠ Usuario {$email} no encontrado. Saltando...");
                continue;
            }

            $modelClass::firstOrCreate(
                ['user_id' => $user->id],
                array_merge($data, [
                    'porcentaje_comision' => 40.00,
                    'fecha_contratacion'  => now()->subMonths(6)->toDateString(),
                    'estado'              => 'activo',
                ])
            );
            $this->command->info("   ✓ Estilista enlazado: {$data['nombre']} {$data['apellido']} ({$email})");
        }
    }

    /**
     * ═══════════════════════════════════════════════════
     * 5. PERFILES DE CLIENTES (P2)
     * ═══════════════════════════════════════════════════
     */
    private function seedClientes(): void
    {
        $this->command->info('');
        $this->command->info('👥 Enlazando perfiles de clientes...');

        $modelClass = 'App\\Modules\\P2_GestionPersonalClientes\\Models\\Cliente';

        if (!class_exists($modelClass)) {
            $this->command->warn('   ⚠ Modelo Cliente no encontrado. Saltando...');
            return;
        }

        $clientesData = [
            [
                'nombre'    => 'Ana',
                'apellido'  => 'Pérez',
                'telefono'  => '71111111',
                'email'     => 'ramosvargasbrayanjoel66@gmail.com',
                'direccion' => 'Av. América #123, Cochabamba',
            ],
            [
                'nombre'    => 'Laura',
                'apellido'  => 'Gutiérrez',
                'telefono'  => '72222222',
                'email'     => 'etsech67@gmail.com',
                'direccion' => 'C. Heroínas #456, Cochabamba',
            ],
            [
                'nombre'    => 'Carlos',
                'apellido'  => 'Mendoza',
                'telefono'  => '73333333',
                'email'     => 'xdreicarlos@gmail.com',
                'direccion' => 'Av. Oquendo #789, Cochabamba',
            ],
            [
                'nombre'    => 'Sofía',
                'apellido'  => 'Proyecto SI2',
                'telefono'  => '74444444',
                'email'     => 'si2psicologiaproy@gmail.com',
                'direccion' => 'C. Sucre #321, Cochabamba',
            ],
        ];

        foreach ($clientesData as $data) {
            $modelClass::firstOrCreate(
                ['email' => $data['email']],
                $data
            );
            $this->command->info("   ✓ Cliente creado: {$data['nombre']} {$data['apellido']} ({$data['email']})");
        }
    }

    /**
     * ═══════════════════════════════════════════════════
     * 6. SERVICIOS DE PRUEBA (P4)
     * ═══════════════════════════════════════════════════
     */
    private function seedServicios(): void
    {
        $this->command->info('');
        $this->command->info('✂️ Creando servicios de prueba...');

        $modelClass = 'App\\Modules\\P4_GestionServiciosCitas\\Models\\Servicio';

        if (!class_exists($modelClass)) {
            $this->command->warn('   ⚠ Modelo Servicio no encontrado. Saltando...');
            return;
        }

        $servicios = [
            [
                'nombre'           => 'Corte de Cabello',
                'descripcion'      => 'Corte profesional para dama, incluye lavado y secado.',
                'precio'           => 50.00,
                'duracion_minutos' => 45,
                'categoria'        => 'Cabello',
            ],
            [
                'nombre'           => 'Maquillaje Profesional',
                'descripcion'      => 'Maquillaje completo para eventos, bodas y sesiones fotográficas.',
                'precio'           => 150.00,
                'duracion_minutos' => 90,
                'categoria'        => 'Maquillaje',
            ],
            [
                'nombre'           => 'Pedicure Spa',
                'descripcion'      => 'Pedicure completo con exfoliación, hidratación y esmaltado.',
                'precio'           => 80.00,
                'duracion_minutos' => 60,
                'categoria'        => 'Uñas - Pedicure',
            ],
            [
                'nombre'           => 'Manicure Gel',
                'descripcion'      => 'Manicure con aplicación de gel semipermanente.',
                'precio'           => 70.00,
                'duracion_minutos' => 50,
                'categoria'        => 'Uñas - Manicure',
            ],
            [
                'nombre'           => 'Tinte y Coloración',
                'descripcion'      => 'Aplicación de tinte profesional con técnica de balayage o mechas.',
                'precio'           => 200.00,
                'duracion_minutos' => 120,
                'categoria'        => 'Cabello',
            ],
        ];

        foreach ($servicios as $data) {
            $modelClass::firstOrCreate(
                ['nombre' => $data['nombre']],
                $data
            );
            $this->command->info("   ✓ Servicio: {$data['nombre']}");
        }
    }
}
