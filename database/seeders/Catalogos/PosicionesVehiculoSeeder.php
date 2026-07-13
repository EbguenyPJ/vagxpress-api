<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_posiciones_vehiculo` (14 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class PosicionesVehiculoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_posiciones_vehiculo')->truncate();

        $rows = [
            ['id_posicion_vehiculo' => 1, 's_posicion_vehiculo' => 'No Aplica', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_posicion_vehiculo' => 2, 's_posicion_vehiculo' => 'Delantero', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_posicion_vehiculo' => 3, 's_posicion_vehiculo' => 'Trasero', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_posicion_vehiculo' => 4, 's_posicion_vehiculo' => 'Izquierdo (Lado Conductor)', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_posicion_vehiculo' => 5, 's_posicion_vehiculo' => 'Derecho (Lado Pasajero)', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_posicion_vehiculo' => 6, 's_posicion_vehiculo' => 'Delantero Izquierdo', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_posicion_vehiculo' => 7, 's_posicion_vehiculo' => 'Delantero Derecho', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_posicion_vehiculo' => 8, 's_posicion_vehiculo' => 'Trasero Izquierdo', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_posicion_vehiculo' => 9, 's_posicion_vehiculo' => 'Trasero Derecho', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_posicion_vehiculo' => 10, 's_posicion_vehiculo' => 'Superior', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_posicion_vehiculo' => 11, 's_posicion_vehiculo' => 'Inferior', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_posicion_vehiculo' => 12, 's_posicion_vehiculo' => 'Central', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_posicion_vehiculo' => 13, 's_posicion_vehiculo' => 'Interior', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_posicion_vehiculo' => 14, 's_posicion_vehiculo' => 'Exterior', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_posiciones_vehiculo')->insert($chunk);
        }
    }
}
