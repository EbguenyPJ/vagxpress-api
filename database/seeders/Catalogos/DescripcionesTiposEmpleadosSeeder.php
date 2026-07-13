<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_descripciones_tipos_empleados` (8 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class DescripcionesTiposEmpleadosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_descripciones_tipos_empleados')->truncate();

        $rows = [
            ['id_descripcion_tipo_empleado' => 1, 'id_tipo_empleado' => 1, 's_descripcion' => 'Profesional encargado de atender a los clientes, brindando orientación sobre servicios o productos, levantando Ordenes de trabajo y asegurando una atención de calidad durante todo el proceso.', 'b_activo' => 1],
            ['id_descripcion_tipo_empleado' => 2, 'id_tipo_empleado' => 2, 's_descripcion' => 'Responsable de la supervisión general de la sucursal o unidad de negocio. Administra recursos, coordina equipos de trabajo y toma decisiones estrategicas para el cumplimiento de objetivos operativos y comerciales.', 'b_activo' => 1],
            ['id_descripcion_tipo_empleado' => 3, 'id_tipo_empleado' => 3, 's_descripcion' => 'Encargado de coordinar y supervisar las actividades técnicas dentro del taller. Asigna trabajos a técnicos, verifica la calidad de los servicios realizados y asegura el cumplimiento de los tiempos de entrega.', 'b_activo' => 1],
            ['id_descripcion_tipo_empleado' => 4, 'id_tipo_empleado' => 4, 's_descripcion' => 'Especialista en la gestión de refacciones y autopartes. Administra inventarios, realiza pedidos y asegura la disponibilidad oportuna de materiales para la operación del taller.', 'b_activo' => 1],
            ['id_descripcion_tipo_empleado' => 5, 'id_tipo_empleado' => 5, 's_descripcion' => 'Personal encargado de dar continuidad a los procesos post-servicio, seguimiento a clientes, monitoreo de estatus de unidades y gestión de retroalimentación o quejas para mejorar la atención.', 'b_activo' => 1],
            ['id_descripcion_tipo_empleado' => 6, 'id_tipo_empleado' => 6, 's_descripcion' => 'Profesional en tecnologias de la información que brinda soporte tecnico, mantiene los sistemas operativos en funcionamiento y participa en el desarrollo o mantenimiento de soluciones digitales internas.', 'b_activo' => 1],
            ['id_descripcion_tipo_empleado' => 7, 'id_tipo_empleado' => 7, 's_descripcion' => 'Personal especializado en la ejecución de trabajos tecnicos, reparaciones y mantenimiento de equipos o vehículos, siguiendo los procedimientos establecidos y estandares de calidad.', 'b_activo' => 1],
            ['id_descripcion_tipo_empleado' => 8, 'id_tipo_empleado' => 8, 's_descripcion' => 'Personal responsable de la comercialización de productos y servicios, atención a clientes potenciales y cierre de ventas.', 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_descripciones_tipos_empleados')->insert($chunk);
        }
    }
}
