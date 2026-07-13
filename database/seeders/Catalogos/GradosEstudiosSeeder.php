<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_grados_estudios` (6 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class GradosEstudiosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_grados_estudios')->truncate();

        $rows = [
            ['id_grado_estudios' => 1, 's_grado_estudios' => 'Secundaria', 'b_activo' => 1],
            ['id_grado_estudios' => 2, 's_grado_estudios' => 'Bachillerato - Preparatoria', 'b_activo' => 1],
            ['id_grado_estudios' => 3, 's_grado_estudios' => 'Carrera técnica', 'b_activo' => 1],
            ['id_grado_estudios' => 4, 's_grado_estudios' => 'Licencuatura trunca', 'b_activo' => 1],
            ['id_grado_estudios' => 5, 's_grado_estudios' => 'Licenciatura terminada', 'b_activo' => 1],
            ['id_grado_estudios' => 6, 's_grado_estudios' => 'Maestria', 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_grados_estudios')->insert($chunk);
        }
    }
}
