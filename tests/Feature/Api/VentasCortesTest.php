<?php

namespace Tests\Feature\Api;

use App\Models\Refaccion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class VentasCortesTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::where('name', 'admin')->firstOrFail());
    }

    public function test_bitacora_de_ventas_con_relaciones(): void
    {
        $this->getJson('/api/ventas')
            ->assertOk()
            ->assertJsonCount(9, 'data')
            ->assertJsonStructure(['data' => [['id_venta', 's_estatus_venta', 's_metodo_pago', 's_nombre_cliente', 'fecha_venta']]]);
    }

    public function test_crear_venta_descuenta_stock_y_genera_ticket(): void
    {
        $stockAntes = Refaccion::find(1)->n_stock_actual;

        $respuesta = $this->postJson('/api/ventas', [
            'id_cliente' => 3,
            'id_metodo_pago' => 2, // Efectivo
            'refacciones' => [
                ['id_refaccion' => 1, 'n_cantidad' => 2],
            ],
        ])->assertCreated();

        $this->assertSame($stockAntes - 2, Refaccion::find(1)->n_stock_actual);
        $this->assertNotEmpty($respuesta->json('ticket_base64'));
        $this->assertSame('PDF', substr(base64_decode($respuesta->json('ticket_base64')), 1, 3));

        // subtotal = 2 × 260.00 (precio venta), total = subtotal × 1.16
        $this->assertDatabaseHas('tw_ventas', [
            'n_subtotal' => 520.00,
            'n_total' => 603.20,
            'b_corte' => 0,
        ]);
    }

    public function test_venta_aplica_porcentaje_de_utilidad_al_total(): void
    {
        // corrige el bug del código original que guardaba el porcentaje pero no lo aplicaba
        $porcentaje = \App\Models\PorcentajeUtilidad::activo()->whereIn('id_tipo_configuracion', [2, 3])->firstOrFail();

        $this->postJson('/api/ventas', [
            'id_cliente' => 3,
            'id_metodo_pago' => 2,
            'refacciones' => [
                ['id_refaccion' => 1, 'n_cantidad' => 1, 'id_porcentaje_utilidad' => $porcentaje->id_porcentaje_utilidad],
            ],
        ])->assertCreated();

        $esperado = round(260.00 * (1 + $porcentaje->n_porcentaje_utilidad / 100), 2);
        $this->assertDatabaseHas('tr_ventas_refacciones', ['n_total' => $esperado]);
    }

    public function test_venta_a_credito_crea_credito_y_sube_saldo_del_cliente(): void
    {
        $saldoAntes = (float) \App\Models\Cliente::find(2)->n_saldo_actual;

        $this->postJson('/api/ventas', [
            'id_cliente' => 2,
            'id_metodo_pago' => 1, // Crédito
            'refacciones' => [['id_refaccion' => 3, 'n_cantidad' => 1]],
        ])->assertCreated();

        $totalVenta = 1250.00 * 1.16;
        $this->assertDatabaseHas('tw_creditos', ['n_total_a_pagar' => $totalVenta, 'id_estatus_credito' => 1]);
        $this->assertEquals($saldoAntes + $totalVenta, (float) \App\Models\Cliente::find(2)->fresh()->n_saldo_actual);
    }

    public function test_venta_con_stock_bajo_dispara_requisicion_automatica(): void
    {
        // Alternador (id 11): stock 2, mínimo 2 → vender 1 deja 1 <= 2
        $this->postJson('/api/ventas', [
            'id_cliente' => 1,
            'id_metodo_pago' => 2,
            'refacciones' => [['id_refaccion' => 11, 'n_cantidad' => 1]],
        ])->assertCreated();

        $this->assertDatabaseHas('tr_requisiciones_refacciones', [
            'id_refaccion' => 11,
            'id_motivo_pedido' => 2,
            'id_prioridad' => 3,
        ]);
        $this->assertDatabaseHas('tw_requisiciones', ['id_tipo_requisicion' => 1, 'id_estatus_requisicion' => 1]);
    }

    public function test_venta_sin_refacciones_devuelve_422(): void
    {
        $this->postJson('/api/ventas', ['id_cliente' => 1, 'id_metodo_pago' => 2, 'refacciones' => []])
            ->assertStatus(422);
    }

    public function test_crear_cotizacion_calcula_totales_sin_tocar_stock(): void
    {
        $stockAntes = Refaccion::find(4)->n_stock_actual;

        $this->postJson('/api/cotizaciones', [
            'id_cliente' => 2,
            'refacciones' => [['id_refaccion' => 4, 'n_cantidad' => 2]],
        ])->assertCreated();

        $this->assertSame($stockAntes, Refaccion::find(4)->n_stock_actual);
        $this->assertDatabaseHas('tw_cotizaciones', [
            'n_subtotal' => 4200.00, // 2 × 2100
            'n_total' => 4872.00,
            'id_estatus_cotizacion' => 1,
        ]);
    }

    public function test_crear_corte_asocia_ventas_del_dia_y_las_marca(): void
    {
        $ventasHoySinCorte = \App\Models\Venta::where('b_corte', 0)->whereDate('created_at', now())->count();
        $this->assertGreaterThan(0, $ventasHoySinCorte);

        $respuesta = $this->postJson('/api/cortes', [
            'id_tipo_corte' => 1,
            'fecha_corte' => now()->toDateString(),
            'monto_efectivo' => 5000,
            'monto_transferencia' => 1200,
            'monto_tarjeta_debito' => 8000,
        ])->assertCreated();

        $this->assertNotEmpty($respuesta->json('data.ventas_corte'));
        $this->assertSame(0, \App\Models\Venta::where('b_corte', 0)->whereDate('created_at', now())->whereIn('id_metodo_pago', [2, 4, 5])->count());
    }

    public function test_desglosado_del_dia_agrupa_por_metodo(): void
    {
        $respuesta = $this->getJson('/api/cortes/desglosado')->assertOk();

        $this->assertGreaterThan(0, $respuesta->json('data.total_general'));
        $metodos = collect($respuesta->json('data.resumen'))->pluck('s_nombre_metodo');
        $this->assertTrue($metodos->contains('Efectivo'));
    }

    public function test_ventas_corte_del_dia(): void
    {
        $respuesta = $this->getJson('/api/ventas/corte')->assertOk();

        $this->assertCount(3, $respuesta->json('data')); // 3 ventas de hoy en el seed
        $this->assertGreaterThan(0, $respuesta->json('total_dia'));
    }
}
