<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::where('name', 'admin')->firstOrFail());
    }

    public function test_serie_de_ventas_por_dia_en_formato_apex(): void
    {
        $serie = $this->getJson('/api/dashboard/ventas-pagadas-por-dia')->assertOk()->json();

        $this->assertCount(7, $serie); // 7 días con ventas en el seed
        $this->assertIsInt($serie[0][0]); // timestamp ms
        $this->assertIsInt($serie[0][1]); // conteo
    }

    public function test_contadores_del_dia(): void
    {
        $this->getJson('/api/dashboard/ventas-hoy')->assertOk()->assertJsonPath('total_ventas_hoy', 3);
        $this->getJson('/api/dashboard/ordenes-en-reparto-hoy')->assertOk()->assertJsonPath('total_ordenes_reparto_hoy', 1);
        $this->assertGreaterThan(0, $this->getJson('/api/dashboard/total-ventas-hoy')->json('acumulado_ventas_hoy'));
    }

    public function test_tops_con_los_shapes_que_consume_el_frontend(): void
    {
        $this->getJson('/api/dashboard/top5-clientes')->assertOk()
            ->assertJsonStructure([['id_cliente', 's_nombre_cliente', 'total_ventas', 'monto_total']]);

        $this->getJson('/api/dashboard/top5-refacciones-vendidas')->assertOk()
            ->assertJsonStructure(['top_mas', 'top_menos']);

        $this->getJson('/api/dashboard/ventas-metodos-hoy')->assertOk()
            ->assertJsonStructure(['ventas_por_metodo_pago_hoy' => [['id_metodo_pago', 's_metodo_pago', 'total_ventas']]]);

        $this->getJson('/api/dashboard/top5-refaccionistas')->assertOk()
            ->assertJsonStructure(['top_5_refaccionistas_ingresos']);

        $this->getJson('/api/dashboard/refacciones-criticas')->assertOk()
            ->assertJsonStructure(['top_5_refacciones_criticas']);

        $this->getJson('/api/dashboard/top-proveedores')->assertOk()
            ->assertJsonStructure(['top_proveedores_refacciones' => [['id_proveedor', 'proveedor', 'total_refacciones']]]);
    }

    public function test_refacciones_criticas_detecta_stock_bajo(): void
    {
        $criticas = collect($this->getJson('/api/dashboard/refacciones-criticas')->json('top_5_refacciones_criticas'));

        // Alternador Valeo: stock 2 = mínimo 2
        $this->assertTrue($criticas->contains('s_nombre_refaccion', 'Alternador Valeo 439565'));
    }
}
