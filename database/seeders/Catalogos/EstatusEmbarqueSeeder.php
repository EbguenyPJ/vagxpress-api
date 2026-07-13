<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_estatus_embarque` (3 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class EstatusEmbarqueSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_estatus_embarque')->truncate();

        $rows = [
            ['id_estatus_embarque' => 1, 's_estatus_embarque' => 'Pendiente de validación', 'b_activo' => 1],
            ['id_estatus_embarque' => 2, 's_estatus_embarque' => 'Aprobado', 'b_activo' => 1],
            ['id_estatus_embarque' => 3, 's_estatus_embarque' => 'Rechazado', 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_estatus_embarque')->insert($chunk);
        }
    }
}
