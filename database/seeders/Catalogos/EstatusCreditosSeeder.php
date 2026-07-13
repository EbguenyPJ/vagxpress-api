<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_estatus_creditos` (3 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class EstatusCreditosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_estatus_creditos')->truncate();

        $rows = [
            ['id_estatus_credito' => 1, 's_estatus_credito' => 'Pagado', 'b_activo' => 1],
            ['id_estatus_credito' => 2, 's_estatus_credito' => 'Cancelado', 'b_activo' => 1],
            ['id_estatus_credito' => 3, 's_estatus_credito' => 'Pendiente', 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_estatus_creditos')->insert($chunk);
        }
    }
}
