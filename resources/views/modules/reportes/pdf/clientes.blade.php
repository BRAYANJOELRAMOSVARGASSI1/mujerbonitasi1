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
        .kpis { display: flex; gap: 15px; margin: 0 20px 20px; }
        .kpi { background: #662a5b; color: white; border-radius: 8px; padding: 12px 15px; flex: 1; text-align: center; }
        .kpi .valor { font-size: 18px; font-weight: bold; }
        .kpi .etiq  { font-size: 9px; opacity: .85; margin-top: 3px; }
        .section { margin: 0 20px 20px; }
        .section-title { font-size: 13px; font-weight: bold; color: #662a5b; border-bottom: 2px solid #662a5b; padding-bottom: 6px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        thead th { background: #662a5b; color: white; padding: 7px 10px; text-align: left; }
        tbody tr:nth-child(even) { background: #fdf5fc; }
        tbody td { padding: 6px 10px; border-bottom: 1px solid #f0e8ef; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge-activo   { background:#d4edda; color:#155724; padding:2px 8px; border-radius:10px; }
        .badge-inactivo { background:#e2e3e5; color:#383d41; padding:2px 8px; border-radius:10px; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 9px; color: #999; padding: 8px; border-top: 1px solid #f0e8ef; }
    </style>
</head>
<body>
<div class="header">
    <div class="logo-text">MUJER BONITA</div>
    <h1>Reporte de Clientes</h1>
    <div class="sub">Generado el {{ now()->format('d/m/Y H:i') }} por {{ auth()->user()->name }}</div>
</div>

<div class="kpis">
    <div class="kpi">
        <div class="valor">{{ $datos['clientesList']->total() }}</div>
        <div class="etiq">TOTAL CLIENTES</div>
    </div>
    <div class="kpi">
        <div class="valor">{{ $datos['totalActivos'] }}</div>
        <div class="etiq">ACTIVOS</div>
    </div>
    <div class="kpi">
        <div class="valor">{{ $datos['totalInactivos'] }}</div>
        <div class="etiq">INACTIVOS</div>
    </div>
    <div class="kpi">
        <div class="valor">{{ $datos['clientesNuevos'] }}</div>
        <div class="etiq">NUEVOS (PERÍODO)</div>
    </div>
</div>

<div class="section">
    <div class="section-title">Listado de Clientes</div>
    <table>
        <thead>
            <tr>
                <th>Nombre Completo</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th class="text-center">Citas</th>
                <th class="text-center">Estado</th>
                <th>Registro</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datos['clientesList'] as $c)
            <tr>
                <td>{{ $c->nombre_completo }}</td>
                <td>{{ $c->telefono ?? '—' }}</td>
                <td>{{ $c->email ?? '—' }}</td>
                <td class="text-center">{{ $c->total_citas }}</td>
                <td class="text-center">
                    <span class="badge-{{ $c->estado }}">{{ ucfirst($c->estado) }}</span>
                </td>
                <td>{{ $c->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($datos['topClientes']->count())
<div class="section">
    <div class="section-title">Top 5 Clientes (por número de citas)</div>
    <table>
        <thead>
            <tr><th>#</th><th>Nombre</th><th>Teléfono</th><th class="text-right">Total Citas</th></tr>
        </thead>
        <tbody>
            @foreach($datos['topClientes'] as $i => $tc)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $tc->nombre_completo }}</td>
                <td>{{ $tc->telefono ?? '—' }}</td>
                <td class="text-right">{{ $tc->citas_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<div class="footer">MUJER BONITA — Sistema de Gestión — Reporte confidencial</div>
</body>
</html>
