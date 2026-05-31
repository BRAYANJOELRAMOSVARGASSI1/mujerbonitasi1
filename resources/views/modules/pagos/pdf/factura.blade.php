<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura_{{ $pago->transaccion_id }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; color: #d63384; }
        .header p { margin: 5px 0; color: #666; }
        .details { width: 100%; margin-bottom: 30px; }
        .details td { vertical-align: top; width: 50%; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .table th, .table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .table th { background-color: #f8f9fa; }
        .total-row th { text-align: right; }
        .footer { text-align: center; color: #777; font-size: 12px; margin-top: 50px; }
        .badge { background: #eee; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>MUJER BONITA</h1>
        <p>Salón de Belleza Integral</p>
        <h2>FACTURA DE PAGO</h2>
    </div>

    <table class="details">
        <tr>
            <td>
                <strong>Cliente:</strong><br>
                Nombre: {{ $pago->cita->cliente->nombre ?? 'N/A' }}<br>
                Teléfono: {{ $pago->cita->cliente->telefono ?? 'N/A' }}<br>
                Email: {{ $pago->cita->cliente->usuario->email ?? 'N/A' }}
            </td>
            <td style="text-align: right;">
                <strong>Detalles:</strong><br>
                Transacción: {{ $pago->transaccion_id }}<br>
                Fecha: {{ $pago->updated_at->format('d/m/Y H:i') }}<br>
                Método: <span class="badge">{{ strtoupper($pago->metodo) }}</span>
            </td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Estilista</th>
                <th>Monto</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $pago->cita->servicio->nombre ?? 'Servicio Estándar' }} (Cita #{{ $pago->cita->id }})</td>
                <td>{{ $pago->cita->estilista->nombre ?? 'N/A' }}</td>
                <td>${{ number_format($pago->monto, 2) }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <th colspan="2">TOTAL PAGADO:</th>
                <th>${{ number_format($pago->monto, 2) }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>¡Gracias por tu preferencia!</p>
        <p>Este documento es un comprobante válido de pago electrónico o manual.</p>
    </div>
</body>
</html>
