<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tw_sucursales', function (Blueprint $table) {
            $table->id('id_sucursal');
            $table->string('s_sucursal', 255);
            $table->string('s_razon_social', 255);
            $table->string('s_representante_legal', 255);
            $table->string('s_rfc', 255);
            $table->string('n_telefono', 255);
            $table->string('s_correo', 200)->nullable();
            $table->string('s_latitud', 255)->nullable();
            $table->string('s_longitud', 255)->nullable();
            $table->string('s_direccion', 255);
            $table->string('s_colonia', 255);
            $table->string('s_codigo_postal', 255);
            $table->string('s_logo', 255)->nullable();
            $table->string('s_firma', 255)->nullable();
            $table->unsignedBigInteger('id_estado_republica');
            $table->unsignedBigInteger('id_municipio');
            $table->boolean('b_activo');
        });

        Schema::create('tw_empleados', function (Blueprint $table) {
            $table->id('id_empleado');
            $table->string('s_nombre', 255);
            $table->string('s_apellido_paterno', 255);
            $table->string('s_apellido_materno', 255)->nullable();
            $table->string('s_foto_empleado', 255)->nullable();
            $table->string('s_rfc', 255)->nullable();
            $table->string('s_curp', 255)->nullable();
            $table->string('s_correo', 255)->nullable();
            $table->string('s_direccion', 255)->nullable();
            $table->string('s_num_licencia', 255)->nullable();
            $table->string('s_num_seguro', 255)->nullable();
            $table->string('s_qr_empleado', 255)->nullable();
            $table->string('s_telefono', 255)->nullable();
            $table->string('s_contacto_emergencia', 255)->nullable();
            $table->string('s_telefono_contacto_emergencia', 255)->nullable();
            $table->date('d_fecha_nacimiento')->nullable();
            $table->date('d_fecha_ingreso')->nullable();
            $table->unsignedBigInteger('id_tipo_empleado');
            $table->unsignedBigInteger('id_profesion')->nullable();
            $table->unsignedBigInteger('id_grado_estudios')->nullable();
            $table->unsignedBigInteger('id_sucursal')->nullable();
            $table->unsignedBigInteger('id_estado_disponibilidad')->nullable();
            $table->tinyInteger('id_sexo')->nullable();
            $table->unsignedBigInteger('id_registro_rh')->nullable();
            $table->boolean('b_es_usuario')->default(0);
            $table->boolean('b_activo')->default(1);
            $table->string('s_comodin', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('tr_habilidades_empleados', function (Blueprint $table) {
            $table->id('id_habilidad_empleado');
            $table->unsignedBigInteger('id_habilidad');
            $table->unsignedBigInteger('id_empleado');
            $table->tinyInteger('n_nivel_dominio');
            $table->boolean('b_activo');
            $table->unique(['id_habilidad', 'id_empleado']);
        });

        Schema::create('tr_modulos_usuarios', function (Blueprint $table) {
            $table->id('id_modulo_usuario');
            $table->unsignedBigInteger('id_modulo')->nullable();
            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
            $table->unique(['id_modulo', 'id_usuario']);
        });

        Schema::create('tw_versiones', function (Blueprint $table) {
            $table->id('id_version');
            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->string('s_nombre_version', 255)->nullable();
            $table->string('s_descripcion_version', 255)->nullable();
            $table->date('d_fecha_actualizacion_version')->nullable();
            $table->boolean('b_activo')->default(1);
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('tw_versiones');
        Schema::dropIfExists('tr_modulos_usuarios');
        Schema::dropIfExists('tr_habilidades_empleados');
        Schema::dropIfExists('tw_empleados');
        Schema::dropIfExists('tw_sucursales');
    }
};
