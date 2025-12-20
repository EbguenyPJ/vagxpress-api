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
        Schema::create('tr_ordenes_productos', function (Blueprint $table) {
            $table->id('id_orden_producto');
            $table->Integer('id_orden')->nullable();
            $table->String('s_producto');
            $table->Integer('n_cantidad');
            $table->String('s_comentario');
            $table->tinyInteger('b_activo')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_ordenes_productos');
    }
};
