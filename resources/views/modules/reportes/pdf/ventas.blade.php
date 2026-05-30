<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; background: #fff; }
        .header { background: #381432; color: white; padding: 20px 25px; margin-bottom: 20px; }
        .header h1 { font-size: 18px; margin-bottom: 4px; }
        .header .sub { font-size: 11px; opacity: .8; }
        .logo-text { font-size: 22px; font-weight: bold; color: #cc5cb8; }
        .periodo { background: #f8f0f6; border-left: 4px solid #662a5b; padding: 10px 15px; margin: 0 20px 15px; border-radius: 4px; font-size: 11px; }
        .kpis { display: flex; gap: 15px; margin: 0 20px 20px; }
        .kpi { background: #662a5b; color: white; border-radius: 8px; padding: 12px 15px; flex: 1; text-align: center; }
        .kpi .valor { font-size: 16px; font-weight: bold; }
        .kpi .etiq  { font-size: 9px; opacity: .85; margin-top: 3px; }
        .section { margin: 0 20px 20px; }
        .section-title { font-size: 13px; font-weight: bold; color: #662a5b; border-bottom: 2px solid #662a5b; padding-bottom: 6px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        thead th { background: #662a5b; color: white; padding: 7px 10px; text-align: left; }
        tbody tr:nth-child(even) { background: #fdf5fc; }
        tbody td { padding: 6px 10px; border-bottom: 1px solid #f0e8ef; }
        tfoot td { background: #381432; color: white; padding: 7px 10px; font-weight: bold; }
        .text-right { text-align: right; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 9px; color: #999; padding: 8px; border-top: 1px solid #f0e8ef; }
    </style>
</head>
<body>
<div class="header">
    <div class="logo-text">MUJER BONITA</div>
    <h1>Reporte de Ventas e Ingresos</h1>
    <div class="sub">Generado el {{ now()->format('d/m/Y H:i') }} por {{ auth()->user()->name }}</div>
</div>

<div class="periodo">
    📅 Período: <strong>{{ $fechaInicio->format('d/m/Y') }}</strong> — <strong>{{ $fechaFin->format('d/m/Y') }}</strong>
</div>

<div class="kpis">
    <div class="kpi">
        <div class="valor">₡{{ number_format($datos['totalIngresos'], 2, ',', '.') }}</div>
        <div class="etiq">INGRESOS TOTALES</div>
    </div>
    <div class="kpi">
        <div class="valor">{{ $datos['registros']->count() }}</div>
        <div class="etiq">SERVICIOS REALIZADOS</div>
    </div>
    <div class="kpi">
        <div class="valor">₡{{ number_format($datos['totalComisiones'], 2, ',', '.') }}</div>
        <div class="etiq">TOTAL COMISIONES</div>
    </div>
    <div class="kpi">
        <div class="valor">₡{{ number_format($datos['ticketPromedio'], 2, ',', '.') }}</div>
        <div class="etiq">TICKET PROMEDIO</div>
    </div>
</div>

<div class="section">
    <div class="section-title">Detalle de Servicios Realizados</div>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Estilista</th>
                <th>Servicio</th>
                <th class="text-right">Precio (₡)</th>
                <th class="text-right">Comisión (₡)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datos['registros'] as $sr)
            <tr>
                <td>{{ optional($sr->fecha_realizacion)->format('d/m/Y H:i') }}</td>
                <td>{{ $sr->cliente?->nombre_completo ?? 'N/A' }}</td>
                <td>{{ $sr->estilista?->nombre_completo ?? 'N/A' }}</td>
                <td>{{ $sr->servicio?->nombre ?? 'N/A' }}</td>
                <td class="text-right">₡{{ number_format($sr->precio_cobrado, 2, ',', '.') }}</td>
                <td class="text-right">₡{{ number_format($sr->comision_monto, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right">TOTALES:</td>
                <td class="text-right">₡{{ number_format($datos['totalIngresos'], 2, ',', '.') }}</td>
                <td class="text-right">₡{{ number_format($datos['totalComisiones'], 2, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</div>

@if($datos['topEstilistas']->count())
<div class="section">
    <div class="section-title">Top Estilistas por Ingresos</div>
    <table>
        <thead>
            <tr><th>#</th><th>Estilista</th><th class="text-right">Ingresos (₡)</th><th class="text-right">Servicios</th></tr>
        </thead>
        <tbody>
            @foreach($datos['topEstilistas'] as $i => $est)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $est->estilista?->nombre_completo ?? 'N/A' }}</td>
                <td class="text-right">₡{{ number_format($est->total, 2, ',', '.') }}</td>
                <td class="text-right">{{ $est->cantidad }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<div class="footer">MUJER BONITA — Sistema de Gestión — Reporte confidencial</div>
</body>
</html>
