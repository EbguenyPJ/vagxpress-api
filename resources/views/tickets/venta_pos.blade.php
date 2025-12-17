<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Venta #{{ $venta->id_venta }}</title>
<style>
/* Configuración general para ticket térmico */
@page {
    margin: 0;
    padding: 0;
}
body {
    font-family: 'Courier New', Courier, monospace; /* Fuente tipo ticket */
            font-size: 10px; /* Tamaño pequeño para que quepa todo */
            margin: 5px;
            color: #000;
        }

        /* Utilidades */
.text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }

        /* Separadores */
.linea {
    border-bottom: 1px dashed #000;
            margin: 5px 0;
        }
        .linea-doble {
    border-bottom: 3px double #000;
            margin: 5px 0;
        }

        /* Tabla de productos */
table {
    width: 100%;
    border-collapse: collapse;
        }
        th {
    text-align: left;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
        }
        td {
    padding: 2px 0;
            vertical-align: top;
        }

        /* Ajuste de columnas */
.col-cant { width: 15%; }
.col-desc { width: 55%; }
.col-precio { width: 30%; text-align: right; }

        /* Totales */
.tabla-totales {
    margin-top: 10px;
        }
        .tabla-totales td {
    padding: 1px 0;
        }

        .footer {
    margin-top: 20px;
            font-size: 9px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header text-center">
{{-- <img src="{{ public_path('img/logo-ticket.png') }}" width="100" alt="Logo"> --}}

<h2 style="margin: 5px 0;">Vag Xpress</h2>
<div>RFC: AAA010101AAA</div>
<div>Av. Principal #123, Centro</div>
<div>Puebla, Pue. Tel: 222-123-4567</div>
</div>

<div class="linea-doble"></div>

<div>
    <strong>Folio:</strong> {{ str_pad($venta->id_venta, 6, '0', STR_PAD_LEFT) }}<br>
    <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($venta->created_at)->format('d/m/Y h:i A') }}<br>
    <strong>Cajero:</strong> {{ $venta->id_usuario_crea }} <br> <strong>Cliente:</strong> {{ isset($cliente->nombre) ? $cliente->nombre : 'Público General' }}
</div>

<div class="linea"></div>

<table>
    <thead>
    <tr>
        <th class="col-cant">Cant.</th>
        <th class="col-desc">Concepto</th>
        <th class="col-precio">Importe</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($detalles as $item)
        <tr>
            <td class="text-center">{{ $item['n_cantidad'] }}</td>
            <td>
                {{ $item['nombre_refaccion'] ?? 'Refacción ID: ' . $item['id_refaccion'] }}
            </td>
            <td class="text-right">${{ number_format($item['n_total'], 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="linea"></div>

<table class="tabla-totales">
    <tr>
        <td class="text-right" colspan="2">Subtotal:</td>
        <td class="text-right" style="width: 30%">${{ number_format($venta->n_subtotal, 2) }}</td>
    </tr>
    <tr>
        <td class="text-right" colspan="2">IVA (16%):</td>
        <td class="text-right">${{ number_format($venta->n_total - $venta->n_subtotal, 2) }}</td>
    </tr>
    <tr>
        <td class="text-right bold" colspan="2" style="font-size: 12px;">TOTAL:</td>
        <td class="text-right bold" style="font-size: 12px;">${{ number_format($venta->n_total, 2) }}</td>
    </tr>
</table>

<div style="margin-top: 10px;">
    <strong>Método de Pago:</strong>
    @if($venta->id_metodo_pago == 1) Crédito @elseif($venta->id_metodo_pago == 2) Efectivo @elseif($venta->id_metodo_pago == 3) Tarjeta Credito @elseif($venta->id_metodo_pago == 4) Tarjeta Debito @elseif($venta->id_metodo_pago == 5) Transferencia @else Otro @endif
</div>

@if(isset($credito))
    <div style="margin-top: 5px; border: 1px solid #000; padding: 5px;">
        <div class="text-center bold">PAGARÉ / CRÉDITO</div>
        <div style="font-size: 9px;">
            Debo y pagaré incondicionalmente a la orden de Vag Xpress la cantidad de ${{ number_format($credito->n_total_a_pagar, 2) }}.
        </div>
        <br><br>
        <div class="linea" style="width: 80%; margin: 0 auto;"></div>
        <div class="text-center">Firma del Cliente</div>
    </div>
@endif

<div class="footer">
    <p>¡Gracias por su compra!</p>
    <p>Este ticket no es comprobante fiscal.</p>
    <p>Software POS v1.0</p>
</div>

</body>
</html>
