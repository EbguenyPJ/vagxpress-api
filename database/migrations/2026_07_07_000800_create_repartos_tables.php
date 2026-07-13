<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tw_destinos', function (Blueprint $table) {
            $table->id('id_destino');
            $table->string('s_nombre_destino', 255)->nullable();
            $table->text('s_direccion')->nullable();
            $table->string('s_referencia_destino', 255)->nullable();
            $table->unsignedBigInteger('id_tipo_destino')->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tw_ordenes', function (Blueprint $table) {
            $table->id('id_orden');
            $table->unsignedBigInteger('id_destino')->nullable();
            $table->text('s_nota_refaccionista')->nullable();
            $table->unsignedBigInteger('id_repartidor')->nullable();
            $table->dateTime('d_fecha_asignacion')->nullable();
            $table->unsignedBigInteger('id_estatus_orden')->nullable();
            $table->dateTime('d_fecha_entrega')->nullable();
            $table->string('s_nombre_recibe', 255)->nullable();
            $table->string('s_firma', 255)->nullable();
            $table->dateTime('d_fin_regreso')->nullable();
            $table->dateTime('d_inicio_regreso')->nullable();
            $table->dateTime('d_fin_reparto')->nullable();
            $table->dateTime('d_inicio_reparto')->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tr_ordenes_productos', function (Blueprint $table) {
            $table->id('id_orden_producto');
            $table->unsignedBigInteger('id_orden')->nullable();
            $table->string('s_producto', 255);
            $table->integer('n_cantidad');
            $table->string('s_comentario', 255);
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tw_evidencias_orden', function (Blueprint $table) {
            $table->id('id_evidencia_orden');
            $table->unsignedBigInteger('id_orden')->nullable();
            $table->string('s_evidencia_orden', 255)->nullable();
            $table->unsignedBigInteger('id_tipo_evidencia')->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tw_puntos_ruta', function (Blueprint $table) {
            $table->id('id_punto_ruta');
            $table->unsignedBigInteger('id_orden')->nullable();
            $table->unsignedBigInteger('id_tipo_ruta')->nullable();
            $table->decimal('n_latitud', 10, 7)->nullable();
            $table->decimal('n_longitud', 10, 7)->nullable();
            $table->dateTime('timestamp')->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('tw_puntos_ruta');
        Schema::dropIfExists('tw_evidencias_orden');
        Schema::dropIfExists('tr_ordenes_productos');
        Schema::dropIfExists('tw_ordenes');
        Schema::dropIfExists('tw_destinos');
    }
};
