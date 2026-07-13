<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_tipos_configuraciones` (3 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class TiposConfiguracionesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_tipos_configuraciones')->truncate();

        $rows = [
            ['id_tipo_configuracion' => 1, 'id_modulo' => null, 's_tipo_configuracion' => 'Porcentaje Utilidad Base', 's_descripcion' => 'Porcentaje de utilidad que se aumenta automáticamente al precio de compra', 'created_at' => null, 'updated_at' => null, 'b_activo' => 1],
            ['id_tipo_configuracion' => 2, 'id_modulo' => null, 's_tipo_configuracion' => 'Porcentaje Utilidad Default', 's_descripcion' => 'Porcentaje de utilidad que se utiliza para mostrar el precio por defecto en los catálogos', 'created_at' => null, 'updated_at' => null, 'b_activo' => 1],
            ['id_tipo_configuracion' => 3, 'id_modulo' => null, 's_tipo_configuracion' => 'Porcentaje Utilidad', 's_descripcion' => 'Porcentaje de utilidad que se aumenta al precio base para mostrar el coto final de venta', 'created_at' => null, 'updated_at' => null, 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_tipos_configuraciones')->insert($chunk);
        }
    }
}
