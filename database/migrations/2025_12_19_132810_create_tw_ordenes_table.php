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
        Schema::create('tw_ordenes', function (Blueprint $table) {
            $table->id('id_orden');
            $table->Integer('id_destino')->nullable();
            $table->Text('s_nota_refaccionista')->nullable();
            $table->Integer('id_repartidor')->nullable();
            $table->DateTime('d_fecha_asignacion')->nullable();
            $table->Integer('id_estatus_orden')->nullable();
            $table->DateTime('d_fecha_entrega')->nullable();
            $table->String('s_nombre_recibe')->nullable();
            $table->String('s_firma')->nullable();
            $table->tinyInteger('b_activo')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tw_ordenes');
    }
};
