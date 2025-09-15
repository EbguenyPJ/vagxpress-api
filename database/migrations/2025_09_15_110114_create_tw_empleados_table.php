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
        Schema::create('tw_empleados', function (Blueprint $table) {
            $table->id('id_empleado');
            $table->string('s_nombre')->nullable(false);
            $table->string('s_apellido_paterno')->nullable(false);
            $table->string('s_apellido_materno')->nullable();
            $table->string('s_foto_empleado')->nullable();
            $table->string('s_rfc')->nullable();
            $table->string('s_curp')->nullable();
            $table->string('s_correo')->nullable();
            $table->string('s_direccion')->nullable();
            $table->string('s_num_licencia')->nullable();
            $table->string('s_num_seguro')->nullable();
            $table->string('s_qr_empleado')->nullable();
            $table->string('s_telefono')->nullable();
            $table->string('s_contacto_emergencia')->nullable();
            $table->string('s_telefono_contacto_emergencia')->nullable();
            $table->date('d_fecha_nacimiento')->nullable();
            $table->date('d_fecha_ingreso')->nullable();
            $table->unsignedInteger('id_tipo_empleado')->nullable(false);
            $table->unsignedInteger('id_profesion')->nullable();
            $table->unsignedInteger('id_grado_estudios')->nullable();
            $table->unsignedInteger('id_sucursal')->nullable();
            $table->unsignedInteger('id_estado_disponibilidad')->nullable();
            $table->unsignedTinyInteger('id_sexo')->nullable();
            $table->unsignedInteger('id_registro_rh')->nullable();
            $table->tinyInteger('b_es_usuario')->default(0);
            $table->tinyInteger('b_activo')->default(1);
            $table->string('s_comodin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tw_empleados');
    }
};
