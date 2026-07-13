<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_tipos_evidencias` (5 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class TiposEvidenciasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_tipos_evidencias')->truncate();

        $rows = [
            ['id_tipo_evidencia' => 1, 's_tipo_evidencia' => 'Imagen General', 's_mime_type' => null, 's_extension' => null, 'b_activo' => 1],
            ['id_tipo_evidencia' => 2, 's_tipo_evidencia' => 'Imagen Factura', 's_mime_type' => null, 's_extension' => null, 'b_activo' => 1],
            ['id_tipo_evidencia' => 3, 's_tipo_evidencia' => 'PDF', 's_mime_type' => null, 's_extension' => null, 'b_activo' => 1],
            ['id_tipo_evidencia' => 4, 's_tipo_evidencia' => 'Salida de reparto', 's_mime_type' => null, 's_extension' => null, 'b_activo' => 1],
            ['id_tipo_evidencia' => 5, 's_tipo_evidencia' => 'Fin de reparto', 's_mime_type' => null, 's_extension' => null, 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_tipos_evidencias')->insert($chunk);
        }
    }
}
