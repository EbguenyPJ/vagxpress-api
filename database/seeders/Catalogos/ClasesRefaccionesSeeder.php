<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_clases_refacciones` (3 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class ClasesRefaccionesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_clases_refacciones')->truncate();

        $rows = [
            ['id_clase_refaccion' => 1, 's_clase_refaccion' => 'Original', 's_color_clase_refaccion' => '#000000', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_clase_refaccion' => 2, 's_clase_refaccion' => 'Funcional', 's_color_clase_refaccion' => '#FF0000', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_clase_refaccion' => 3, 's_clase_refaccion' => 'Genérica', 's_color_clase_refaccion' => '#0000FF', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_clases_refacciones')->insert($chunk);
        }
    }
}
