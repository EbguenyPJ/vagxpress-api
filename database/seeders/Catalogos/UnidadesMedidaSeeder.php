<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_unidades_medida` (9 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class UnidadesMedidaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_unidades_medida')->truncate();

        $rows = [
            ['id_unidad_medida' => 1, 's_unidad_medida' => 'Pieza', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_unidad_medida' => 2, 's_unidad_medida' => 'Kit', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_unidad_medida' => 3, 's_unidad_medida' => 'Juego', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_unidad_medida' => 4, 's_unidad_medida' => 'Par', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_unidad_medida' => 5, 's_unidad_medida' => 'Litro', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_unidad_medida' => 6, 's_unidad_medida' => 'Galón', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_unidad_medida' => 7, 's_unidad_medida' => 'Metro', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_unidad_medida' => 8, 's_unidad_medida' => 'Rollo', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_unidad_medida' => 9, 's_unidad_medida' => 'Caja', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_unidades_medida')->insert($chunk);
        }
    }
}
