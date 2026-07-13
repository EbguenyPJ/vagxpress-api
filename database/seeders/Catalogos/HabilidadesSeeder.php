<?php

namespace Database\Seeders\Catalogos;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de catálogo de `tc_habilidades` (40 filas, réplica exacta del entorno de referencia).
 * Generado automáticamente — no editar a mano: regenerar desde la fuente si cambia.
 */
class HabilidadesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tc_habilidades')->truncate();

        $rows = [
            ['id_habilidad' => 1, 'id_tipo_empleado' => 1, 's_habilidad_empleado' => 'Comunicacion asertiva con clientes', 'b_activo' => 1],
            ['id_habilidad' => 2, 'id_tipo_empleado' => 1, 's_habilidad_empleado' => 'Conocimiento detallado de productos/servicios', 'b_activo' => 1],
            ['id_habilidad' => 3, 'id_tipo_empleado' => 1, 's_habilidad_empleado' => 'Manejo de objeciones y reclamos', 'b_activo' => 1],
            ['id_habilidad' => 4, 'id_tipo_empleado' => 1, 's_habilidad_empleado' => 'Tecnicas de escucha activa', 'b_activo' => 1],
            ['id_habilidad' => 5, 'id_tipo_empleado' => 1, 's_habilidad_empleado' => 'Elaboracion de reportes de asesoria', 'b_activo' => 1],
            ['id_habilidad' => 6, 'id_tipo_empleado' => 2, 's_habilidad_empleado' => 'Liderazgo y motivacion de equipos', 'b_activo' => 1],
            ['id_habilidad' => 7, 'id_tipo_empleado' => 2, 's_habilidad_empleado' => 'Planificacion estrategica operativa', 'b_activo' => 1],
            ['id_habilidad' => 8, 'id_tipo_empleado' => 2, 's_habilidad_empleado' => 'Analisis financiero basico', 'b_activo' => 1],
            ['id_habilidad' => 9, 'id_tipo_empleado' => 2, 's_habilidad_empleado' => 'Toma de decisiones bajo presion', 'b_activo' => 1],
            ['id_habilidad' => 10, 'id_tipo_empleado' => 2, 's_habilidad_empleado' => 'Gestion de conflictos laborales', 'b_activo' => 1],
            ['id_habilidad' => 11, 'id_tipo_empleado' => 3, 's_habilidad_empleado' => 'Supervision de procesos tecnicos', 'b_activo' => 1],
            ['id_habilidad' => 12, 'id_tipo_empleado' => 3, 's_habilidad_empleado' => 'Optimizacion de recursos en taller', 'b_activo' => 1],
            ['id_habilidad' => 13, 'id_tipo_empleado' => 3, 's_habilidad_empleado' => 'Conocimiento de normas de seguridad', 'b_activo' => 1],
            ['id_habilidad' => 14, 'id_tipo_empleado' => 3, 's_habilidad_empleado' => 'Programacion de mantenimientos', 'b_activo' => 1],
            ['id_habilidad' => 15, 'id_tipo_empleado' => 3, 's_habilidad_empleado' => 'Control de calidad en reparaciones', 'b_activo' => 1],
            ['id_habilidad' => 16, 'id_tipo_empleado' => 4, 's_habilidad_empleado' => 'Gestion de inventario automatizado', 'b_activo' => 1],
            ['id_habilidad' => 17, 'id_tipo_empleado' => 4, 's_habilidad_empleado' => 'Identificacion rapida de partes', 'b_activo' => 1],
            ['id_habilidad' => 18, 'id_tipo_empleado' => 4, 's_habilidad_empleado' => 'Manejo de sistemas de pedidos', 'b_activo' => 1],
            ['id_habilidad' => 19, 'id_tipo_empleado' => 4, 's_habilidad_empleado' => 'Clasificacion de refacciones', 'b_activo' => 1],
            ['id_habilidad' => 20, 'id_tipo_empleado' => 4, 's_habilidad_empleado' => 'Atencion a proveedores', 'b_activo' => 1],
            ['id_habilidad' => 21, 'id_tipo_empleado' => 5, 's_habilidad_empleado' => 'Monitoreo de indicadores clave', 'b_activo' => 1],
            ['id_habilidad' => 22, 'id_tipo_empleado' => 5, 's_habilidad_empleado' => 'Seguimiento de garantias', 'b_activo' => 1],
            ['id_habilidad' => 23, 'id_tipo_empleado' => 5, 's_habilidad_empleado' => 'Actualizacion de bases de datos', 'b_activo' => 1],
            ['id_habilidad' => 24, 'id_tipo_empleado' => 5, 's_habilidad_empleado' => 'Coordinacion interdepartamental', 'b_activo' => 1],
            ['id_habilidad' => 25, 'id_tipo_empleado' => 5, 's_habilidad_empleado' => 'Generacion de reportes ejecutivos', 'b_activo' => 1],
            ['id_habilidad' => 26, 'id_tipo_empleado' => 6, 's_habilidad_empleado' => 'Administracion de redes', 'b_activo' => 1],
            ['id_habilidad' => 27, 'id_tipo_empleado' => 6, 's_habilidad_empleado' => 'Soporte tecnico nivel 1 y 2', 'b_activo' => 1],
            ['id_habilidad' => 28, 'id_tipo_empleado' => 6, 's_habilidad_empleado' => 'Mantenimiento preventivo de equipos', 'b_activo' => 1],
            ['id_habilidad' => 29, 'id_tipo_empleado' => 6, 's_habilidad_empleado' => 'Configuracion de software', 'b_activo' => 1],
            ['id_habilidad' => 30, 'id_tipo_empleado' => 6, 's_habilidad_empleado' => 'Resolucion de incidencias IT', 'b_activo' => 1],
            ['id_habilidad' => 31, 'id_tipo_empleado' => 7, 's_habilidad_empleado' => 'Diagnostico preciso de fallas', 'b_activo' => 1],
            ['id_habilidad' => 32, 'id_tipo_empleado' => 7, 's_habilidad_empleado' => 'Manejo de equipos de medicion', 'b_activo' => 1],
            ['id_habilidad' => 33, 'id_tipo_empleado' => 7, 's_habilidad_empleado' => 'Interpretacion de manuales tecnicos', 'b_activo' => 1],
            ['id_habilidad' => 34, 'id_tipo_empleado' => 7, 's_habilidad_empleado' => 'Soldadura y reparacion fisica', 'b_activo' => 1],
            ['id_habilidad' => 35, 'id_tipo_empleado' => 7, 's_habilidad_empleado' => 'Actualizacion tecnica constante', 'b_activo' => 1],
            ['id_habilidad' => 36, 'id_tipo_empleado' => 8, 's_habilidad_empleado' => 'Tecnicas avanzadas de venta', 'b_activo' => 1],
            ['id_habilidad' => 37, 'id_tipo_empleado' => 8, 's_habilidad_empleado' => 'Fidelizacion de clientes', 'b_activo' => 1],
            ['id_habilidad' => 38, 'id_tipo_empleado' => 8, 's_habilidad_empleado' => 'Manejo de plataformas CRM', 'b_activo' => 1],
            ['id_habilidad' => 39, 'id_tipo_empleado' => 8, 's_habilidad_empleado' => 'Negociacion comercial', 'b_activo' => 1],
            ['id_habilidad' => 40, 'id_tipo_empleado' => 8, 's_habilidad_empleado' => 'Analisis de mercado', 'b_activo' => 1],
        ];

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('tc_habilidades')->insert($chunk);
        }
    }
}
