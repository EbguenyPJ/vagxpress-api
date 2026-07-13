<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CatalogosTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::where('name', 'admin')->firstOrFail());
    }

    public function test_catalogos_planos_devuelven_solo_activos(): void
    {
        foreach ([
            'marcas-refacciones' => 19,
            'categorias-refacciones' => 15,
            'subcategorias-refacciones' => 80,
            'metodos-pago' => 5,
            'unidades-medida' => 9,
            'tipos-cliente' => 2,
        ] as $catalogo => $esperados) {
            $respuesta = $this->getJson("/api/catalogos/$catalogo")->assertOk();
            $this->assertCount($esperados, $respuesta->json('data'), "catálogo $catalogo");
        }
    }

    public function test_metodos_de_pago_conservan_ids_semanticos(): void
    {
        $metodos = collect($this->getJson('/api/catalogos/metodos-pago')->json('data'));

        $this->assertSame('Credito', $metodos->firstWhere('id_metodo_pago', 1)['s_metodo_pago']);
        $this->assertSame('Efectivo', $metodos->firstWhere('id_metodo_pago', 2)['s_metodo_pago']);
    }

    public function test_porcentajes_utilidad_solo_tipos_configurables(): void
    {
        $porcentajes = collect($this->getJson('/api/catalogos/porcentajes-utilidad')->json('data'));

        $this->assertNotEmpty($porcentajes);
        $this->assertTrue($porcentajes->every(fn ($p) => in_array($p['id_tipo_configuracion'], [2, 3])));
    }

    public function test_cuentas_bancarias_incluyen_imagen_del_banco(): void
    {
        $this->getJson('/api/catalogos/cuentas-bancarias')
            ->assertOk()
            ->assertJsonStructure(['data' => [['id_cuenta_bancaria', 's_nombre_cuenta', 'id_metodo_pago', 's_img_banco']]]);
    }

    public function test_sucursales_incluyen_estado_y_municipio(): void
    {
        $this->getJson('/api/catalogos/sucursales')
            ->assertOk()
            ->assertJsonPath('data.0.s_sucursal', 'Matriz');
    }

    public function test_catalogo_desconocido_devuelve_404(): void
    {
        $this->getJson('/api/catalogos/no-existe')->assertNotFound();
    }
}
