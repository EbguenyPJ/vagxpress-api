<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_tipos_cuentas` (2 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class TiposCuentasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_tipos_cuentas')->truncate();

        $rows = [
            ['id_tipo_cuenta' => 1, 's_tipo_cuenta' => 'Credito', 'b_activo' => 1],
            ['id_tipo_cuenta' => 2, 's_tipo_cuenta' => 'Debito', 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_tipos_cuentas')->insert($chunk);
        }
    }
}
