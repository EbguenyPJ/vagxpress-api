<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_estatus_entrada` (3 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class EstatusEntradaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_estatus_entrada')->truncate();

        $rows = [
            ['id_estatus_entrada' => 1, 's_estatus_entrada' => 'Pendiente', 'b_activo' => 1],
            ['id_estatus_entrada' => 2, 's_estatus_entrada' => 'Aprobado', 'b_activo' => 1],
            ['id_estatus_entrada' => 3, 's_estatus_entrada' => 'Rechazado', 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_estatus_entrada')->insert($chunk);
        }
    }
}
