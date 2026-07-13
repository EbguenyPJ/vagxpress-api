<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tw_versiones` (1 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class VersionesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tw_versiones')->truncate();

        $rows = [
            ['id_version' => 1, 'id_usuario' => 1, 's_nombre_version' => 'V.1.1.1', 's_descripcion_version' => 'Primera versión de la refaccionaria', 'd_fecha_actualizacion_version' => null, 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tw_versiones')->insert($chunk);
        }
    }
}
