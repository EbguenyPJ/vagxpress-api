<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_cuentas_bancarias` (6 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class CuentasBancariasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_cuentas_bancarias')->truncate();

        $rows = [
            ['id_cuenta_bancaria' => 1, 's_nombre_cuenta' => 'Santander', 'n_numero_cuenta' => 1234567890, 'n_numero_tarjeta' => null, 'n_CLABE' => null, 'id_metodo_pago' => 3, 'id_tipo_cuenta' => 1, 'id_banco' => 1, 'b_activo' => '1'],
            ['id_cuenta_bancaria' => 2, 's_nombre_cuenta' => 'BBVA', 'n_numero_cuenta' => 987654321, 'n_numero_tarjeta' => null, 'n_CLABE' => null, 'id_metodo_pago' => 3, 'id_tipo_cuenta' => 1, 'id_banco' => 2, 'b_activo' => '1'],
            ['id_cuenta_bancaria' => 3, 's_nombre_cuenta' => 'Santander', 'n_numero_cuenta' => 2468013579, 'n_numero_tarjeta' => null, 'n_CLABE' => null, 'id_metodo_pago' => 4, 'id_tipo_cuenta' => 2, 'id_banco' => 1, 'b_activo' => '1'],
            ['id_cuenta_bancaria' => 4, 's_nombre_cuenta' => 'BBVA', 'n_numero_cuenta' => 1357924680, 'n_numero_tarjeta' => null, 'n_CLABE' => null, 'id_metodo_pago' => 4, 'id_tipo_cuenta' => 2, 'id_banco' => 2, 'b_activo' => '1'],
            ['id_cuenta_bancaria' => 5, 's_nombre_cuenta' => 'Santander', 'n_numero_cuenta' => 2468013579, 'n_numero_tarjeta' => null, 'n_CLABE' => null, 'id_metodo_pago' => 5, 'id_tipo_cuenta' => 2, 'id_banco' => 1, 'b_activo' => '1'],
            ['id_cuenta_bancaria' => 6, 's_nombre_cuenta' => 'BBVA', 'n_numero_cuenta' => 1357924680, 'n_numero_tarjeta' => null, 'n_CLABE' => null, 'id_metodo_pago' => 5, 'id_tipo_cuenta' => 2, 'id_banco' => 2, 'b_activo' => '1'],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_cuentas_bancarias')->insert($chunk);
        }
    }
}
