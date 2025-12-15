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
        Schema::create('tw_creditos', function (Blueprint $table) {
            $table->id('id_credito');
            $table->unsignedInteger('id_venta')->nullable();
            $table->string('s_comentario_credito')->nullable();
            $table->decimal('n_total_a_pagar', 12, 2)->nullable()->default(0);
            $table->decimal('n_total_pagado', 12, 2)->nullable()->default(0);
            $table->unsignedInteger('id_tipo_credito')->nullable();
            $table->unsignedInteger('id_estatus_credito')->nullable();
            $table->unsignedInteger('id_usuario_crea')->nullable();
            $table->unsignedInteger('id_usuario_modifica')->nullable();
            $table->date('d_fecha_vencimiento')->nullable();
            $table->timestamps();
            $table->unsignedTinyInteger('b_activo')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tw_creditos');
    }
};
