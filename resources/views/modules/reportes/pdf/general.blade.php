<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; }
        .header { background: #381432; color: white; padding: 20px 25px; margin-bottom: 20px; }
        .header h1 { font-size: 20px; margin-bottom: 4px; }
        .header .sub { font-size: 11px; opacity: .8; }
        .logo-text { font-size: 24px; font-weight: bold; color: #cc5cb8; margin-bottom: 6px; }
        .periodo { background: #f8f0f6; border-left: 4px solid #662a5b; padding: 10px 15px; margin: 0 20px 15px; border-radius: 4px; }
        .kpis { display: flex; gap: 10px; margin: 0 20px 20px; }
        .kpi { background: #662a5b; color: white; border-radius: 8px; padding: 10px; flex: 1; text-align: center; }
        .kpi .valor { font-size: 15px; font-weight: bold; }
        .kpi .etiq  { font-size: 8px; opacity: .85; margin-top: 3px; }
        .section { margin: 0 20px 20px; page-break-inside: avoid; }
        .section-title { font-size: 13px; font-weight: bold; color: #662a5b; border-bottom: 2px solid #662a5b; padding-bottom: 5px; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; font-size: 9px; }
        thead th { background: #662a5b; color: white; padding: 5px 7px; text-align: left; }
        tbody tr:nth-child(even) { background: #fdf5fc; }
        tbody td { padding: 4px 7px; border-bottom: 1px solid #f0e8ef; }
        tfoot td { background: #381432; color: white; padding: 5px 7px; font-weight: bold; }
        .text-right  { text-align: right; }
        .text-center { text-align: center; }
        .badge { padding: 1px 5px; border-radius: 8px; font-size: 8px; }
        .badge-normal  { background:#d4edda; color:#155724; }
        .badge-bajo    { background:#fff3cd; color:#856404; }
        .badge-critico { background:#f8d7da; color:#721c24; }
        .page-break { page-break-after: always; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 9px; color: #999; padding: 8px; border-top: 1px solid #f0e8ef; }
    </style>
</head>
<body>
<div class="header">
    <div class="logo-text">MUJER BONITA</div>
    <h1>Reporte General del Negocio</h1>
    <div class="sub">Generado el {{ now()->format('d/m/Y H:i') }} | Administrador: {{ auth()->user()->name }}</div>
</div>

<div class="periodo">
    📅 Período de análisis: <strong>{{ $fechaInicio->format('d \d\e F Y') }}</strong> — <strong>{{ $fechaFin->format('d \d\e F Y') }}</strong>
</div>

{{-- KPIs generales --}}
<div class="kpis">
    <div class="kpi">
        <div class="valor">₡{{ number_format($datos['kpis']['ingresosPeriodo'], 0, ',', '.') }}</div>
        <div class="etiq">INGRESOS PERÍODO</div>
    </div>
    <div class="kpi">
        <div class="valor">{{ $datos['kpis']['serviciosRealizadosPeriodo'] }}</div>
        <div class="etiq">SERVICIOS</div>
    </div>
    <div class="kpi">
        <div class="valor">{{ $datos['kpis']['citasPeriodo'] }}</div>
        <div class="etiq">CITAS</div>
    </div>
    <div class="kpi">
        <div class="valor">{{ $datos['kpis']['clientesActivos'] }}</div>
        <div class="etiq">CLIENTES ACTIVOS</div>
    </div>
    <div class="kpi">
        <div class="valor">{{ $datos['kpis']['estilistasTotales'] }}</div>
        <div class="etiq">ESTILISTAS</div>
    </div>
    <div class="kpi" style="background:#dc3545;">
        <div class="valor">{{ $datos['kpis']['productosBajoStock'] }}</div>
        <div class="etiq">BAJO STOCK</div>
    </div>
</div>

{{-- Ventas resumen --}}
<div class="section">
    <div class="section-title">💰 Resumen de Ventas</div>
    <table>
        <thead>
            <tr><th>Fecha</th><th>Cliente</th><th>Estilista</th><th>Servicio</th><th class="text-right">Precio (₡)</th><th class="text-right">Comisión (₡)</th></tr>
        </thead>
        <tbody>
            @foreach($datos['ventas']['registros']->take(20) as $sr)
            <tr>
                <td>{{ optional($sr->fecha_realizacion)->format('d/m/Y') }}</td>
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
                <td class="text-right">₡{{ number_format($datos['ventas']['totalIngresos'], 2, ',', '.') }}</td>
                <td class="text-right">₡{{ number_format($datos['ventas']['totalComisiones'], 2, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</div>

{{-- Inventario crítico --}}
@php $prodCriticos = $datos['inventario']['productos']->filter(fn($p) => $p->nivel_alerta !== 'normal'); @endphp
@if($prodCriticos->count())
<div class="section">
    <div class="section-title">⚠️ Productos con Alerta de Stock</div>
    <table>
        <thead>
            <tr><th>Producto</th><th>Categoría</th><th class="text-center">Stock</th><th class="text-center">Mínimo</th><th class="text-center">Alerta</th></tr>
        </thead>
        <tbody>
            @foreach($prodCriticos as $p)
            <tr>
                <td>{{ $p->nombre }}</td>
                <td>{{ $p->categoria }}</td>
                <td class="text-center">{{ $p->stock_actual }}</td>
                <td class="text-center">{{ $p->stock_minimo }}</td>
                <td class="text-center"><span class="badge badge-{{ $p->nivel_alerta }}">{{ ucfirst($p->nivel_alerta) }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<div class="footer">MUJER BONITA — Reporte General Confidencial — {{ now()->format('d/m/Y H:i') }}</div>
</body>
</html>
