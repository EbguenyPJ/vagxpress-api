<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_estatus_orden` (4 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class EstatusOrdenSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_estatus_orden')->truncate();

        $rows = [
            ['id_estatus_orden' => 1, 's_estatus_orden' => 'Asignada', 'b_activo' => 1],
            ['id_estatus_orden' => 2, 's_estatus_orden' => 'En reparto', 'b_activo' => 1],
            ['id_estatus_orden' => 3, 's_estatus_orden' => 'Finalizada', 'b_activo' => 1],
            ['id_estatus_orden' => 4, 's_estatus_orden' => 'Pendiente', 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_estatus_orden')->insert($chunk);
        }
    }
}
