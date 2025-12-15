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
        Schema::create('tw_evidencias_embarque', function (Blueprint $table) {
            $table->id('id_evidenica_embarque');
            $table->Integer('id_embarque')->nullable();
            $table->Integer('id_tipo_evidencia')->nullable();
            $table->String('s_evidencia_embarque')->nullable();
            $table->DateTime('d_fecha_creacion')->nullable();
            $table->tinyInteger('b_activo')->nullable()->default(1);
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tw_evidencias_embarque');
    }
};
