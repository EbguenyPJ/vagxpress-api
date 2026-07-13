<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_login_correcto_devuelve_token_y_perfil(): void
    {
        $respuesta = $this->postJson('/api/auth/login', [
            'name' => 'admin',
            'password' => 'admin123',
        ]);

        $respuesta->assertCreated()
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure(['data' => [
                'token', 'id_usuario', 'username', 's_nombre_completo',
                'id_empleado', 'id_tipo_usuario', 's_tipo_empleado', 'id_sucursal',
            ]])
            ->assertJsonPath('data.username', 'admin');
    }

    public function test_login_con_password_incorrecta_devuelve_401(): void
    {
        $this->postJson('/api/auth/login', ['name' => 'admin', 'password' => 'incorrecta'])
            ->assertStatus(401)
            ->assertJsonPath('status', 'error');
    }

    public function test_login_sin_datos_devuelve_422_con_errores(): void
    {
        $this->postJson('/api/auth/login', [])
            ->assertStatus(422)
            ->assertJsonStructure(['errors' => ['name', 'password']]);
    }

    public function test_rutas_protegidas_sin_token_devuelven_401(): void
    {
        $this->getJson('/api/usuarios')->assertStatus(401);
        $this->getJson('/api/modulos')->assertStatus(401);
    }

    public function test_logout_revoca_el_token(): void
    {
        $token = $this->postJson('/api/auth/login', ['name' => 'admin', 'password' => 'admin123'])
            ->json('data.token');

        $this->withToken($token)->postJson('/api/auth/logout')->assertOk();

        // limpiar el guard cacheado dentro del mismo test HTTP
        $this->app['auth']->forgetGuards();

        $this->withToken($token)->getJson('/api/usuarios')->assertStatus(401);
    }
}
