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
        Schema::create('tr_requisiciones_refacciones', function (Blueprint $table) {
            $table->id('id_requisicion_refaccion');
            $table->unsignedInteger('id_requisicion')->nullable();
            $table->unsignedInteger('id_refaccion')->nullable();
            $table->integer('n_cantidad_sugerida')->nullable()->default(0);
            $table->integer('n_cantidad_solicitada')->nullable()->default(0);
            $table->decimal('n_costo_unitario', 10, 2)->nullable()->default(0);
            $table->unsignedInteger('id_motivo_pedido')->nullable();
            $table->unsignedInteger('id_prioridad')->nullable();
            $table->timestamps();
            $table->unsignedTinyInteger('b_activo')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_requisiciones_refacciones');
    }
};
