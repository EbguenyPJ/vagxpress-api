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
        Schema::create('tw_abonos', function (Blueprint $table) {
            $table->id('id_abono');
            $table->unsignedInteger('id_credito')->nullable();
            $table->string('s_referencia_pago')->nullable();
            $table->string('s_img_evidencia_pago')->nullable();
            $table->decimal('n_saldo_venta_actual', 12, 2)->nullable()->default(0);
            $table->decimal('n_saldo_cliente_actual', 12, 2)->nullable()->default(0);
            $table->decimal('n_abono', 12, 2)->nullable()->default(0);
            $table->unsignedInteger('id_estatus_abono')->nullable();
            $table->unsignedInteger('id_metodo_pago')->nullable();
            $table->unsignedInteger('id_usuario_crea')->nullable();
            $table->unsignedInteger('id_usuario_modifica')->nullable();
            $table->timestamps();
            $table->unsignedTinyInteger('b_activo')->default(1);




        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tw_abonos');
    }
};
