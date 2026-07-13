<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RefaccionesCompatibilidadTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::where('name', 'admin')->firstOrFail());
    }

    public function test_listado_de_refacciones_con_catalogos(): void
    {
        $respuesta = $this->getJson('/api/refacciones')->assertOk();

        $primera = $respuesta->json('data.0');
        $this->assertArrayHasKey('s_marca_refaccion', $primera);
        $this->assertArrayHasKey('s_estatus_refaccion', $primera);
        $this->assertCount(12, $respuesta->json('data'));
    }

    public function test_detalle_de_refaccion_incluye_equivalentes(): void
    {
        $this->getJson('/api/refacciones/1')
            ->assertOk()
            ->assertJsonPath('data.s_nombre_refaccion', 'Filtro de aceite MANN HU719/7x')
            ->assertJsonStructure(['data' => ['refacciones_equivalentes']]);
    }

    public function test_crear_refaccion_calcula_precio_venta_con_utilidad_base(): void
    {
        $respuesta = $this->postJson('/api/refacciones', [
            's_nombre_refaccion' => 'Refacción Test Utilidad',
            's_numero_parte' => 'TEST-001',
            'n_precio_compra' => 100,
            'id_categoria_refaccion' => 1,
            'id_subcategoria_refaccion' => 1,
        ])->assertCreated();

        // precio venta = compra * (1 + utilidad_base/100), nunca 0
        $this->assertGreaterThan(100, (float) $respuesta->json('data.n_precio_venta'));
    }

    public function test_crear_refaccion_con_nombre_duplicado_devuelve_422(): void
    {
        $this->postJson('/api/refacciones', [
            's_nombre_refaccion' => 'Filtro de aceite MANN HU719/7x',
        ])->assertStatus(422)
            ->assertJsonStructure(['errors' => ['s_nombre_refaccion']]);
    }

    public function test_crear_refaccion_con_equivalentes_crea_grupo(): void
    {
        $respuesta = $this->postJson('/api/refacciones', [
            's_nombre_refaccion' => 'Filtro alterno compatible',
            's_numero_parte' => 'ALT-9000',
            'refacciones_equivalentes' => [1],
        ])->assertCreated();

        $id = $respuesta->json('data.id_refaccion');

        $equivalentes = $this->getJson("/api/refacciones/$id")->json('data.refacciones_equivalentes');
        $this->assertCount(1, $equivalentes);
        $this->assertSame(1, $equivalentes[0]['id_refaccion']);
    }

    public function test_quitar_equivalentes_disuelve_grupos_de_un_miembro(): void
    {
        // crear con equivalencia y luego quitarla
        $id = $this->postJson('/api/refacciones', [
            's_nombre_refaccion' => 'Refacción con grupo temporal',
            'refacciones_equivalentes' => [2],
        ])->json('data.id_refaccion');

        $this->putJson("/api/refacciones/$id", [
            's_nombre_refaccion' => 'Refacción con grupo temporal',
            'id_categoria_refaccion' => 1,
            'id_subcategoria_refaccion' => 1,
            'refacciones_equivalentes' => [],
        ])->assertOk();

        $this->assertDatabaseMissing('tr_refacciones_equivalencias', [
            'id_refaccion' => $id,
            'b_activo' => 1,
        ]);
    }

    public function test_carga_masiva_crea_refacciones_minimas(): void
    {
        $this->postJson('/api/refacciones/masivo', [
            'refacciones' => [
                ['s_nombre_refaccion' => 'Masiva 1', 's_numero_parte' => 'M-001', 'id_marca_refaccion' => 1, 'id_categoria_refaccion' => 1, 'id_subcategoria_refaccion' => 1],
                ['s_nombre_refaccion' => 'Masiva 2', 's_numero_parte' => 'M-002', 'id_marca_refaccion' => 2, 'id_categoria_refaccion' => 2, 'id_subcategoria_refaccion' => 6],
            ],
        ])->assertCreated();

        $this->assertDatabaseHas('tw_refacciones', ['s_numero_parte' => 'M-001', 'id_estatus_refaccion' => 1]);
        $this->assertDatabaseHas('tw_refacciones', ['s_numero_parte' => 'M-002']);
    }

    public function test_catalogos_vehiculos_trae_las_cuatro_dimensiones(): void
    {
        $this->getJson('/api/compatibilidad/catalogos-vehiculos')
            ->assertOk()
            ->assertJsonStructure(['data' => ['marcas', 'modelos', 'generaciones', 'motores']]);
    }

    public function test_regla_de_compatibilidad_y_motor_de_match(): void
    {
        $marcas = collect($this->getJson('/api/compatibilidad/catalogos-vehiculos')->json('data.marcas'));
        $honda = $marcas->firstWhere('s_marca_vehiculo', 'Honda');
        $ford = $marcas->firstWhere('s_marca_vehiculo', 'Ford');

        // la refacción 1 solo compatible con Honda
        $this->postJson('/api/compatibilidad/reglas', [
            'id_refaccion' => 1,
            'id_marcas' => [$honda['id_marca_vehiculo']],
        ])->assertCreated();

        // la refacción 2 con regla universal
        $this->postJson('/api/compatibilidad/reglas', ['id_refaccion' => 2])->assertCreated();

        $compatiblesHonda = collect($this->postJson('/api/compatibilidad/buscar-compatibles', [
            'id_marca_vehiculo' => $honda['id_marca_vehiculo'],
        ])->json('data'))->pluck('id_refaccion');

        $compatiblesFord = collect($this->postJson('/api/compatibilidad/buscar-compatibles', [
            'id_marca_vehiculo' => $ford['id_marca_vehiculo'],
        ])->json('data'))->pluck('id_refaccion');

        $this->assertTrue($compatiblesHonda->contains(1), 'Honda debe incluir la refacción 1');
        $this->assertTrue($compatiblesHonda->contains(2), 'la universal aplica a Honda');
        $this->assertFalse($compatiblesFord->contains(1), 'Ford no debe incluir la refacción restringida a Honda');
        $this->assertTrue($compatiblesFord->contains(2), 'la universal aplica a Ford');
    }

    public function test_eliminar_regla_es_soft_delete(): void
    {
        $idRegla = $this->postJson('/api/compatibilidad/reglas', ['id_refaccion' => 3])->json('data.id_regla');

        $this->deleteJson("/api/compatibilidad/reglas/$idRegla")->assertOk();

        $this->assertDatabaseHas('tw_reglas_compatibilidad', ['id_regla' => $idRegla, 'b_activo' => 0]);
        $this->assertSame([], $this->getJson('/api/compatibilidad/reglas/refaccion/3')->json('data'));
    }
}
