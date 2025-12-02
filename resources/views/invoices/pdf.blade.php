<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
        }
        
        .container {
            max-width: 100%;
            margin: 0;
            padding: 30px;
        }
        
        .header {
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        
        .header-top {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .company-info {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }
        
        .invoice-info {
            display: table-cell;
            width: 40%;
            text-align: right;
            vertical-align: top;
        }
        
        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }
        
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 8px;
        }
        
        .invoice-number {
            font-size: 16px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 12px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .info-table {
            width: 100%;
            display: table;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            width: 30%;
            padding: 6px 0;
            font-weight: bold;
            color: #555;
            font-size: 11px;
        }
        
        .info-value {
            display: table-cell;
            padding: 6px 0;
            color: #333;
            font-size: 11px;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .details-table thead {
            background-color: #2563eb;
            color: white;
        }
        
        .details-table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
        }
        
        .details-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        
        .details-table tbody tr:hover {
            background-color: #f9fafb;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .totals {
            margin-top: 20px;
            text-align: right;
        }
        
        .totals-table {
            display: inline-block;
            min-width: 300px;
        }
        
        .total-row {
            display: table;
            width: 100%;
            padding: 8px 0;
        }
        
        .total-label {
            display: table-cell;
            text-align: left;
            font-weight: bold;
            color: #555;
            font-size: 13px;
        }
        
        .total-value {
            display: table-cell;
            text-align: right;
            color: #333;
            font-size: 13px;
        }
        
        .grand-total {
            border-top: 3px solid #2563eb;
            padding-top: 12px;
            margin-top: 12px;
        }
        
        .grand-total .total-label,
        .grand-total .total-value {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #666;
            line-height: 1.4;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-paid {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .notes {
            background-color: #f9fafb;
            padding: 15px;
            border-left: 4px solid #2563eb;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Cabecera --}}
        <div class="header">
            <div class="header-top">
                <div class="company-info">
                    <div class="company-name">{{ config('app.name', 'HouseFixes') }}</div>
                    <div style="color: #666; margin-top: 2px; font-size: 9px;">
                        www.serviconnect.com | contacto@serviconnect.com
                    </div>
                </div>
                <div class="invoice-info">
                    <div class="invoice-title">FACTURA</div>
                    <div class="invoice-number">#{{ $invoice->invoice_number }}</div>
                    <div style="margin-top: 5px;">
                        @if($invoice->status === 'pending')
                            <span class="status-badge status-pending">Pendiente</span>
                        @elseif($invoice->status === 'paid')
                            <span class="status-badge status-paid">Pagada</span>
                        @else
                            <span class="status-badge status-cancelled">Cancelada</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Información Profesional y Cliente en dos columnas --}}
        <div class="section">
            <div style="display: table; width: 100%;">
                <div style="display: table-cell; width: 50%; padding-right: 15px; vertical-align: top;">
                    <div class="section-title">Profesional</div>
                    <div style="font-size: 12px; line-height: 1.6;">
                        <strong>{{ $booking->professional->name }}</strong><br>
                        {{ $booking->professional->email }}
                        @if($booking->professional->phone)<br>{{ $booking->professional->phone }}@endif
                    </div>
                </div>
                <div style="display: table-cell; width: 50%; padding-left: 15px; vertical-align: top;">
                    <div class="section-title">Cliente</div>
                    <div style="font-size: 12px; line-height: 1.6;">
                        <strong>{{ $booking->client->name }}</strong><br>
                        {{ $booking->client->email }}
                        @if($booking->client->phone)<br>{{ $booking->client->phone }}@endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Información de la Factura en una línea --}}
        <div class="section">
            <div class="section-title">Datos de la Factura</div>
            <div style="font-size: 12px; line-height: 1.6;">
                <strong>Emisión:</strong> {{ $invoice->issued_at->format('d/m/Y') }} | 
                <strong>Servicio:</strong> {{ $booking->datetime->format('d/m/Y H:i') }} | 
                <strong>Dirección:</strong> {{ $booking->address }}
            </div>
        </div>

        {{-- Detalles del Servicio --}}
        <div class="section">
            <div class="section-title">Detalles del Servicio</div>
            <table class="details-table">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>{{ $booking->service->title }}</strong><br>
                            <span style="color: #666; font-size: 9px;">
                                {{ Str::limit($booking->service->description, 60) }}
                            </span>
                        </td>
                        <td>{{ $booking->service->category->name }}</td>
                        <td class="text-right">{{ number_format($booking->total_price, 2) }}€</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Totales --}}
        <div class="totals">
            <div class="totals-table">
                <div class="total-row">
                    <div class="total-label">Subtotal:</div>
                    <div class="total-value">{{ number_format($booking->total_price, 2) }}€</div>
                </div>
                <div class="total-row">
                    <div class="total-label">IVA (21%):</div>
                    <div class="total-value">{{ number_format($booking->total_price * 0.21, 2) }}€</div>
                </div>
                <div class="total-row grand-total">
                    <div class="total-label">TOTAL:</div>
                    <div class="total-value">{{ number_format($booking->total_price * 1.21, 2) }}€</div>
                </div>
            </div>
        </div>

        {{-- Notas --}}
        @if($invoice->notes)
        <div class="notes">
            <strong>Notas:</strong><br>
            {{ $invoice->notes }}
        </div>
        @endif

        {{-- Pie de página --}}
        <div class="footer">
            Factura generada por {{ config('app.name') }} | contacto@serviconnect.com | Gracias por confiar en nosotros.
        </div>
    </div>
</body>
</html>
