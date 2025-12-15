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
        Schema::create('tc_grados_estudios', function (Blueprint $table) {
            $table->id('id_grado_estudios');
            $table->string('s_grado_estudios');
            $table->tinyInteger('b_activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tc_grados_estudios');
    }
};
