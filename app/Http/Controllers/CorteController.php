<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CorteController extends Controller
{
    /**
     * Listar cortes
     */
    public function index()
    {
        $cortes = DB::table('tw_cortes as c')
            ->select(
                'c.id_corte',
                'c.d_fecha_corte',
                'c.n_monto_total',
                'c.s_descripcion_corte',
                'c.created_at',
                'c.b_activo'
            )
            ->orderByDesc('c.id_corte')
            ->get();

        return response()->json($cortes);
    }

    /**
     * Crear nuevo corte
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_tipo_corte' => 'required|integer',
            'id_usuario'    => 'required|integer',
            'fecha_corte'   => 'required|date',

            'monto_efectivo'          => 'numeric|min:0',
            'monto_transferencia'     => 'numeric|min:0',
            'monto_credito'           => 'numeric|min:0',
            'monto_tarjeta_debito'    => 'numeric|min:0',
            'monto_tarjeta_credito'   => 'numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {

            $total =
                $request->monto_efectivo +
                $request->monto_transferencia +
                $request->monto_credito +
                $request->monto_tarjeta_debito +
                $request->monto_tarjeta_credito;

            $idCorte = DB::table('tw_cortes')->insertGetId([
                'id_tipo_corte' => $request->id_tipo_corte,
                'id_usuario_crea' => $request->id_usuario,
                'd_fecha_corte' => $request->fecha_corte,

                'n_monto_efectivo' => $request->monto_efectivo ?? 0,
                'n_monto_transferencia' => $request->monto_transferencia ?? 0,
                'n_monto_credito' => $request->monto_credito ?? 0,
                'n_monto_tarjeta_debito' => $request->monto_tarjeta_debito ?? 0,
                'n_monto_tarjeta_credito' => $request->monto_tarjeta_credito ?? 0,
                'n_monto_total' => $total,

                's_descripcion_corte' => $request->descripcion,
                'created_at' => now(),
                'updated_at' => now(),
                'b_activo' => 1
            ]);

            DB::commit();

            return response()->json([
                'status' => 'ok',
                'id_corte' => $idCorte
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Detalle de un corte
     */
    public function show($idCorte)
    {
        $corte = DB::table('tw_cortes')
            ->where('id_corte', $idCorte)
            ->where('b_activo', 1)
            ->first();

        if (!$corte) {
            return response()->json(['message' => 'Corte no encontrado'], 404);
        }

        $evidencias = DB::table('tw_cortes_evidencias as e')
            ->join('tc_tipos_evidencias as t', 't.id_tipo_evidencia', '=', 'e.id_tipo_evidencia')
            ->where('e.id_corte', $idCorte)
            ->where('e.b_activo', 1)
            ->select(
                'e.id_corte_evidencia',
                'e.id_metodo_pago',
                't.s_tipo_evidencia',
                'e.s_nombre_archivo',
                'e.s_ruta_archivo'
            )
            ->get();

        return response()->json([
            'corte' => $corte,
            'evidencias' => $evidencias
        ]);
    }

    /**
     * Subir evidencia
     */
    public function storeEvidencia(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_corte' => 'required|integer',
            'id_metodo_pago' => 'required|integer',
            'id_tipo_evidencia' => 'required|integer',
            'archivo' => 'required|file'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $file = $request->file('archivo');
            $nombre = uniqid() . '_' . $file->getClientOriginalName();
            $ruta = $file->storeAs('cortes', $nombre, 'public');

            DB::table('tw_cortes_evidencias')->insert([
                'id_corte' => $request->id_corte,
                'id_metodo_pago' => $request->id_metodo_pago,
                'id_tipo_evidencia' => $request->id_tipo_evidencia,
                's_nombre_archivo' => $nombre,
                's_ruta_archivo' => $ruta,
                'created_at' => now(),
                'updated_at' => now(),
                'b_activo' => 1
            ]);

            DB::commit();

            return response()->json(['status' => 'ok']);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Cerrar corte
     */
    public function cerrar($idCorte)
    {
        // Aquí luego metemos validaciones fuertes
        DB::table('tw_cortes')
            ->where('id_corte', $idCorte)
            ->update([
                'b_activo' => 0,
                'updated_at' => now()
            ]);

        return response()->json(['status' => 'ok']);
    }





   public function getCorteCajaDesglosado($fechaHora = null)
{
    try {
        $fechaHora = $fechaHora ? date('Y-m-d', strtotime($fechaHora)) : date('Y-m-d');
        $inicioDia = $fechaHora . ' 00:00:00';
        $finDia = $fechaHora . ' 23:59:59';

        // 1️⃣ Resumen general por método de pago
        $resumen = DB::table('tw_ventas AS T1')
            ->join('tc_metodos_pagos AS T3', 'T1.id_metodo_pago', '=', 'T3.id_metodo_pago')
            ->select(
                'T1.id_metodo_pago',
                'T3.s_metodo_pago AS s_nombre_metodo',
                DB::raw('GROUP_CONCAT(T1.id_venta) AS id_ventas'),
                DB::raw('COUNT(T1.id_venta) AS total_ventas'),
                DB::raw('SUM(T1.n_total) AS total_dinero')
            )
            ->where('T1.id_estatus_venta', 1)
            ->whereBetween('T1.created_at', [$inicioDia, $finDia])
            ->groupBy('T1.id_metodo_pago', 'T3.s_metodo_pago')
            ->orderBy('T1.id_metodo_pago')
            ->get();

        // 2️⃣ Desglose solo para métodos 3,4,5 usando cuentas bancarias
        $bancos = DB::table('tw_ventas AS T1')
            ->join('tc_cuentas_bancarias AS T2', 'T1.id_cuenta_bancaria', '=', 'T2.id_cuenta_bancaria')
            ->join('tc_metodos_pagos AS T3', 'T1.id_metodo_pago', '=', 'T3.id_metodo_pago')
            ->select(
                'T1.id_metodo_pago',
                'T3.s_metodo_pago AS s_nombre_metodo',
                'T2.s_nombre_cuenta AS cuenta',
                DB::raw('GROUP_CONCAT(T1.id_venta) AS id_ventas'),
                DB::raw('COUNT(T1.id_venta) AS total_ventas'),
                DB::raw('SUM(T1.n_total) AS total_dinero')
            )
            ->whereIn('T1.id_metodo_pago', [3, 4, 5])
            ->where('T1.id_estatus_venta', 1)
            ->whereBetween('T1.created_at', [$inicioDia, $finDia])
            ->groupBy('T1.id_metodo_pago', 'T3.s_metodo_pago', 'T2.id_cuenta_bancaria', 'T2.s_nombre_cuenta')
            ->orderBy('T1.id_metodo_pago')
            ->orderBy('T2.s_nombre_cuenta')
            ->get();

        // 3️⃣ Total general de todas las ventas
        $totalGeneral = DB::table('tw_ventas AS T1')
            ->where('T1.id_estatus_venta', 1)
            ->whereBetween('T1.created_at', [$inicioDia, $finDia])
            ->sum('T1.n_total');

        if ($resumen->isEmpty()) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'No hay movimientos para el corte'
            ];
        }

        return [
            'status' => 'success',
            'code' => 200,
            'message' => 'Corte de caja obtenido correctamente',
            'total_general' => $totalGeneral,
            'resumen' => $resumen,
            'desglose_bancos' => $bancos
        ];
    } catch (\Exception $e) {
        return [
            'status' => 'error',
            'code' => 500,
            'message' => 'Error al obtener el corte de caja',
            'error' => $e->getMessage()
        ];
    }
}

}
