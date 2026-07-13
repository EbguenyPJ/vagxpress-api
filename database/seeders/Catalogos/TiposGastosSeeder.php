<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_tipos_gastos` (6 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class TiposGastosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_tipos_gastos')->truncate();

        $rows = [
            ['id_tipo_gasto' => 1, 'id_categoria_gasto' => 1, 's_tipo_gasto' => 'Mano de obra directa', 'b_activo' => 1],
            ['id_tipo_gasto' => 2, 'id_categoria_gasto' => 1, 's_tipo_gasto' => 'servicios directos', 'b_activo' => 1],
            ['id_tipo_gasto' => 3, 'id_categoria_gasto' => 4, 's_tipo_gasto' => 'nomina', 'b_activo' => 1],
            ['id_tipo_gasto' => 4, 'id_categoria_gasto' => 3, 's_tipo_gasto' => 'Gastos de comida', 'b_activo' => 1],
            ['id_tipo_gasto' => 5, 'id_categoria_gasto' => 3, 's_tipo_gasto' => 'Gastos de piezas genericas', 'b_activo' => 1],
            ['id_tipo_gasto' => 6, 'id_categoria_gasto' => 2, 's_tipo_gasto' => 'Herramientas menores', 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_tipos_gastos')->insert($chunk);
        }
    }
}
