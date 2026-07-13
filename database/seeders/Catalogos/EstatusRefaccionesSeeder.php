<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_estatus_refacciones` (2 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class EstatusRefaccionesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_estatus_refacciones')->truncate();

        $rows = [
            ['id_estatus_refaccion' => 1, 's_estatus_refaccion' => 'Disponible', 's_color_estatus_refaccion' => '0FF512', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_estatus_refaccion' => 2, 's_estatus_refaccion' => 'Agotado', 's_color_estatus_refaccion' => 'EB1D19', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_estatus_refacciones')->insert($chunk);
        }
    }
}
