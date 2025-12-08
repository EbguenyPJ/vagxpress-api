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
        Schema::create('tc_tipos_configuraciones', function (Blueprint $table) {
            $table->id('id_tipo_configuracion');
            $table->unsignedInteger('id_modulo')->nullable();
            $table->string('s_tipo_configuracion')->nullable();
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
        Schema::dropIfExists('tc_tipos_configuraciones');
    }
};
