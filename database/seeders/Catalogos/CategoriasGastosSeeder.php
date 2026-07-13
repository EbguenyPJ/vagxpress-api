<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Vacío en el entorno de referencia pese a que tc_tipos_gastos referencia
 * las categorías 1-4 (huérfanas allá por falta de FKs). Se crean con
 * etiquetas inferidas de los tipos de gasto que las usan.
 */
class CategoriasGastosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_categorias_gastos')->truncate();
        DB::table('tc_categorias_gastos')->insert([
            ['id_categoria_gasto' => 1, 's_categoria_gasto' => 'Costos Directos', 'b_activo' => 1],
            ['id_categoria_gasto' => 2, 's_categoria_gasto' => 'Herramientas y Equipo', 'b_activo' => 1],
            ['id_categoria_gasto' => 3, 's_categoria_gasto' => 'Gastos Generales', 'b_activo' => 1],
            ['id_categoria_gasto' => 4, 's_categoria_gasto' => 'Administrativos', 'b_activo' => 1],
        ]);
    }
}
