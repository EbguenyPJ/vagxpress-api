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
        Schema::create('tr_entradas_embarque', function (Blueprint $table) {
            $table->id('id_entrada_embarque');
            $table->Integer('id_embarque')->nullable();
            $table->Integer('id_refaccion')->nullable();
            $table->Integer('id_pre_registro_refaccion')->nullable();
            $table->Integer('id_estatus_entrada')->nullable();
            $table->Integer('n_cantidad')->nullable();
            $table->decimal('n_precio_compra', 10, 2)->default(0);
            $table->String('s_codigo_barras')->nullable();
            $table->DateTime('d_fecha_creacion')->nullable();
            $table->tinyInteger('b_activo')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_entradas_embarque');
    }
};
