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
        Schema::create('tc_subcategorias_refacciones', function (Blueprint $table) {
            $table->id('id_subcategoria_refaccion');
            $table->integer('id_categoria_refaccion');
            $table->string('s_subcategoria_refaccion')->nullable()->unique();
            $table->tinyInteger('b_activo')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tc_subcategorias_refacciones');
    }
};
