<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_tipos_usuarios` (2 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class TiposUsuariosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_tipos_usuarios')->truncate();

        $rows = [
            ['id_tipo_usuario' => 1, 's_tipo_usuario' => 'Super Admin', 'b_activo' => 1, 'created_at' => '2025-12-15 21:57:00', 'updated_at' => '2025-12-15 21:59:00'],
            ['id_tipo_usuario' => 2, 's_tipo_usuario' => 'Admin', 'b_activo' => 1, 'created_at' => '2025-12-15 21:51:00', 'updated_at' => '2025-12-15 21:51:00'],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_tipos_usuarios')->insert($chunk);
        }
    }
}
