<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_estatus_ventas` (3 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class EstatusVentasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_estatus_ventas')->truncate();

        $rows = [
            ['id_estatus_venta' => 1, 's_estatus_venta' => 'Pagada', 'b_activo' => 1],
            ['id_estatus_venta' => 2, 's_estatus_venta' => 'Cancelada', 'b_activo' => 1],
            ['id_estatus_venta' => 3, 's_estatus_venta' => 'Pendiente', 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_estatus_ventas')->insert($chunk);
        }
    }
}
