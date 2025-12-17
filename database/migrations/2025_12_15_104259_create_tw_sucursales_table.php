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
        Schema::create('tw_sucursales', function (Blueprint $table) {
            $table->id('id_sucursal');
            $table->string('s_sucursal');
            $table->string('s_razon_social');
            $table->string('s_representante_legal');
            $table->string('s_rfc');
            $table->string('n_telefono');
            $table->string('s_correo', 200)->nullable();
            $table->string('s_latitud')->nullable();
            $table->string('s_longitud')->nullable();
            $table->string('s_direccion');
            $table->string('s_colonia');
            $table->string('s_codigo_postal');
            $table->string('s_logo')->nullable(true);
            $table->string('s_firma')->nullable(true);
            $table->integer('id_estado_republica');
            $table->integer('id_municipio');
            $table->tinyInteger('b_activo');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tw_sucursales');
    }
};
