<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tw_reglas_compatibilidad', function (Blueprint $table) {
            $table->id('id_regla');
            $table->unsignedBigInteger('id_refaccion')->nullable();
            $table->string('s_resumen', 255)->nullable();
            $table->unsignedBigInteger('id_usuario_crea')->nullable();
            $table->unsignedBigInteger('id_usuario_edita')->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tr_reglas_marcas', function (Blueprint $table) {
            $table->id('id_regla_marca');
            $table->unsignedBigInteger('id_regla')->nullable();
            $table->unsignedBigInteger('id_marca_vehiculo')->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tr_reglas_modelos', function (Blueprint $table) {
            $table->id('id_regla_modelo');
            $table->unsignedBigInteger('id_regla')->nullable();
            $table->unsignedBigInteger('id_modelo_vehiculo')->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tr_reglas_generaciones', function (Blueprint $table) {
            $table->id('id_regla_generacion');
            $table->unsignedBigInteger('id_regla')->nullable();
            $table->unsignedBigInteger('id_generacion')->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

        Schema::create('tr_reglas_motores', function (Blueprint $table) {
            $table->id('id_regla_motor');
            $table->unsignedBigInteger('id_regla')->nullable();
            $table->unsignedBigInteger('id_motor')->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('tr_reglas_motores');
        Schema::dropIfExists('tr_reglas_generaciones');
        Schema::dropIfExists('tr_reglas_modelos');
        Schema::dropIfExists('tr_reglas_marcas');
        Schema::dropIfExists('tw_reglas_compatibilidad');
    }
};
