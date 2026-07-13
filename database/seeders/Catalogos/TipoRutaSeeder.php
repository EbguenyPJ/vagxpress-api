<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_tipo_ruta` (2 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class TipoRutaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_tipo_ruta')->truncate();

        $rows = [
            ['id_tipo_ruta' => 1, 's_tipo_ruta' => 'Salida', 'b_activo' => 1],
            ['id_tipo_ruta' => 2, 's_tipo_ruta' => 'Regreso', 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_tipo_ruta')->insert($chunk);
        }
    }
}
