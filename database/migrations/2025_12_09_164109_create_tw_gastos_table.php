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
        Schema::create('tw_gastos', function (Blueprint $table) {
            $table->id('id_gasto');
            $table->Integer('id_tipo_gasto')->nullable();
            $table->Integer('id_tipo_evidencia')->nullable();
            $table->Integer('id_sucursal')->nullable();
            $table->Integer('n_cantidad')->nullable();
            $table->Decimal('n_costo', 10, 2)->nullable();
            $table->String('s_concepto')->nullable();
            $table->String('s_evidencia')->nullable();
            $table->dateTime('d_fecha_gasto')->nullable();
            $table->dateTime('d_fecha_creacion')->nullable();
            $table->Integer('id_usuario_crea')->nullable();
            $table->tinyInteger('b_movil')->nullable();
            $table->tinyInteger('b_activo')->nullable()->default(1);
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tw_gastos');
    }
};
