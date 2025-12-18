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
        Schema::create('tw_clientes', function (Blueprint $table) {
            $table->id('id_cliente');
            $table->string('s_nombre_cliente')->nullable();
            $table->string('s_razon_social')->nullable();
            $table->string('s_rfc')->nullable();
            $table->string('s_ine')->nullable();
            $table->string('s_numero_telefono')->nullable();
            $table->string('s_correo')->nullable();
            $table->string('s_comentario')->nullable();
            $table->decimal('n_saldo_actual', 12, 2)->nullable()->default(0);
            $table->decimal('n_limite_credito', 12, 2)->nullable()->default(0);
            $table->unsignedInteger('id_tipo_cliente')->nullable();
            $table->unsignedInteger('id_usuario_crea')->nullable();
            $table->unsignedInteger('id_usuario_modifica')->nullable();
            $table->unsignedTinyInteger('b_credito')->nullable()->default(0);
            $table->timestamps();
            $table->unsignedTinyInteger('b_activo')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tw_clientes');
    }
};
