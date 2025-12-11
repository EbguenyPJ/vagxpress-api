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
        Schema::create('tw_embarques', function (Blueprint $table) {
            $table->id('id_embarque');
            $table->Integer('id_proveedor')->nullable();
            $table->dateTime('d_fecha_creacion')->nullable();
            $table->Integer('id_usuario_crea')->nullable();
            $table->Integer('id_estatus_embarque')->nullable();
            $table->tinyInteger('b_activo')->nullable()->default(1);
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tw_embarques');
    }
};
