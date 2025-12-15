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
        Schema::create('tc_estados_disponibilidad', function (Blueprint $table) {
            $table->id('id_estado_disponibilidad');
            $table->string('s_estado_disponibilidad');
            $table->tinyInteger('b_activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tc_estados_disponibilidad');
    }
};
