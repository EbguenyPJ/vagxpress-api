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
        Schema::create('tw_puntos_ruta', function (Blueprint $table) {
            $table->id('id_punto_prueba_ruta');
            $table->Integer('id_orden')->nullable();
            $table->Integer('id_tipo_ruta')->nullable();
            $table->decimal('n_latitud', 10, 7)->nullable();
            $table->decimal('n_longitud', 10, 7)->nullable();
            $table->dateTime('timestamp')->nullable();
            $table->tinyInteger('b_activo')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tw_puntos_prueba_ruta');
    }
};
