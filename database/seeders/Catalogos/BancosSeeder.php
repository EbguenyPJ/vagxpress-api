<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_bancos` (2 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class BancosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_bancos')->truncate();

        $rows = [
            ['id_banco' => 1, 's_banco' => 'Santander', 's_img_banco' => 'https://i.pinimg.com/1200x/51/68/a8/5168a85631d2a0923374c252c2c5a0a8.jpg', 'b_activo' => 1],
            ['id_banco' => 2, 's_banco' => 'BBVA', 's_img_banco' => 'https://i.pinimg.com/736x/a9/c4/3b/a9c43bd6eaa641ebb4aa7429911afba4.jpg', 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_bancos')->insert($chunk);
        }
    }
}
