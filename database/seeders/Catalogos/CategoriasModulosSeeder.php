<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_categorias_modulos` (6 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class CategoriasModulosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_categorias_modulos')->truncate();

        $rows = [
            ['id_categoria_modulo' => 1, 's_categoria_modulo' => 'Inicio', 'b_activo' => 0, 'created_at' => null, 'updated_at' => null],
            ['id_categoria_modulo' => 2, 's_categoria_modulo' => 'Operación', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_categoria_modulo' => 3, 's_categoria_modulo' => 'Inventario', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_categoria_modulo' => 4, 's_categoria_modulo' => 'Compras', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_categoria_modulo' => 5, 's_categoria_modulo' => 'Administracion', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_categoria_modulo' => 6, 's_categoria_modulo' => 'Configuracion', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_categorias_modulos')->insert($chunk);
        }
    }
}
