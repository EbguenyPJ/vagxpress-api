<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UsuariosModulosTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function autenticar(): User
    {
        $admin = User::where('name', 'admin')->firstOrFail();
        Sanctum::actingAs($admin);

        return $admin;
    }

    public function test_listar_usuarios(): void
    {
        $this->autenticar();

        $this->getJson('/api/usuarios')
            ->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.0.name', 'admin');
    }

    public function test_registrar_usuario_desde_empleado(): void
    {
        $this->autenticar();

        $respuesta = $this->postJson('/api/usuarios', [
            'id_empleado' => 3, // Luis Hernández (repartidor demo, sin usuario)
            'name' => 'luis.hernandez',
            'password' => 'secreto123',
            'id_tipo_usuario' => 2,
        ]);

        $respuesta->assertCreated()->assertJsonPath('data.name', 'luis.hernandez');

        $this->assertDatabaseHas('users', ['name' => 'luis.hernandez', 'id_empleado' => 3]);
        $this->assertDatabaseHas('tw_empleados', ['id_empleado' => 3, 'b_es_usuario' => 1]);
    }

    public function test_registrar_usuario_para_empleado_con_usuario_devuelve_409(): void
    {
        $admin = $this->autenticar();

        $this->postJson('/api/usuarios', [
            'id_empleado' => $admin->id_empleado,
            'name' => 'otro.usuario',
            'password' => 'secreto123',
            'id_tipo_usuario' => 2,
        ])->assertStatus(409);
    }

    public function test_perfil_de_usuario_incluye_datos_de_empleado(): void
    {
        $admin = $this->autenticar();

        $this->getJson("/api/usuarios/{$admin->id}/perfil")
            ->assertOk()
            ->assertJsonPath('data.s_tipo_empleado', 'Sistema');
    }

    public function test_perfil_inexistente_devuelve_404(): void
    {
        $this->autenticar();

        $this->getJson('/api/usuarios/99999/perfil')->assertNotFound();
    }

    public function test_actualizar_accesos_web_y_movil(): void
    {
        $admin = $this->autenticar();

        $this->putJson("/api/usuarios/{$admin->id}/accesos", ['b_usuario_movil' => 0])
            ->assertOk()
            ->assertJsonPath('data.b_usuario_movil', false);
    }

    public function test_modulos_del_usuario_traen_categoria_y_ruta(): void
    {
        $admin = $this->autenticar();

        $respuesta = $this->getJson("/api/usuarios/{$admin->id}/modulos")->assertOk();

        $modulos = collect($respuesta->json('data'));
        $this->assertGreaterThan(0, $modulos->count());
        $this->assertTrue($modulos->contains(fn ($m) => $m['s_ruta'] === '/punto-venta'));
    }

    public function test_sincronizar_modulos_conserva_historial_inactivo(): void
    {
        $admin = $this->autenticar();

        $this->putJson("/api/usuarios/{$admin->id}/modulos", ['modulos' => [1, 2]])
            ->assertOk()
            ->assertJsonCount(2, 'data');

        $this->assertDatabaseHas('tr_modulos_usuarios', [
            'id_usuario' => $admin->id, 'id_modulo' => 3, 'b_activo' => 0,
        ]);
    }

    public function test_catalogo_de_modulos_disponibles(): void
    {
        $this->autenticar();

        $respuesta = $this->getJson('/api/modulos')->assertOk();
        // 15 = módulos activos cuya categoría también está activa ("Inicio" está inactiva en el catálogo)
        $this->assertCount(15, $respuesta->json('data'));
    }
}
