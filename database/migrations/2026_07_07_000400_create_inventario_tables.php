<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tw_proveedores', function (Blueprint $table) {
            $table->id('id_proveedor');
            $table->string('s_proveedor', 255)->nullable();
            $table->string('s_nombre_contacto', 255)->nullable();
            $table->string('s_telefono', 255)->nullable();
            $table->string('s_rfc', 255)->nullable();
            $table->string('s_img_proveedor', 255)->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tw_equivalencias', function (Blueprint $table) {
            $table->id('id_equivalencia');
            $table->string('s_nombre_equivalencia', 255)->nullable();
            $table->string('s_descripcion_equivalencia', 255)->nullable();
            $table->unsignedBigInteger('id_usuario_crea')->nullable();
            $table->unsignedBigInteger('id_usuario_edita')->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tw_refacciones', function (Blueprint $table) {
            $table->id('id_refaccion');
            $table->string('s_nombre_refaccion', 255)->nullable();
            $table->text('s_descripcion')->nullable();
            $table->text('s_observaciones')->nullable();
            $table->string('s_numero_parte', 255)->nullable();
            $table->string('s_codigo_interno', 255)->nullable();
            $table->string('s_codigo_alterno', 255)->nullable();
            $table->string('s_sku', 255)->nullable();
            $table->string('s_codigo_aces', 255)->nullable();
            $table->string('s_imagen_refaccion', 255)->nullable();
            $table->string('s_codigo_qr', 255)->nullable();
            $table->decimal('n_precio_compra', 10, 2)->default(0.00);
            $table->decimal('n_precio_venta', 10, 2)->default(0.00);
            $table->decimal('n_costo_promedio', 10, 2)->default(0.00);
            $table->decimal('n_precio_mayoreo', 10, 2)->default(0.00);
            $table->decimal('n_precio_minimo_autorizado', 10, 2)->default(0.00);
            $table->integer('n_stock_actual')->default(0);
            $table->integer('n_stock_minimo')->default(0);
            $table->integer('n_stock_maximo')->default(0);
            $table->integer('n_tiempo_reposicion')->default(0);
            $table->unsignedBigInteger('id_marca_refaccion')->nullable();
            $table->unsignedBigInteger('id_unidad_medida')->nullable();
            $table->unsignedBigInteger('id_proveedor')->nullable();
            $table->unsignedBigInteger('id_clase_refaccion')->nullable();
            $table->unsignedBigInteger('id_categoria_refaccion')->nullable();
            $table->unsignedBigInteger('id_subcategoria_refaccion')->nullable();
            $table->unsignedBigInteger('id_posicion_vehiculo')->nullable();
            $table->unsignedBigInteger('id_ubicacion_almacen')->nullable();
            $table->unsignedBigInteger('id_codigo_sat')->nullable();
            $table->unsignedBigInteger('id_estatus_refaccion')->nullable();
            $table->unsignedBigInteger('id_codigo_aces')->nullable();
            $table->boolean('b_importado')->default(0);
            $table->boolean('b_activo')->default(1);
            $table->unsignedBigInteger('id_usuario_crea')->nullable();
            $table->unsignedBigInteger('id_usuario_edita')->nullable();
            $table->timestamps();
            $table->unique('s_numero_parte');
            $table->unique('s_codigo_qr');
            $table->index('s_nombre_refaccion');
            $table->index(['b_activo', 'id_categoria_refaccion']);
        });

        Schema::create('tr_refacciones_equivalencias', function (Blueprint $table) {
            $table->id('id_refaccion_equivalencia');
            $table->unsignedBigInteger('id_refaccion')->nullable();
            $table->unsignedBigInteger('id_equivalencia')->nullable();
            $table->unsignedBigInteger('id_usuario_crea')->nullable();
            $table->unsignedBigInteger('id_usuario_edita')->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
            $table->unique(['id_refaccion', 'id_equivalencia']);
        });

        Schema::create('tr_proveedores_refacciones', function (Blueprint $table) {
            $table->id('id_proveedor_refaccion');
            $table->unsignedBigInteger('id_proveedor')->nullable();
            $table->unsignedBigInteger('id_refaccion')->nullable();
            $table->decimal('n_ultimo_costo', 10, 2)->default(0.00);
            $table->date('d_fecha_ultima_compra')->nullable();
            $table->string('s_sku_proveedor', 255)->nullable();
            $table->string('s_no_parte_proveedor', 255)->nullable();
            $table->string('s_codigo_qr_proveedor', 255)->nullable();
            $table->unsignedBigInteger('id_usuario_crea')->nullable();
            $table->unsignedBigInteger('id_usuario_edita')->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tw_pre_registro_refacciones', function (Blueprint $table) {
            $table->id('id_pre_registro_refaccion');
            $table->string('s_nombre_refaccion', 255)->nullable();
            $table->string('s_numero_parte', 255)->nullable();
            $table->unsignedBigInteger('id_marca_refaccion')->nullable();
            $table->unsignedBigInteger('id_categoria_refaccion')->nullable();
            $table->unsignedBigInteger('id_subcategoria_refaccion')->nullable();
            $table->unsignedBigInteger('id_clase_refaccion')->nullable();
            $table->decimal('n_precio_compra', 10, 2)->default(0.00);
            $table->unsignedBigInteger('id_usuario_crea')->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('tw_pre_registro_refacciones');
        Schema::dropIfExists('tr_proveedores_refacciones');
        Schema::dropIfExists('tr_refacciones_equivalencias');
        Schema::dropIfExists('tw_refacciones');
        Schema::dropIfExists('tw_equivalencias');
        Schema::dropIfExists('tw_proveedores');
    }
};
