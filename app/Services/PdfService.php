<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfService
{
    /** Ticket POS térmico (72 mm) en base64 para impresión desde el frontend. */
    public function ticketVentaBase64(array $datos): string
    {
        $pdf = Pdf::loadView('tickets.venta_pos', $datos);
        $pdf->setPaper([0, 0, 205, 1000], 'portrait');

        return base64_encode($pdf->output());
    }

    /** PDF de orden de compra para descarga. */
    public function ordenCompra(array $datos)
    {
        return Pdf::loadView('ordenes-compras.orden_compra', $datos);
    }
}
