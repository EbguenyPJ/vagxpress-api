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
        Schema::create('tc_unidades_medida', function (Blueprint $table) {
            $table->id('id_unidad_medida');
            $table->string('s_unidad_medida')->unique()->nullable();
            $table->tinyInteger('b_activo')->default(1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tc_unidades_medida');
    }
};
