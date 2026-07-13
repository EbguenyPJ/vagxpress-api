<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_profesiones` (7 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class ProfesionesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_profesiones')->truncate();

        $rows = [
            ['id_profesion' => 1, 's_profesion' => 'Administrador', 'b_activo' => 1],
            ['id_profesion' => 2, 's_profesion' => 'Contador', 'b_activo' => 1],
            ['id_profesion' => 3, 's_profesion' => 'Diseñador grafico', 'b_activo' => 1],
            ['id_profesion' => 4, 's_profesion' => 'Técnico mecánico', 'b_activo' => 1],
            ['id_profesion' => 5, 's_profesion' => 'Ingeniero automotriz', 'b_activo' => 1],
            ['id_profesion' => 6, 's_profesion' => 'Mercadologo', 'b_activo' => 1],
            ['id_profesion' => 7, 's_profesion' => 'Programador', 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_profesiones')->insert($chunk);
        }
    }
}
