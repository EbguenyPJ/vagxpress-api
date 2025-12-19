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
        Schema::create('tw_cortes_evidencias', function (Blueprint $table) {
            $table->id('id_corte_evidencia');

            $table->unsignedBigInteger('id_corte');
            $table->unsignedBigInteger('id_metodo_pago');
            $table->unsignedBigInteger('id_tipo_evidencia');

            $table->string('s_nombre_archivo');
            $table->string('s_descripcion')->nullable();

            $table->timestamps();
            $table->tinyInteger('b_activo')->default(1);

            // Evita duplicados accidentales
            // $table->unique(
            //     ['id_corte', 'id_metodo_pago', 's_nombre_archivo'],
            //     'uk_corte_metodo_archivo'
            // );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tw_cortes_evidencias');
    }
};
