<?php

namespace App\Services;

use App\Exceptions\DomainException;
use App\Models\Empleado;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class UsuarioService
{
    public function listar(): Collection
    {
        return User::orderBy('id')->get();
    }

    public function perfil(int $idUsuario): User
    {
        return User::with('empleado.tipoEmpleado')->findOrFail($idUsuario);
    }

    /**
     * Registra un usuario a partir de un empleado existente sin usuario previo.
     */
    public function registrar(array $datos): User
    {
        $empleado = Empleado::findOrFail($datos['id_empleado']);

        if (User::where('id_empleado', $empleado->id_empleado)->exists()) {
            throw new DomainException('El empleado ya tiene un usuario registrado');
        }

        return DB::transaction(function () use ($datos, $empleado) {
            $user = User::create([
                'name' => $datos['name'],
                'password' => $datos['password'],
                'email' => $empleado->s_correo,
                'id_empleado' => $empleado->id_empleado,
                's_nombre_completo' => trim("{$empleado->s_nombre} {$empleado->s_apellido_paterno} {$empleado->s_apellido_materno}"),
                'id_tipo_usuario' => $datos['id_tipo_usuario'],
                's_token' => '',
                'b_activo' => 1,
            ]);

            $empleado->update(['b_es_usuario' => 1]);

            return $user;
        });
    }

    public function actualizarAccesos(int $idUsuario, array $datos): User
    {
        $user = User::findOrFail($idUsuario);
        $user->fill(array_intersect_key($datos, array_flip(['b_usuario_web', 'b_usuario_movil'])));
        $user->save();

        return $user;
    }

    public function actualizarEstatus(int $idUsuario, bool $activo): User
    {
        $user = User::findOrFail($idUsuario);
        $user->update(['b_activo' => $activo]);

        return $user;
    }

    public function actualizarTipoUsuario(int $idUsuario, int $idTipoUsuario): User
    {
        $user = User::findOrFail($idUsuario);
        $user->update(['id_tipo_usuario' => $idTipoUsuario]);

        return $user;
    }
}
