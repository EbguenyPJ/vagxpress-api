<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_metodos_pagos` (5 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class MetodosPagosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_metodos_pagos')->truncate();

        $rows = [
            ['id_metodo_pago' => 1, 's_metodo_pago' => 'Credito', 's_img_metodo_pago' => 'https://cdn-icons-png.flaticon.com/512/5550/5550530.png', 's_descripcion_metodo_pago' => null, 'b_requiere_referencia' => 0, 'b_requiere_evidencia' => 0, 'created_at' => null, 'updated_at' => null, 'b_activo' => 1],
            ['id_metodo_pago' => 2, 's_metodo_pago' => 'Efectivo', 's_img_metodo_pago' => 'https://cdn-icons-png.freepik.com/512/8993/8993556.png', 's_descripcion_metodo_pago' => null, 'b_requiere_referencia' => 0, 'b_requiere_evidencia' => 0, 'created_at' => null, 'updated_at' => null, 'b_activo' => 1],
            ['id_metodo_pago' => 3, 's_metodo_pago' => 'Tarjeta Credito', 's_img_metodo_pago' => 'https://png.pngtree.com/png-clipart/20250429/original/pngtree-blue-credit-card-payment-icon-on-black-background-vector-png-image_20894040.png', 's_descripcion_metodo_pago' => null, 'b_requiere_referencia' => 0, 'b_requiere_evidencia' => 0, 'created_at' => null, 'updated_at' => null, 'b_activo' => 1],
            ['id_metodo_pago' => 4, 's_metodo_pago' => 'Tarjeta Debito', 's_img_metodo_pago' => 'https://static.vecteezy.com/system/resources/previews/026/792/182/non_2x/credit-card-vector-flat-icon-online-payment-credit-debit-card-cash-withdrawal-credit-card-minimal-style-financial-operations-png.png', 's_descripcion_metodo_pago' => null, 'b_requiere_referencia' => 0, 'b_requiere_evidencia' => 0, 'created_at' => null, 'updated_at' => null, 'b_activo' => 1],
            ['id_metodo_pago' => 5, 's_metodo_pago' => 'Transferencia', 's_img_metodo_pago' => 'https://cdn-icons-png.flaticon.com/512/8043/8043733.png', 's_descripcion_metodo_pago' => null, 'b_requiere_referencia' => 0, 'b_requiere_evidencia' => 0, 'created_at' => null, 'updated_at' => null, 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_metodos_pagos')->insert($chunk);
        }
    }
}
