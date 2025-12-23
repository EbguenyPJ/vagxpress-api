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
        Schema::create('tr_ordenes_compras_requisiciones_refacciones', function (Blueprint $table) {
            $table->id('id_orden_compra_requisicion_refaccion');
            $table->unsignedInteger('id_orden_compra')->nullable();
            $table->unsignedInteger('id_requisicion_refaccion')->nullable();
            $table->integer('n_cantidad_recibida')->nullable()->default(0);
            $table->timestamps();
            $table->unsignedTinyInteger('b_activo')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_ordenes_compras_requisiciones_refacciones');
    }
};
