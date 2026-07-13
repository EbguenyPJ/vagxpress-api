<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tw_clientes', function (Blueprint $table) {
            $table->id('id_cliente');
            $table->string('s_nombre_cliente', 255)->nullable();
            $table->string('s_razon_social', 255)->nullable();
            $table->string('s_rfc', 255)->nullable();
            $table->string('s_ine', 255)->nullable();
            $table->string('s_numero_telefono', 255)->nullable();
            $table->string('s_correo', 255)->nullable();
            $table->string('s_comentario', 255)->nullable();
            $table->decimal('n_saldo_actual', 12, 2)->nullable()->default(0.00);
            $table->decimal('n_limite_credito', 12, 2)->nullable()->default(0.00);
            $table->unsignedBigInteger('id_tipo_cliente')->nullable();
            $table->unsignedBigInteger('id_usuario_crea')->nullable();
            $table->unsignedBigInteger('id_usuario_modifica')->nullable();
            $table->boolean('b_credito')->nullable()->default(0);
            $table->boolean('b_activo')->nullable()->default(1);
            $table->timestamps();
            $table->index('s_nombre_cliente');
        });

        Schema::create('tw_ventas', function (Blueprint $table) {
            $table->id('id_venta');
            $table->decimal('n_subtotal', 12, 2)->nullable()->default(0.00);
            $table->decimal('n_porcentaje_iva', 12, 7)->nullable()->default(0.0000000);
            $table->decimal('n_total', 12, 2)->nullable()->default(0.00);
            $table->integer('n_cantidad_refacciones')->nullable()->default(0);
            $table->unsignedBigInteger('id_estatus_venta')->nullable();
            $table->unsignedBigInteger('id_metodo_pago')->nullable();
            $table->unsignedBigInteger('id_cliente')->nullable();
            $table->unsignedBigInteger('id_cuenta_bancaria')->nullable();
            $table->unsignedBigInteger('id_usuario_crea')->nullable();
            $table->unsignedBigInteger('id_usuario_modifica')->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
            $table->boolean('b_corte')->default(0);
            $table->timestamps();
            $table->index(['b_corte', 'id_estatus_venta', 'created_at'], 'idx_ventas_corte');
            $table->index('created_at');
        });

        Schema::create('tr_ventas_refacciones', function (Blueprint $table) {
            $table->id('id_venta_refaccion');
            $table->integer('n_cantidad')->nullable()->default(0);
            $table->decimal('n_costo_unitario', 12, 2)->nullable()->default(0.00);
            $table->decimal('n_porcentaje_utilidad', 12, 2)->nullable()->default(0.00);
            $table->decimal('n_total', 12, 2)->nullable()->default(0.00);
            $table->integer('n_stock_previo')->nullable()->default(0);
            $table->integer('n_stock_posterior')->nullable()->default(0);
            $table->unsignedBigInteger('id_venta')->nullable();
            $table->unsignedBigInteger('id_refaccion')->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
            $table->timestamps();
        });

        Schema::create('tw_creditos', function (Blueprint $table) {
            $table->id('id_credito');
            $table->unsignedBigInteger('id_venta')->nullable();
            $table->string('s_comentario_credito', 255)->nullable();
            $table->decimal('n_total_a_pagar', 12, 2)->nullable()->default(0.00);
            $table->decimal('n_total_pagado', 12, 2)->nullable()->default(0.00);
            $table->unsignedBigInteger('id_tipo_credito')->nullable();
            $table->unsignedBigInteger('id_estatus_credito')->nullable();
            $table->unsignedBigInteger('id_usuario_crea')->nullable();
            $table->unsignedBigInteger('id_usuario_modifica')->nullable();
            $table->date('d_fecha_vencimiento')->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
            $table->timestamps();
        });

        Schema::create('tw_abonos', function (Blueprint $table) {
            $table->id('id_abono');
            $table->unsignedBigInteger('id_credito')->nullable();
            $table->string('s_referencia_pago', 255)->nullable();
            $table->string('s_img_evidencia_pago', 255)->nullable();
            $table->decimal('n_saldo_venta_actual', 12, 2)->nullable()->default(0.00);
            $table->decimal('n_saldo_cliente_actual', 12, 2)->nullable()->default(0.00);
            $table->decimal('n_abono', 12, 2)->nullable()->default(0.00);
            $table->unsignedBigInteger('id_estatus_abono')->nullable();
            $table->unsignedBigInteger('id_metodo_pago')->nullable();
            $table->unsignedBigInteger('id_usuario_crea')->nullable();
            $table->unsignedBigInteger('id_usuario_modifica')->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tw_cortes', function (Blueprint $table) {
            $table->id('id_corte');
            $table->unsignedBigInteger('id_tipo_corte');
            $table->unsignedBigInteger('id_usuario_crea');
            $table->date('d_fecha_corte');
            $table->decimal('n_monto_efectivo', 12, 2)->default(0.00);
            $table->decimal('n_monto_transferencia', 12, 2)->default(0.00);
            $table->decimal('n_monto_credito', 12, 2)->default(0.00);
            $table->decimal('n_monto_tarjeta_debito', 12, 2)->default(0.00);
            $table->decimal('n_monto_tarjeta_credito', 12, 2)->default(0.00);
            $table->decimal('n_monto_total', 12, 2)->default(0.00);
            $table->string('s_descripcion_corte', 255)->nullable();
            $table->text('s_comentario')->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tr_cortes_ventas', function (Blueprint $table) {
            $table->id('id_corte_ventas');
            $table->unsignedBigInteger('id_corte');
            $table->unsignedBigInteger('id_venta');
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
            $table->unique(['id_corte', 'id_venta']);
        });

        Schema::create('tw_cortes_evidencias', function (Blueprint $table) {
            $table->id('id_corte_evidencia');
            $table->unsignedBigInteger('id_corte');
            $table->unsignedBigInteger('id_metodo_pago');
            $table->unsignedBigInteger('id_tipo_evidencia');
            $table->string('s_nombre_archivo', 255);
            $table->string('s_descripcion', 255)->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tw_cotizaciones', function (Blueprint $table) {
            $table->id('id_cotizacion');
            $table->decimal('n_subtotal', 12, 2)->nullable()->default(0.00);
            $table->decimal('n_porcentaje_iva', 12, 7)->nullable()->default(0.0000000);
            $table->decimal('n_total', 12, 2)->nullable()->default(0.00);
            $table->integer('n_cantidad_refacciones')->nullable()->default(0);
            $table->unsignedBigInteger('id_estatus_cotizacion')->nullable();
            $table->unsignedBigInteger('id_tipo_cotizacion')->nullable();
            $table->unsignedBigInteger('id_cliente')->nullable();
            $table->unsignedBigInteger('id_usuario_crea')->nullable();
            $table->unsignedBigInteger('id_usuario_modifica')->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
            $table->timestamps();
        });

        Schema::create('tr_cotizaciones_refacciones', function (Blueprint $table) {
            $table->id('id_cotizacion_refaccion');
            $table->unsignedBigInteger('id_cotizacion')->nullable();
            $table->unsignedBigInteger('id_refaccion')->nullable();
            $table->integer('n_cantidad')->nullable()->default(0);
            $table->decimal('n_costo_unitario', 12, 2)->nullable()->default(0.00);
            $table->decimal('n_porcentaje_utilidad', 12, 2)->nullable()->default(0.00);
            $table->decimal('n_total', 12, 2)->nullable()->default(0.00);
            $table->boolean('b_activo')->nullable()->default(1);
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('tr_cotizaciones_refacciones');
        Schema::dropIfExists('tw_cotizaciones');
        Schema::dropIfExists('tw_cortes_evidencias');
        Schema::dropIfExists('tr_cortes_ventas');
        Schema::dropIfExists('tw_cortes');
        Schema::dropIfExists('tw_abonos');
        Schema::dropIfExists('tw_creditos');
        Schema::dropIfExists('tr_ventas_refacciones');
        Schema::dropIfExists('tw_ventas');
        Schema::dropIfExists('tw_clientes');
    }
};
