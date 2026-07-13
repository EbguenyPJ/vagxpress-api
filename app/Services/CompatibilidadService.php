<?php

namespace App\Services;

use App\Models\Generacion;
use App\Models\MarcaVehiculo;
use App\Models\ModeloVehiculo;
use App\Models\Motor;
use App\Models\Refaccion;
use App\Models\ReglaCompatibilidad;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Reglas de compatibilidad vehicular por refacción.
 *
 * Una regla restringe por marca/modelo/generación/motor; una dimensión sin
 * filas queda sin restricción y una regla sin filas en ninguna dimensión es
 * universal. Una refacción es compatible con un vehículo si alguna de sus
 * reglas activas coincide en todas las dimensiones consultadas.
 */
class CompatibilidadService
{
    private const DIMENSIONES = [
        'id_marcas' => ['tabla' => 'tr_reglas_marcas', 'columna' => 'id_marca_vehiculo'],
        'id_modelos' => ['tabla' => 'tr_reglas_modelos', 'columna' => 'id_modelo_vehiculo'],
        'id_generaciones' => ['tabla' => 'tr_reglas_generaciones', 'columna' => 'id_generacion'],
        'id_motores' => ['tabla' => 'tr_reglas_motores', 'columna' => 'id_motor'],
    ];

    /** Catálogos vehiculares para el constructor de reglas (filtrado en cascada en el frontend). */
    public function catalogosVehiculos(): array
    {
        return [
            'marcas' => MarcaVehiculo::activo()->get(['id_marca_vehiculo', 's_marca_vehiculo']),
            'modelos' => ModeloVehiculo::activo()->get(['id_modelo_vehiculo', 'id_marca_vehiculo', 's_modelo_vehiculo']),
            'generaciones' => Generacion::activo()->get(['id_generacion', 'id_modelo_vehiculo', 's_generacion', 'n_anio_inicio', 'n_anio_fin']),
            'motores' => Motor::activo()->get(['id_motor', 's_motor']),
        ];
    }

    public function crearRegla(int $idRefaccion, array $regla, ?int $idUsuario): int
    {
        return DB::transaction(fn () => $this->persistirRegla($idRefaccion, $regla, $idUsuario));
    }

    /** Persiste una regla + pivotes; debe invocarse dentro de una transacción. */
    public function persistirRegla(int $idRefaccion, array $regla, ?int $idUsuario): int
    {
        $idRegla = ReglaCompatibilidad::create([
            'id_refaccion' => $idRefaccion,
            's_resumen' => $regla['s_resumen'] ?? null,
            'id_usuario_crea' => $idUsuario,
            'b_activo' => 1,
        ])->id_regla;

        foreach (self::DIMENSIONES as $clave => $dim) {
            $filas = collect($regla[$clave] ?? [])->unique()->map(fn ($id) => [
                'id_regla' => $idRegla,
                $dim['columna'] => $id,
                'b_activo' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($filas->isNotEmpty()) {
                DB::table($dim['tabla'])->insert($filas->all());
            }
        }

        return $idRegla;
    }

    /** Reglas activas de una refacción con etiquetas legibles para las tarjetas del frontend. */
    public function reglasDeRefaccion(int $idRefaccion): Collection
    {
        return ReglaCompatibilidad::activo()
            ->where('id_refaccion', $idRefaccion)
            ->with([
                'reglasMarcas' => fn ($q) => $q->where('b_activo', 1)->with('marcaVehiculo'),
                'reglasModelos' => fn ($q) => $q->where('b_activo', 1)->with('modeloVehiculo'),
                'reglasGeneraciones' => fn ($q) => $q->where('b_activo', 1)->with('generacion'),
                'reglasMotores' => fn ($q) => $q->where('b_activo', 1)->with('motor'),
            ])
            ->get()
            ->map(function (ReglaCompatibilidad $regla) {
                $marcas = $regla->reglasMarcas->map(fn ($p) => $p->marcaVehiculo?->s_marca_vehiculo)->filter()->values();
                $modelos = $regla->reglasModelos->map(fn ($p) => $p->modeloVehiculo?->s_modelo_vehiculo)->filter()->values();
                $generaciones = $regla->reglasGeneraciones->map(fn ($p) => $p->generacion?->s_generacion)->filter()->values();
                $motores = $regla->reglasMotores->map(fn ($p) => $p->motor?->s_motor)->filter()->values();

                return [
                    'id_regla' => $regla->id_regla,
                    's_resumen' => $regla->s_resumen,
                    'b_universal' => $marcas->isEmpty() && $modelos->isEmpty() && $generaciones->isEmpty() && $motores->isEmpty(),
                    'marcas' => $marcas,
                    'modelos' => $modelos,
                    'generaciones' => $generaciones,
                    'motores' => $motores,
                ];
            });
    }

    /** Soft-delete de la regla y sus pivotes. */
    public function eliminarRegla(int $idRegla): void
    {
        ReglaCompatibilidad::findOrFail($idRegla);

        DB::transaction(function () use ($idRegla) {
            ReglaCompatibilidad::where('id_regla', $idRegla)->update(['b_activo' => 0]);
            foreach (self::DIMENSIONES as $dim) {
                DB::table($dim['tabla'])->where('id_regla', $idRegla)->update(['b_activo' => 0]);
            }
        });
    }

    /** Motor de match: refacciones compatibles con el vehículo consultado. */
    public function buscarCompatibles(array $vehiculo): Collection
    {
        $reglas = DB::table('tw_reglas_compatibilidad AS r')->where('r.b_activo', 1);

        $dimensionesConsulta = [
            'id_marca_vehiculo' => self::DIMENSIONES['id_marcas'],
            'id_modelo_vehiculo' => self::DIMENSIONES['id_modelos'],
            'id_generacion' => self::DIMENSIONES['id_generaciones'],
            'id_motor' => self::DIMENSIONES['id_motores'],
        ];

        foreach ($dimensionesConsulta as $campo => $dim) {
            if (! empty($vehiculo[$campo])) {
                $this->aplicarDimension($reglas, $dim['tabla'], $dim['columna'], $vehiculo[$campo]);
            }
        }

        $idsRefacciones = $reglas->distinct()->pluck('r.id_refaccion')->filter()->values();

        return Refaccion::activo()
            ->whereIn('id_refaccion', $idsRefacciones)
            ->get([
                'id_refaccion', 's_nombre_refaccion', 's_numero_parte', 's_sku',
                'n_precio_venta', 'n_stock_actual', 'id_clase_refaccion', 's_imagen_refaccion',
            ]);
    }

    /**
     * Restringe la consulta a las reglas que pasan una dimensión:
     * (sin filas activas en el pivote) OR (el valor del vehículo está presente).
     */
    private function aplicarDimension($query, string $pivote, string $columna, int|string $valor): void
    {
        $query->where(function ($w) use ($pivote, $columna, $valor) {
            $w->whereNotExists(function ($s) use ($pivote) {
                $s->select(DB::raw(1))->from($pivote)
                    ->whereColumn("$pivote.id_regla", 'r.id_regla')
                    ->where("$pivote.b_activo", 1);
            })->orWhereExists(function ($s) use ($pivote, $columna, $valor) {
                $s->select(DB::raw(1))->from($pivote)
                    ->whereColumn("$pivote.id_regla", 'r.id_regla')
                    ->where("$pivote.b_activo", 1)
                    ->where("$pivote.$columna", $valor);
            });
        });
    }
}
