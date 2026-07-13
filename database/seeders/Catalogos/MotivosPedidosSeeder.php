<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_motivos_pedidos` (2 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class MotivosPedidosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_motivos_pedidos')->truncate();

        $rows = [
            ['id_motivo_pedido' => 1, 's_motivo_pedido' => 'Sin Stock', 'b_activo' => 1],
            ['id_motivo_pedido' => 2, 's_motivo_pedido' => 'Motivo Stock', 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_motivos_pedidos')->insert($chunk);
        }
    }
}
