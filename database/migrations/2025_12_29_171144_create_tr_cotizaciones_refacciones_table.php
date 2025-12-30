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
        Schema::create('tr_cotizaciones_refacciones', function (Blueprint $table) {
            $table->id('id_cotizacion_refaccion');
            $table->unsignedInteger('id_cotizacion')->nullable();
            $table->unsignedInteger('id_refaccion')->nullable();
            $table->integer('n_cantidad')->nullable()->default(0);
            $table->decimal('n_costo_unitario', 12, 2)->nullable()->default(0);
            $table->decimal('n_porcentaje_utilidad', 12, 2)->nullable()->default(0);
            $table->decimal('n_total', 12, 2)->nullable()->default(0);
            $table->timestamps();
            $table->unsignedTinyInteger('b_activo')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_cotizaciones_refacciones');
    }
};
