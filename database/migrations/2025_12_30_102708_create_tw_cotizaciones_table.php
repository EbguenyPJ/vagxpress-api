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
        Schema::create('tw_cotizaciones', function (Blueprint $table) {
            $table->id('id_cotizacion');
            $table->decimal('n_subtotal', 12, 2)->nullable()->default(0);
            $table->decimal('n_porcentaje_iva', 12, 7)->nullable()->default(0);
            $table->decimal('n_total', 12, 2)->nullable()->default(0);
            $table->integer('n_cantidad_refacciones')->nullable()->default(0);
            $table->unsignedInteger('id_estatus_cotizacion')->nullable();
            $table->unsignedInteger('id_tipo_cotizacion')->nullable();
            $table->unsignedInteger('id_cliente')->nullable();
            $table->unsignedInteger('id_usuario_crea')->nullable();
            $table->unsignedInteger('id_usuario_modifica')->nullable();
            $table->timestamps();
            $table->unsignedTinyInteger('b_activo')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tw_cotizaciones');
    }
};
