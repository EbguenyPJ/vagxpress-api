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
        Schema::create('tc_descripciones_tipos_empleados', function (Blueprint $table) {
            $table->id('id_descripcion_tipo_empleado');
            $table->unsignedBigInteger('id_tipo_empleado');
            $table->text('s_descripcion');
            $table->tinyInteger('b_activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tc_descripciones_tipos_empleados');
    }
};
