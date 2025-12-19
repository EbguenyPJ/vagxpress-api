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
        Schema::create('tr_cortes_ventas', function (Blueprint $table) {
            $table->id('id_corte_ventas');

            $table->unsignedBigInteger('id_corte');
            $table->unsignedBigInteger('id_venta');

            $table->tinyInteger('b_activo')->default(1);
            $table->timestamps();

            // Índices
            $table->index('id_corte');
            $table->index('id_venta');

            // Evita duplicar la misma venta en el mismo corte
            //$table->unique(['id_corte', 'id_venta'], 'uk_corte_venta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_cortes_ventas');
    }
};
