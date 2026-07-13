<?php

namespace App\Services;

use App\Exceptions\DomainException;
use App\Models\Equivalencia;
use App\Models\PorcentajeUtilidad;
use App\Models\Refaccion;
use App\Models\RefaccionEquivalencia;
use App\Models\TipoConfiguracion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class RefaccionService
{
    private const RELACIONES_DETALLE = [
        'marcaRefaccion', 'unidadMedida', 'proveedor', 'categoriaRefaccion',
        'subcategoriaRefaccion', 'posicionVehiculo', 'ubicacionAlmacen',
        'estatusRefaccion', 'claseRefaccion',
    ];

    public function __construct(private readonly CompatibilidadService $compatibilidadService)
    {
    }

    public function listar(): Collection
    {
        return Refaccion::activo()
            ->with(['marcaRefaccion', 'categoriaRefaccion', 'subcategoriaRefaccion', 'estatusRefaccion', 'claseRefaccion'])
            ->orderByDesc('id_refaccion')
            ->get();
    }

    public function porId(int $idRefaccion): Refaccion
    {
        $refaccion = Refaccion::activo()
            ->with(self::RELACIONES_DETALLE)
            ->findOrFail($idRefaccion);

        $refaccion->setAttribute('refacciones_equivalentes', $this->equivalentesDe($idRefaccion));

        return $refaccion;
    }

    public function crear(array $datos, ?int $idUsuario): Refaccion
    {
        return DB::transaction(function () use ($datos, $idUsuario) {
            $refaccion = Refaccion::create([
                ...array_intersect_key($datos, array_flip([
                    's_nombre_refaccion', 's_numero_parte', 's_imagen_refaccion',
                    'id_marca_refaccion', 'id_unidad_medida', 'id_proveedor',
                    'id_clase_refaccion', 'id_categoria_refaccion', 'id_subcategoria_refaccion',
                    'id_posicion_vehiculo', 'id_ubicacion_almacen',
                ])),
                'n_precio_compra' => $datos['n_precio_compra'] ?? 0,
                'n_precio_venta' => $datos['n_precio_venta'] ?? $this->precioVentaConUtilidadBase($datos['n_precio_compra'] ?? 0),
                'n_stock_actual' => $datos['n_stock_actual'] ?? 0,
                'b_importado' => $datos['b_importado'] ?? 0,
                'id_estatus_refaccion' => 1, // Disponible
                'id_usuario_crea' => $idUsuario,
                'b_activo' => 1,
            ]);

            if (! empty($datos['refacciones_equivalentes'])) {
                $this->unirAGrupoDeEquivalencia($refaccion->id_refaccion, $datos['refacciones_equivalentes'], $idUsuario);
            }

            foreach ($datos['reglas_compatibilidad'] ?? [] as $regla) {
                $this->compatibilidadService->persistirRegla($refaccion->id_refaccion, $regla, $idUsuario);
            }

            return $refaccion;
        });
    }

    public function actualizar(int $idRefaccion, array $datos, ?int $idUsuario): Refaccion
    {
        $refaccion = Refaccion::findOrFail($idRefaccion);

        return DB::transaction(function () use ($refaccion, $datos, $idUsuario) {
            $refaccion->fill(array_filter(
                array_intersect_key($datos, array_flip([
                    's_imagen_refaccion', 'n_precio_compra', 'n_precio_venta', 'n_precio_mayoreo',
                    'n_stock_actual', 'id_marca_refaccion', 'id_unidad_medida', 'id_proveedor',
                    'id_clase_refaccion', 'id_categoria_refaccion', 'id_subcategoria_refaccion',
                    'id_posicion_vehiculo', 'id_ubicacion_almacen', 'b_importado',
                ])),
                fn ($v) => $v !== null,
            ));
            $refaccion->s_nombre_refaccion = $datos['s_nombre_refaccion'];
            $refaccion->s_numero_parte = $datos['s_numero_parte'] ?? null;
            $refaccion->id_usuario_edita = $idUsuario;
            $refaccion->save();

            $this->sincronizarEquivalencias(
                $refaccion,
                collect($datos['refacciones_equivalentes'] ?? [])->pluck('id_refaccion')->filter()->unique()->values()->all(),
                $idUsuario,
            );

            return $refaccion;
        });
    }

    /** @return Collection<int, Refaccion> */
    public function crearMasivo(array $refacciones, ?int $idUsuario): array
    {
        return DB::transaction(fn () => array_map(
            fn (array $datos) => Refaccion::create([
                ...$datos,
                'n_precio_compra' => 0,
                'n_precio_venta' => 0,
                'n_stock_actual' => 0,
                'b_importado' => 0,
                'id_estatus_refaccion' => 1,
                'id_usuario_crea' => $idUsuario,
                'b_activo' => 1,
            ]),
            $refacciones,
        ));
    }

    /** Refacciones del mismo grupo de equivalencia (excluyendo la propia). */
    private function equivalentesDe(int $idRefaccion)
    {
        $grupo = RefaccionEquivalencia::activo()->where('id_refaccion', $idRefaccion)->first();

        if (! $grupo) {
            return collect();
        }

        return RefaccionEquivalencia::activo()
            ->where('id_equivalencia', $grupo->id_equivalencia)
            ->where('id_refaccion', '!=', $idRefaccion)
            ->whereHas('refaccion', fn ($q) => $q->where('b_activo', 1))
            ->with('refaccion.marcaRefaccion')
            ->get()
            ->map(fn (RefaccionEquivalencia $eq) => [
                'id_refaccion' => $eq->id_refaccion,
                's_nombre_refaccion' => $eq->refaccion->s_nombre_refaccion,
                's_numero_parte' => $eq->refaccion->s_numero_parte,
                's_marca_refaccion' => $eq->refaccion->marcaRefaccion?->s_marca_refaccion,
                's_imagen_refaccion' => $eq->refaccion->s_imagen_refaccion,
            ]);
    }

    /**
     * Une la refacción al grupo de sus equivalentes; si no hay grupo, lo crea
     * con todas. Falla si los equivalentes pertenecen a grupos distintos.
     */
    private function unirAGrupoDeEquivalencia(int $idRefaccion, array $equivalentes, ?int $idUsuario): void
    {
        $activos = Refaccion::activo()->whereIn('id_refaccion', $equivalentes)->pluck('id_refaccion');
        if ($activos->count() !== count($equivalentes)) {
            throw new DomainException('Alguna de las refacciones equivalentes no existe o está inactiva.', 422);
        }

        $grupos = RefaccionEquivalencia::activo()
            ->whereIn('id_refaccion', $equivalentes)
            ->distinct()
            ->pluck('id_equivalencia');

        if ($grupos->count() > 1) {
            throw new DomainException('Las refacciones equivalentes pertenecen a diferentes grupos activos.', 422);
        }

        if ($grupos->count() === 1) {
            RefaccionEquivalencia::create([
                'id_refaccion' => $idRefaccion,
                'id_equivalencia' => $grupos->first(),
                'id_usuario_crea' => $idUsuario,
                'b_activo' => 1,
            ]);

            return;
        }

        $grupo = Equivalencia::create([
            's_nombre_equivalencia' => 'Equivalencia ' . now()->format('YmdHis'),
            's_descripcion_equivalencia' => 'Grupo creado automáticamente',
            'id_usuario_crea' => $idUsuario,
            'b_activo' => 1,
        ]);

        foreach ([...$equivalentes, $idRefaccion] as $id) {
            RefaccionEquivalencia::create([
                'id_refaccion' => $id,
                'id_equivalencia' => $grupo->id_equivalencia,
                'id_usuario_crea' => $idUsuario,
                'b_activo' => 1,
            ]);
        }
    }

    /**
     * Sincroniza el grupo de equivalencia en una actualización: lista vacía
     * saca a la refacción del grupo (y disuelve grupos de un solo miembro);
     * con elementos, une a todas al grupo final sin permitir mezclar grupos.
     */
    private function sincronizarEquivalencias(Refaccion $refaccion, array $nuevosIds, ?int $idUsuario): void
    {
        $idGrupoActual = RefaccionEquivalencia::activo()
            ->where('id_refaccion', $refaccion->id_refaccion)
            ->value('id_equivalencia');

        if (empty($nuevosIds)) {
            if (! $idGrupoActual) {
                return;
            }

            RefaccionEquivalencia::where('id_refaccion', $refaccion->id_refaccion)
                ->update(['b_activo' => 0, 'id_usuario_edita' => $idUsuario]);

            $miembrosActivos = RefaccionEquivalencia::activo()->where('id_equivalencia', $idGrupoActual)->count();
            if ($miembrosActivos <= 1) {
                RefaccionEquivalencia::where('id_equivalencia', $idGrupoActual)
                    ->update(['b_activo' => 0, 'id_usuario_edita' => $idUsuario]);
                Equivalencia::where('id_equivalencia', $idGrupoActual)
                    ->update(['b_activo' => 0, 'id_usuario_edita' => $idUsuario]);
            }

            return;
        }

        $gruposNuevos = RefaccionEquivalencia::activo()
            ->whereIn('id_refaccion', $nuevosIds)
            ->distinct()
            ->pluck('id_equivalencia');

        if ($gruposNuevos->count() > 1) {
            throw new DomainException('Las refacciones seleccionadas pertenecen a grupos de equivalencia distintos.', 422);
        }

        $idGrupoNuevo = $gruposNuevos->first();

        if ($idGrupoActual && $idGrupoNuevo && $idGrupoActual !== $idGrupoNuevo) {
            throw new DomainException('No se puede mover una refacción de un grupo a otro en esta operación.', 422);
        }

        $idGrupoFinal = $idGrupoActual ?? $idGrupoNuevo ?? Equivalencia::create([
            's_nombre_equivalencia' => 'Grupo para ' . $refaccion->s_nombre_refaccion,
            'id_usuario_crea' => $idUsuario,
            'b_activo' => 1,
        ])->id_equivalencia;

        foreach (array_unique([$refaccion->id_refaccion, ...$nuevosIds]) as $id) {
            RefaccionEquivalencia::updateOrCreate(
                ['id_refaccion' => $id, 'id_equivalencia' => $idGrupoFinal],
                ['b_activo' => 1, 'id_usuario_crea' => $idUsuario, 'id_usuario_edita' => $idUsuario],
            );
        }
    }

    private function precioVentaConUtilidadBase(float $precioCompra): float
    {
        if ($precioCompra <= 0) {
            return 0;
        }

        $utilidad = PorcentajeUtilidad::activo()
            ->where('id_tipo_configuracion', TipoConfiguracion::UTILIDAD_BASE)
            ->value('n_porcentaje_utilidad');

        if ($utilidad === null) {
            throw new DomainException('No está configurado el porcentaje de utilidad base.', 422);
        }

        return round($precioCompra * (1 + $utilidad / 100), 2);
    }
}
