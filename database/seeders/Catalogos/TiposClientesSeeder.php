<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_tipos_clientes` (2 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class TiposClientesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_tipos_clientes')->truncate();

        $rows = [
            ['id_tipo_cliente' => 1, 's_tipo_cliente' => 'Premium', 'b_activo' => 1],
            ['id_tipo_cliente' => 2, 's_tipo_cliente' => 'Recurrente', 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_tipos_clientes')->insert($chunk);
        }
    }
}
