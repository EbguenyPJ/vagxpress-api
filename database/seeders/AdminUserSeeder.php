<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Usuario administrador local con acceso a todos los módulos.
 *
 * Credenciales: admin / admin123 (solo para entorno local).
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $idEmpleado = DB::table('tw_empleados')->insertGetId([
            's_nombre' => 'Administrador',
            's_apellido_paterno' => 'Local',
            's_apellido_materno' => 'VagXpress',
            's_correo' => 'admin@vagxpress.local',
            's_telefono' => '5555555555',
            'd_fecha_nacimiento' => '1990-01-01',
            'd_fecha_ingreso' => $now->toDateString(),
            'id_tipo_empleado' => 1, // Sistema
            'id_estado_disponibilidad' => 1, // Disponible
            'id_sucursal' => 1, // Matriz
            'b_es_usuario' => 1,
            'b_activo' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $idUsuario = DB::table('users')->insertGetId([
            'name' => 'admin',
            'email' => 'admin@vagxpress.local',
            'password' => Hash::make('admin123'),
            's_nombre_completo' => 'Administrador Local VagXpress',
            's_token' => '',
            'id_empleado' => $idEmpleado,
            'id_tipo_usuario' => 1, // Super Admin
            'b_usuario_web' => 1,
            'b_usuario_movil' => 1,
            'b_activo' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $modulos = DB::table('tc_modulos')->where('b_activo', 1)->pluck('id_modulo');

        DB::table('tr_modulos_usuarios')->insert(
            $modulos->map(fn ($idModulo) => [
                'id_modulo' => $idModulo,
                'id_usuario' => $idUsuario,
                'b_activo' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ])->all()
        );
    }
}
