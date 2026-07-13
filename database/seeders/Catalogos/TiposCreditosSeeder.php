<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_tipos_creditos` (2 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class TiposCreditosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_tipos_creditos')->truncate();

        $rows = [
            ['id_tipo_credito' => 1, 's_tipo_credito' => 'Credito Especial', 'b_activo' => 1],
            ['id_tipo_credito' => 2, 's_tipo_credito' => 'Credito Frecuente', 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_tipos_creditos')->insert($chunk);
        }
    }
}
