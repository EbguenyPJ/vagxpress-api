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
        Schema::create('tc_cuentas_bancarias', function (Blueprint $table) {
            $table->id('id_cuenta_bancaria');
            $table->string('s_nombre_cuenta')->nullable();
            $table->unsignedBigInteger('n_numero_cuenta')->nullable();
            $table->unsignedBigInteger('n_numero_tarjeta')->nullable();
            $table->unsignedBigInteger('n_CLABE')->nullable();
            $table->unsignedInteger('id_metodo_pago')->nullable();
            $table->unsignedInteger('id_tipo_cuenta')->nullable();
            $table->unsignedInteger('id_banco')->nullable();
            $table->string('b_activo')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tc_cuentas_bancarias');
    }
};
