<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden de Compra</title>
    <style>
        @page { margin: 0px; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
            margin: 40px;
        }
        /* Header */
        .header-table { width: 100%; margin-bottom: 30px; }
        .company-logo { font-size: 24px; font-weight: bold; color: #2c3e50; }
        .company-details { text-align: right; font-size: 10px; color: #777; }

        /* Título y Folio */
        .doc-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            color: #2c3e50;
        }

        /* Info del Proveedor y Orden */
        .info-section { width: 100%; margin-bottom: 20px; }
        .info-box {
            width: 48%;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 4px;
            vertical-align: top;
        }
        .info-label { font-weight: bold; font-size: 10px; color: #555; text-transform: uppercase; display: block; margin-bottom: 4px;}
        .info-value { font-size: 12px; font-weight: bold;}

        /* Tabla de Productos */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background-color: #2c3e50;
            color: #fff;
            padding: 8px;
            text-align: left;
            font-weight: normal;
            font-size: 11px;
        }
        .items-table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
            font-size: 11px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* Totales */
        .totals-table { width: 100%; margin-top: 10px; }
        .totals-table td { padding: 5px; }
        .total-row {
            background-color: #2c3e50;
            color: #fff;
            font-weight: bold;
            padding: 8px;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            border-top: 1px solid #eee;
            padding-top: 10px;
            font-size: 10px;
            color: #777;
            text-align: center;
        }
        .observaciones {
            margin-top: 20px;
            font-size: 11px;
            font-style: italic;
            border: 1px dashed #ddd;
            padding: 10px;
        }
    </style>
</head>
<body>

<table class="header-table">
    <tr>
        <td width="50%">
            <div class="company-logo">Vag Xpress</div>
        </td>
        <td width="50%" class="company-details">
            Calle Principal #123, Puebla<br>
            RFC: AAA010101AAA<br>
            Tel: (555) 123-4567<br>
            contacto@vag.com
        </td>
    </tr>
</table>

<div class="doc-title">Orden de Compra</div>

<table class="info-section">
    <tr>
        <td class="info-box">
            <span class="info-label">Proveedor</span>
            <div class="info-value">{{ $orden->s_proveedor }}</div>
            <div>RFC/ID: {{ $orden->id_proveedor }}</div> <br>
            <span class="info-label">Contacto</span>
            <div></div>
        </td>
        <td width="4%"></td> <td class="info-box">
            <table width="100%">
                <tr>
                    <td><span class="info-label">Folio Interno:</span></td>
                    <td class="text-right"><strong>{{ $orden->s_folio_interno }}</strong></td>
                </tr>
                <tr>
                    <td><span class="info-label">Fecha Emisión:</span></td>
                    <td class="text-right">{{ \Carbon\Carbon::parse($orden->d_fecha_orden)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td><span class="info-label">Fecha Entrega Est.:</span></td>
                    <td class="text-right">{{ $orden->d_fecha_recepcion_estimada ? \Carbon\Carbon::parse($orden->d_fecha_recepcion_estimada)->format('d/m/Y') : 'Por definir' }}</td>
                </tr>
                <tr>
                    <td><span class="info-label">Estatus:</span></td>
                    <td class="text-right">{{ $orden->s_estatus_orden_compra }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table class="items-table">
    <thead>
    <tr>
        <th width="15%">No. Parte</th>
        <th width="40%">Descripción / Refacción</th>
        <th width="10%" class="text-center">Cant.</th>
        <th width="15%" class="text-right">Costo Unit.</th>
        <th width="20%" class="text-right">Importe</th>
    </tr>
    </thead>
    <tbody>
    @foreach($detalles as $item)
    <tr>
        <td>{{ $item->s_numero_parte }}</td>
        <td>
            <strong>{{ $item->s_nombre_refaccion }}</strong><br>
            <span style="color:#777; font-size:9px;">SKU: {{ $item->s_sku }}</span>
        </td>
        <td class="text-center">{{ $item->n_cantidad_solicitada }}</td>
        <td class="text-right">$ {{ number_format($item->n_costo_unitario, 2) }}</td>
        <td class="text-right">$ {{ number_format($item->n_cantidad_solicitada * $item->n_costo_unitario, 2) }}</td>
    </tr>
    @endforeach
    </tbody>
</table>

<table class="totals-table">
    <tr>
        <td width="60%">
            @if($orden->s_observacion)
            <div class="observaciones">
                <strong>Observaciones:</strong><br>
                {{ $orden->s_observacion }}
            </div>
            @endif
        </td>
        <td width="40%">
            <table width="100%">
                <tr>
                    <td class="text-right">Subtotal:</td>
                    <td class="text-right">$ {{ number_format($orden->n_total_estimado, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-right total-row">TOTAL:</td>
                    <td class="text-right total-row">$ {{ number_format($orden->n_total_estimado, 2) }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div class="footer">
    Este documento es una orden de compra generada electrónicamente.<br>
    Generado por Sistema VagXpress - {{ date('d/m/Y H:i') }}
</div>

</body>
</html>
