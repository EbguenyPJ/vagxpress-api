<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_prioridades` (4 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class PrioridadesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_prioridades')->truncate();

        $rows = [
            ['id_prioridad' => 1, 's_prioridad' => 'Urgente', 'b_activo' => 1],
            ['id_prioridad' => 2, 's_prioridad' => 'Alta', 'b_activo' => 1],
            ['id_prioridad' => 3, 's_prioridad' => 'Media', 'b_activo' => 1],
            ['id_prioridad' => 4, 's_prioridad' => 'Baja', 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_prioridades')->insert($chunk);
        }
    }
}
