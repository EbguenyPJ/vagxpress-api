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
        Schema::create('tc_tipos_empleados', function (Blueprint $table) {
            $table->id('id_tipo_empleado');
            $table->string('s_tipo_empleado')->nullable();
            $table->unsignedInteger('b_activo')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tc_tipos_empleados');
    }
};
