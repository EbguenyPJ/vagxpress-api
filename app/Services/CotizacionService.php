<?php

namespace App\Services;

use App\Models\Cotizacion;
use App\Models\CotizacionRefaccion;
use App\Models\PorcentajeUtilidad;
use App\Models\Refaccion;
use Illuminate\Support\Facades\DB;

class CotizacionService
{
    /**
     * Crea una cotización con su detalle aplicando el porcentaje de
     * utilidad por renglón. Misma aritmética que la venta, sin stock.
     *
     * @return array{cotizacion: Cotizacion, detalle: array}
     */
    public function crear(array $datos, int $idUsuario): array
    {
        return DB::transaction(function () use ($datos, $idUsuario) {
            $cotizacion = Cotizacion::create([
                'id_estatus_cotizacion' => 1, // Pendiente
                'id_tipo_cotizacion' => $datos['id_tipo_cotizacion'] ?? 1,
                'id_cliente' => $datos['id_cliente'],
                'id_usuario_crea' => $idUsuario,
                'n_porcentaje_iva' => 16.00,
                'b_activo' => 1,
            ]);

            $detalle = [];
            $subtotal = 0;

            foreach ($datos['refacciones'] as $item) {
                $refaccion = Refaccion::findOrFail($item['id_refaccion']);

                $porcentajeUtilidad = isset($item['id_porcentaje_utilidad'])
                    ? PorcentajeUtilidad::findOrFail($item['id_porcentaje_utilidad'])
                    : null;
                $factorUtilidad = 1 + ($porcentajeUtilidad?->n_porcentaje_utilidad ?? 0) / 100;

                $renglon = CotizacionRefaccion::create([
                    'id_cotizacion' => $cotizacion->id_cotizacion,
                    'id_refaccion' => $refaccion->id_refaccion,
                    'n_cantidad' => $item['n_cantidad'],
                    'n_costo_unitario' => $refaccion->n_precio_venta,
                    'n_porcentaje_utilidad' => $porcentajeUtilidad?->n_porcentaje_utilidad,
                    'n_total' => round($item['n_cantidad'] * $refaccion->n_precio_venta * $factorUtilidad, 2),
                    'b_activo' => 1,
                ]);

                $subtotal += (float) $renglon->n_total;
                $detalle[] = [...$renglon->toArray(), 'nombre_refaccion' => $refaccion->s_nombre_refaccion];
            }

            $cotizacion->update([
                'n_subtotal' => round($subtotal, 2),
                'n_total' => round($subtotal * 1.16, 2),
                'n_cantidad_refacciones' => count($datos['refacciones']),
            ]);

            return ['cotizacion' => $cotizacion, 'detalle' => $detalle];
        });
    }
}
