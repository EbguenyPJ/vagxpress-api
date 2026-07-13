<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_marcas_refacciones` (19 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class MarcasRefaccionesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_marcas_refacciones')->truncate();

        $rows = [
            ['id_marca_refaccion' => 1, 's_marca_refaccion' => 'Bosch', 's_img_marca_refaccion' => null, 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_marca_refaccion' => 2, 's_marca_refaccion' => 'ACDelco', 's_img_marca_refaccion' => null, 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_marca_refaccion' => 3, 's_marca_refaccion' => 'Motorcraft', 's_img_marca_refaccion' => null, 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_marca_refaccion' => 4, 's_marca_refaccion' => 'Dorman', 's_img_marca_refaccion' => null, 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_marca_refaccion' => 5, 's_marca_refaccion' => 'Gates', 's_img_marca_refaccion' => null, 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_marca_refaccion' => 6, 's_marca_refaccion' => 'MOOG', 's_img_marca_refaccion' => null, 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_marca_refaccion' => 7, 's_marca_refaccion' => 'KYB', 's_img_marca_refaccion' => null, 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_marca_refaccion' => 8, 's_marca_refaccion' => 'Wagner', 's_img_marca_refaccion' => null, 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_marca_refaccion' => 9, 's_marca_refaccion' => 'Fel-Pro', 's_img_marca_refaccion' => null, 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_marca_refaccion' => 10, 's_marca_refaccion' => 'NGK', 's_img_marca_refaccion' => null, 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_marca_refaccion' => 11, 's_marca_refaccion' => 'Denso', 's_img_marca_refaccion' => null, 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_marca_refaccion' => 12, 's_marca_refaccion' => 'Timken', 's_img_marca_refaccion' => null, 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_marca_refaccion' => 13, 's_marca_refaccion' => 'Standard Motor Products (SMP)', 's_img_marca_refaccion' => null, 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_marca_refaccion' => 14, 's_marca_refaccion' => 'Valeo', 's_img_marca_refaccion' => null, 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_marca_refaccion' => 15, 's_marca_refaccion' => 'LUK', 's_img_marca_refaccion' => null, 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_marca_refaccion' => 16, 's_marca_refaccion' => 'Ghoner', 's_img_marca_refaccion' => null, 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_marca_refaccion' => 17, 's_marca_refaccion' => 'Fram', 's_img_marca_refaccion' => null, 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_marca_refaccion' => 18, 's_marca_refaccion' => 'Interfil', 's_img_marca_refaccion' => null, 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_marca_refaccion' => 19, 's_marca_refaccion' => 'Mazda', 's_img_marca_refaccion' => null, 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_marcas_refacciones')->insert($chunk);
        }
    }
}
