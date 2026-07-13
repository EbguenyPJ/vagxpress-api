<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_tipos_empleados` (2 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class TiposEmpleadosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_tipos_empleados')->truncate();

        $rows = [
            ['id_tipo_empleado' => 1, 's_tipo_empleado' => 'Sistema', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_tipo_empleado' => 2, 's_tipo_empleado' => 'Repartidor', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_tipos_empleados')->insert($chunk);
        }
    }
}
