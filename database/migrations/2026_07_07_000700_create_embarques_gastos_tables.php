<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tw_embarques', function (Blueprint $table) {
            $table->id('id_embarque');
            $table->unsignedBigInteger('id_proveedor')->nullable();
            $table->dateTime('d_fecha_creacion')->nullable();
            $table->unsignedBigInteger('id_usuario_crea')->nullable();
            $table->unsignedBigInteger('id_estatus_embarque')->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tw_evidencias_embarque', function (Blueprint $table) {
            $table->id('id_evidencia_embarque');
            $table->unsignedBigInteger('id_embarque')->nullable();
            $table->unsignedBigInteger('id_tipo_evidencia')->nullable();
            $table->string('s_evidencia_embarque', 255)->nullable();
            $table->dateTime('d_fecha_creacion')->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tr_entradas_embarque', function (Blueprint $table) {
            $table->id('id_entrada_embarque');
            $table->unsignedBigInteger('id_embarque')->nullable();
            $table->unsignedBigInteger('id_refaccion')->nullable();
            $table->unsignedBigInteger('id_pre_registro_refaccion')->nullable();
            $table->unsignedBigInteger('id_estatus_entrada')->nullable();
            $table->integer('n_cantidad')->nullable();
            $table->decimal('n_precio_compra', 10, 2)->default(0.00);
            $table->string('s_codigo_barras', 255)->nullable();
            $table->dateTime('d_fecha_creacion')->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
        });

        Schema::create('tw_gastos', function (Blueprint $table) {
            $table->id('id_gasto');
            $table->unsignedBigInteger('id_tipo_gasto')->nullable();
            $table->unsignedBigInteger('id_tipo_evidencia')->nullable();
            $table->unsignedBigInteger('id_sucursal')->nullable();
            $table->integer('n_cantidad')->nullable();
            $table->decimal('n_costo', 10, 2)->nullable();
            $table->string('s_concepto', 255)->nullable();
            $table->string('s_evidencia', 255)->nullable();
            $table->dateTime('d_fecha_gasto')->nullable();
            $table->dateTime('d_fecha_creacion')->nullable();
            $table->unsignedBigInteger('id_usuario_crea')->nullable();
            $table->boolean('b_movil')->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
            $table->index('d_fecha_gasto');
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('tw_gastos');
        Schema::dropIfExists('tr_entradas_embarque');
        Schema::dropIfExists('tw_evidencias_embarque');
        Schema::dropIfExists('tw_embarques');
    }
};
