<?php

namespace App\Services;

use App\Events\VerificarStockBajo;
use App\Models\Cliente;
use App\Models\Credito;
use App\Models\EstatusVenta;
use App\Models\MetodoPago;
use App\Models\PorcentajeUtilidad;
use App\Models\Refaccion;
use App\Models\Venta;
use App\Models\VentaRefaccion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VentaService
{
    public const CLIENTE_PUBLICO_GENERAL = 1;

    public function __construct(private readonly PdfService $pdfService)
    {
    }

    public function listar(): Collection
    {
        return Venta::activo()
            ->with(['estatusVenta', 'metodoPago', 'cliente'])
            ->orderByDesc('id_venta')
            ->get();
    }

    public function porId(int $idVenta): Venta
    {
        return Venta::activo()
            ->with(['estatusVenta', 'metodoPago', 'cliente'])
            ->findOrFail($idVenta);
    }

    /** Ventas pagadas de un día, para armar el corte. */
    public function ventasDelDia(?string $fecha): Collection
    {
        $fecha = $fecha ? date('Y-m-d', strtotime($fecha)) : now()->toDateString();

        return Venta::activo()
            ->with(['cliente', 'metodoPago'])
            ->where('id_estatus_venta', EstatusVenta::PAGADA)
            ->whereBetween('created_at', ["$fecha 00:00:00", "$fecha 23:59:59"])
            ->orderBy('created_at')
            ->get();
    }

    /**
     * Crea la venta con su detalle, descuenta stock, genera el crédito si
     * aplica y devuelve el detalle + ticket PDF en base64. Tras el commit
     * dispara la verificación de stock bajo (requisición automática).
     *
     * @return array{venta: Venta, detalle: array, ticket_base64: string}
     */
    public function crear(array $datos, int $idUsuario): array
    {
        [$venta, $detalle, $credito] = DB::transaction(function () use ($datos, $idUsuario) {
            $venta = Venta::create([
                'id_estatus_venta' => EstatusVenta::PAGADA,
                'id_cliente' => $datos['id_cliente'],
                'id_metodo_pago' => $datos['id_metodo_pago'],
                'id_cuenta_bancaria' => $datos['id_cuenta_bancaria'] ?? null,
                'id_usuario_crea' => $idUsuario,
                'n_porcentaje_iva' => 16.00,
                'b_corte' => 0,
                'b_activo' => 1,
            ]);

            $detalle = [];
            $subtotal = 0;

            foreach ($datos['refacciones'] as $item) {
                $refaccion = Refaccion::lockForUpdate()->findOrFail($item['id_refaccion']);

                $porcentajeUtilidad = isset($item['id_porcentaje_utilidad'])
                    ? PorcentajeUtilidad::findOrFail($item['id_porcentaje_utilidad'])
                    : null;
                $factorUtilidad = 1 + ($porcentajeUtilidad?->n_porcentaje_utilidad ?? 0) / 100;

                $renglon = VentaRefaccion::create([
                    'id_venta' => $venta->id_venta,
                    'id_refaccion' => $refaccion->id_refaccion,
                    'n_cantidad' => $item['n_cantidad'],
                    'n_costo_unitario' => $refaccion->n_precio_venta,
                    'n_porcentaje_utilidad' => $porcentajeUtilidad?->n_porcentaje_utilidad,
                    'n_total' => round($item['n_cantidad'] * $refaccion->n_precio_venta * $factorUtilidad, 2),
                    'n_stock_previo' => $refaccion->n_stock_actual,
                    'n_stock_posterior' => $refaccion->n_stock_actual - $item['n_cantidad'],
                    'b_activo' => 1,
                ]);

                $refaccion->decrement('n_stock_actual', $item['n_cantidad']);

                $subtotal += (float) $renglon->n_total;
                $detalle[] = [...$renglon->toArray(), 'nombre_refaccion' => $refaccion->s_nombre_refaccion];
            }

            $venta->update([
                'n_subtotal' => round($subtotal, 2),
                'n_total' => round($subtotal * 1.16, 2),
                'n_cantidad_refacciones' => count($datos['refacciones']),
            ]);

            $credito = null;
            if ((int) $datos['id_metodo_pago'] === MetodoPago::CREDITO) {
                $credito = Credito::create([
                    'id_venta' => $venta->id_venta,
                    'n_total_a_pagar' => $venta->n_total,
                    'n_total_pagado' => 0,
                    'id_tipo_credito' => 1,
                    'id_estatus_credito' => 1,
                    'id_usuario_crea' => $idUsuario,
                    'b_activo' => 1,
                ]);

                if ((int) $datos['id_cliente'] !== self::CLIENTE_PUBLICO_GENERAL) {
                    Cliente::where('id_cliente', $datos['id_cliente'])
                        ->increment('n_saldo_actual', (float) $venta->n_total);
                }
            }

            return [$venta, $detalle, $credito];
        });

        $this->verificarStockBajo($datos['refacciones'], $idUsuario);

        return [
            'venta' => $venta,
            'detalle' => $detalle,
            'ticket_base64' => $this->pdfService->ticketVentaBase64([
                'venta' => $venta,
                'detalles' => $detalle,
                'cliente' => Cliente::find($datos['id_cliente']),
                'credito' => $credito,
            ]),
        ];
    }

    /** La requisición automática nunca debe tirar la venta ya confirmada. */
    private function verificarStockBajo(array $refacciones, int $idUsuario): void
    {
        try {
            $ids = collect($refacciones)->pluck('id_refaccion')->all();
            event(new VerificarStockBajo($ids, $idUsuario));
        } catch (\Throwable $e) {
            Log::error('Error al generar requisición automática: ' . $e->getMessage());
        }
    }
}
