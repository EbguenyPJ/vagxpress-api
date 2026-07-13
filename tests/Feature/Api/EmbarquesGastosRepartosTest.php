<?php

namespace Tests\Feature\Api;

use App\Models\Refaccion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EmbarquesGastosRepartosTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    // 1×1 px válido para probar compresión de evidencias
    private const PIXEL_JPG = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/wAALCAABAAEBAREA/8QAFAABAAAAAAAAAAAAAAAAAAAACf/EABQQAQAAAAAAAAAAAAAAAAAAAAD/2gAIAQEAAD8AKp//2Q==';

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::where('name', 'admin')->firstOrFail());
    }

    public function test_listado_de_embarques_con_estatus(): void
    {
        $this->getJson('/api/embarques')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure(['data' => [['id_embarque', 's_proveedor', 's_estatus_embarque', 's_nombre_completo']]]);
    }

    public function test_crear_embarque_con_entrada_existente_y_pre_registro(): void
    {
        $this->postJson('/api/embarques', [
            'id_proveedor' => 1,
            'evidencias' => [['imagen' => self::PIXEL_JPG]],
            'entradas' => [
                ['id_refaccion' => 1, 'n_cantidad_recibida' => 5, 'n_precio_compra' => 175.00, 'codigo_barras' => 'CB-T1'],
                ['id_refaccion' => null, 'n_cantidad_recibida' => 3, 'n_precio_compra' => 99.00, 'codigo_barras' => 'CB-T2',
                 's_nombre_refaccion' => 'Refacción Nueva Embarque', 's_numero_parte' => 'NEW-01',
                 'id_marca_refaccion' => 1, 'id_categoria_refaccion' => 1, 'id_subcategoria_refaccion' => 1, 'id_clase_refaccion' => 1],
            ],
        ])->assertCreated();

        $this->assertDatabaseHas('tw_pre_registro_refacciones', ['s_nombre_refaccion' => 'Refacción Nueva Embarque']);
        $this->assertDatabaseHas('tr_entradas_embarque', ['s_codigo_barras' => 'CB-T1', 'id_estatus_entrada' => 1]);
        $this->assertDatabaseHas('tw_evidencias_embarque', ['id_tipo_evidencia' => 1]);
    }

    public function test_aprobar_embarque_suma_stock_y_da_de_alta_pendientes(): void
    {
        $stockAntes = Refaccion::find(1)->n_stock_actual;

        // embarque 1 del seed está pendiente con 10 unidades de la refacción 1
        $this->postJson('/api/embarques/1/aprobar', [
            'entradas' => [['id_refaccion' => 1, 'n_cantidad' => 10, 'n_precio_compra' => 180.00]],
            'pendientes' => [[
                's_nombre_refaccion' => 'Alta desde embarque', 's_numero_parte' => 'ALTA-01',
                'id_marca_refaccion' => 1, 'id_categoria_refaccion' => 1,
                'id_subcategoria_refaccion' => 1, 'id_clase_refaccion' => 1, 'n_precio_compra' => 50,
            ]],
        ])->assertOk();

        $this->assertSame($stockAntes + 10, Refaccion::find(1)->fresh()->n_stock_actual);
        $this->assertDatabaseHas('tw_refacciones', ['s_nombre_refaccion' => 'Alta desde embarque']);
        $this->assertDatabaseHas('tw_embarques', ['id_embarque' => 1, 'id_estatus_embarque' => 2]);
        $this->assertDatabaseMissing('tr_entradas_embarque', ['id_embarque' => 1, 'id_estatus_entrada' => 1]);
    }

    public function test_rechazar_embarque_marca_entradas(): void
    {
        $this->postJson('/api/embarques/1/rechazar')->assertOk();

        $this->assertDatabaseHas('tw_embarques', ['id_embarque' => 1, 'id_estatus_embarque' => 3]);
    }

    public function test_refacciones_insertadas_agrupa_aprobadas(): void
    {
        // el embarque 2 del seed está aprobado con 2 entradas
        $respuesta = $this->getJson('/api/embarques/refacciones-insertadas')->assertOk();

        $this->assertCount(2, $respuesta->json('data'));
    }

    public function test_detalle_de_embarque_separa_entradas_y_pendientes(): void
    {
        $this->getJson('/api/embarques/1')
            ->assertOk()
            ->assertJsonStructure(['data' => ['embarque', 'entradas', 'pendientes', 'factura', 'base64']]);
    }

    public function test_gastos_lista_con_url_de_evidencia(): void
    {
        $respuesta = $this->getJson('/api/gastos')->assertOk();

        $this->assertCount(4, $respuesta->json('data'));
        $this->assertArrayHasKey('url_evidencia', $respuesta->json('data.0'));
        $this->assertArrayHasKey('s_tipo_gasto', $respuesta->json('data.0'));
    }

    public function test_crear_gasto_web(): void
    {
        $this->postJson('/api/gastos', [
            'id_tipo_gasto' => 4,
            's_concepto' => 'Gasto de prueba',
            'n_costo' => 150.50,
        ])->assertCreated();

        $this->assertDatabaseHas('tw_gastos', ['s_concepto' => 'Gasto de prueba', 'b_movil' => 0]);
    }

    public function test_crear_tipo_de_gasto(): void
    {
        $this->postJson('/api/gastos/tipos', [
            'id_categoria_gasto' => 1,
            's_tipo_gasto' => 'Tipo nuevo test',
        ])->assertCreated();

        $this->assertDatabaseHas('tc_tipos_gastos', ['s_tipo_gasto' => 'Tipo nuevo test']);
    }

    public function test_repartos_ciclo_asignacion(): void
    {
        // orden 1 del seed está pendiente de asignación (estatus 4)
        $pendientes = $this->getJson('/api/repartos/ordenes-pendientes')->json('data');
        $this->assertCount(1, $pendientes);

        $repartidores = $this->getJson('/api/repartos/repartidores')->json('data');
        $this->assertNotEmpty($repartidores);

        $idRepartidor = \App\Models\Empleado::where('s_nombre', 'Pedro')->value('id_empleado');

        $this->postJson('/api/repartos/asignar', [
            'id_orden' => $pendientes[0]['id_orden'],
            'id_repartidor' => $idRepartidor,
        ])->assertOk();

        $this->assertDatabaseHas('tw_ordenes', [
            'id_orden' => $pendientes[0]['id_orden'],
            'id_estatus_orden' => 1,
            'id_repartidor' => $idRepartidor,
        ]);
    }

    public function test_registrar_reparto_completa_la_orden(): void
    {
        // orden 2 del seed está en reparto
        $this->postJson('/api/repartos', [
            'id_orden' => 2,
            's_nombre_recibe' => 'Recepción Alfa',
            'hora_inicio_reparto' => now()->subHours(2)->toDateTimeString(),
            'hora_fin_reparto' => now()->toDateTimeString(),
            'firma_cliente' => self::PIXEL_JPG,
            'ubicaciones_reparto' => [
                ['latitud' => 25.6866, 'longitud' => -100.3161, 'timestamp' => now()->subHour()->toDateTimeString()],
            ],
        ])->assertOk();

        $this->assertDatabaseHas('tw_ordenes', ['id_orden' => 2, 'id_estatus_orden' => 3, 's_nombre_recibe' => 'Recepción Alfa']);
        $this->assertDatabaseHas('tw_puntos_ruta', ['id_orden' => 2, 'id_tipo_ruta' => 1]);
        $this->assertNotNull(\App\Models\Orden::find(2)->s_firma);
    }

    public function test_detalle_de_reparto_incluye_evidencias_y_rutas(): void
    {
        $this->getJson('/api/repartos/2')
            ->assertOk()
            ->assertJsonStructure(['data' => ['orden', 'evidencias_salida_reparto', 'evidencias_fin_reparto', 'ruta_salida', 'ruta_regreso']]);
    }
}
