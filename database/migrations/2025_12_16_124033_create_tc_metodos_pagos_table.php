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
        Schema::create('tc_metodos_pagos', function (Blueprint $table) {
            $table->id('id_metodo_pago');
            $table->string('s_metodo_pago')->nullable();
            $table->string('s_img_metodo_pago')->nullable();
            $table->string('s_descripcion_metodo_pago')->nullable();
            $table->unsignedTinyInteger('b_requiere_referencia')->nullable()->default(0);
            $table->unsignedTinyInteger('b_requiere_evidencia')->nullable()->default(0);
            $table->timestamps();
            $table->unsignedTinyInteger('b_activo')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tc_metodos_pagos');
    }
};
