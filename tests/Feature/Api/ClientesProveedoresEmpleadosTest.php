<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ClientesProveedoresEmpleadosTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::where('name', 'admin')->firstOrFail());
    }

    public function test_listar_clientes_incluye_tipo_cliente(): void
    {
        $this->getJson('/api/clientes')
            ->assertOk()
            ->assertJsonPath('data.0.s_nombre_cliente', 'Público General')
            ->assertJsonPath('data.0.s_tipo_cliente', 'Premium');
    }

    public function test_selector_de_clientes_calcula_saldo_disponible(): void
    {
        $clientes = collect($this->getJson('/api/clientes/selector')->json('data'));
        $taller = $clientes->firstWhere('s_nombre_cliente', 'Taller García Hnos');

        // límite 50,000 menos las 2 ventas a crédito del seed
        $this->assertNotNull($taller);
        $this->assertLessThan(50000, (float) $taller['saldo_actual']);
    }

    public function test_crear_cliente_asigna_usuario_desde_el_token(): void
    {
        $this->postJson('/api/clientes', [
            's_nombre_cliente' => 'Cliente Nuevo Test',
            'id_tipo_cliente' => 1,
        ])->assertCreated();

        $this->assertDatabaseHas('tw_clientes', [
            's_nombre_cliente' => 'Cliente Nuevo Test',
            'id_usuario_crea' => User::where('name', 'admin')->first()->id,
        ]);
    }

    public function test_crear_cliente_sin_nombre_devuelve_422(): void
    {
        $this->postJson('/api/clientes', ['id_tipo_cliente' => 1])
            ->assertStatus(422)
            ->assertJsonStructure(['errors' => ['s_nombre_cliente']]);
    }

    public function test_actualizar_cliente(): void
    {
        $this->putJson('/api/clientes/3', [
            's_nombre_cliente' => 'María F. López de Test',
            'id_tipo_cliente' => 2,
        ])->assertOk();

        $this->assertDatabaseHas('tw_clientes', ['id_cliente' => 3, 's_nombre_cliente' => 'María F. López de Test']);
    }

    public function test_crear_proveedor_duplicado_devuelve_409(): void
    {
        $this->postJson('/api/proveedores', [
            's_proveedor' => 'Autopartes del Norte SA',
            's_rfc' => 'ADN010101AB1',
        ])->assertStatus(409);
    }

    public function test_crear_proveedor_limpia_telefono(): void
    {
        $this->postJson('/api/proveedores', [
            's_proveedor' => 'Proveedor Test',
            's_telefono' => '(81) 8123-4567',
        ])->assertCreated();

        $this->assertDatabaseHas('tw_proveedores', ['s_proveedor' => 'Proveedor Test', 's_telefono' => '8181234567']);
    }

    public function test_listar_empleados_incluye_catalogos(): void
    {
        $respuesta = $this->getJson('/api/empleados')->assertOk();

        $admin = collect($respuesta->json('data'))->firstWhere('s_nombre', 'Administrador');
        $this->assertSame('Sistema', $admin['s_tipo_empleado']);
        $this->assertSame('Matriz', $admin['s_sucursal']);
    }

    public function test_crear_empleado_asigna_qr_y_habilidades_por_tipo(): void
    {
        $respuesta = $this->postJson('/api/empleados', [
            's_nombre' => 'Nuevo',
            's_apellido_paterno' => 'Empleado',
            's_apellido_materno' => 'Test',
            's_telefono' => '81-9999-0000',
            'd_fecha_nacimiento' => '1995-05-05',
            'd_fecha_ingreso' => '2026-07-01',
            'id_tipo_empleado' => 2, // Repartidor: tiene habilidades en catálogo
            'id_sucursal' => 1,
        ])->assertCreated();

        $idEmpleado = $respuesta->json('data.id_empleado');
        $this->assertSame('ESC-' . $idEmpleado, $respuesta->json('data.s_qr_empleado'));

        $habilidades = $this->getJson("/api/empleados/{$idEmpleado}/habilidades")->json('data');
        $this->assertNotEmpty($habilidades);
    }

    public function test_crear_empleado_duplicado_devuelve_409(): void
    {
        $payload = [
            's_nombre' => 'Pedro',
            's_apellido_paterno' => 'Ramírez',
            's_apellido_materno' => 'Soto',
            's_telefono' => '8188888888',
            'd_fecha_nacimiento' => '1992-06-15',
            'd_fecha_ingreso' => '2026-07-01',
            'id_tipo_empleado' => 2,
            'id_sucursal' => 1,
        ];

        $this->postJson('/api/empleados', $payload)->assertStatus(409);
    }

    public function test_cambiar_tipo_de_empleado_reemplaza_habilidades(): void
    {
        // Pedro (id 2) es Repartidor; su set de habilidades es del tipo 2
        $antes = $this->getJson('/api/empleados/2/habilidades')->json('data');

        $this->putJson('/api/empleados/2', ['id_tipo_empleado' => 1])->assertOk();

        $despues = $this->getJson('/api/empleados/2/habilidades')->json('data');
        $this->assertNotEquals(
            collect($antes)->pluck('id_habilidad')->sort()->values(),
            collect($despues)->pluck('id_habilidad')->sort()->values(),
        );
    }

    public function test_empleado_por_usuario_resuelve_via_users(): void
    {
        $admin = User::where('name', 'admin')->first();

        $this->getJson("/api/empleados/usuario/{$admin->id}")
            ->assertOk()
            ->assertJsonPath('data.id_empleado', $admin->id_empleado);
    }
}
