<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CorteController extends Controller
{
    /**
     * Listar cortes
     */
    public function index()
    {
        $data = DB::table('tw_cortes as T1')
            ->join('users as T2', 'T1.id_usuario_crea', '=', 'T2.id')
            ->select(
                'T1.id_corte',
                'T1.id_tipo_corte',
                'T1.id_usuario_crea',
                'T2.name as nombre_usuario', 
                'T1.d_fecha_corte',
                'T1.n_monto_efectivo',
                'T1.n_monto_transferencia',
                'T1.n_monto_credito',
                'T1.n_monto_tarjeta_debito',
                'T1.n_monto_tarjeta_credito',
                'T1.n_monto_total',
                'T1.s_descripcion_corte',
                'T1.s_comentario',
                'T1.created_at',
                'T1.updated_at',
                'T1.b_activo'
            )
            ->where('T1.b_activo', true)
            ->orderByDesc('T1.id_corte')
            ->get();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $data
        ]);
    }



    /**
     * Crear nuevo corte
     */
    public function crearCorte(Request $request)
    {
        // Validación de campos
        $validator = Validator::make($request->all(), [
            'id_tipo_corte'        => 'required|integer',
            'id_usuario'           => 'required|integer',
            'fecha_corte'          => 'required|date',
            'monto_efectivo'       => 'numeric|min:0',
            'monto_transferencia'  => 'numeric|min:0',
            'monto_credito'        => 'numeric|min:0',
            'monto_tarjeta_debito' => 'numeric|min:0',
            'monto_tarjeta_credito' => 'numeric|min:0',
            'descripcion'          => 'nullable|string',
            'comentario'           => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Crear el corte
            $idCorte = DB::table('tw_cortes')->insertGetId([
                'id_tipo_corte' => $request->id_tipo_corte,
                'id_usuario_crea' => $request->id_usuario,
                'd_fecha_corte' => $request->fecha_corte,
                'n_monto_efectivo'       => $request->monto_efectivo ?? 0,
                'n_monto_transferencia'  => $request->monto_transferencia ?? 0,
                'n_monto_credito'        => $request->monto_credito ?? 0,
                'n_monto_tarjeta_debito' => $request->monto_tarjeta_debito ?? 0,
                'n_monto_tarjeta_credito' => $request->monto_tarjeta_credito ?? 0,
                'n_monto_total' => ($request->monto_efectivo ?? 0) +
                    ($request->monto_transferencia ?? 0) +
                    ($request->monto_credito ?? 0) +
                    ($request->monto_tarjeta_debito ?? 0) +
                    ($request->monto_tarjeta_credito ?? 0),
                's_descripcion_corte' => $request->descripcion ?: null,
                's_comentario'        => $request->comentario ?: null,
                'created_at' => now(),
                'updated_at' => now(),
                'b_activo' => 1
            ]);

            // Montos por método de pago
            $montos = [
                2 => $request->monto_efectivo ?? 0,
                5 => $request->monto_transferencia ?? 0,
                1 => $request->monto_credito ?? 0,
                4 => $request->monto_tarjeta_debito ?? 0,
                3 => $request->monto_tarjeta_credito ?? 0
            ];

            $ventasCorte = [];

            foreach ($montos as $idMetodo => $montoUsuario) {
                if ($montoUsuario <= 0) continue;

                // Obtener todas las ventas pendientes de ese método
                $ventas = DB::table('tw_ventas')
                    ->where('b_corte', 0)
                    ->whereDate('created_at', $request->fecha_corte)
                    ->where('id_metodo_pago', $idMetodo)
                    ->orderBy('created_at')
                    ->get();

                foreach ($ventas as $venta) {
                    // Agregar venta al corte
                    DB::table('tr_cortes_ventas')->insert([
                        'id_corte' => $idCorte,
                        'id_venta' => $venta->id_venta,
                        'b_activo' => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    // Marcar venta como incluida en corte
                    DB::table('tw_ventas')
                        ->where('id_venta', $venta->id_venta)
                        ->update(['b_corte' => 1]);

                    $ventasCorte[] = $venta;
                }
            }

            DB::commit();

            $totalUsuario = ($request->monto_efectivo ?? 0) +
                ($request->monto_transferencia ?? 0) +
                ($request->monto_credito ?? 0) +
                ($request->monto_tarjeta_debito ?? 0) +
                ($request->monto_tarjeta_credito ?? 0);

            $totalVentas = collect($ventasCorte)->sum('n_total');

            return response()->json([
                'status' => 'ok',
                'id_corte' => $idCorte,
                'total_usuario' => $totalUsuario,
                'total_ventas' => $totalVentas,
                'diferencia' => $totalUsuario - $totalVentas,
                'ventas_corte' => $ventasCorte
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }



    public function subirEvidenciasCorte(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_corte' => 'required|integer|exists:tw_cortes,id_corte',
            'evidencias' => 'required|array',
            'evidencias.*.archivo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'evidencias.*.id_metodo_pago' => 'required|integer',
            'evidencias.*.id_tipo_evidencia' => 'required|integer',
            'evidencias.*.s_descripcion' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $idCorte = $request->id_corte;

            // 📂 Ruta física REAL
            $rutaPublica = public_path('evidenciasCortes');

            // 👉 Crear carpeta si no existe
            if (!File::exists($rutaPublica)) {
                File::makeDirectory($rutaPublica, 0755, true);
            }

            $evidenciasInsert = [];

            foreach ($request->evidencias as $index => $ev) {
                $archivo = $ev['archivo'];
                $extension = $archivo->getClientOriginalExtension();
                $timestamp = now()->format('YmdHis');

                $nombreArchivo = "evidencia_corte{$idCorte}_{$timestamp}_{$index}.{$extension}";

                // 💾 MOVER ARCHIVO A PUBLIC
                $archivo->move($rutaPublica, $nombreArchivo);

                $evidenciasInsert[] = [
                    'id_corte' => $idCorte,
                    'id_metodo_pago' => $ev['id_metodo_pago'],
                    'id_tipo_evidencia' => $ev['id_tipo_evidencia'],
                    's_nombre_archivo' => $nombreArchivo,
                    's_descripcion' => $ev['s_descripcion'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'b_activo' => 1
                ];
            }

            DB::table('tw_cortes_evidencias')->insert($evidenciasInsert);
            DB::commit();

            return response()->json([
                'status' => 'ok',
                'message' => 'Evidencias subidas correctamente'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
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




    public function getCorteCajaDesglosado()
    {
        try {
            // Obtener el parámetro 'fechaHora' desde query string (ej: 2025-12-21 15:30:00)
            $fechaHora = request('fechaHora') ? date('Y-m-d H:i:s', strtotime(request('fechaHora'))) : date('Y-m-d H:i:s');

            // Obtener inicio y fin del día basado en la fecha de $fechaHora
            $fechaSolo = date('Y-m-d', strtotime($fechaHora));
            $inicioDia = $fechaSolo . ' 00:00:00';
            $finDia = $fechaSolo . ' 23:59:59';

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

            //  Si no hay ventas, devolvemos error
            if ($resumen->isEmpty()) {
                return [
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'No hay movimientos para el corte'
                ];
            }

            // Desglose solo para métodos 3,4,5 usando cuentas bancarias
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

            //  Total general de todas las ventas (suma de resumen)
            $totalGeneral = $resumen->sum('total_dinero');

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
