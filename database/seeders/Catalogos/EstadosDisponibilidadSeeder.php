<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_estados_disponibilidad` (5 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class EstadosDisponibilidadSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_estados_disponibilidad')->truncate();

        $rows = [
            ['id_estado_disponibilidad' => 1, 's_estado_disponibilidad' => 'Disponible', 'b_activo' => 1],
            ['id_estado_disponibilidad' => 2, 's_estado_disponibilidad' => 'En servicio', 'b_activo' => 1],
            ['id_estado_disponibilidad' => 3, 's_estado_disponibilidad' => 'Incapacitado', 'b_activo' => 1],
            ['id_estado_disponibilidad' => 4, 's_estado_disponibilidad' => 'Permiso', 'b_activo' => 1],
            ['id_estado_disponibilidad' => 5, 's_estado_disponibilidad' => 'Vacaciones', 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_estados_disponibilidad')->insert($chunk);
        }
    }
}
