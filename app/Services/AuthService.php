<?php

namespace App\Services;

use App\Exceptions\DomainException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Autentica por nombre de usuario y emite un token Sanctum.
     *
     * @param  bool  $web  true exige acceso web, false exige acceso móvil.
     * @return array{token: string, user: User}
     */
    public function login(string $name, string $password, bool $web = true): array
    {
        $user = User::activo()
            ->where('name', $name)
            ->with('empleado.tipoEmpleado')
            ->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw new DomainException('Usuario o contraseña incorrectos', 401);
        }

        if ($web && ! $user->b_usuario_web) {
            throw new DomainException('No es un usuario web', 401);
        }

        if (! $web && ! $user->b_usuario_movil) {
            throw new DomainException('No es un usuario móvil', 401);
        }

        return [
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => $user,
        ];
    }
}
