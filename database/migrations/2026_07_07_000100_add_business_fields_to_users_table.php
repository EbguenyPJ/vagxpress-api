<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Campos de negocio del usuario: vínculo con empleado, tipo de usuario,
     * flags de acceso web/móvil y estado. El login se hace por `name`,
     * por lo que debe ser único.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('s_nombre_completo')->nullable();
            $table->string('s_token')->nullable();
            $table->unsignedBigInteger('id_empleado')->nullable();
            $table->unsignedBigInteger('id_tipo_usuario')->nullable();
            $table->boolean('b_usuario_web')->nullable()->default(1);
            $table->boolean('b_usuario_movil')->nullable()->default(0);
            $table->boolean('b_activo')->nullable()->default(1);

            $table->unique('name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['name']);
            $table->dropColumn([
                's_nombre_completo', 's_token', 'id_empleado', 'id_tipo_usuario',
                'b_usuario_web', 'b_usuario_movil', 'b_activo',
            ]);
        });
    }
};
