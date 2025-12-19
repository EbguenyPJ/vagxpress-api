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
}
