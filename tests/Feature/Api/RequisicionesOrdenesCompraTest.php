<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RequisicionesOrdenesCompraTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::where('name', 'admin')->firstOrFail());
    }

    public function test_listar_requisiciones_con_estatus_y_tipo(): void
    {
        $this->getJson('/api/requisiciones')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure(['data' => [['id_requisicion', 's_estatus_requisicion', 's_tipo_requisicion', 'n_total_estimado']]]);
    }

    public function test_detalle_de_requisicion_trae_renglones_completos(): void
    {
        $respuesta = $this->getJson('/api/requisiciones/1')->assertOk();

        $this->assertCount(2, $respuesta->json('data')); // requisición demo 1 tiene 2 renglones
        $this->assertArrayHasKey('s_motivo_pedido', $respuesta->json('data.0'));
        $this->assertArrayHasKey('s_prioridad', $respuesta->json('data.0'));
    }

    public function test_previsualizacion_por_proveedor_con_inteligencia_de_precios(): void
    {
        $respuesta = $this->getJson('/api/requisiciones/1/por-proveedor')->assertOk();

        $grupos = $respuesta->json('data');
        $this->assertNotEmpty($grupos);
        $this->assertArrayHasKey('total_estimado_proveedor', $grupos[0]);
        $this->assertArrayHasKey('alerta_mejor_precio', $grupos[0]['items'][0]);
    }

    public function test_generar_orden_de_compra_desde_requisicion(): void
    {
        $renglones = $this->getJson('/api/requisiciones/1')->json('data');

        $respuesta = $this->postJson('/api/ordenes-compra', [
            'ordenes' => [[
                'id_requisicion' => 1,
                'id_proveedor' => 1,
                'refacciones' => collect($renglones)->map(fn ($r) => [
                    'id_requisicion_refaccion' => $r['id_requisicion_refaccion'],
                    'b_autorizada' => 1,
                ])->all(),
            ]],
        ])->assertCreated();

        $orden = $respuesta->json('data.0');
        $this->assertStringStartsWith('OC', $orden['s_folio_interno']);
        $this->assertStringContainsString('-R1-P1', $orden['s_folio_interno']);

        // los renglones pasan a "En Orden de Compra" (3)
        foreach ($renglones as $r) {
            $this->assertDatabaseHas('tr_requisiciones_refacciones', [
                'id_requisicion_refaccion' => $r['id_requisicion_refaccion'],
                'id_estatus_requisicion' => 3,
            ]);
        }
    }

    public function test_ordenes_sin_renglones_autorizados_no_se_generan(): void
    {
        $renglones = $this->getJson('/api/requisiciones/1')->json('data');

        $respuesta = $this->postJson('/api/ordenes-compra', [
            'ordenes' => [[
                'id_requisicion' => 1,
                'id_proveedor' => 1,
                'refacciones' => [[
                    'id_requisicion_refaccion' => $renglones[0]['id_requisicion_refaccion'],
                    'b_autorizada' => 0,
                ]],
            ]],
        ])->assertCreated();

        $this->assertSame([], $respuesta->json('data'));
    }

    public function test_aprobar_orden_actualiza_cantidades_y_total(): void
    {
        // la OC 1 del seed está Creada, con 1 renglón (12 × costo de compra)
        $detalle = $this->getJson('/api/ordenes-compra/1')->json('data');

        $this->putJson('/api/ordenes-compra/1/gestionar', [
            'id_estatus_orden_compra' => 2,
            'refacciones' => [[
                'id_requisicion_refaccion' => $detalle[0]['id_requisicion_refaccion'],
                'n_cantidad_solicitada' => 5,
            ]],
        ])->assertOk();

        $this->assertDatabaseHas('tw_ordenes_compras', [
            'id_orden_compra' => 1,
            'id_estatus_orden_compra' => 2,
            'n_total_estimado' => 5 * 950.00, // filtro K&N costo compra
        ]);
    }

    public function test_rechazar_orden_libera_los_renglones(): void
    {
        $detalle = $this->getJson('/api/ordenes-compra/1')->json('data');

        $this->putJson('/api/ordenes-compra/1/gestionar', [
            'id_estatus_orden_compra' => 3,
            'refacciones' => [[
                'id_requisicion_refaccion' => $detalle[0]['id_requisicion_refaccion'],
                'n_cantidad_solicitada' => 0,
            ]],
        ])->assertOk();

        $this->assertDatabaseHas('tw_ordenes_compras', ['id_orden_compra' => 1, 'id_estatus_orden_compra' => 3]);
        $this->assertDatabaseHas('tr_requisiciones_refacciones', [
            'id_requisicion_refaccion' => $detalle[0]['id_requisicion_refaccion'],
            'id_estatus_requisicion' => 4,
        ]);
    }

    public function test_pdf_de_orden_de_compra_en_base64(): void
    {
        $respuesta = $this->getJson('/api/ordenes-compra/1/pdf')->assertOk();

        $this->assertNotEmpty($respuesta->json('data.folio'));
        $this->assertSame('PDF', substr(base64_decode($respuesta->json('data.file_base64')), 1, 3));
    }
}
