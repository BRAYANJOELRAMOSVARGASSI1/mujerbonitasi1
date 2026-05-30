<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; }
        .header { background: #381432; color: white; padding: 20px 25px; margin-bottom: 20px; }
        .header h1 { font-size: 18px; margin-bottom: 4px; }
        .header .sub { font-size: 11px; opacity: .8; }
        .logo-text { font-size: 22px; font-weight: bold; color: #cc5cb8; }
        .periodo { background: #f8f0f6; border-left: 4px solid #662a5b; padding: 10px 15px; margin: 0 20px 15px; border-radius: 4px; }
        .kpis { display: flex; gap: 12px; margin: 0 20px 20px; }
        .kpi { background: #662a5b; color: white; border-radius: 8px; padding: 10px; flex: 1; text-align: center; }
        .kpi .valor { font-size: 16px; font-weight: bold; }
        .kpi .etiq  { font-size: 8px; opacity: .85; margin-top: 3px; }
        .kpi.ok      { background: #28a745; }
        .kpi.warning { background: #ffc107; color: #333; }
        .kpi.danger  { background: #dc3545; }
        .section { margin: 0 20px 20px; }
        .section-title { font-size: 13px; font-weight: bold; color: #662a5b; border-bottom: 2px solid #662a5b; padding-bottom: 6px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; font-size: 9.5px; }
        thead th { background: #662a5b; color: white; padding: 6px 8px; text-align: left; }
        tbody tr:nth-child(even) { background: #fdf5fc; }
        tbody td { padding: 5px 8px; border-bottom: 1px solid #f0e8ef; }
        .text-right  { text-align: right; }
        .text-center { text-align: center; }
        .badge { padding: 2px 6px; border-radius: 10px; font-size: 8px; }
        .badge-completada { background:#d4edda; color:#155724; }
        .badge-pendiente  { background:#fff3cd; color:#856404; }
        .badge-cancelada  { background:#f8d7da; color:#721c24; }
        .badge-en_curso   { background:#cce5ff; color:#004085; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 9px; color: #999; padding: 8px; border-top: 1px solid #f0e8ef; }
    </style>
</head>
<body>
<div class="header">
    <div class="logo-text">MUJER BONITA</div>
    <h1>Reporte de Servicios y Citas</h1>
    <div class="sub">Generado el {{ now()->format('d/m/Y H:i') }} por {{ auth()->user()->name }}</div>
</div>

<div class="periodo">
    📅 Período: <strong>{{ $fechaInicio->format('d/m/Y') }}</strong> — <strong>{{ $fechaFin->format('d/m/Y') }}</strong>
</div>

<div class="kpis">
    <div class="kpi">
        <div class="valor">{{ $datos['totalCitas'] }}</div>
        <div class="etiq">TOTAL CITAS</div>
    </div>
    <div class="kpi ok">
        <div class="valor">{{ $datos['citasCompletadas'] }}</div>
        <div class="etiq">COMPLETADAS</div>
    </div>
    <div class="kpi warning">
        <div class="valor">{{ $datos['citasPendientes'] }}</div>
        <div class="etiq">PENDIENTES</div>
    </div>
    <div class="kpi danger">
        <div class="valor">{{ $datos['citasCanceladas'] }}</div>
        <div class="etiq">CANCELADAS</div>
    </div>
</div>

<div class="section">
    <div class="section-title">Historial de Citas</div>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Horario</th>
                <th>Cliente</th>
                <th>Estilista</th>
                <th>Servicio</th>
                <th class="text-right">Precio (₡)</th>
                <th class="text-center">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datos['citas'] as $cita)
            <tr>
                <td>{{ $cita->fecha?->format('d/m/Y') }}</td>
                <td>{{ $cita->hora_inicio }} - {{ $cita->hora_fin }}</td>
                <td>{{ $cita->cliente?->nombre_completo ?? 'N/A' }}</td>
                <td>{{ $cita->estilista?->nombre_completo ?? 'N/A' }}</td>
                <td>{{ $cita->servicio?->nombre ?? 'N/A' }}</td>
                <td class="text-right">₡{{ number_format($cita->precio_total ?? 0, 2, ',', '.') }}</td>
                <td class="text-center">
                    <span class="badge badge-{{ $cita->estado }}">{{ ucfirst($cita->estado) }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($datos['topServicios']->count())
<div class="section">
    <div class="section-title">Top 5 Servicios más Solicitados</div>
    <table>
        <thead>
            <tr><th>#</th><th>Servicio</th><th>Categoría</th><th class="text-right">Total Citas</th></tr>
        </thead>
        <tbody>
            @foreach($datos['topServicios'] as $i => $s)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $s->nombre }}</td>
                <td>{{ $s->categoria }}</td>
                <td class="text-right">{{ $s->total_citas }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<div class="footer">MUJER BONITA — Sistema de Gestión — Reporte confidencial</div>
</body>
</html>
