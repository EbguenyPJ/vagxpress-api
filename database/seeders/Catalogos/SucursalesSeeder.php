<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tw_sucursales` (1 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class SucursalesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tw_sucursales')->truncate();

        $rows = [
            ['id_sucursal' => 1, 's_sucursal' => 'Matriz', 's_razon_social' => 'EKDE3DD', 's_representante_legal' => 'Kenia', 's_rfc' => 'RCHSEKSEWS', 'n_telefono' => '244183526', 's_correo' => 'matriz12@gmail.com', 's_latitud' => '12.344', 's_longitud' => '-12.3443', 's_direccion' => 'puebla', 's_colonia' => 'puebla', 's_codigo_postal' => '12345', 's_logo' => null, 's_firma' => null, 'id_estado_republica' => 1, 'id_municipio' => 1, 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tw_sucursales')->insert($chunk);
        }
    }
}
