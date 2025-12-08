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
        Schema::create('tc_porcentajes_utilidad', function (Blueprint $table) {
            $table->id('id_porcentaje_utilidad');
            $table->string('id_tipo_configuracion')->nullable();
            $table->decimal('n_porcentaje_utilidad', 12, 7)->nullable();
            $table->string('s_porcentaje_utilidad')->nullable();
            $table->string('s_descripcion')->nullable();
            $table->timestamps();
            $table->unsignedTinyInteger('b_activo')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tc_porcentajes_utilidad');
    }
};
