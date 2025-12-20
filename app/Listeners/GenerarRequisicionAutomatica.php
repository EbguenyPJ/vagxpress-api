<?php

namespace App\Listeners;

use App\Events\VerificarStockBajo;
use App\Models\Refaccion; // Asumo que tu modelo de Refacción existe
use App\Models\Requisicion;
use App\Models\RequisicionRefaccion;
use Illuminate\Support\Facades\Log;

class GenerarRequisicionAutomatica
{
    public function handle(VerificarStockBajo $event)
    {

        $refacciones = Refaccion::whereIn('id_refaccion', $event->idsRefacciones)->get();


        $refaccionesBajas = $refacciones->filter(function ($refaccion) {

            $minimo = $refaccion->n_stock_minimo ?? 0;
            return $refaccion->n_stock_actual <= $minimo;
        });


        if ($refaccionesBajas->isEmpty()) {
            return;
        }

        // 3. Buscar Requisición existente (Abierta) o Crear una nueva
        // Estatus 1 = Abierta, Tipo 1 = Automátizada.
        // firstOrCreate busca por el primer array, si no encuentra, crea usando la unión de ambos.
        $requisicion = Requisicion::firstOrCreate(
            [
                'id_estatus_requisicion' => 1,
                'id_tipo_requisicion' => 1,
                'b_activo' => 1
            ],
            [
                'id_usuario_crea' => $event->idUsuario,
                'd_fecha_solicitud' => now(),
                'n_cantidad_refacciones' => 0,
                'n_total_estimado' => 0
            ]
        );

        // Insertar o Actualizar el Detalle
        foreach ($refaccionesBajas as $refaccion) {


            $maximo = $refaccion->n_stock_maximo ?? 0;
            $cantidadSugerida = max(0, $maximo - $refaccion->n_stock_actual);


            if ($cantidadSugerida == 0) $cantidadSugerida = 1;


            RequisicionRefaccion::updateOrCreate(
                [
                    'id_requisicion' => $requisicion->id_requisicion,
                    'id_refaccion' => $refaccion->id_refaccion
                ],
                [
                    'n_cantidad_sugerida' => $cantidadSugerida,
                    'n_costo_unitario' => $refaccion->n_precio_compra ?? 0,
                    'id_motivo_pedido' => 2,
                    'id_prioridad' => 3,
                    'b_activo' => 1
                ]
            );
        }


        $detallesActualizados = RequisicionRefaccion::where('id_requisicion', $requisicion->id_requisicion)
            ->where('b_activo', 1)
            ->get();

        $nuevoConteo = $detallesActualizados->count();

        $nuevoTotal = $detallesActualizados->sum(function ($item) {
            return $item->n_cantidad_sugerida * $item->n_costo_unitario;
        });


        $requisicion->update([
            'n_cantidad_refacciones' => $nuevoConteo,
            'n_total_estimado' => $nuevoTotal,
            'id_usuario_modifica' => $event->idUsuario
        ]);
    }
}
