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
        Schema::table('users', function (Blueprint $table) {
            $table->string('s_nombre_completo')->nullable();
            $table->string('s_token')->nullable();
            $table->unsignedInteger('id_empleado')->nullable(false);
            $table->unsignedInteger('id_tipo_usuario')->nullable(false);
            $table->unsignedTinyInteger('b_usuario_web')->default(0);
            $table->unsignedTinyInteger('b_usuario_movil')->default(0);
            $table->tinyInteger('b_activo')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
