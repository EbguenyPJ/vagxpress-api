<?php

namespace App\Services;

use App\Models\ProveedorRefaccion;
use App\Models\Requisicion;
use App\Models\RequisicionRefaccion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class RequisicionService
{
    public function listar(): Collection
    {
        return Requisicion::activo()
            ->with(['estatusRequisicion', 'tipoRequisicion'])
            ->orderByDesc('id_requisicion')
            ->get();
    }

    /** Renglones de la requisición con datos de refacción, motivo y prioridad. */
    public function detalle(int $idRequisicion): SupportCollection
    {
        $requisicion = Requisicion::activo()->with('estatusRequisicion')->findOrFail($idRequisicion);

        return RequisicionRefaccion::activo()
            ->where('id_requisicion', $idRequisicion)
            ->with(['refaccion', 'motivoPedido', 'prioridad'])
            ->get()
            ->map(fn (RequisicionRefaccion $renglon) => [
                'id_requisicion' => $idRequisicion,
                'id_requisicion_refaccion' => $renglon->id_requisicion_refaccion,
                'id_refaccion' => $renglon->id_refaccion,
                's_nombre_refaccion' => $renglon->refaccion?->s_nombre_refaccion,
                's_numero_parte' => $renglon->refaccion?->s_numero_parte,
                'n_costo_unitario' => $renglon->refaccion?->n_precio_compra,
                'n_precio_venta' => $renglon->refaccion?->n_precio_venta,
                'n_stock_actual' => $renglon->refaccion?->n_stock_actual,
                'n_tiempo_reposicion' => $renglon->refaccion?->n_tiempo_reposicion,
                'n_cantidad_sugerida' => $renglon->n_cantidad_sugerida,
                'id_motivo_pedido' => $renglon->id_motivo_pedido,
                's_motivo_pedido' => $renglon->motivoPedido?->s_motivo_pedido,
                'id_prioridad' => $renglon->id_prioridad,
                's_prioridad' => $renglon->prioridad?->s_prioridad,
                'id_estatus_requisicion' => $requisicion->id_estatus_requisicion,
                's_estatus_requisicion' => $requisicion->estatusRequisicion?->s_estatus_requisicion,
            ]);
    }

    public function actualizarEstatus(int $idRequisicion, int $idEstatus, int $idUsuario): Requisicion
    {
        $requisicion = Requisicion::findOrFail($idRequisicion);
        $requisicion->update([
            'id_estatus_requisicion' => $idEstatus,
            'id_usuario_modifica' => $idUsuario,
        ]);

        return $requisicion;
    }

    /**
     * Previsualización agrupada por proveedor con inteligencia de precios:
     * marca los renglones donde algún proveedor histórico ofrece costo menor.
     */
    public function previsualizarPorProveedor(int $idRequisicion): SupportCollection
    {
        Requisicion::activo()->findOrFail($idRequisicion);

        $renglones = RequisicionRefaccion::activo()
            ->where('id_requisicion', $idRequisicion)
            ->with(['refaccion.proveedor', 'motivoPedido', 'prioridad'])
            ->get();

        $historialPrecios = ProveedorRefaccion::activo()
            ->whereIn('id_refaccion', $renglones->pluck('id_refaccion')->unique())
            ->with('proveedor')
            ->get()
            ->groupBy('id_refaccion');

        return $renglones
            ->groupBy(fn (RequisicionRefaccion $r) => $r->refaccion?->id_proveedor)
            ->map(function (SupportCollection $grupo, $idProveedor) use ($historialPrecios) {
                $items = $grupo->map(function (RequisicionRefaccion $renglon) use ($historialPrecios) {
                    $mejorOpcion = $historialPrecios
                        ->get($renglon->id_refaccion, collect())
                        ->where('n_ultimo_costo', '<', $renglon->n_costo_unitario)
                        ->sortBy('n_ultimo_costo')
                        ->first();

                    return [
                        'id_proveedor' => $renglon->refaccion?->id_proveedor,
                        's_proveedor' => $renglon->refaccion?->proveedor?->s_proveedor,
                        'id_refaccion' => $renglon->id_refaccion,
                        's_nombre_refaccion' => $renglon->refaccion?->s_nombre_refaccion,
                        's_numero_parte' => $renglon->refaccion?->s_numero_parte,
                        'n_stock_actual' => $renglon->refaccion?->n_stock_actual,
                        'costo_estimado_refaccion' => round($renglon->n_cantidad_sugerida * $renglon->n_costo_unitario, 2),
                        'id_requisicion_refaccion' => $renglon->id_requisicion_refaccion,
                        'n_cantidad_sugerida' => $renglon->n_cantidad_sugerida,
                        'n_costo_unitario' => $renglon->n_costo_unitario,
                        'id_prioridad' => $renglon->id_prioridad,
                        's_prioridad' => $renglon->prioridad?->s_prioridad,
                        'id_motivo_pedido' => $renglon->id_motivo_pedido,
                        's_motivo_pedido' => $renglon->motivoPedido?->s_motivo_pedido,
                        'alerta_mejor_precio' => $mejorOpcion !== null,
                        'mejor_opcion' => $mejorOpcion ? [
                            'id_proveedor' => $mejorOpcion->id_proveedor,
                            's_proveedor' => $mejorOpcion->proveedor?->s_proveedor,
                            'n_ultimo_costo' => $mejorOpcion->n_ultimo_costo,
                            'd_fecha_ultima_compra' => $mejorOpcion->d_fecha_ultima_compra?->toDateString(),
                            'n_ahorro_unitario' => round($renglon->n_costo_unitario - $mejorOpcion->n_ultimo_costo, 2),
                        ] : null,
                    ];
                })->values();

                return [
                    'id_proveedor' => $idProveedor,
                    's_proveedor' => $items->first()['s_proveedor'] ?? 'Proveedor Desconocido',
                    'total_estimado_proveedor' => round($items->sum(fn ($i) => $i['n_cantidad_sugerida'] * $i['n_costo_unitario']), 2),
                    'cantidad_refacciones_proveedor' => $items->count(),
                    'items' => $items,
                ];
            })
            ->values();
    }
}
