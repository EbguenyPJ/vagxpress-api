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
        Schema::create('tc_tipos_evidencias', function (Blueprint $table) {
            $table->id('id_tipo_evidencia');
            $table->String('s_tipo_evidencia')->nullable();
            $table->tinyInteger('b_activo')->nullable()->default(1);
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tc_tipos_evidencias');
    }
};
