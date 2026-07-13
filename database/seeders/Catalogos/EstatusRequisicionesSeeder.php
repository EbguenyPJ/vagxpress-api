<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_estatus_requisiciones` (4 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class EstatusRequisicionesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_estatus_requisiciones')->truncate();

        $rows = [
            ['id_estatus_requisicion' => 1, 's_estatus_requisicion' => 'Abierta', 'b_activo' => 1],
            ['id_estatus_requisicion' => 2, 's_estatus_requisicion' => 'Cerrada', 'b_activo' => 1],
            ['id_estatus_requisicion' => 3, 's_estatus_requisicion' => 'Aprobada', 'b_activo' => 1],
            ['id_estatus_requisicion' => 4, 's_estatus_requisicion' => 'Cancelada', 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_estatus_requisiciones')->insert($chunk);
        }
    }
}
