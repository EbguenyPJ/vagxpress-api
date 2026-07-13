<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_estados_republica` (32 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class EstadosRepublicaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_estados_republica')->truncate();

        $rows = [
            ['id_estado_republica' => 1, 's_estado_republica' => 'AGUASCALIENTES', 'b_activo' => 1],
            ['id_estado_republica' => 2, 's_estado_republica' => 'BAJA CALIFORNIA', 'b_activo' => 1],
            ['id_estado_republica' => 3, 's_estado_republica' => 'BAJA CALIFORNIA SUR', 'b_activo' => 1],
            ['id_estado_republica' => 4, 's_estado_republica' => 'CAMPECHE', 'b_activo' => 1],
            ['id_estado_republica' => 5, 's_estado_republica' => 'COAHULIA', 'b_activo' => 1],
            ['id_estado_republica' => 6, 's_estado_republica' => 'COLIMA', 'b_activo' => 1],
            ['id_estado_republica' => 7, 's_estado_republica' => 'CHIAPAS', 'b_activo' => 1],
            ['id_estado_republica' => 8, 's_estado_republica' => 'CHIHUAHUA', 'b_activo' => 1],
            ['id_estado_republica' => 9, 's_estado_republica' => 'CIUDAD DE MEXICO', 'b_activo' => 1],
            ['id_estado_republica' => 10, 's_estado_republica' => 'DURANGO', 'b_activo' => 1],
            ['id_estado_republica' => 11, 's_estado_republica' => 'GUANAJUATO', 'b_activo' => 1],
            ['id_estado_republica' => 12, 's_estado_republica' => 'GUERRERO', 'b_activo' => 1],
            ['id_estado_republica' => 13, 's_estado_republica' => 'HIDALGO', 'b_activo' => 1],
            ['id_estado_republica' => 14, 's_estado_republica' => 'JALISCO', 'b_activo' => 1],
            ['id_estado_republica' => 15, 's_estado_republica' => 'ESTADO DE MEXICO', 'b_activo' => 1],
            ['id_estado_republica' => 16, 's_estado_republica' => 'MICHOACAN', 'b_activo' => 1],
            ['id_estado_republica' => 17, 's_estado_republica' => 'MORELOS', 'b_activo' => 1],
            ['id_estado_republica' => 18, 's_estado_republica' => 'NAYARIT', 'b_activo' => 1],
            ['id_estado_republica' => 19, 's_estado_republica' => 'NUEVO LEON', 'b_activo' => 1],
            ['id_estado_republica' => 20, 's_estado_republica' => 'OAXACA', 'b_activo' => 1],
            ['id_estado_republica' => 21, 's_estado_republica' => 'PUEBLA', 'b_activo' => 1],
            ['id_estado_republica' => 22, 's_estado_republica' => 'QUERETARO', 'b_activo' => 1],
            ['id_estado_republica' => 23, 's_estado_republica' => 'QUINTANA ROO', 'b_activo' => 1],
            ['id_estado_republica' => 24, 's_estado_republica' => 'SAN LUIS POTOSI', 'b_activo' => 1],
            ['id_estado_republica' => 25, 's_estado_republica' => 'SINALOA', 'b_activo' => 1],
            ['id_estado_republica' => 26, 's_estado_republica' => 'SONORA', 'b_activo' => 1],
            ['id_estado_republica' => 27, 's_estado_republica' => 'TABASCO', 'b_activo' => 1],
            ['id_estado_republica' => 28, 's_estado_republica' => 'TAMAULIPAS', 'b_activo' => 1],
            ['id_estado_republica' => 29, 's_estado_republica' => 'TLAXCALA', 'b_activo' => 1],
            ['id_estado_republica' => 30, 's_estado_republica' => 'VERACRUZ', 'b_activo' => 1],
            ['id_estado_republica' => 31, 's_estado_republica' => 'YUCATAN', 'b_activo' => 1],
            ['id_estado_republica' => 32, 's_estado_republica' => 'ZACATECAS', 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_estados_republica')->insert($chunk);
        }
    }
}
