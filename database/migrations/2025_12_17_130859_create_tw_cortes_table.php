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
        Schema::create('tw_cortes', function (Blueprint $table) {
            $table->id('id_corte');

            $table->unsignedBigInteger('id_tipo_corte');
            $table->unsignedBigInteger('id_usuario_crea');

            $table->date('d_fecha_corte');

            $table->decimal('n_monto_efectivo', 12, 2)->default(0);
            $table->decimal('n_monto_transferencia', 12, 2)->default(0);
            $table->decimal('n_monto_credito', 12, 2)->default(0);
            $table->decimal('n_monto_tarjeta_debito', 12, 2)->default(0);
            $table->decimal('n_monto_tarjeta_credito', 12, 2)->default(0);

            $table->decimal('n_monto_total', 12, 2)->default(0);

            $table->string('s_descripcion_corte')->nullable();
            $table->text('s_comentario')->nullable();

            $table->timestamps();
            $table->tinyInteger('b_activo')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tw_cortes');
    }
};
