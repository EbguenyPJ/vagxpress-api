<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class RequisicionRefaccionController extends Controller
{
    public function previsualizarPorProveedor($id_requisicion)
    {
        try {
            // 1. Tu consulta original (Intacta)
            $refacciones = DB::table('tw_requisiciones AS T1')
                ->leftJoin('tr_requisiciones_refacciones AS T2', 'T2.id_requisicion', '=', 'T1.id_requisicion')
                ->leftJoin('tw_refacciones AS T3', 'T3.id_refaccion', '=', 'T2.id_refaccion')
                ->leftJoin('tw_proveedores AS T4', 'T4.id_proveedor', '=', 'T3.id_proveedor') // Proveedor Default
                ->leftJoin('tc_prioridades AS T5', 'T5.id_prioridad', '=', 'T2.id_prioridad')
                ->leftJoin('tc_motivos_pedidos AS T6', 'T6.id_motivo_pedido', '=', 'T2.id_motivo_pedido')
                ->select(
                    'T3.id_proveedor',
                    'T4.s_proveedor',
                    // Datos de la Refacción
                    'T3.id_refaccion',
                    'T3.s_nombre_refaccion',
                    'T3.s_numero_parte',
                    'T3.n_stock_actual',
                    DB::raw('(T2.n_cantidad_sugerida * T2.n_costo_unitario) AS costo_estimado_refaccion'),
                    // Datos del Detalle de la Requisición
                    'T2.id_requisicion_refaccion',
                    'T2.n_cantidad_sugerida',
                    'T2.n_costo_unitario', // Costo actual capturado en la requisición
                    'T2.id_prioridad',
                    'T5.s_prioridad',
                    'T2.id_motivo_pedido',
                    'T6.s_motivo_pedido',
                )
                ->where('T1.id_requisicion', $id_requisicion)
                ->where('T1.b_activo', 1)
                ->where('T2.b_activo', 1)
                // Agregamos filtro para no traer filas si T2 es null (por leftJoin)
                ->whereNotNull('T2.id_requisicion_refaccion')
                ->get();

            // --- INICIO LÓGICA DE INTELIGENCIA DE PRECIOS ---

            // 2. Extraemos los IDs únicos de las refacciones involucradas
            $idsRefacciones = $refacciones->pluck('id_refaccion')->unique()->toArray();

            // 3. Consultamos el historial de precios "masivo" para estas refacciones
            $historialPrecios = DB::table('tr_proveedores_refacciones AS H')
                ->join('tw_proveedores AS P', 'H.id_proveedor', '=', 'P.id_proveedor')
                ->select(
                    'H.id_refaccion',
                    'H.id_proveedor',
                    'P.s_proveedor',
                    'H.n_ultimo_costo',
                    'H.d_fecha_ultima_compra'
                )
                ->whereIn('H.id_refaccion', $idsRefacciones)
                ->where('H.b_activo', 1)
                ->get()
                ->groupBy('id_refaccion'); // Agrupamos por refacción para búsqueda rápida (Hash Map)

            // --- FIN PREPARACIÓN INTELIGENCIA ---


            $refaccionesProveedor = $refacciones->groupBy('id_proveedor');

            $refaccionesAgrupadas = $refaccionesProveedor->map(function ($itemsGrupo, $id_proveedor) use ($historialPrecios) {

                // Nombre del proveedor del grupo actual
                $s_proveedor = $itemsGrupo->first()->s_proveedor ?? 'Proveedor Desconocido';

                // Procesamos los items para inyectarles la comparación de precios INDIVIDUALMENTE
                $itemsProcesados = $itemsGrupo->map(function($item) use ($historialPrecios) {

                    $item->alerta_mejor_precio = false;
                    $item->mejor_opcion = null;

                    // Verificamos si existe historial para esta refacción
                    if (isset($historialPrecios[$item->id_refaccion])) {

                        // Buscamos si hay algún proveedor con precio MENOR al que tenemos en la requisición
                        // Filtramos: Costo Histórico < Costo Requisición Actual
                        $mejorOpcion = $historialPrecios[$item->id_refaccion]
                            ->where('n_ultimo_costo', '<', $item->n_costo_unitario)
                            ->sortBy('n_ultimo_costo') // Ordenamos del más barato al más caro
                            ->first(); // Tomamos el ganador

                        if ($mejorOpcion) {
                            $item->alerta_mejor_precio = true;
                            $item->mejor_opcion = [
                                'id_proveedor' => $mejorOpcion->id_proveedor,
                                's_proveedor' => $mejorOpcion->s_proveedor,
                                'n_ultimo_costo' => $mejorOpcion->n_ultimo_costo,
                                'd_fecha_ultima_compra' => $mejorOpcion->d_fecha_ultima_compra,
                                'n_ahorro_unitario' => $item->n_costo_unitario - $mejorOpcion->n_ultimo_costo
                            ];
                        }
                    }
                    return $item;
                });

                // Calculamos totales usando los items procesados
                $totalProveedor = $itemsProcesados->sum(function($refaccion){
                    // Usamos la cantidad sugerida para el estimado
                    return $refaccion->n_cantidad_sugerida * $refaccion->n_costo_unitario;
                });

                $cantidadRefacciones = $itemsProcesados->count();

                return [
                    'id_proveedor' => $id_proveedor,
                    's_proveedor' => $s_proveedor,
                    'total_estimado_proveedor' => $totalProveedor,
                    'cantidad_refacciones_proveedor' => $cantidadRefacciones,
                    'items' => $itemsProcesados->values() // Devolvemos los items ya con las banderas
                ];
            })->values();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Operación realizada correctamente.',
                'data' => $refaccionesAgrupadas,
            ], 200);

        } catch (\Exception $e) { // Un solo catch para todo
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
