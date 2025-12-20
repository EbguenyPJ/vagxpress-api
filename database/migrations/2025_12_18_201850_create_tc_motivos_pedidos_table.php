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
        Schema::create('tc_motivos_pedidos', function (Blueprint $table) {
            $table->id('id_motivo_pedido');
            $table->string('s_motivo_pedido')->nullable();
            $table->unsignedTinyInteger('b_activo')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tc_motivos_pedidos');
    }
};
