<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Catálogo vacío en el entorno de referencia pero requerido por las FKs:
 * la creación de cotizaciones asigna estatus 1 y tipo 1.
 */
class EstatusCotizacionesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_estatus_cotizaciones')->truncate();
        DB::table('tc_estatus_cotizaciones')->insert([
            ['id_estatus_cotizacion' => 1, 's_estatus_cotizacion' => 'Pendiente', 'b_activo' => 1],
            ['id_estatus_cotizacion' => 2, 's_estatus_cotizacion' => 'Aprobada', 'b_activo' => 1],
            ['id_estatus_cotizacion' => 3, 's_estatus_cotizacion' => 'Cancelada', 'b_activo' => 1],
        ]);

        DB::table('tc_tipos_cotizaciones')->truncate();
        DB::table('tc_tipos_cotizaciones')->insert([
            ['id_tipo_cotizacion' => 1, 's_tipo_cotizacion' => 'Manual', 'b_activo' => 1],
            ['id_tipo_cotizacion' => 2, 's_tipo_cotizacion' => 'Semiautomatizada', 'b_activo' => 1],
        ]);
    }
}
