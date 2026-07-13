<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tc_categorias_modulos', function (Blueprint $table) {
            $table->id('id_categoria_modulo');
            $table->string('s_categoria_modulo', 255)->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tc_modulos', function (Blueprint $table) {
            $table->id('id_modulo');
            $table->unsignedBigInteger('id_categoria_modulo')->nullable();
            $table->string('s_modulo', 255)->nullable();
            $table->string('s_ruta', 255)->nullable();
            $table->string('s_icono', 255)->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tc_tipos_configuraciones', function (Blueprint $table) {
            $table->id('id_tipo_configuracion');
            $table->unsignedBigInteger('id_modulo')->nullable();
            $table->string('s_tipo_configuracion', 255)->nullable();
            $table->string('s_descripcion', 255)->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tc_configuraciones', function (Blueprint $table) {
            $table->id('id_configuracion');
            $table->string('s_configuracion', 255)->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tc_porcentajes_utilidad', function (Blueprint $table) {
            $table->id('id_porcentaje_utilidad');
            $table->unsignedBigInteger('id_tipo_configuracion')->nullable();
            $table->decimal('n_porcentaje_utilidad', 12, 7)->nullable();
            $table->string('s_porcentaje_utilidad', 255)->nullable();
            $table->string('s_descripcion', 255)->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tc_tipos_usuarios', function (Blueprint $table) {
            $table->id('id_tipo_usuario');
            $table->string('s_tipo_usuario', 255)->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tc_tipos_empleados', function (Blueprint $table) {
            $table->id('id_tipo_empleado');
            $table->string('s_tipo_empleado', 255)->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tc_descripciones_tipos_empleados', function (Blueprint $table) {
            $table->id('id_descripcion_tipo_empleado');
            $table->unsignedBigInteger('id_tipo_empleado');
            $table->text('s_descripcion');
            $table->boolean('b_activo');
        });

        Schema::create('tc_habilidades', function (Blueprint $table) {
            $table->id('id_habilidad');
            $table->unsignedBigInteger('id_tipo_empleado');
            $table->string('s_habilidad_empleado', 255);
            $table->boolean('b_activo');
        });

        Schema::create('tc_estados_disponibilidad', function (Blueprint $table) {
            $table->id('id_estado_disponibilidad');
            $table->string('s_estado_disponibilidad', 255);
            $table->boolean('b_activo');
        });

        Schema::create('tc_profesiones', function (Blueprint $table) {
            $table->id('id_profesion');
            $table->string('s_profesion', 255);
            $table->boolean('b_activo');
        });

        Schema::create('tc_grados_estudios', function (Blueprint $table) {
            $table->id('id_grado_estudios');
            $table->string('s_grado_estudios', 255);
            $table->boolean('b_activo');
        });

        Schema::create('tc_estados_republica', function (Blueprint $table) {
            $table->id('id_estado_republica');
            $table->string('s_estado_republica', 255);
            $table->boolean('b_activo');
        });

        Schema::create('tc_municipios', function (Blueprint $table) {
            $table->id('id_municipio');
            $table->string('s_municipio', 255);
            $table->unsignedBigInteger('id_estado_republica');
            $table->boolean('b_activo');
        });

        Schema::create('tc_marcas_refacciones', function (Blueprint $table) {
            $table->id('id_marca_refaccion');
            $table->string('s_marca_refaccion', 255)->nullable();
            $table->string('s_img_marca_refaccion', 255)->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tc_categorias_refacciones', function (Blueprint $table) {
            $table->id('id_categoria_refaccion');
            $table->string('s_categoria_refaccion', 255)->nullable();
            $table->string('s_img_categoria_refaccion', 255)->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tc_subcategorias_refacciones', function (Blueprint $table) {
            $table->id('id_subcategoria_refaccion');
            $table->unsignedBigInteger('id_categoria_refaccion')->nullable();
            $table->string('s_subcategoria_refaccion', 255)->nullable();
            $table->string('s_img_subcategoria_refaccion', 255)->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tc_clases_refacciones', function (Blueprint $table) {
            $table->id('id_clase_refaccion');
            $table->string('s_clase_refaccion', 255)->nullable();
            $table->string('s_color_clase_refaccion', 255)->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tc_estatus_refacciones', function (Blueprint $table) {
            $table->id('id_estatus_refaccion');
            $table->string('s_estatus_refaccion', 255)->nullable();
            $table->string('s_color_estatus_refaccion', 255)->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tc_unidades_medida', function (Blueprint $table) {
            $table->id('id_unidad_medida');
            $table->string('s_unidad_medida', 255)->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tc_posiciones_vehiculo', function (Blueprint $table) {
            $table->id('id_posicion_vehiculo');
            $table->string('s_posicion_vehiculo', 255)->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tc_ubicaciones_almacen', function (Blueprint $table) {
            $table->id('id_ubicacion_almacen');
            $table->string('s_ubicacion_almacen', 255)->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tc_metodos_pagos', function (Blueprint $table) {
            $table->id('id_metodo_pago');
            $table->string('s_metodo_pago', 255)->nullable();
            $table->string('s_img_metodo_pago', 255)->nullable();
            $table->string('s_descripcion_metodo_pago', 255)->nullable();
            $table->boolean('b_requiere_referencia')->nullable()->default(0);
            $table->boolean('b_requiere_evidencia')->nullable()->default(0);
            $table->boolean('b_activo')->nullable()->default(1);
            $table->timestamps();
        });

        Schema::create('tc_bancos', function (Blueprint $table) {
            $table->id('id_banco');
            $table->string('s_banco', 255)->nullable();
            $table->string('s_img_banco', 255)->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tc_tipos_cuentas', function (Blueprint $table) {
            $table->id('id_tipo_cuenta');
            $table->string('s_tipo_cuenta', 255)->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tc_cuentas_bancarias', function (Blueprint $table) {
            $table->id('id_cuenta_bancaria');
            $table->string('s_nombre_cuenta', 255)->nullable();
            $table->unsignedBigInteger('n_numero_cuenta')->nullable();
            $table->unsignedBigInteger('n_numero_tarjeta')->nullable();
            $table->unsignedBigInteger('n_CLABE')->nullable();
            $table->unsignedBigInteger('id_metodo_pago')->nullable();
            $table->unsignedBigInteger('id_tipo_cuenta')->nullable();
            $table->unsignedBigInteger('id_banco')->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tc_tipos_clientes', function (Blueprint $table) {
            $table->id('id_tipo_cliente');
            $table->string('s_tipo_cliente', 255)->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tc_tipos_creditos', function (Blueprint $table) {
            $table->id('id_tipo_credito');
            $table->string('s_tipo_credito', 255)->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tc_estatus_creditos', function (Blueprint $table) {
            $table->id('id_estatus_credito');
            $table->string('s_estatus_credito', 255)->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tc_estatus_ventas', function (Blueprint $table) {
            $table->id('id_estatus_venta');
            $table->string('s_estatus_venta', 255)->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tc_estatus_cotizaciones', function (Blueprint $table) {
            $table->id('id_estatus_cotizacion');
            $table->string('s_estatus_cotizacion', 255)->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tc_tipos_cotizaciones', function (Blueprint $table) {
            $table->id('id_tipo_cotizacion');
            $table->string('s_tipo_cotizacion', 255)->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tc_tipos_requisiciones', function (Blueprint $table) {
            $table->id('id_tipo_requisicion');
            $table->string('s_tipo_requisicion', 255)->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tc_estatus_requisiciones', function (Blueprint $table) {
            $table->id('id_estatus_requisicion');
            $table->string('s_estatus_requisicion', 255)->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tc_motivos_pedidos', function (Blueprint $table) {
            $table->id('id_motivo_pedido');
            $table->string('s_motivo_pedido', 255)->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tc_prioridades', function (Blueprint $table) {
            $table->id('id_prioridad');
            $table->string('s_prioridad', 255);
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tc_estatus_ordenes_compras', function (Blueprint $table) {
            $table->id('id_estatus_orden_compra');
            $table->string('s_estatus_orden_compra', 255)->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tc_categorias_gastos', function (Blueprint $table) {
            $table->id('id_categoria_gasto');
            $table->string('s_categoria_gasto', 255)->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tc_tipos_gastos', function (Blueprint $table) {
            $table->id('id_tipo_gasto');
            $table->unsignedBigInteger('id_categoria_gasto')->nullable();
            $table->string('s_tipo_gasto', 255)->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
            $table->timestamps();
        });

        Schema::create('tc_tipos_evidencias', function (Blueprint $table) {
            $table->id('id_tipo_evidencia');
            $table->string('s_tipo_evidencia', 255)->nullable();
            $table->string('s_mime_type', 255)->nullable();
            $table->string('s_extension', 10)->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tc_estatus_embarque', function (Blueprint $table) {
            $table->id('id_estatus_embarque');
            $table->string('s_estatus_embarque', 255)->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tc_estatus_entrada', function (Blueprint $table) {
            $table->id('id_estatus_entrada');
            $table->string('s_estatus_entrada', 255)->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tc_estatus_orden', function (Blueprint $table) {
            $table->id('id_estatus_orden');
            $table->string('s_estatus_orden', 255)->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tc_tipos_destinos', function (Blueprint $table) {
            $table->id('id_tipo_destino');
            $table->string('s_tipo_destino', 255)->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tc_tipo_ruta', function (Blueprint $table) {
            $table->id('id_tipo_ruta');
            $table->string('s_tipo_ruta', 255);
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tc_marcas_vehiculos', function (Blueprint $table) {
            $table->id('id_marca_vehiculo');
            $table->string('s_marca_vehiculo', 255)->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tc_modelos_vehiculos', function (Blueprint $table) {
            $table->id('id_modelo_vehiculo');
            $table->unsignedBigInteger('id_marca_vehiculo')->nullable();
            $table->string('s_modelo_vehiculo', 255)->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tc_generaciones', function (Blueprint $table) {
            $table->id('id_generacion');
            $table->unsignedBigInteger('id_modelo_vehiculo')->nullable();
            $table->string('s_generacion', 255)->nullable();
            $table->integer('n_anio_inicio')->nullable();
            $table->integer('n_anio_fin')->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tc_motores', function (Blueprint $table) {
            $table->id('id_motor');
            $table->string('s_motor', 255)->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('tc_motores');
        Schema::dropIfExists('tc_generaciones');
        Schema::dropIfExists('tc_modelos_vehiculos');
        Schema::dropIfExists('tc_marcas_vehiculos');
        Schema::dropIfExists('tc_tipo_ruta');
        Schema::dropIfExists('tc_tipos_destinos');
        Schema::dropIfExists('tc_estatus_orden');
        Schema::dropIfExists('tc_estatus_entrada');
        Schema::dropIfExists('tc_estatus_embarque');
        Schema::dropIfExists('tc_tipos_evidencias');
        Schema::dropIfExists('tc_tipos_gastos');
        Schema::dropIfExists('tc_categorias_gastos');
        Schema::dropIfExists('tc_estatus_ordenes_compras');
        Schema::dropIfExists('tc_prioridades');
        Schema::dropIfExists('tc_motivos_pedidos');
        Schema::dropIfExists('tc_estatus_requisiciones');
        Schema::dropIfExists('tc_tipos_requisiciones');
        Schema::dropIfExists('tc_tipos_cotizaciones');
        Schema::dropIfExists('tc_estatus_cotizaciones');
        Schema::dropIfExists('tc_estatus_ventas');
        Schema::dropIfExists('tc_estatus_creditos');
        Schema::dropIfExists('tc_tipos_creditos');
        Schema::dropIfExists('tc_tipos_clientes');
        Schema::dropIfExists('tc_cuentas_bancarias');
        Schema::dropIfExists('tc_tipos_cuentas');
        Schema::dropIfExists('tc_bancos');
        Schema::dropIfExists('tc_metodos_pagos');
        Schema::dropIfExists('tc_ubicaciones_almacen');
        Schema::dropIfExists('tc_posiciones_vehiculo');
        Schema::dropIfExists('tc_unidades_medida');
        Schema::dropIfExists('tc_estatus_refacciones');
        Schema::dropIfExists('tc_clases_refacciones');
        Schema::dropIfExists('tc_subcategorias_refacciones');
        Schema::dropIfExists('tc_categorias_refacciones');
        Schema::dropIfExists('tc_marcas_refacciones');
        Schema::dropIfExists('tc_municipios');
        Schema::dropIfExists('tc_estados_republica');
        Schema::dropIfExists('tc_grados_estudios');
        Schema::dropIfExists('tc_profesiones');
        Schema::dropIfExists('tc_estados_disponibilidad');
        Schema::dropIfExists('tc_habilidades');
        Schema::dropIfExists('tc_descripciones_tipos_empleados');
        Schema::dropIfExists('tc_tipos_empleados');
        Schema::dropIfExists('tc_tipos_usuarios');
        Schema::dropIfExists('tc_porcentajes_utilidad');
        Schema::dropIfExists('tc_configuraciones');
        Schema::dropIfExists('tc_tipos_configuraciones');
        Schema::dropIfExists('tc_modulos');
        Schema::dropIfExists('tc_categorias_modulos');
    }
};
