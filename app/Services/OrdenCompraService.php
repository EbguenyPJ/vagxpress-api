<?php

namespace App\Services;

use App\Exceptions\DomainException;
use App\Models\EstatusOrdenCompra;
use App\Models\EstatusRequisicion;
use App\Models\OrdenCompra;
use App\Models\OrdenCompraRequisicionRefaccion;
use App\Models\RequisicionRefaccion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;

class OrdenCompraService
{
    public function __construct(private readonly PdfService $pdfService)
    {
    }

    public function listar(): Collection
    {
        return OrdenCompra::activo()
            ->with(['proveedor', 'estatusOrdenCompra'])
            ->orderByDesc('id_orden_compra')
            ->get();
    }

    /** Renglones de la orden con datos de refacción, motivo y prioridad. */
    public function detalle(int $idOrdenCompra): SupportCollection
    {
        OrdenCompra::activo()->findOrFail($idOrdenCompra);

        return OrdenCompraRequisicionRefaccion::activo()
            ->where('id_orden_compra', $idOrdenCompra)
            ->with(['requisicionRefaccion.refaccion', 'requisicionRefaccion.motivoPedido', 'requisicionRefaccion.prioridad'])
            ->get()
            ->map(fn (OrdenCompraRequisicionRefaccion $renglon) => [
                'id_orden_compra_requisicion_refaccion' => $renglon->id_orden_compra_requisicion_refaccion,
                's_nombre_refaccion' => $renglon->requisicionRefaccion?->refaccion?->s_nombre_refaccion,
                's_numero_parte' => $renglon->requisicionRefaccion?->refaccion?->s_numero_parte,
                'id_requisicion_refaccion' => $renglon->id_requisicion_refaccion,
                'n_cantidad_sugerida' => $renglon->requisicionRefaccion?->n_cantidad_sugerida,
                'n_cantidad_solicitada' => $renglon->requisicionRefaccion?->n_cantidad_solicitada,
                'n_costo_unitario' => $renglon->requisicionRefaccion?->n_costo_unitario,
                'id_motivo_pedido' => $renglon->requisicionRefaccion?->id_motivo_pedido,
                's_motivo_pedido' => $renglon->requisicionRefaccion?->motivoPedido?->s_motivo_pedido,
                'id_prioridad' => $renglon->requisicionRefaccion?->id_prioridad,
                's_prioridad' => $renglon->requisicionRefaccion?->prioridad?->s_prioridad,
            ]);
    }

    /**
     * Genera órdenes de compra desde requisiciones: una orden por bloque
     * (proveedor), solo con los renglones autorizados, folio interno
     * OC{orden}-R{requisición}-P{proveedor} y total estimado calculado.
     *
     * @return array<int, OrdenCompra>
     */
    public function generarDesdeRequisiciones(array $ordenes, int $idUsuario): array
    {
        return DB::transaction(function () use ($ordenes, $idUsuario) {
            $generadas = [];

            foreach ($ordenes as $ordenData) {
                $autorizadas = collect($ordenData['refacciones'])->where('b_autorizada', 1);

                if ($autorizadas->isEmpty()) {
                    continue;
                }

                $orden = OrdenCompra::create([
                    's_observacion' => 'Generada desde Requisición #' . $ordenData['id_requisicion'],
                    'd_fecha_orden' => now(),
                    'n_total_estimado' => 0,
                    'id_proveedor' => $ordenData['id_proveedor'] ?? null,
                    'id_requisicion' => $ordenData['id_requisicion'],
                    'id_estatus_orden_compra' => EstatusOrdenCompra::CREADA,
                    'id_usuario_crea' => $idUsuario,
                    'b_activo' => 1,
                ]);

                $orden->update([
                    's_folio_interno' => sprintf(
                        'OC%d-R%d-P%s',
                        $orden->id_orden_compra,
                        $ordenData['id_requisicion'],
                        $ordenData['id_proveedor'] ?? '0',
                    ),
                ]);

                $totalOrden = 0;

                foreach ($autorizadas as $refaccionData) {
                    $renglon = RequisicionRefaccion::find($refaccionData['id_requisicion_refaccion']);

                    if (! $renglon) {
                        continue;
                    }

                    OrdenCompraRequisicionRefaccion::create([
                        'id_orden_compra' => $orden->id_orden_compra,
                        'id_requisicion_refaccion' => $renglon->id_requisicion_refaccion,
                        'n_cantidad_recibida' => 0,
                        'b_activo' => 1,
                    ]);

                    $renglon->update(['id_estatus_requisicion' => EstatusRequisicion::EN_ORDEN_COMPRA]);

                    $totalOrden += $renglon->n_cantidad_solicitada * $renglon->n_costo_unitario;
                }

                $orden->update(['n_total_estimado' => round($totalOrden, 2)]);
                $generadas[] = $orden;
            }

            return $generadas;
        });
    }

    /**
     * Aprueba (con cantidades finales) o rechaza una orden de compra.
     * Al rechazar, los renglones de requisición pasan a Rechazada para
     * que el stock bajo pueda volver a detectarse.
     */
    public function gestionar(int $idOrdenCompra, array $datos, int $idUsuario): void
    {
        DB::transaction(function () use ($idOrdenCompra, $datos, $idUsuario) {
            $orden = OrdenCompra::activo()->find($idOrdenCompra)
                ?? throw new DomainException('La orden de compra no existe o no está activa.', 404);

            if ((int) $datos['id_estatus_orden_compra'] === EstatusOrdenCompra::APROBADA) {
                $nuevoTotal = 0;

                foreach ($datos['refacciones'] as $refaccionData) {
                    $renglon = RequisicionRefaccion::find($refaccionData['id_requisicion_refaccion']);

                    if (! $renglon) {
                        continue;
                    }

                    $renglon->update(['n_cantidad_solicitada' => $refaccionData['n_cantidad_solicitada']]);
                    $nuevoTotal += $refaccionData['n_cantidad_solicitada'] * $renglon->n_costo_unitario;
                }

                $orden->update([
                    'id_estatus_orden_compra' => EstatusOrdenCompra::APROBADA,
                    'n_total_estimado' => round($nuevoTotal, 2),
                    'id_usuario_autoriza' => $idUsuario,
                ]);

                return;
            }

            $orden->update([
                'id_estatus_orden_compra' => EstatusOrdenCompra::RECHAZADA,
                'id_usuario_autoriza' => $idUsuario,
            ]);

            RequisicionRefaccion::whereIn(
                'id_requisicion_refaccion',
                collect($datos['refacciones'])->pluck('id_requisicion_refaccion'),
            )->update(['id_estatus_requisicion' => EstatusRequisicion::RECHAZADA]);
        });
    }

    /** @return array{folio: string, file_base64: string} */
    public function generarPdf(int $idOrdenCompra): array
    {
        $orden = OrdenCompra::activo()
            ->with(['proveedor', 'estatusOrdenCompra'])
            ->findOrFail($idOrdenCompra);

        $detalles = OrdenCompraRequisicionRefaccion::activo()
            ->where('id_orden_compra', $idOrdenCompra)
            ->with('requisicionRefaccion.refaccion')
            ->get()
            ->map(fn ($r) => (object) [
                's_nombre_refaccion' => $r->requisicionRefaccion?->refaccion?->s_nombre_refaccion,
                's_numero_parte' => $r->requisicionRefaccion?->refaccion?->s_numero_parte,
                's_sku' => $r->requisicionRefaccion?->refaccion?->s_sku,
                'n_cantidad_solicitada' => $r->requisicionRefaccion?->n_cantidad_solicitada,
                'n_costo_unitario' => $r->requisicionRefaccion?->n_costo_unitario,
            ]);

        $ordenParaVista = (object) [
            ...$orden->attributesToArray(),
            's_proveedor' => $orden->proveedor?->s_proveedor,
            's_estatus_orden_compra' => $orden->estatusOrdenCompra?->s_estatus_orden_compra,
        ];

        $pdf = $this->pdfService->ordenCompra(['orden' => $ordenParaVista, 'detalles' => $detalles]);
        $pdf->setPaper('letter', 'portrait');

        return [
            'folio' => $orden->s_folio_interno,
            'file_base64' => base64_encode($pdf->output()),
        ];
    }
}
