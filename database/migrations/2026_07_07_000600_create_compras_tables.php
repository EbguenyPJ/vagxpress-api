<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tw_requisiciones', function (Blueprint $table) {
            $table->id('id_requisicion');
            $table->string('s_observacion', 255)->nullable();
            $table->unsignedInteger('n_cantidad_refacciones')->nullable()->default(0);
            $table->decimal('n_total_estimado', 12, 2)->nullable()->default(0.00);
            $table->date('d_fecha_limite')->nullable();
            $table->date('d_fecha_solicitud')->nullable();
            $table->unsignedBigInteger('id_estatus_requisicion')->nullable();
            $table->unsignedBigInteger('id_tipo_requisicion')->nullable();
            $table->unsignedBigInteger('id_usuario_crea')->nullable();
            $table->unsignedBigInteger('id_usuario_modifica')->nullable();
            $table->unsignedBigInteger('id_usuario_autoriza')->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
            $table->timestamps();
        });

        Schema::create('tr_requisiciones_refacciones', function (Blueprint $table) {
            $table->id('id_requisicion_refaccion');
            $table->unsignedBigInteger('id_requisicion')->nullable();
            $table->unsignedBigInteger('id_refaccion')->nullable();
            $table->integer('n_cantidad_sugerida')->nullable()->default(0);
            $table->integer('n_cantidad_solicitada')->nullable()->default(0);
            $table->decimal('n_costo_unitario', 10, 2)->nullable()->default(0.00);
            $table->unsignedBigInteger('id_motivo_pedido')->nullable();
            $table->unsignedBigInteger('id_prioridad')->nullable();
            $table->unsignedBigInteger('id_estatus_requisicion')->nullable();
            $table->boolean('b_activo')->nullable()->default(1);
            $table->timestamps();
        });

        Schema::create('tw_ordenes_compras', function (Blueprint $table) {
            $table->id('id_orden_compra');
            $table->string('s_folio_interno', 255)->nullable();
            $table->text('s_observacion')->nullable();
            $table->date('d_fecha_orden')->nullable();
            $table->date('d_fecha_recepcion_estimada')->nullable();
            $table->decimal('n_total_estimado', 12, 2)->default(0.00);
            $table->unsignedBigInteger('id_proveedor')->nullable();
            $table->unsignedBigInteger('id_requisicion')->nullable();
            $table->unsignedBigInteger('id_estatus_orden_compra')->nullable();
            $table->unsignedBigInteger('id_usuario_crea')->nullable();
            $table->unsignedBigInteger('id_usuario_modifica')->nullable();
            $table->unsignedBigInteger('id_usuario_autoriza')->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
            $table->unique('s_folio_interno');
        });

        Schema::create('tr_ordenes_compras_requisiciones_refacciones', function (Blueprint $table) {
            $table->id('id_orden_compra_requisicion_refaccion');
            $table->unsignedBigInteger('id_orden_compra')->nullable();
            $table->unsignedBigInteger('id_requisicion_refaccion')->nullable();
            $table->integer('n_cantidad_recibida')->nullable()->default(0);
            $table->boolean('b_activo')->nullable()->default(1);
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('tr_ordenes_compras_requisiciones_refacciones');
        Schema::dropIfExists('tw_ordenes_compras');
        Schema::dropIfExists('tr_requisiciones_refacciones');
        Schema::dropIfExists('tw_requisiciones');
    }
};
