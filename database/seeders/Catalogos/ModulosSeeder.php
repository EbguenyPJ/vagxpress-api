<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_modulos` (21 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class ModulosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_modulos')->truncate();

        $rows = [
            ['id_modulo' => 1, 'id_categoria_modulo' => 1, 's_modulo' => 'Dashboard', 's_ruta' => '/dashboard', 's_icono' => 'assignment', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_modulo' => 2, 'id_categoria_modulo' => 2, 's_modulo' => 'Punto de venta', 's_ruta' => '/punto-venta', 's_icono' => 'assignment', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_modulo' => 3, 'id_categoria_modulo' => 5, 's_modulo' => 'Clientes', 's_ruta' => '/clientes', 's_icono' => 'assignment', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_modulo' => 4, 'id_categoria_modulo' => 2, 's_modulo' => 'Cotizaciones', 's_ruta' => '/cotizaciones', 's_icono' => 'assignment', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_modulo' => 5, 'id_categoria_modulo' => 3, 's_modulo' => 'Catalogo de refacciones', 's_ruta' => '/refacciones', 's_icono' => 'assignment', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_modulo' => 6, 'id_categoria_modulo' => 3, 's_modulo' => 'Existencias', 's_ruta' => '/existencias', 's_icono' => 'assignment', 'b_activo' => 0, 'created_at' => null, 'updated_at' => null],
            ['id_modulo' => 7, 'id_categoria_modulo' => 3, 's_modulo' => 'Movimientos', 's_ruta' => '/movimientos', 's_icono' => 'assignment', 'b_activo' => 0, 'created_at' => null, 'updated_at' => null],
            ['id_modulo' => 8, 'id_categoria_modulo' => 3, 's_modulo' => 'Bitacora Movimientos', 's_ruta' => '/bitacora-movimientos', 's_icono' => 'assignment', 'b_activo' => 0, 'created_at' => null, 'updated_at' => null],
            ['id_modulo' => 9, 'id_categoria_modulo' => 3, 's_modulo' => 'Proveedores', 's_ruta' => '/proveedores', 's_icono' => 'assignment', 'b_activo' => 0, 'created_at' => null, 'updated_at' => null],
            ['id_modulo' => 10, 'id_categoria_modulo' => 4, 's_modulo' => 'Ordenes de compra', 's_ruta' => '/ordenes-compra', 's_icono' => 'assignment', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_modulo' => 11, 'id_categoria_modulo' => 3, 's_modulo' => 'Recepciones', 's_ruta' => '/recepciones', 's_icono' => 'assignment', 'b_activo' => 0, 'created_at' => null, 'updated_at' => null],
            ['id_modulo' => 12, 'id_categoria_modulo' => 6, 's_modulo' => 'Configuraciones', 's_ruta' => '/configuraciones', 's_icono' => 'assignment', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_modulo' => 13, 'id_categoria_modulo' => 5, 's_modulo' => 'Proveedores', 's_ruta' => '/proveedores', 's_icono' => 'assignment', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_modulo' => 14, 'id_categoria_modulo' => 2, 's_modulo' => 'Bitacora de ventas', 's_ruta' => '/bitacora-ventas', 's_icono' => 'assignment', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_modulo' => 15, 'id_categoria_modulo' => 2, 's_modulo' => 'Cortes', 's_ruta' => '/cortes', 's_icono' => 'assignment', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_modulo' => 16, 'id_categoria_modulo' => 2, 's_modulo' => 'Repartos', 's_ruta' => '/repartos', 's_icono' => 'assignment', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_modulo' => 17, 'id_categoria_modulo' => 3, 's_modulo' => 'Embarques', 's_ruta' => '/embarques', 's_icono' => 'assignment', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_modulo' => 18, 'id_categoria_modulo' => 3, 's_modulo' => 'Entradas', 's_ruta' => '/entradas', 's_icono' => 'assignment', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_modulo' => 19, 'id_categoria_modulo' => 5, 's_modulo' => 'Reportes', 's_ruta' => '/reportes', 's_icono' => 'assignment', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_modulo' => 20, 'id_categoria_modulo' => 4, 's_modulo' => 'Requisiciones', 's_ruta' => '/requisiciones', 's_icono' => 'assignment', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_modulo' => 21, 'id_categoria_modulo' => 5, 's_modulo' => 'Gastos', 's_ruta' => '/gastos', 's_icono' => 'assignment', 'b_activo' => 1, 'created_at' => null, 'updated_at' => null],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_modulos')->insert($chunk);
        }
    }
}
