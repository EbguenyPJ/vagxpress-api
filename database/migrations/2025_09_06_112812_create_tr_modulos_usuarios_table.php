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
        Schema::create('tr_modulos_usuarios', function (Blueprint $table) {
            $table->id('id_modulo_usuario');
            $table->unsignedInteger('id_modulo')->nullable(false);
            $table->unsignedInteger('id_usuario')->nullable(false);
            $table->tinyInteger('b_activo')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_modulos_usuarios');
    }
};
