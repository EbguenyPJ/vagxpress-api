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
        Schema::create('tw_versiones', function (Blueprint $table) {
            $table->id('id_version');
            $table->integer('id_usuario')->unsigned()->nullable();
            $table->string('s_nombre_version');
            $table->string('s_descripcion_version');
            $table->date('d_fecha_actualizacion_version')->nullable();
            $table->tinyInteger('b_activo')->unsigned()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tw_versiones');
    }
};
