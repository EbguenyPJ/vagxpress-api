<?php

namespace App\Services;

use App\Models\Corte;
use App\Models\CorteEvidencia;
use App\Models\CorteVenta;
use App\Models\EstatusVenta;
use App\Models\MetodoPago;
use App\Models\Venta;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CorteService
{
    private const DIRECTORIO_EVIDENCIAS = 'evidenciasCortes';

    /** Campo de monto del request → método de pago que liquida. */
    private const MONTOS_POR_METODO = [
        'monto_efectivo' => MetodoPago::EFECTIVO,
        'monto_transferencia' => MetodoPago::TRANSFERENCIA,
        'monto_credito' => MetodoPago::CREDITO,
        'monto_tarjeta_debito' => MetodoPago::TARJETA_DEBITO,
        'monto_tarjeta_credito' => MetodoPago::TARJETA_CREDITO,
    ];

    public function listar(): Collection
    {
        return Corte::activo()->with('usuarioCrea')->orderByDesc('id_corte')->get();
    }

    public function porId(int $idCorte): array
    {
        $corte = Corte::with('usuarioCrea')->findOrFail($idCorte);

        $evidencias = CorteEvidencia::activo()
            ->where('id_corte', $idCorte)
            ->with('tipoEvidencia')
            ->get()
            ->map(fn (CorteEvidencia $e) => [
                'id_corte_evidencia' => $e->id_corte_evidencia,
                'id_metodo_pago' => $e->id_metodo_pago,
                's_tipo_evidencia' => $e->tipoEvidencia?->s_tipo_evidencia,
                's_nombre_archivo' => $e->s_nombre_archivo,
                's_descripcion' => $e->s_descripcion,
            ]);

        return ['corte' => $corte, 'evidencias' => $evidencias];
    }

    /**
     * Crea el corte con los montos declarados y asocia todas las ventas del
     * día sin corte para cada método con monto > 0, marcándolas con b_corte.
     *
     * @return array{corte: Corte, total_usuario: float, total_ventas: float, diferencia: float, ventas_corte: Collection}
     */
    public function crear(array $datos, int $idUsuario): array
    {
        return DB::transaction(function () use ($datos, $idUsuario) {
            $totalUsuario = collect(self::MONTOS_POR_METODO)
                ->keys()
                ->sum(fn (string $campo) => (float) ($datos[$campo] ?? 0));

            $corte = Corte::create([
                'id_tipo_corte' => $datos['id_tipo_corte'],
                'id_usuario_crea' => $idUsuario,
                'd_fecha_corte' => $datos['fecha_corte'],
                'n_monto_efectivo' => $datos['monto_efectivo'] ?? 0,
                'n_monto_transferencia' => $datos['monto_transferencia'] ?? 0,
                'n_monto_credito' => $datos['monto_credito'] ?? 0,
                'n_monto_tarjeta_debito' => $datos['monto_tarjeta_debito'] ?? 0,
                'n_monto_tarjeta_credito' => $datos['monto_tarjeta_credito'] ?? 0,
                'n_monto_total' => $totalUsuario,
                's_descripcion_corte' => $datos['descripcion'] ?? null,
                's_comentario' => $datos['comentario'] ?? null,
                'b_activo' => 1,
            ]);

            $ventasCorte = collect();

            foreach (self::MONTOS_POR_METODO as $campo => $idMetodo) {
                if ((float) ($datos[$campo] ?? 0) <= 0) {
                    continue;
                }

                $ventas = Venta::where('b_corte', 0)
                    ->whereDate('created_at', $datos['fecha_corte'])
                    ->where('id_metodo_pago', $idMetodo)
                    ->orderBy('created_at')
                    ->get();

                foreach ($ventas as $venta) {
                    CorteVenta::create([
                        'id_corte' => $corte->id_corte,
                        'id_venta' => $venta->id_venta,
                        'b_activo' => 1,
                    ]);
                    $venta->update(['b_corte' => 1]);
                    $ventasCorte->push($venta);
                }
            }

            $totalVentas = (float) $ventasCorte->sum('n_total');

            return [
                'corte' => $corte,
                'total_usuario' => $totalUsuario,
                'total_ventas' => $totalVentas,
                'diferencia' => round($totalUsuario - $totalVentas, 2),
                'ventas_corte' => $ventasCorte,
            ];
        });
    }

    /** @param array<int, array{archivo: UploadedFile, id_metodo_pago: int, id_tipo_evidencia: int, s_descripcion?: string}> $evidencias */
    public function subirEvidencias(int $idCorte, array $evidencias): void
    {
        Corte::findOrFail($idCorte);

        $ruta = public_path(self::DIRECTORIO_EVIDENCIAS);
        File::ensureDirectoryExists($ruta);

        DB::transaction(function () use ($idCorte, $evidencias, $ruta) {
            foreach ($evidencias as $indice => $evidencia) {
                /** @var UploadedFile $archivo */
                $archivo = $evidencia['archivo'];
                $nombre = sprintf(
                    'evidencia_corte%d_%s_%d.%s',
                    $idCorte,
                    now()->format('YmdHis'),
                    $indice,
                    $archivo->getClientOriginalExtension(),
                );

                $archivo->move($ruta, $nombre);

                CorteEvidencia::create([
                    'id_corte' => $idCorte,
                    'id_metodo_pago' => $evidencia['id_metodo_pago'],
                    'id_tipo_evidencia' => $evidencia['id_tipo_evidencia'],
                    's_nombre_archivo' => $nombre,
                    's_descripcion' => $evidencia['s_descripcion'] ?? null,
                    'b_activo' => 1,
                ]);
            }
        });
    }

    public function cerrar(int $idCorte): void
    {
        Corte::findOrFail($idCorte)->update(['b_activo' => 0]);
    }

    /** Resumen del día por método de pago + desglose por cuenta bancaria. */
    public function desglosadoDelDia(?string $fechaHora): array
    {
        $fecha = $fechaHora ? date('Y-m-d', strtotime($fechaHora)) : now()->toDateString();
        $rango = ["$fecha 00:00:00", "$fecha 23:59:59"];

        $resumen = DB::table('tw_ventas AS v')
            ->join('tc_metodos_pagos AS m', 'v.id_metodo_pago', '=', 'm.id_metodo_pago')
            ->select(
                'v.id_metodo_pago',
                'm.s_metodo_pago AS s_nombre_metodo',
                DB::raw('GROUP_CONCAT(v.id_venta) AS id_ventas'),
                DB::raw('COUNT(v.id_venta) AS total_ventas'),
                DB::raw('SUM(v.n_total) AS total_dinero'),
            )
            ->where('v.id_estatus_venta', EstatusVenta::PAGADA)
            ->whereBetween('v.created_at', $rango)
            ->groupBy('v.id_metodo_pago', 'm.s_metodo_pago')
            ->orderBy('v.id_metodo_pago')
            ->get();

        $bancos = DB::table('tw_ventas AS v')
            ->join('tc_cuentas_bancarias AS c', 'v.id_cuenta_bancaria', '=', 'c.id_cuenta_bancaria')
            ->join('tc_metodos_pagos AS m', 'v.id_metodo_pago', '=', 'm.id_metodo_pago')
            ->select(
                'v.id_metodo_pago',
                'm.s_metodo_pago AS s_nombre_metodo',
                'c.s_nombre_cuenta AS cuenta',
                DB::raw('GROUP_CONCAT(v.id_venta) AS id_ventas'),
                DB::raw('COUNT(v.id_venta) AS total_ventas'),
                DB::raw('SUM(v.n_total) AS total_dinero'),
            )
            ->whereIn('v.id_metodo_pago', [MetodoPago::TARJETA_CREDITO, MetodoPago::TARJETA_DEBITO, MetodoPago::TRANSFERENCIA])
            ->where('v.id_estatus_venta', EstatusVenta::PAGADA)
            ->whereBetween('v.created_at', $rango)
            ->groupBy('v.id_metodo_pago', 'm.s_metodo_pago', 'c.id_cuenta_bancaria', 'c.s_nombre_cuenta')
            ->orderBy('v.id_metodo_pago')
            ->orderBy('c.s_nombre_cuenta')
            ->get();

        return [
            'resumen' => $resumen,
            'desglose_bancos' => $bancos,
            'total_general' => round((float) $resumen->sum('total_dinero'), 2),
        ];
    }
}
