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
        Schema::create('tr_refacciones_equivalencias', function (Blueprint $table) {
            $table->id('id_refaccion_equivalencia');
            $table->unsignedInteger('id_refaccion')->nullable();
            $table->unsignedInteger('id_equivalencia')->nullable();
            $table->unsignedInteger('id_usuario_crea')->nullable();
            $table->unsignedInteger('id_usuario_edita')->nullable();
            $table->tinyInteger('b_activo')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_refacciones_equivalencias');
    }
};
