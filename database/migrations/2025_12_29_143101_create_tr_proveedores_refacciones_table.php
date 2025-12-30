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
        Schema::create('tr_proveedores_refacciones', function (Blueprint $table) {
            $table->id('id_proveedor_refaccion');

            $table->unsignedInteger('id_proveedor')->nullable();
            $table->unsignedInteger('id_refaccion')->nullable();

            $table->decimal('n_ultimo_costo', 10, 2)->default(0);
            $table->date('d_fecha_ultima_compra')->nullable();

            $table->string('s_sku_proveedor')->nullable();
            $table->string('s_no_parte_proveedor')->nullable();
            $table->string('s_codigo_qr_proveedor')->nullable();
            $table->unsignedInteger('id_usuario_crea')->nullable();
            $table->unsignedInteger('id_usuario_edita')->nullable();

            $table->timestamps();
            $table->unsignedTinyInteger('b_activo')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_proveedores_refacciones');
    }
};
