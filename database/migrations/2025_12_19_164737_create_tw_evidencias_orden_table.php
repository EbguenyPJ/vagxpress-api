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
        Schema::create('tw_evidencias_orden', function (Blueprint $table) {
            $table->id('id_evidencia_orden');
            $table->Integer('id_orden')->nullable();
            $table->String('s_evidencia_orden')->nullable();
            $table->Integer('id_tipo_evidencia')->nullable();
            $table->tinyInteger('b_activo')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tw_evidencias_orden');
    }
};
