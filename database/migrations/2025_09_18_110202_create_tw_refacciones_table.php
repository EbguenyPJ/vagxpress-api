<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tw_refacciones', function (Blueprint $table) {
            $table->id('id_refaccion');

            // Datos principales
            $table->string('s_nombre_refaccion')->nullable();
            $table->text('s_descripcion')->nullable();
            $table->text('s_observaciones')->nullable();
            $table->string('s_numero_parte')->nullable();
            $table->string('s_codigo_interno')->nullable();
            $table->string('s_codigo_alterno')->nullable();
            $table->string('s_sku')->nullable();
            $table->string('s_codigo_aces')->nullable();
            $table->string('s_imagen_refaccion')->nullable();
            $table->string('s_codigo_qr')->nullable();

            // Precios y costos
            $table->decimal('n_precio_compra', 10, 2)->default(0);
            $table->decimal('n_precio_venta', 10, 2)->default(0);
            $table->decimal('n_costo_promedio', 10, 2)->default(0);
            $table->decimal('n_precio_mayoreo', 10, 2)->default(0);
            $table->decimal('n_precio_minimo_autorizado', 10, 2)->default(0);

            // Inventario
            $table->integer('n_stock_actual')->default(0);
            $table->integer('n_stock_minimo')->default(0);
            $table->integer('n_stock_maximo')->default(0);
            $table->integer('n_tiempo_reposicion')->default(0);

            // Relaciones (FKs)
            $table->unsignedInteger('id_marca_refaccion')->nullable();
            $table->unsignedInteger('id_unidad_medida')->nullable();
            $table->unsignedInteger('id_proveedor')->nullable();
            $table->unsignedInteger('id_clase_refaccion')->nullable();          // Niveles Calidad
            $table->unsignedInteger('id_categoria_refaccion')->nullable();      //  Sistemas de ...
            $table->unsignedInteger('id_subcategoria_refaccion')->nullable();       //  Tipos de ...
            $table->unsignedInteger('id_posicion_vehiculo')->nullable();
            $table->unsignedInteger('id_ubicacion_almacen')->nullable();
            $table->unsignedInteger('id_codigo_sat')->nullable();
            $table->unsignedInteger('id_estatus_refaccion')->nullable();
            $table->unsignedInteger('id_codigo_aces')->nullable();

            // Flags
            $table->tinyInteger('b_importado')->default(0);
            $table->tinyInteger('b_activo')->default(1);

            // Auditoría
            $table->unsignedInteger('id_usuario_crea')->nullable();
            $table->unsignedInteger('id_usuario_edita')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tw_refacciones');
    }
};
