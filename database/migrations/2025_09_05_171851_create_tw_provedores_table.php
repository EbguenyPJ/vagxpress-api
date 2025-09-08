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
        Schema::create('tw_provedores', function (Blueprint $table) {
            $table->id('id_provedor');
            $table->string('s_provedor')->unique()->nullable();
            $table->string('s_nombre_contacto')->nullable();
            $table->string('s_telefono')->nullable();
            $table->string('s_rfc')->nullable();
            $table->tinyInteger('b_activo')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tw_provedores');
    }
};
