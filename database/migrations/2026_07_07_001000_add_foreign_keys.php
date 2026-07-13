<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tc_modulos', function (Blueprint $table) {
            $table->foreign('id_categoria_modulo')->references('id_categoria_modulo')->on('tc_categorias_modulos')->onDelete('restrict');
        });

        Schema::table('tc_tipos_configuraciones', function (Blueprint $table) {
            $table->foreign('id_modulo')->references('id_modulo')->on('tc_modulos')->onDelete('restrict');
        });

        Schema::table('tc_porcentajes_utilidad', function (Blueprint $table) {
            $table->foreign('id_tipo_configuracion')->references('id_tipo_configuracion')->on('tc_tipos_configuraciones')->onDelete('restrict');
        });

        Schema::table('tc_descripciones_tipos_empleados', function (Blueprint $table) {
            $table->foreign('id_tipo_empleado')->references('id_tipo_empleado')->on('tc_tipos_empleados')->onDelete('restrict');
        });

        Schema::table('tc_habilidades', function (Blueprint $table) {
            $table->foreign('id_tipo_empleado')->references('id_tipo_empleado')->on('tc_tipos_empleados')->onDelete('restrict');
        });

        Schema::table('tc_municipios', function (Blueprint $table) {
            $table->foreign('id_estado_republica')->references('id_estado_republica')->on('tc_estados_republica')->onDelete('restrict');
        });

        Schema::table('tc_subcategorias_refacciones', function (Blueprint $table) {
            $table->foreign('id_categoria_refaccion')->references('id_categoria_refaccion')->on('tc_categorias_refacciones')->onDelete('restrict');
        });

        Schema::table('tc_cuentas_bancarias', function (Blueprint $table) {
            $table->foreign('id_metodo_pago')->references('id_metodo_pago')->on('tc_metodos_pagos')->onDelete('restrict');
            $table->foreign('id_tipo_cuenta')->references('id_tipo_cuenta')->on('tc_tipos_cuentas')->onDelete('restrict');
            $table->foreign('id_banco')->references('id_banco')->on('tc_bancos')->onDelete('restrict');
        });

        Schema::table('tc_tipos_gastos', function (Blueprint $table) {
            $table->foreign('id_categoria_gasto')->references('id_categoria_gasto')->on('tc_categorias_gastos')->onDelete('restrict');
        });

        Schema::table('tc_modelos_vehiculos', function (Blueprint $table) {
            $table->foreign('id_marca_vehiculo')->references('id_marca_vehiculo')->on('tc_marcas_vehiculos')->onDelete('restrict');
        });

        Schema::table('tc_generaciones', function (Blueprint $table) {
            $table->foreign('id_modelo_vehiculo')->references('id_modelo_vehiculo')->on('tc_modelos_vehiculos')->onDelete('restrict');
        });

        Schema::table('tw_sucursales', function (Blueprint $table) {
            $table->foreign('id_estado_republica')->references('id_estado_republica')->on('tc_estados_republica')->onDelete('restrict');
            $table->foreign('id_municipio')->references('id_municipio')->on('tc_municipios')->onDelete('restrict');
        });

        Schema::table('tw_empleados', function (Blueprint $table) {
            $table->foreign('id_tipo_empleado')->references('id_tipo_empleado')->on('tc_tipos_empleados')->onDelete('restrict');
            $table->foreign('id_profesion')->references('id_profesion')->on('tc_profesiones')->onDelete('restrict');
            $table->foreign('id_grado_estudios')->references('id_grado_estudios')->on('tc_grados_estudios')->onDelete('restrict');
            $table->foreign('id_sucursal')->references('id_sucursal')->on('tw_sucursales')->onDelete('restrict');
            $table->foreign('id_estado_disponibilidad')->references('id_estado_disponibilidad')->on('tc_estados_disponibilidad')->onDelete('restrict');
        });

        Schema::table('tr_habilidades_empleados', function (Blueprint $table) {
            $table->foreign('id_habilidad')->references('id_habilidad')->on('tc_habilidades')->onDelete('cascade');
            $table->foreign('id_empleado')->references('id_empleado')->on('tw_empleados')->onDelete('cascade');
        });

        Schema::table('tr_modulos_usuarios', function (Blueprint $table) {
            $table->foreign('id_modulo')->references('id_modulo')->on('tc_modulos')->onDelete('cascade');
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('tw_versiones', function (Blueprint $table) {
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('restrict');
        });

        Schema::table('tw_equivalencias', function (Blueprint $table) {
            $table->foreign('id_usuario_crea')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('id_usuario_edita')->references('id')->on('users')->onDelete('restrict');
        });

        Schema::table('tw_refacciones', function (Blueprint $table) {
            $table->foreign('id_marca_refaccion')->references('id_marca_refaccion')->on('tc_marcas_refacciones')->onDelete('restrict');
            $table->foreign('id_unidad_medida')->references('id_unidad_medida')->on('tc_unidades_medida')->onDelete('restrict');
            $table->foreign('id_proveedor')->references('id_proveedor')->on('tw_proveedores')->onDelete('restrict');
            $table->foreign('id_clase_refaccion')->references('id_clase_refaccion')->on('tc_clases_refacciones')->onDelete('restrict');
            $table->foreign('id_categoria_refaccion')->references('id_categoria_refaccion')->on('tc_categorias_refacciones')->onDelete('restrict');
            $table->foreign('id_subcategoria_refaccion')->references('id_subcategoria_refaccion')->on('tc_subcategorias_refacciones')->onDelete('restrict');
            $table->foreign('id_posicion_vehiculo')->references('id_posicion_vehiculo')->on('tc_posiciones_vehiculo')->onDelete('restrict');
            $table->foreign('id_ubicacion_almacen')->references('id_ubicacion_almacen')->on('tc_ubicaciones_almacen')->onDelete('restrict');
            $table->foreign('id_estatus_refaccion')->references('id_estatus_refaccion')->on('tc_estatus_refacciones')->onDelete('restrict');
            $table->foreign('id_usuario_crea')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('id_usuario_edita')->references('id')->on('users')->onDelete('restrict');
        });

        Schema::table('tr_refacciones_equivalencias', function (Blueprint $table) {
            $table->foreign('id_refaccion')->references('id_refaccion')->on('tw_refacciones')->onDelete('cascade');
            $table->foreign('id_equivalencia')->references('id_equivalencia')->on('tw_equivalencias')->onDelete('cascade');
            $table->foreign('id_usuario_crea')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_usuario_edita')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('tr_proveedores_refacciones', function (Blueprint $table) {
            $table->foreign('id_proveedor')->references('id_proveedor')->on('tw_proveedores')->onDelete('cascade');
            $table->foreign('id_refaccion')->references('id_refaccion')->on('tw_refacciones')->onDelete('cascade');
            $table->foreign('id_usuario_crea')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_usuario_edita')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('tw_pre_registro_refacciones', function (Blueprint $table) {
            $table->foreign('id_marca_refaccion')->references('id_marca_refaccion')->on('tc_marcas_refacciones')->onDelete('restrict');
            $table->foreign('id_categoria_refaccion')->references('id_categoria_refaccion')->on('tc_categorias_refacciones')->onDelete('restrict');
            $table->foreign('id_subcategoria_refaccion')->references('id_subcategoria_refaccion')->on('tc_subcategorias_refacciones')->onDelete('restrict');
            $table->foreign('id_clase_refaccion')->references('id_clase_refaccion')->on('tc_clases_refacciones')->onDelete('restrict');
            $table->foreign('id_usuario_crea')->references('id')->on('users')->onDelete('restrict');
        });

        Schema::table('tw_clientes', function (Blueprint $table) {
            $table->foreign('id_tipo_cliente')->references('id_tipo_cliente')->on('tc_tipos_clientes')->onDelete('restrict');
            $table->foreign('id_usuario_crea')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('id_usuario_modifica')->references('id')->on('users')->onDelete('restrict');
        });

        Schema::table('tw_ventas', function (Blueprint $table) {
            $table->foreign('id_estatus_venta')->references('id_estatus_venta')->on('tc_estatus_ventas')->onDelete('restrict');
            $table->foreign('id_metodo_pago')->references('id_metodo_pago')->on('tc_metodos_pagos')->onDelete('restrict');
            $table->foreign('id_cliente')->references('id_cliente')->on('tw_clientes')->onDelete('restrict');
            $table->foreign('id_cuenta_bancaria')->references('id_cuenta_bancaria')->on('tc_cuentas_bancarias')->onDelete('restrict');
            $table->foreign('id_usuario_crea')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('id_usuario_modifica')->references('id')->on('users')->onDelete('restrict');
        });

        Schema::table('tr_ventas_refacciones', function (Blueprint $table) {
            $table->foreign('id_venta')->references('id_venta')->on('tw_ventas')->onDelete('cascade');
            $table->foreign('id_refaccion')->references('id_refaccion')->on('tw_refacciones')->onDelete('cascade');
        });

        Schema::table('tw_creditos', function (Blueprint $table) {
            $table->foreign('id_venta')->references('id_venta')->on('tw_ventas')->onDelete('restrict');
            $table->foreign('id_tipo_credito')->references('id_tipo_credito')->on('tc_tipos_creditos')->onDelete('restrict');
            $table->foreign('id_estatus_credito')->references('id_estatus_credito')->on('tc_estatus_creditos')->onDelete('restrict');
            $table->foreign('id_usuario_crea')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('id_usuario_modifica')->references('id')->on('users')->onDelete('restrict');
        });

        Schema::table('tw_abonos', function (Blueprint $table) {
            $table->foreign('id_credito')->references('id_credito')->on('tw_creditos')->onDelete('restrict');
            $table->foreign('id_metodo_pago')->references('id_metodo_pago')->on('tc_metodos_pagos')->onDelete('restrict');
            $table->foreign('id_usuario_crea')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('id_usuario_modifica')->references('id')->on('users')->onDelete('restrict');
        });

        Schema::table('tw_cortes', function (Blueprint $table) {
            $table->foreign('id_usuario_crea')->references('id')->on('users')->onDelete('restrict');
        });

        Schema::table('tr_cortes_ventas', function (Blueprint $table) {
            $table->foreign('id_corte')->references('id_corte')->on('tw_cortes')->onDelete('cascade');
            $table->foreign('id_venta')->references('id_venta')->on('tw_ventas')->onDelete('cascade');
        });

        Schema::table('tw_cortes_evidencias', function (Blueprint $table) {
            $table->foreign('id_corte')->references('id_corte')->on('tw_cortes')->onDelete('restrict');
            $table->foreign('id_metodo_pago')->references('id_metodo_pago')->on('tc_metodos_pagos')->onDelete('restrict');
            $table->foreign('id_tipo_evidencia')->references('id_tipo_evidencia')->on('tc_tipos_evidencias')->onDelete('restrict');
        });

        Schema::table('tw_cotizaciones', function (Blueprint $table) {
            $table->foreign('id_estatus_cotizacion')->references('id_estatus_cotizacion')->on('tc_estatus_cotizaciones')->onDelete('restrict');
            $table->foreign('id_tipo_cotizacion')->references('id_tipo_cotizacion')->on('tc_tipos_cotizaciones')->onDelete('restrict');
            $table->foreign('id_cliente')->references('id_cliente')->on('tw_clientes')->onDelete('restrict');
            $table->foreign('id_usuario_crea')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('id_usuario_modifica')->references('id')->on('users')->onDelete('restrict');
        });

        Schema::table('tr_cotizaciones_refacciones', function (Blueprint $table) {
            $table->foreign('id_cotizacion')->references('id_cotizacion')->on('tw_cotizaciones')->onDelete('cascade');
            $table->foreign('id_refaccion')->references('id_refaccion')->on('tw_refacciones')->onDelete('cascade');
        });

        Schema::table('tw_requisiciones', function (Blueprint $table) {
            $table->foreign('id_estatus_requisicion')->references('id_estatus_requisicion')->on('tc_estatus_requisiciones')->onDelete('restrict');
            $table->foreign('id_tipo_requisicion')->references('id_tipo_requisicion')->on('tc_tipos_requisiciones')->onDelete('restrict');
            $table->foreign('id_usuario_crea')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('id_usuario_modifica')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('id_usuario_autoriza')->references('id')->on('users')->onDelete('restrict');
        });

        Schema::table('tr_requisiciones_refacciones', function (Blueprint $table) {
            $table->foreign('id_requisicion')->references('id_requisicion')->on('tw_requisiciones')->onDelete('cascade');
            $table->foreign('id_refaccion')->references('id_refaccion')->on('tw_refacciones')->onDelete('cascade');
            $table->foreign('id_motivo_pedido')->references('id_motivo_pedido')->on('tc_motivos_pedidos')->onDelete('cascade');
            $table->foreign('id_prioridad')->references('id_prioridad')->on('tc_prioridades')->onDelete('cascade');
            $table->foreign('id_estatus_requisicion')->references('id_estatus_requisicion')->on('tc_estatus_requisiciones')->onDelete('cascade');
        });

        Schema::table('tw_ordenes_compras', function (Blueprint $table) {
            $table->foreign('id_proveedor')->references('id_proveedor')->on('tw_proveedores')->onDelete('restrict');
            $table->foreign('id_requisicion')->references('id_requisicion')->on('tw_requisiciones')->onDelete('restrict');
            $table->foreign('id_estatus_orden_compra')->references('id_estatus_orden_compra')->on('tc_estatus_ordenes_compras')->onDelete('restrict');
            $table->foreign('id_usuario_crea')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('id_usuario_modifica')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('id_usuario_autoriza')->references('id')->on('users')->onDelete('restrict');
        });

        Schema::table('tr_ordenes_compras_requisiciones_refacciones', function (Blueprint $table) {
            $table->foreign('id_orden_compra', 'fk_tr_ocrr_orden_compra')->references('id_orden_compra')->on('tw_ordenes_compras')->onDelete('cascade');
            $table->foreign('id_requisicion_refaccion', 'fk_tr_ocrr_requisicion_refaccion')->references('id_requisicion_refaccion')->on('tr_requisiciones_refacciones')->onDelete('cascade');
        });

        Schema::table('tw_embarques', function (Blueprint $table) {
            $table->foreign('id_proveedor')->references('id_proveedor')->on('tw_proveedores')->onDelete('restrict');
            $table->foreign('id_usuario_crea')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('id_estatus_embarque')->references('id_estatus_embarque')->on('tc_estatus_embarque')->onDelete('restrict');
        });

        Schema::table('tw_evidencias_embarque', function (Blueprint $table) {
            $table->foreign('id_embarque')->references('id_embarque')->on('tw_embarques')->onDelete('restrict');
            $table->foreign('id_tipo_evidencia')->references('id_tipo_evidencia')->on('tc_tipos_evidencias')->onDelete('restrict');
        });

        Schema::table('tr_entradas_embarque', function (Blueprint $table) {
            $table->foreign('id_embarque')->references('id_embarque')->on('tw_embarques')->onDelete('cascade');
            $table->foreign('id_refaccion')->references('id_refaccion')->on('tw_refacciones')->onDelete('cascade');
            $table->foreign('id_pre_registro_refaccion')->references('id_pre_registro_refaccion')->on('tw_pre_registro_refacciones')->onDelete('cascade');
            $table->foreign('id_estatus_entrada')->references('id_estatus_entrada')->on('tc_estatus_entrada')->onDelete('cascade');
        });

        Schema::table('tw_gastos', function (Blueprint $table) {
            $table->foreign('id_tipo_gasto')->references('id_tipo_gasto')->on('tc_tipos_gastos')->onDelete('restrict');
            $table->foreign('id_tipo_evidencia')->references('id_tipo_evidencia')->on('tc_tipos_evidencias')->onDelete('restrict');
            $table->foreign('id_sucursal')->references('id_sucursal')->on('tw_sucursales')->onDelete('restrict');
            $table->foreign('id_usuario_crea')->references('id')->on('users')->onDelete('restrict');
        });

        Schema::table('tw_destinos', function (Blueprint $table) {
            $table->foreign('id_tipo_destino')->references('id_tipo_destino')->on('tc_tipos_destinos')->onDelete('restrict');
        });

        Schema::table('tw_ordenes', function (Blueprint $table) {
            $table->foreign('id_destino')->references('id_destino')->on('tw_destinos')->onDelete('restrict');
            $table->foreign('id_repartidor')->references('id_empleado')->on('tw_empleados')->onDelete('restrict');
            $table->foreign('id_estatus_orden')->references('id_estatus_orden')->on('tc_estatus_orden')->onDelete('restrict');
        });

        Schema::table('tr_ordenes_productos', function (Blueprint $table) {
            $table->foreign('id_orden')->references('id_orden')->on('tw_ordenes')->onDelete('cascade');
        });

        Schema::table('tw_evidencias_orden', function (Blueprint $table) {
            $table->foreign('id_orden')->references('id_orden')->on('tw_ordenes')->onDelete('restrict');
            $table->foreign('id_tipo_evidencia')->references('id_tipo_evidencia')->on('tc_tipos_evidencias')->onDelete('restrict');
        });

        Schema::table('tw_puntos_ruta', function (Blueprint $table) {
            $table->foreign('id_orden')->references('id_orden')->on('tw_ordenes')->onDelete('restrict');
            $table->foreign('id_tipo_ruta')->references('id_tipo_ruta')->on('tc_tipo_ruta')->onDelete('restrict');
        });

        Schema::table('tw_reglas_compatibilidad', function (Blueprint $table) {
            $table->foreign('id_refaccion')->references('id_refaccion')->on('tw_refacciones')->onDelete('restrict');
            $table->foreign('id_usuario_crea')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('id_usuario_edita')->references('id')->on('users')->onDelete('restrict');
        });

        Schema::table('tr_reglas_marcas', function (Blueprint $table) {
            $table->foreign('id_regla')->references('id_regla')->on('tw_reglas_compatibilidad')->onDelete('cascade');
            $table->foreign('id_marca_vehiculo')->references('id_marca_vehiculo')->on('tc_marcas_vehiculos')->onDelete('cascade');
        });

        Schema::table('tr_reglas_modelos', function (Blueprint $table) {
            $table->foreign('id_regla')->references('id_regla')->on('tw_reglas_compatibilidad')->onDelete('cascade');
            $table->foreign('id_modelo_vehiculo')->references('id_modelo_vehiculo')->on('tc_modelos_vehiculos')->onDelete('cascade');
        });

        Schema::table('tr_reglas_generaciones', function (Blueprint $table) {
            $table->foreign('id_regla')->references('id_regla')->on('tw_reglas_compatibilidad')->onDelete('cascade');
            $table->foreign('id_generacion')->references('id_generacion')->on('tc_generaciones')->onDelete('cascade');
        });

        Schema::table('tr_reglas_motores', function (Blueprint $table) {
            $table->foreign('id_regla')->references('id_regla')->on('tw_reglas_compatibilidad')->onDelete('cascade');
            $table->foreign('id_motor')->references('id_motor')->on('tc_motores')->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('id_empleado')->references('id_empleado')->on('tw_empleados')->onDelete('restrict');
            $table->foreign('id_tipo_usuario')->references('id_tipo_usuario')->on('tc_tipos_usuarios')->onDelete('restrict');
        });

    }

    public function down(): void
    {
        Schema::table('tc_modulos', function (Blueprint $table) {
            $table->dropForeign(['id_categoria_modulo']);
        });

        Schema::table('tc_tipos_configuraciones', function (Blueprint $table) {
            $table->dropForeign(['id_modulo']);
        });

        Schema::table('tc_porcentajes_utilidad', function (Blueprint $table) {
            $table->dropForeign(['id_tipo_configuracion']);
        });

        Schema::table('tc_descripciones_tipos_empleados', function (Blueprint $table) {
            $table->dropForeign(['id_tipo_empleado']);
        });

        Schema::table('tc_habilidades', function (Blueprint $table) {
            $table->dropForeign(['id_tipo_empleado']);
        });

        Schema::table('tc_municipios', function (Blueprint $table) {
            $table->dropForeign(['id_estado_republica']);
        });

        Schema::table('tc_subcategorias_refacciones', function (Blueprint $table) {
            $table->dropForeign(['id_categoria_refaccion']);
        });

        Schema::table('tc_cuentas_bancarias', function (Blueprint $table) {
            $table->dropForeign(['id_metodo_pago']);
            $table->dropForeign(['id_tipo_cuenta']);
            $table->dropForeign(['id_banco']);
        });

        Schema::table('tc_tipos_gastos', function (Blueprint $table) {
            $table->dropForeign(['id_categoria_gasto']);
        });

        Schema::table('tc_modelos_vehiculos', function (Blueprint $table) {
            $table->dropForeign(['id_marca_vehiculo']);
        });

        Schema::table('tc_generaciones', function (Blueprint $table) {
            $table->dropForeign(['id_modelo_vehiculo']);
        });

        Schema::table('tw_sucursales', function (Blueprint $table) {
            $table->dropForeign(['id_estado_republica']);
            $table->dropForeign(['id_municipio']);
        });

        Schema::table('tw_empleados', function (Blueprint $table) {
            $table->dropForeign(['id_tipo_empleado']);
            $table->dropForeign(['id_profesion']);
            $table->dropForeign(['id_grado_estudios']);
            $table->dropForeign(['id_sucursal']);
            $table->dropForeign(['id_estado_disponibilidad']);
        });

        Schema::table('tr_habilidades_empleados', function (Blueprint $table) {
            $table->dropForeign(['id_habilidad']);
            $table->dropForeign(['id_empleado']);
        });

        Schema::table('tr_modulos_usuarios', function (Blueprint $table) {
            $table->dropForeign(['id_modulo']);
            $table->dropForeign(['id_usuario']);
        });

        Schema::table('tw_versiones', function (Blueprint $table) {
            $table->dropForeign(['id_usuario']);
        });

        Schema::table('tw_equivalencias', function (Blueprint $table) {
            $table->dropForeign(['id_usuario_crea']);
            $table->dropForeign(['id_usuario_edita']);
        });

        Schema::table('tw_refacciones', function (Blueprint $table) {
            $table->dropForeign(['id_marca_refaccion']);
            $table->dropForeign(['id_unidad_medida']);
            $table->dropForeign(['id_proveedor']);
            $table->dropForeign(['id_clase_refaccion']);
            $table->dropForeign(['id_categoria_refaccion']);
            $table->dropForeign(['id_subcategoria_refaccion']);
            $table->dropForeign(['id_posicion_vehiculo']);
            $table->dropForeign(['id_ubicacion_almacen']);
            $table->dropForeign(['id_estatus_refaccion']);
            $table->dropForeign(['id_usuario_crea']);
            $table->dropForeign(['id_usuario_edita']);
        });

        Schema::table('tr_refacciones_equivalencias', function (Blueprint $table) {
            $table->dropForeign(['id_refaccion']);
            $table->dropForeign(['id_equivalencia']);
            $table->dropForeign(['id_usuario_crea']);
            $table->dropForeign(['id_usuario_edita']);
        });

        Schema::table('tr_proveedores_refacciones', function (Blueprint $table) {
            $table->dropForeign(['id_proveedor']);
            $table->dropForeign(['id_refaccion']);
            $table->dropForeign(['id_usuario_crea']);
            $table->dropForeign(['id_usuario_edita']);
        });

        Schema::table('tw_pre_registro_refacciones', function (Blueprint $table) {
            $table->dropForeign(['id_marca_refaccion']);
            $table->dropForeign(['id_categoria_refaccion']);
            $table->dropForeign(['id_subcategoria_refaccion']);
            $table->dropForeign(['id_clase_refaccion']);
            $table->dropForeign(['id_usuario_crea']);
        });

        Schema::table('tw_clientes', function (Blueprint $table) {
            $table->dropForeign(['id_tipo_cliente']);
            $table->dropForeign(['id_usuario_crea']);
            $table->dropForeign(['id_usuario_modifica']);
        });

        Schema::table('tw_ventas', function (Blueprint $table) {
            $table->dropForeign(['id_estatus_venta']);
            $table->dropForeign(['id_metodo_pago']);
            $table->dropForeign(['id_cliente']);
            $table->dropForeign(['id_cuenta_bancaria']);
            $table->dropForeign(['id_usuario_crea']);
            $table->dropForeign(['id_usuario_modifica']);
        });

        Schema::table('tr_ventas_refacciones', function (Blueprint $table) {
            $table->dropForeign(['id_venta']);
            $table->dropForeign(['id_refaccion']);
        });

        Schema::table('tw_creditos', function (Blueprint $table) {
            $table->dropForeign(['id_venta']);
            $table->dropForeign(['id_tipo_credito']);
            $table->dropForeign(['id_estatus_credito']);
            $table->dropForeign(['id_usuario_crea']);
            $table->dropForeign(['id_usuario_modifica']);
        });

        Schema::table('tw_abonos', function (Blueprint $table) {
            $table->dropForeign(['id_credito']);
            $table->dropForeign(['id_metodo_pago']);
            $table->dropForeign(['id_usuario_crea']);
            $table->dropForeign(['id_usuario_modifica']);
        });

        Schema::table('tw_cortes', function (Blueprint $table) {
            $table->dropForeign(['id_usuario_crea']);
        });

        Schema::table('tr_cortes_ventas', function (Blueprint $table) {
            $table->dropForeign(['id_corte']);
            $table->dropForeign(['id_venta']);
        });

        Schema::table('tw_cortes_evidencias', function (Blueprint $table) {
            $table->dropForeign(['id_corte']);
            $table->dropForeign(['id_metodo_pago']);
            $table->dropForeign(['id_tipo_evidencia']);
        });

        Schema::table('tw_cotizaciones', function (Blueprint $table) {
            $table->dropForeign(['id_estatus_cotizacion']);
            $table->dropForeign(['id_tipo_cotizacion']);
            $table->dropForeign(['id_cliente']);
            $table->dropForeign(['id_usuario_crea']);
            $table->dropForeign(['id_usuario_modifica']);
        });

        Schema::table('tr_cotizaciones_refacciones', function (Blueprint $table) {
            $table->dropForeign(['id_cotizacion']);
            $table->dropForeign(['id_refaccion']);
        });

        Schema::table('tw_requisiciones', function (Blueprint $table) {
            $table->dropForeign(['id_estatus_requisicion']);
            $table->dropForeign(['id_tipo_requisicion']);
            $table->dropForeign(['id_usuario_crea']);
            $table->dropForeign(['id_usuario_modifica']);
            $table->dropForeign(['id_usuario_autoriza']);
        });

        Schema::table('tr_requisiciones_refacciones', function (Blueprint $table) {
            $table->dropForeign(['id_requisicion']);
            $table->dropForeign(['id_refaccion']);
            $table->dropForeign(['id_motivo_pedido']);
            $table->dropForeign(['id_prioridad']);
            $table->dropForeign(['id_estatus_requisicion']);
        });

        Schema::table('tw_ordenes_compras', function (Blueprint $table) {
            $table->dropForeign(['id_proveedor']);
            $table->dropForeign(['id_requisicion']);
            $table->dropForeign(['id_estatus_orden_compra']);
            $table->dropForeign(['id_usuario_crea']);
            $table->dropForeign(['id_usuario_modifica']);
            $table->dropForeign(['id_usuario_autoriza']);
        });

        Schema::table('tr_ordenes_compras_requisiciones_refacciones', function (Blueprint $table) {
            $table->dropForeign(['id_orden_compra']);
            $table->dropForeign(['id_requisicion_refaccion']);
        });

        Schema::table('tw_embarques', function (Blueprint $table) {
            $table->dropForeign(['id_proveedor']);
            $table->dropForeign(['id_usuario_crea']);
            $table->dropForeign(['id_estatus_embarque']);
        });

        Schema::table('tw_evidencias_embarque', function (Blueprint $table) {
            $table->dropForeign(['id_embarque']);
            $table->dropForeign(['id_tipo_evidencia']);
        });

        Schema::table('tr_entradas_embarque', function (Blueprint $table) {
            $table->dropForeign(['id_embarque']);
            $table->dropForeign(['id_refaccion']);
            $table->dropForeign(['id_pre_registro_refaccion']);
            $table->dropForeign(['id_estatus_entrada']);
        });

        Schema::table('tw_gastos', function (Blueprint $table) {
            $table->dropForeign(['id_tipo_gasto']);
            $table->dropForeign(['id_tipo_evidencia']);
            $table->dropForeign(['id_sucursal']);
            $table->dropForeign(['id_usuario_crea']);
        });

        Schema::table('tw_destinos', function (Blueprint $table) {
            $table->dropForeign(['id_tipo_destino']);
        });

        Schema::table('tw_ordenes', function (Blueprint $table) {
            $table->dropForeign(['id_destino']);
            $table->dropForeign(['id_repartidor']);
            $table->dropForeign(['id_estatus_orden']);
        });

        Schema::table('tr_ordenes_productos', function (Blueprint $table) {
            $table->dropForeign(['id_orden']);
        });

        Schema::table('tw_evidencias_orden', function (Blueprint $table) {
            $table->dropForeign(['id_orden']);
            $table->dropForeign(['id_tipo_evidencia']);
        });

        Schema::table('tw_puntos_ruta', function (Blueprint $table) {
            $table->dropForeign(['id_orden']);
            $table->dropForeign(['id_tipo_ruta']);
        });

        Schema::table('tw_reglas_compatibilidad', function (Blueprint $table) {
            $table->dropForeign(['id_refaccion']);
            $table->dropForeign(['id_usuario_crea']);
            $table->dropForeign(['id_usuario_edita']);
        });

        Schema::table('tr_reglas_marcas', function (Blueprint $table) {
            $table->dropForeign(['id_regla']);
            $table->dropForeign(['id_marca_vehiculo']);
        });

        Schema::table('tr_reglas_modelos', function (Blueprint $table) {
            $table->dropForeign(['id_regla']);
            $table->dropForeign(['id_modelo_vehiculo']);
        });

        Schema::table('tr_reglas_generaciones', function (Blueprint $table) {
            $table->dropForeign(['id_regla']);
            $table->dropForeign(['id_generacion']);
        });

        Schema::table('tr_reglas_motores', function (Blueprint $table) {
            $table->dropForeign(['id_regla']);
            $table->dropForeign(['id_motor']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_empleado']);
            $table->dropForeign(['id_tipo_usuario']);
        });

    }
};
