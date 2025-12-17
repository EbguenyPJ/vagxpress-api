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
        Schema::create('tr_habilidades_empleados', function (Blueprint $table) {
            $table->id('id_habilidad_empleado');
            $table->unsignedBigInteger('id_habilidad');
            $table->unsignedBigInteger('id_empleado');
            $table->tinyInteger('n_nivel_dominio')->unsigned();
            $table->tinyInteger('b_activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_habilidades_empleados');
    }
};
