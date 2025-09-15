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
        Schema::create('tc_modulos', function (Blueprint $table) {
            $table->id('id_modulo');
            $table->unsignedInteger('id_categoria_modulo')->nullable();
            $table->string('s_modulo')->nullable();
            $table->string('s_ruta')->nullable();
            $table->string('s_icono')->nullable();
            $table->tinyInteger('b_activo')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tc_modulos');
    }
};
