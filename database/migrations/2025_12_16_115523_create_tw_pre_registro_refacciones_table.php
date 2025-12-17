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
        Schema::create('tw_pre_registro_refacciones', function (Blueprint $table) {
            $table->id('id_pre_registro_refaccion');
            $table->String('s_nombre_refaccion')->nullable();
            $table->String('s_numero_parte')->nullable();
            $table->Integer('id_marca_refaccion')->nullable();
            $table->Integer('id_categoria_refaccion')->nullable();
            $table->Integer('id_subcategoria_refaccion')->nullable();
            $table->Integer('id_clase_refaccion')->nullable();
            $table->decimal('n_precio_compra', 10, 2)->default(0);
            $table->Integer('id_usuario_crea')->nullable();
            $table->tinyInteger('b_activo')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tw_pre_registro_refacciones');
    }
};
