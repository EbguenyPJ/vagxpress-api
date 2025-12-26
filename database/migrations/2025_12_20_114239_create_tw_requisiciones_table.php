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
        Schema::create('tw_requisiciones', function (Blueprint $table) {
            $table->id('id_requisicion');
            $table->string('s_observacion')->nullable();
            $table->unsignedInteger('n_cantidad_refacciones')->nullable()->default(0);
            $table->decimal('n_total_estimado', 12, 2)->nullable()->default(0);
            $table->date('d_fecha_limite')->nullable();
            $table->date('d_fecha_solicitud')->nullable();
            $table->unsignedInteger('id_estatus_requisicion')->nullable();
            $table->unsignedInteger('id_tipo_requisicion')->nullable();
            $table->unsignedInteger('id_usuario_crea')->nullable();
            $table->unsignedInteger('id_usuario_modifica')->nullable();
            $table->unsignedInteger('id_usuario_autoriza')->nullable();
            $table->timestamps();
            $table->unsignedTinyInteger('b_activo')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tw_requisiciones');
    }
};
