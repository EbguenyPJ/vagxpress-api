<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_porcentajes_utilidad` (5 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class PorcentajesUtilidadSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_porcentajes_utilidad')->truncate();

        $rows = [
            ['id_porcentaje_utilidad' => 1, 'id_tipo_configuracion' => '1', 'n_porcentaje_utilidad' => '5.0000000', 's_porcentaje_utilidad' => 'Porcentaje Utilidad Base', 's_descripcion' => 'Este porcentaje se utilisará para calcular el precio base de la refacción', 'created_at' => null, 'updated_at' => null, 'b_activo' => 1],
            ['id_porcentaje_utilidad' => 2, 'id_tipo_configuracion' => '2', 'n_porcentaje_utilidad' => '20.0000000', 's_porcentaje_utilidad' => 'Porcentaje Utilidad Default', 's_descripcion' => 'Este Porcentaje se utilizará para mostrar el precio de venta en los catalogos', 'created_at' => null, 'updated_at' => null, 'b_activo' => 1],
            ['id_porcentaje_utilidad' => 3, 'id_tipo_configuracion' => '3', 'n_porcentaje_utilidad' => '10.0000000', 's_porcentaje_utilidad' => 'Porcentaje de Utilidad 1', 's_descripcion' => 'Este porcentaje se utilizará para calcular el precio de venta a taller', 'created_at' => null, 'updated_at' => null, 'b_activo' => 1],
            ['id_porcentaje_utilidad' => 4, 'id_tipo_configuracion' => '3', 'n_porcentaje_utilidad' => '15.0000000', 's_porcentaje_utilidad' => 'Porcentaje de Utilidad 2', 's_descripcion' => 'Este porcentaje se utilizará para calcular el precio de venta a mayoristas', 'created_at' => null, 'updated_at' => null, 'b_activo' => 1],
            ['id_porcentaje_utilidad' => 5, 'id_tipo_configuracion' => '3', 'n_porcentaje_utilidad' => '24.0000000', 's_porcentaje_utilidad' => 'Porcentaje de Utilidad 3', 's_descripcion' => 'Este porcentaje se utilizará para calcular el precio de venta a clientes preferenciales', 'created_at' => null, 'updated_at' => null, 'b_activo' => 0],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_porcentajes_utilidad')->insert($chunk);
        }
    }
}
