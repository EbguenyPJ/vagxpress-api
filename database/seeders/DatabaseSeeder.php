<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Siembra la base de datos local completa:
     * catálogos, vehículos, usuario admin y datos de demostración.
     */
    public function run(): void
    {
        $this->call([
            CatalogosSeeder::class,
            VehiculosCatalogSeeder::class,
            AdminUserSeeder::class,
            DemoDataSeeder::class,
        ]);
    }
}
