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
        .kpi .valor { font-size: 14px; font-weight: bold; }
        .kpi .etiq  { font-size: 8px; opacity: .85; margin-top: 3px; }
        .kpi.ok      { background: #28a745; }
        .kpi.warning { background: #ffc107; color: #333; }
        .kpi.danger  { background: #dc3545; }
        .section { margin: 0 20px 20px; }
        .section-title { font-size: 13px; font-weight: bold; color: #662a5b; border-bottom: 2px solid #662a5b; padding-bottom: 6px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        thead th { background: #662a5b; color: white; padding: 7px 8px; text-align: left; }
        tbody tr:nth-child(even) { background: #fdf5fc; }
        tbody td { padding: 6px 8px; border-bottom: 1px solid #f0e8ef; }
        .text-right  { text-align: right; }
        .text-center { text-align: center; }
        .badge { padding: 2px 6px; border-radius: 10px; font-size: 8px; }
        .badge-activa  { background:#d4edda; color:#155724; }
        .badge-inactiva { background:#f8d7da; color:#721c24; }
        .badge-aprobada { background:#d4edda; color:#155724; }
        .badge-pendiente { background:#fff3cd; color:#856404; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 9px; color: #999; padding: 8px; border-top: 1px solid #f0e8ef; }
    </style>
</head>
<body>
<div class="header">
    <div class="logo-text">MUJER BONITA</div>
    <h1>Reporte de Promociones y Comisiones</h1>
    <div class="sub">Generado el {{ now()->format('d/m/Y H:i') }} por {{ auth()->user()->name }}</div>
</div>

<div class="periodo">
    📅 Período: <strong>{{ $fechaInicio->format('d/m/Y') }}</strong> — <strong>{{ $fechaFin->format('d/m/Y') }}</strong>
</div>

<div class="kpis">
    <div class="kpi ok">
        <div class="valor">{{ $datos['activas'] }}</div>
        <div class="etiq">PROMOCIONES VIGENTES</div>
    </div>
    <div class="kpi danger">
        <div class="valor">{{ $datos['vencidas'] }}</div>
        <div class="etiq">VENCIDAS</div>
    </div>
    <div class="kpi" style="background:#17a2b8;">
        <div class="valor">₡{{ number_format($datos['totalComisionesPagadas'], 0, ',', '.') }}</div>
        <div class="etiq">COM. PAGADAS</div>
    </div>
    <div class="kpi warning">
        <div class="valor">₡{{ number_format($datos['totalComisionesPendientes'], 0, ',', '.') }}</div>
        <div class="etiq">COM. PENDIENTES</div>
    </div>
</div>

<div class="section">
    <div class="section-title">Promociones</div>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th class="text-center">Descuento</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th class="text-center">Estado</th>
                <th>Servicios</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datos['promociones'] as $p)
            <tr>
                <td><strong>{{ $p->nombre }}</strong></td>
                <td>{{ Str::limit($p->descripcion ?? '', 40) }}</td>
                <td class="text-center"><strong>{{ $p->porcentaje_descuento }}%</strong></td>
                <td>{{ $p->fecha_inicio?->format('d/m/Y') }}</td>
                <td>{{ $p->fecha_fin?->format('d/m/Y') }}</td>
                <td class="text-center">
                    <span class="badge badge-{{ $p->estado }}">{{ ucfirst($p->estado) }}</span>
                </td>
                <td>{{ $p->servicios->pluck('nombre')->join(', ') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($datos['comisiones']->count())
<div class="section">
    <div class="section-title">Comisiones de Estilistas</div>
    <table>
        <thead>
            <tr>
                <th>Estilista</th>
                <th>Período Inicio</th>
                <th>Período Fin</th>
                <th class="text-right">Total Servicios (₡)</th>
                <th class="text-right">Comisión (₡)</th>
                <th class="text-center">Cantidad</th>
                <th class="text-center">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datos['comisiones'] as $com)
            <tr>
                <td>{{ $com->estilista?->nombre_completo ?? 'N/A' }}</td>
                <td>{{ $com->periodo_inicio?->format('d/m/Y') }}</td>
                <td>{{ $com->periodo_fin?->format('d/m/Y') }}</td>
                <td class="text-right">₡{{ number_format($com->total_servicios, 2, ',', '.') }}</td>
                <td class="text-right"><strong>₡{{ number_format($com->total_comision, 2, ',', '.') }}</strong></td>
                <td class="text-center">{{ $com->cantidad_servicios }}</td>
                <td class="text-center">
                    <span class="badge badge-{{ $com->estado }}">{{ ucfirst($com->estado) }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<div class="footer">MUJER BONITA — Sistema de Gestión — Reporte confidencial</div>
</body>
</html>
