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
        Schema::create('tw_ordenes_compras', function (Blueprint $table) {
            $table->id('id_orden_compra');

            $table->string('s_folio_interno')->nullable();
            $table->text('s_observacion')->nullable();
            $table->date('d_fecha_orden')->nullable();
            $table->date('d_fecha_recepcion_estimada')->nullable();
            $table->decimal('n_total_estimado', 12, 2)->default(0);

            $table->unsignedInteger('id_proveedor')->nullable();
            $table->unsignedInteger('id_requisicion')->nullable();
            $table->unsignedInteger('id_estatus_orden_compra')->nullable();

            $table->unsignedInteger('id_usuario_crea')->nullable();
            $table->unsignedInteger('id_usuario_modifica')->nullable();
            $table->unsignedInteger('id_usuario_autoriza')->nullable();
            $table->timestamps();
            $table->unsignedTinyInteger('b_activo')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tw_ordenes_compras');
    }
};
