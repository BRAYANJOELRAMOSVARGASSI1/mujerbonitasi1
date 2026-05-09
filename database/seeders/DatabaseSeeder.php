<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Ejecuta los seeders en orden: primero roles/permisos/usuarios base,
     * luego datos de prueba con credenciales reales.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TestDatabaseSeeder::class,
        ]);
    }
}
