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
        Schema::create('tc_categorias_refacciones', function (Blueprint $table) {
            $table->id('id_categoria_refaccion');
            $table->string('s_categoria_refaccion')->nullable();
            $table->string('s_img_categoria_refaccion')->nullable();
            $table->tinyInteger('b_activo')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tc_categorias_refacciones');
    }
};
