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
        .kpis { display: flex; gap: 12px; margin: 0 20px 20px; }
        .kpi { background: #662a5b; color: white; border-radius: 8px; padding: 10px 12px; flex: 1; text-align: center; }
        .kpi .valor { font-size: 16px; font-weight: bold; }
        .kpi .etiq  { font-size: 8px; opacity: .85; margin-top: 3px; }
        .kpi.ok       { background: #28a745; }
        .kpi.warning  { background: #ffc107; color: #333; }
        .kpi.danger   { background: #dc3545; }
        .section { margin: 0 20px 20px; }
        .section-title { font-size: 13px; font-weight: bold; color: #662a5b; border-bottom: 2px solid #662a5b; padding-bottom: 6px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; font-size: 9.5px; }
        thead th { background: #662a5b; color: white; padding: 6px 8px; text-align: left; }
        tbody tr:nth-child(even) { background: #fdf5fc; }
        tbody td { padding: 5px 8px; border-bottom: 1px solid #f0e8ef; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge-normal  { background:#d4edda; color:#155724; padding:2px 6px; border-radius:10px; font-size:8px; }
        .badge-bajo    { background:#fff3cd; color:#856404; padding:2px 6px; border-radius:10px; font-size:8px; }
        .badge-critico { background:#f8d7da; color:#721c24; padding:2px 6px; border-radius:10px; font-size:8px; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 9px; color: #999; padding: 8px; border-top: 1px solid #f0e8ef; }
    </style>
</head>
<body>
<div class="header">
    <div class="logo-text">MUJER BONITA</div>
    <h1>Reporte de Inventario</h1>
    <div class="sub">Generado el {{ now()->format('d/m/Y H:i') }} por {{ auth()->user()->name }}</div>
</div>

<div class="kpis">
    <div class="kpi">
        <div class="valor">{{ $datos['totalProductos'] }}</div>
        <div class="etiq">TOTAL PRODUCTOS</div>
    </div>
    <div class="kpi ok">
        <div class="valor">{{ $datos['productosOk'] }}</div>
        <div class="etiq">STOCK OK</div>
    </div>
    <div class="kpi warning">
        <div class="valor">{{ $datos['productosBajo'] }}</div>
        <div class="etiq">STOCK BAJO</div>
    </div>
    <div class="kpi danger">
        <div class="valor">{{ $datos['productosCritico'] }}</div>
        <div class="etiq">SIN STOCK</div>
    </div>
    <div class="kpi" style="background:#17a2b8;">
        <div class="valor">₡{{ number_format($datos['valorInventario'], 0, ',', '.') }}</div>
        <div class="etiq">VALOR INVENTARIO</div>
    </div>
</div>

<div class="section">
    <div class="section-title">Inventario Completo de Productos</div>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Categoría</th>
                <th class="text-center">Stock</th>
                <th class="text-center">Mínimo</th>
                <th class="text-right">P. Compra</th>
                <th class="text-right">P. Venta</th>
                <th class="text-center">Alerta</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datos['productos'] as $p)
            <tr>
                <td>{{ $p->nombre }} @if($p->marca)<small>({{ $p->marca }})</small>@endif</td>
                <td>{{ $p->categoria }}</td>
                <td class="text-center">{{ $p->stock_actual }} {{ $p->unidad_medida }}</td>
                <td class="text-center">{{ $p->stock_minimo }}</td>
                <td class="text-right">₡{{ number_format($p->precio_compra, 2, ',', '.') }}</td>
                <td class="text-right">₡{{ number_format($p->precio_venta, 2, ',', '.') }}</td>
                <td class="text-center"><span class="badge-{{ $p->nivel_alerta }}">{{ ucfirst($p->nivel_alerta) }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="footer">MUJER BONITA — Sistema de Gestión — Reporte confidencial</div>
</body>
</html>
