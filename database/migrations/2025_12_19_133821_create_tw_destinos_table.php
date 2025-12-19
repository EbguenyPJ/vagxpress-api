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
        Schema::create('tw_destinos', function (Blueprint $table) {
            $table->id('id_destino');
            $table->String('s_nombre_destino')->nullable();
            $table->Text('s_direccion')->nullable();
            $table->String('s_referencia_destino')->nullable();
            $table->Integer('id_tipo_destino')->nullable();
            $table->tinyInteger('b_activo')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tw_destinos');
    }
};
