<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_estatus_ordenes_compras` (3 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class EstatusOrdenesComprasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_estatus_ordenes_compras')->truncate();

        $rows = [
            ['id_estatus_orden_compra' => 1, 's_estatus_orden_compra' => 'Creada', 'b_activo' => 1],
            ['id_estatus_orden_compra' => 2, 's_estatus_orden_compra' => 'Autorizada', 'b_activo' => 1],
            ['id_estatus_orden_compra' => 3, 's_estatus_orden_compra' => 'Rechazada', 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_estatus_ordenes_compras')->insert($chunk);
        }
    }
}
