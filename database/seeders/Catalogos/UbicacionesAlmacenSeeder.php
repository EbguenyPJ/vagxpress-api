<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_ubicaciones_almacen` (15 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class UbicacionesAlmacenSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_ubicaciones_almacen')->truncate();

        $rows = [
            ['id_ubicacion_almacen' => 1, 's_ubicacion_almacen' => 'A-01-N1', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_ubicacion_almacen' => 2, 's_ubicacion_almacen' => 'A-01-N2', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_ubicacion_almacen' => 3, 's_ubicacion_almacen' => 'A-01-N3', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_ubicacion_almacen' => 4, 's_ubicacion_almacen' => 'A-02-N1', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_ubicacion_almacen' => 5, 's_ubicacion_almacen' => 'A-02-N2', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_ubicacion_almacen' => 6, 's_ubicacion_almacen' => 'A-02-N3', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_ubicacion_almacen' => 7, 's_ubicacion_almacen' => 'B-01-N1', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_ubicacion_almacen' => 8, 's_ubicacion_almacen' => 'B-01-N2', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_ubicacion_almacen' => 9, 's_ubicacion_almacen' => 'B-01-N3', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_ubicacion_almacen' => 10, 's_ubicacion_almacen' => 'B-02-N1', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_ubicacion_almacen' => 11, 's_ubicacion_almacen' => 'B-02-N2', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_ubicacion_almacen' => 12, 's_ubicacion_almacen' => 'B-02-N3', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_ubicacion_almacen' => 13, 's_ubicacion_almacen' => 'Mostrador', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_ubicacion_almacen' => 14, 's_ubicacion_almacen' => 'Bodega Trasera', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_ubicacion_almacen' => 15, 's_ubicacion_almacen' => 'Zona de Recepción', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_ubicaciones_almacen')->insert($chunk);
        }
    }
}
