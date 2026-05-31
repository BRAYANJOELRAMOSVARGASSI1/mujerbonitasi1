@extends('layouts.app')

@section('content')
{{-- ══════════════════════════════════════════════════════════════════════
     MÓDULO P6 — DASHBOARD DE REPORTES
     Solo accesible para admin / super-admin
══════════════════════════════════════════════════════════════════════ --}}

<style>
/* ── Variables de color del sistema MUJER BONITA ── */
:root {
    --mb-primary:    #662a5b;
    --mb-secondary:  #381432;
    --mb-accent:     #cc5cb8;
    --mb-light:      #f8f0f6;
    --mb-success:    #28a745;
    --mb-warning:    #ffc107;
    --mb-danger:     #dc3545;
    --mb-info:       #17a2b8;
    --mb-gold:       #f4a946;
}

/* ── Header del módulo ── */
.reportes-header {
    background: linear-gradient(135deg, var(--mb-secondary) 0%, var(--mb-primary) 60%, var(--mb-accent) 100%);
    border-radius: 16px;
    padding: 2rem;
    color: white;
    margin-bottom: 2rem;
    box-shadow: 0 8px 32px rgba(102,42,91,0.3);
    position: relative;
    overflow: hidden;
}
.reportes-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}
.reportes-header h1 {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0;
    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

/* ── KPI Cards ── */
.kpi-card {
    background: white;
    border-radius: 14px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border-left: 5px solid var(--mb-primary);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    height: 100%;
}
.kpi-card:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(102,42,91,0.15); }
.kpi-card.kpi-warning  { border-left-color: var(--mb-warning); }
.kpi-card.kpi-success  { border-left-color: var(--mb-success); }
.kpi-card.kpi-info     { border-left-color: var(--mb-info); }
.kpi-card.kpi-danger   { border-left-color: var(--mb-danger); }
.kpi-card.kpi-gold     { border-left-color: var(--mb-gold); }

.kpi-value {
    font-size: 2rem;
    font-weight: 800;
    color: var(--mb-primary);
    line-height: 1;
    margin: 0.4rem 0;
}
.kpi-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #888;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.kpi-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: var(--mb-light);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
}

/* ── Filtros ── */
.filtros-card {
    background: white;
    border-radius: 14px;
    padding: 1.5rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    margin-bottom: 1.5rem;
    border: 1px solid #f0e8ef;
}
.filtros-card .form-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--mb-primary);
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.filtros-card .form-control,
.filtros-card .form-select {
    border: 1.5px solid #e0d0de;
    border-radius: 8px;
    font-size: 0.9rem;
}
.filtros-card .form-control:focus,
.filtros-card .form-select:focus {
    border-color: var(--mb-accent);
    box-shadow: 0 0 0 3px rgba(204,92,184,0.15);
}

/* ── Tabs de secciones ── */
.reportes-nav .nav-link {
    color: #666;
    font-weight: 600;
    font-size: 0.85rem;
    padding: 0.75rem 1.25rem;
    border: none;
    border-bottom: 3px solid transparent;
    border-radius: 0;
    transition: all 0.2s;
}
.reportes-nav .nav-link:hover { color: var(--mb-primary); border-bottom-color: var(--mb-accent); }
.reportes-nav .nav-link.active {
    color: var(--mb-primary);
    border-bottom-color: var(--mb-primary);
    background: transparent;
}

/* ── Sección card ── */
.seccion-card {
    background: white;
    border-radius: 14px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.07);
    overflow: hidden;
}
.seccion-header {
    background: linear-gradient(90deg, var(--mb-secondary), var(--mb-primary));
    color: white;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.75rem;
}
.seccion-header h5 { margin: 0; font-weight: 700; font-size: 1rem; }

/* ── Botones de exportación ── */
.btn-export-pdf {
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 0.45rem 1rem;
    font-size: 0.82rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    transition: all 0.2s;
}
.btn-export-pdf:hover { background: #b02a37; color: white; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(220,53,69,0.3); }

.btn-export-excel {
    background: #198754;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 0.45rem 1rem;
    font-size: 0.82rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    transition: all 0.2s;
}
.btn-export-excel:hover { background: #146c43; color: white; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(25,135,84,0.3); }

/* ── Tablas ── */
.tabla-reportes {
    font-size: 0.875rem;
}
.tabla-reportes thead th {
    background: var(--mb-light);
    color: var(--mb-primary);
    font-weight: 700;
    border-top: none;
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.tabla-reportes tbody tr:hover { background: #fdf5fc; }

/* ── Badges de estado ── */
.badge-estado {
    padding: 0.35rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: capitalize;
}
.badge-completada  { background: #d4edda; color: #155724; }
.badge-pendiente   { background: #fff3cd; color: #856404; }
.badge-cancelada   { background: #f8d7da; color: #721c24; }
.badge-en_curso    { background: #cce5ff; color: #004085; }
.badge-activo      { background: #d4edda; color: #155724; }
.badge-inactivo    { background: #e2e3e5; color: #383d41; }
.badge-activa      { background: #d4edda; color: #155724; }
.badge-vencida     { background: #f8d7da; color: #721c24; }
.badge-normal      { background: #d4edda; color: #155724; }
.badge-bajo        { background: #fff3cd; color: #856404; }
.badge-critico     { background: #f8d7da; color: #721c24; }

/* ── Gráficas ── */
.grafica-container {
    position: relative;
    height: 250px;
}

/* ── Sub-KPIs ── */
.sub-kpi {
    text-align: center;
    padding: 1rem;
    border-radius: 10px;
    background: var(--mb-light);
}
.sub-kpi .valor { font-size: 1.4rem; font-weight: 800; color: var(--mb-primary); }
.sub-kpi .etiqueta { font-size: 0.75rem; font-weight: 600; color: #888; text-transform: uppercase; }

/* ── Botón aplicar filtros ── */
.btn-filtrar {
    background: var(--mb-primary);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    padding: 0.5rem 1.5rem;
    transition: all 0.2s;
}
.btn-filtrar:hover { background: var(--mb-secondary); color: white; }

.btn-limpiar-filtros {
    background: transparent;
    color: var(--mb-primary);
    border: 1.5px solid var(--mb-primary);
    border-radius: 8px;
    font-weight: 600;
    padding: 0.5rem 1rem;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}
.btn-limpiar-filtros:hover { background: var(--mb-primary); color: white; }

/* ── Período badge ── */
.periodo-badge {
    background: rgba(255,255,255,0.2);
    border-radius: 20px;
    padding: 0.3rem 0.9rem;
    font-size: 0.85rem;
    font-weight: 500;
}

/* ── Top ranking ── */
.ranking-item {
    display: flex;
    align-items: center;
    padding: 0.6rem 0;
    border-bottom: 1px solid #f0e8ef;
}
.ranking-item:last-child { border-bottom: none; }
.ranking-pos {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--mb-primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 700;
    flex-shrink: 0;
    margin-right: 0.75rem;
}
.ranking-item:first-child .ranking-pos { background: var(--mb-gold); }
</style>

{{-- ─── HEADER ─────────────────────────────────────────────────── --}}
<div class="reportes-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h1>
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="me-2" viewBox="0 0 16 16" style="opacity:.9;">
                    <path d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1z"/>
                </svg>
                Reportes del Negocio
            </h1>
            <p class="mb-0 mt-1" style="opacity:.85; font-size:.9rem;">Análisis completo del sistema MUJER BONITA</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <span class="periodo-badge">
                📅 {{ $fechaInicio->format('d/m/Y') }} — {{ $fechaFin->format('d/m/Y') }}
            </span>
            <a href="{{ route('reportes.pdf', 'general') }}?fecha_inicio={{ $fechaInicio->toDateString() }}&fecha_fin={{ $fechaFin->toDateString() }}"
               class="btn-export-pdf">
                📄 PDF General
            </a>
        </div>
    </div>
</div>

{{-- ─── FILTROS ────────────────────────────────────────────────── --}}
<div class="filtros-card">
    <form method="GET" action="{{ route('reportes.index') }}" id="form-filtros">
        <div class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control"
                       value="{{ $fechaInicio->toDateString() }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Fecha Fin</label>
                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control"
                       value="{{ $fechaFin->toDateString() }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Estilista</label>
                <select name="estilista_id" id="estilista_id" class="form-select">
                    <option value="">Todas las estilistas</option>
                    @foreach($listaEstilistas as $e)
                        <option value="{{ $e->id }}" {{ $estilistaId == $e->id ? 'selected' : '' }}>
                            {{ $e->nombre_completo }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Cliente</label>
                <select name="cliente_id" id="cliente_id" class="form-select">
                    <option value="">Todos los clientes</option>
                    @foreach($listaClientes as $c)
                        <option value="{{ $c->id }}" {{ $clienteId == $c->id ? 'selected' : '' }}>
                            {{ $c->nombre_completo }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn-filtrar flex-grow-1">
                    🔍 Filtrar
                </button>
                <a href="{{ route('reportes.index') }}" class="btn-limpiar-filtros" title="Limpiar filtros">✕</a>
            </div>
        </div>
    </form>
</div>

{{-- ─── KPIs GENERALES ─────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <div class="kpi-card">
            <div class="kpi-icon"><span style="font-size:1.4rem;">💰</span></div>
            <div class="kpi-value">₡{{ number_format($kpis['ingresosPeriodo'], 0, ',', '.') }}</div>
            <div class="kpi-label">Ingresos (período)</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="kpi-card kpi-info">
            <div class="kpi-icon"><span style="font-size:1.4rem;">✂️</span></div>
            <div class="kpi-value">{{ $kpis['serviciosRealizadosPeriodo'] }}</div>
            <div class="kpi-label">Servicios Realizados</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="kpi-card kpi-success">
            <div class="kpi-icon"><span style="font-size:1.4rem;">📅</span></div>
            <div class="kpi-value">{{ $kpis['citasPeriodo'] }}</div>
            <div class="kpi-label">Citas (período)</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="kpi-card kpi-warning">
            <div class="kpi-icon"><span style="font-size:1.4rem;">👥</span></div>
            <div class="kpi-value">{{ $kpis['clientesActivos'] }}</div>
            <div class="kpi-label">Clientes Activos</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="kpi-card kpi-gold">
            <div class="kpi-icon"><span style="font-size:1.4rem;">💅</span></div>
            <div class="kpi-value">{{ $kpis['estilistasTotales'] }}</div>
            <div class="kpi-label">Estilistas Activas</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="kpi-card kpi-danger">
            <div class="kpi-icon"><span style="font-size:1.4rem;">⚠️</span></div>
            <div class="kpi-value">{{ $kpis['productosBajoStock'] }}</div>
            <div class="kpi-label">Prod. Bajo Stock</div>
        </div>
    </div>
</div>

{{-- ─── TABS DE SECCIONES ──────────────────────────────────────── --}}
<ul class="nav reportes-nav border-bottom mb-4" id="reportesTab" role="tablist">
    @foreach([
        ['id' => 'ventas',      'icon' => '💰', 'label' => 'Ventas e Ingresos'],
        ['id' => 'clientes',    'icon' => '👥', 'label' => 'Clientes'],
        ['id' => 'inventario',  'icon' => '📦', 'label' => 'Inventario'],
        ['id' => 'servicios',   'icon' => '📅', 'label' => 'Servicios & Citas'],
        ['id' => 'pagos',       'icon' => '💳', 'label' => 'Pagos Online'],
        ['id' => 'promociones', 'icon' => '🎁', 'label' => 'Promociones & Comisiones'],
    ] as $tab)
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                id="tab-{{ $tab['id'] }}"
                data-bs-toggle="tab"
                data-bs-target="#pane-{{ $tab['id'] }}"
                type="button" role="tab">
            {{ $tab['icon'] }} {{ $tab['label'] }}
        </button>
    </li>
    @endforeach
</ul>

<div class="tab-content" id="reportesTabContent">

{{-- ══════════════════════════════════════════════════════════════
     TAB 1 — VENTAS E INGRESOS
══════════════════════════════════════════════════════════════ --}}
<div class="tab-pane fade show active" id="pane-ventas" role="tabpanel">
    <div class="row g-3 mb-3">
        {{-- KPIs de ventas --}}
        <div class="col-md-4">
            <div class="sub-kpi">
                <div class="valor">₡{{ number_format($ventas['totalIngresos'], 2, ',', '.') }}</div>
                <div class="etiqueta">Total Ingresos</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="sub-kpi">
                <div class="valor">₡{{ number_format($ventas['totalComisiones'], 2, ',', '.') }}</div>
                <div class="etiqueta">Total Comisiones</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="sub-kpi">
                <div class="valor">₡{{ number_format($ventas['ticketPromedio'], 2, ',', '.') }}</div>
                <div class="etiqueta">Ticket Promedio</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        {{-- Gráfica de ingresos por mes --}}
        <div class="col-md-8">
            <div class="seccion-card">
                <div class="seccion-header">
                    <h5>📈 Ingresos por Mes (últimos 12 meses)</h5>
                </div>
                <div class="p-3">
                    <div class="grafica-container">
                        <canvas id="graficaIngresos"></canvas>
                    </div>
                </div>
            </div>
        </div>
        {{-- Top estilistas --}}
        <div class="col-md-4">
            <div class="seccion-card h-100">
                <div class="seccion-header">
                    <h5>🏆 Top Estilistas</h5>
                </div>
                <div class="p-3">
                    @forelse($ventas['topEstilistas'] as $i => $est)
                    <div class="ranking-item">
                        <div class="ranking-pos">{{ $i + 1 }}</div>
                        <div class="flex-grow-1">
                            <div style="font-weight:600; font-size:.9rem;">{{ $est->estilista?->nombre_completo ?? 'N/A' }}</div>
                            <small style="color:#888;">{{ $est->cantidad }} servicios</small>
                        </div>
                        <div style="font-weight:700; color:var(--mb-primary); font-size:.9rem;">
                            ₡{{ number_format($est->total, 0, ',', '.') }}
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center py-3">Sin datos en este período</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de ventas --}}
    <div class="seccion-card">
        <div class="seccion-header">
            <h5>📋 Detalle de Servicios Realizados</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('reportes.pdf', 'ventas') }}?{{ http_build_query(request()->except('_token')) }}"
                   class="btn-export-pdf">📄 PDF</a>
                <a href="{{ route('reportes.excel', 'ventas') }}?{{ http_build_query(request()->except('_token')) }}"
                   class="btn-export-excel">📊 Excel</a>
            </div>
        </div>
        <div class="p-0">
            @if($ventas['registros']->count() > 0)
            <div class="table-responsive">
                <table class="table tabla-reportes mb-0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Estilista</th>
                            <th>Servicio</th>
                            <th class="text-end">Precio</th>
                            <th class="text-end">Comisión</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ventas['registros'] as $sr)
                        <tr>
                            <td>{{ optional($sr->fecha_realizacion)->format('d/m/Y H:i') }}</td>
                            <td>{{ $sr->cliente?->nombre_completo ?? 'N/A' }}</td>
                            <td>{{ $sr->estilista?->nombre_completo ?? 'N/A' }}</td>
                            <td>{{ $sr->servicio?->nombre ?? 'N/A' }}</td>
                            <td class="text-end fw-bold" style="color:var(--mb-primary);">
                                ₡{{ number_format($sr->precio_cobrado, 2, ',', '.') }}
                            </td>
                            <td class="text-end">
                                ₡{{ number_format($sr->comision_monto, 2, ',', '.') }}
                                <small class="text-muted">({{ $sr->comision_porcentaje }}%)</small>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:var(--mb-light); font-weight:700;">
                            <td colspan="4" class="text-end">TOTALES:</td>
                            <td class="text-end" style="color:var(--mb-primary);">
                                ₡{{ number_format($ventas['totalIngresos'], 2, ',', '.') }}
                            </td>
                            <td class="text-end">
                                ₡{{ number_format($ventas['totalComisiones'], 2, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <span style="font-size:3rem;">📊</span>
                <p class="text-muted mt-2">Sin registros de ventas en este período</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     TAB 2 — CLIENTES
══════════════════════════════════════════════════════════════ --}}
<div class="tab-pane fade" id="pane-clientes" role="tabpanel">
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="sub-kpi">
                <div class="valor">{{ $kpis['clientesTotales'] }}</div>
                <div class="etiqueta">Total Clientes</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="sub-kpi" style="background:#d4edda;">
                <div class="valor" style="color:#155724;">{{ $clientes['totalActivos'] }}</div>
                <div class="etiqueta">Activos</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="sub-kpi" style="background:#e2e3e5;">
                <div class="valor" style="color:#383d41;">{{ $clientes['totalInactivos'] }}</div>
                <div class="etiqueta">Inactivos</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="sub-kpi" style="background:#cce5ff;">
                <div class="valor" style="color:#004085;">{{ $clientes['clientesNuevos'] }}</div>
                <div class="etiqueta">Nuevos (período)</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-8">
            <div class="seccion-card">
                <div class="seccion-header">
                    <h5>👥 Lista de Clientes</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('reportes.pdf', 'clientes') }}?{{ http_build_query(request()->except('_token')) }}"
                           class="btn-export-pdf">📄 PDF</a>
                        <a href="{{ route('reportes.excel', 'clientes') }}?{{ http_build_query(request()->except('_token')) }}"
                           class="btn-export-excel">📊 Excel</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table tabla-reportes mb-0">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th class="text-center">Citas</th>
                                <th class="text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clientes['clientesList'] as $c)
                            <tr>
                                <td>
                                    <strong>{{ $c->nombre_completo }}</strong><br>
                                    <small class="text-muted">Reg. {{ $c->created_at->format('d/m/Y') }}</small>
                                </td>
                                <td>{{ $c->telefono ?? '—' }}</td>
                                <td>{{ $c->email ?? '—' }}</td>
                                <td class="text-center">
                                    <span class="fw-bold" style="color:var(--mb-primary);">{{ $c->total_citas }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge-estado badge-{{ $c->estado }}">{{ ucfirst($c->estado) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Sin clientes registrados</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $clientes['clientesList']->withQueryString()->links() }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="seccion-card h-100">
                <div class="seccion-header"><h5>🏆 Top Clientes (más citas)</h5></div>
                <div class="p-3">
                    @forelse($clientes['topClientes'] as $i => $tc)
                    <div class="ranking-item">
                        <div class="ranking-pos">{{ $i + 1 }}</div>
                        <div class="flex-grow-1">
                            <div style="font-weight:600; font-size:.9rem;">{{ $tc->nombre_completo }}</div>
                            <small style="color:#888;">{{ $tc->telefono ?? '—' }}</small>
                        </div>
                        <div style="font-weight:700; color:var(--mb-primary);">{{ $tc->citas_count }}</div>
                    </div>
                    @empty
                    <p class="text-muted text-center py-3">Sin datos</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     TAB 3 — INVENTARIO
══════════════════════════════════════════════════════════════ --}}
<div class="tab-pane fade" id="pane-inventario" role="tabpanel">
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="sub-kpi">
                <div class="valor">{{ $inventario['totalProductos'] }}</div>
                <div class="etiqueta">Total Productos</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="sub-kpi" style="background:#d4edda;">
                <div class="valor" style="color:#155724;">{{ $inventario['productosOk'] }}</div>
                <div class="etiqueta">Stock OK</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="sub-kpi" style="background:#fff3cd;">
                <div class="valor" style="color:#856404;">{{ $inventario['productosBajo'] }}</div>
                <div class="etiqueta">Stock Bajo</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="sub-kpi" style="background:#f8d7da;">
                <div class="valor" style="color:#721c24;">{{ $inventario['productosCritico'] }}</div>
                <div class="etiqueta">Sin Stock</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="sub-kpi">
                <div class="valor">₡{{ number_format($inventario['valorInventario'], 2, ',', '.') }}</div>
                <div class="etiqueta">Valor de Compra en Inventario</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="sub-kpi" style="background:#cce5ff;">
                <div class="valor" style="color:#004085;">₡{{ number_format($inventario['valorVentaTotal'], 2, ',', '.') }}</div>
                <div class="etiqueta">Valor de Venta en Inventario</div>
            </div>
        </div>
    </div>

    <div class="seccion-card">
        <div class="seccion-header">
            <h5>📦 Inventario Completo</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('reportes.pdf', 'inventario') }}" class="btn-export-pdf">📄 PDF</a>
                <a href="{{ route('reportes.excel', 'inventario') }}" class="btn-export-excel">📊 Excel</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table tabla-reportes mb-0">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th class="text-center">Stock Actual</th>
                        <th class="text-center">Mínimo</th>
                        <th class="text-end">P. Venta</th>
                        <th class="text-center">Alerta</th>
                        <th class="text-center">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventario['productos'] as $p)
                    <tr>
                        <td>
                            <strong>{{ $p->nombre }}</strong><br>
                            <small class="text-muted">{{ $p->marca ?? '' }} {{ $p->unidad_medida }}</small>
                        </td>
                        <td>{{ $p->categoria }}</td>
                        <td class="text-center fw-bold {{ $p->stock_actual == 0 ? 'text-danger' : ($p->stock_actual <= $p->stock_minimo ? 'text-warning' : 'text-success') }}">
                            {{ $p->stock_actual }}
                        </td>
                        <td class="text-center text-muted">{{ $p->stock_minimo }}</td>
                        <td class="text-end">₡{{ number_format($p->precio_venta, 2, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="badge-estado badge-{{ $p->nivel_alerta }}">{{ ucfirst($p->nivel_alerta) }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge-estado badge-{{ $p->estado }}">{{ ucfirst($p->estado) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Sin productos en inventario</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     TAB 4 — SERVICIOS & CITAS
══════════════════════════════════════════════════════════════ --}}
<div class="tab-pane fade" id="pane-servicios" role="tabpanel">
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="sub-kpi">
                <div class="valor">{{ $servicios['totalCitas'] }}</div>
                <div class="etiqueta">Total Citas</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="sub-kpi" style="background:#d4edda;">
                <div class="valor" style="color:#155724;">{{ $servicios['citasCompletadas'] }}</div>
                <div class="etiqueta">Completadas</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="sub-kpi" style="background:#fff3cd;">
                <div class="valor" style="color:#856404;">{{ $servicios['citasPendientes'] }}</div>
                <div class="etiqueta">Pendientes</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="sub-kpi" style="background:#f8d7da;">
                <div class="valor" style="color:#721c24;">{{ $servicios['citasCanceladas'] }}</div>
                <div class="etiqueta">Canceladas</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="seccion-card">
                <div class="seccion-header"><h5>🎯 Distribución de Citas</h5></div>
                <div class="p-3">
                    <div class="grafica-container" style="height:220px;">
                        <canvas id="graficaCitas"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="seccion-card">
                <div class="seccion-header"><h5>🌟 Top 5 Servicios</h5></div>
                <div class="p-3">
                    <div class="grafica-container" style="height:220px;">
                        <canvas id="graficaServicios"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="seccion-card">
        <div class="seccion-header">
            <h5>📅 Historial de Citas</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('reportes.pdf', 'servicios') }}?{{ http_build_query(request()->except('_token')) }}"
                   class="btn-export-pdf">📄 PDF</a>
                <a href="{{ route('reportes.excel', 'servicios') }}?{{ http_build_query(request()->except('_token')) }}"
                   class="btn-export-excel">📊 Excel</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table tabla-reportes mb-0">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Estilista</th>
                        <th>Servicio</th>
                        <th class="text-end">Precio</th>
                        <th class="text-center">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($servicios['citas'] as $cita)
                    <tr>
                        <td>
                            {{ $cita->fecha?->format('d/m/Y') }}<br>
                            <small class="text-muted">{{ $cita->hora_inicio }} - {{ $cita->hora_fin }}</small>
                        </td>
                        <td>{{ $cita->cliente?->nombre_completo ?? 'N/A' }}</td>
                        <td>{{ $cita->estilista?->nombre_completo ?? 'N/A' }}</td>
                        <td>{{ $cita->servicio?->nombre ?? 'N/A' }}</td>
                        <td class="text-end fw-bold" style="color:var(--mb-primary);">
                            ₡{{ number_format($cita->precio_total ?? 0, 2, ',', '.') }}
                        </td>
                        <td class="text-center">
                            <span class="badge-estado badge-{{ $cita->estado }}">{{ ucfirst($cita->estado) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Sin citas en este período</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $servicios['citas']->withQueryString()->links() }}</div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     TAB 4.5 — PAGOS ONLINE
══════════════════════════════════════════════════════════════ --}}
<div class="tab-pane fade" id="pane-pagos" role="tabpanel">
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="sub-kpi" style="background:#cce5ff;">
                <div class="valor" style="color:#004085;">₡{{ number_format($pagos['totalOnline'], 2, ',', '.') }}</div>
                <div class="etiqueta">Total Stripe</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="sub-kpi" style="background:#d4edda;">
                <div class="valor" style="color:#155724;">₡{{ number_format($pagos['totalEfectivo'], 2, ',', '.') }}</div>
                <div class="etiqueta">Total Efectivo</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="sub-kpi" style="background:#e2e3e5;">
                <div class="valor" style="color:#383d41;">₡{{ number_format($pagos['totalTarjeta'], 2, ',', '.') }}</div>
                <div class="etiqueta">Total Tarjeta Pres.</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="sub-kpi" style="background:#fff3cd;">
                <div class="valor" style="color:#856404;">₡{{ number_format($pagos['pendientes'], 2, ',', '.') }}</div>
                <div class="etiqueta">Pendientes de Cobro</div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-8">
            <div class="seccion-card">
                <div class="seccion-header">
                    <h5>💳 Registro de Pagos</h5>
                </div>
                <div class="table-responsive" style="max-height:400px; overflow-y:auto;">
                    <table class="table tabla-reportes mb-0">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Monto</th>
                                <th class="text-center">Método</th>
                                <th class="text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pagos['listaPagos'] as $pago)
                            <tr>
                                <td>{{ $pago->updated_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $pago->cita->cliente->nombre ?? 'N/A' }}</td>
                                <td class="fw-bold" style="color:var(--mb-primary);">₡{{ number_format($pago->monto, 2, ',', '.') }}</td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ strtoupper($pago->metodo) }}</span>
                                </td>
                                <td class="text-center">
                                    @if($pago->estado_pago === 'completado')
                                        <span class="badge-estado badge-completada">Completado</span>
                                    @else
                                        <span class="badge-estado badge-pendiente">Pendiente</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Sin pagos en este período</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="seccion-card h-100">
                <div class="seccion-header"><h5>📊 Por Método</h5></div>
                <div class="p-3">
                    <div class="grafica-container" style="height:220px;">
                        <canvas id="graficaMetodosPago"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     TAB 5 — PROMOCIONES & COMISIONES
══════════════════════════════════════════════════════════════ --}}
<div class="tab-pane fade" id="pane-promociones" role="tabpanel">
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="sub-kpi" style="background:#d4edda;">
                <div class="valor" style="color:#155724;">{{ $promociones['activas'] }}</div>
                <div class="etiqueta">Promociones Vigentes</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="sub-kpi" style="background:#f8d7da;">
                <div class="valor" style="color:#721c24;">{{ $promociones['vencidas'] }}</div>
                <div class="etiqueta">Vencidas</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="sub-kpi" style="background:#d4edda;">
                <div class="valor" style="color:#155724;">₡{{ number_format($promociones['totalComisionesPagadas'], 2, ',', '.') }}</div>
                <div class="etiqueta">Comisiones Pagadas</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="sub-kpi" style="background:#fff3cd;">
                <div class="valor" style="color:#856404;">₡{{ number_format($promociones['totalComisionesPendientes'], 2, ',', '.') }}</div>
                <div class="etiqueta">Comisiones Pendientes</div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="seccion-card">
                <div class="seccion-header">
                    <h5>🎁 Promociones</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('reportes.pdf', 'promociones') }}?{{ http_build_query(request()->except('_token')) }}"
                           class="btn-export-pdf">📄 PDF</a>
                        <a href="{{ route('reportes.excel', 'promociones') }}?{{ http_build_query(request()->except('_token')) }}"
                           class="btn-export-excel">📊 Excel</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table tabla-reportes mb-0">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th class="text-center">Desc. %</th>
                                <th>Vigencia</th>
                                <th class="text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($promociones['promociones'] as $p)
                            <tr>
                                <td>
                                    <strong>{{ $p->nombre }}</strong><br>
                                    <small class="text-muted">{{ $p->servicios->count() }} servicio(s)</small>
                                </td>
                                <td class="text-center fw-bold" style="color:var(--mb-primary);">
                                    {{ $p->porcentaje_descuento }}%
                                </td>
                                <td>
                                    <small>{{ $p->fecha_inicio?->format('d/m/Y') }}<br>{{ $p->fecha_fin?->format('d/m/Y') }}</small>
                                </td>
                                <td class="text-center">
                                    @if($p->is_vigente)
                                    <span class="badge-estado badge-activa">Vigente</span>
                                    @elseif($p->fecha_fin < now())
                                    <span class="badge-estado badge-vencida">Vencida</span>
                                    @else
                                    <span class="badge-estado badge-pendiente">Próxima</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">Sin promociones</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="seccion-card">
                <div class="seccion-header"><h5>💼 Comisiones</h5></div>
                <div class="table-responsive">
                    <table class="table tabla-reportes mb-0">
                        <thead>
                            <tr>
                                <th>Estilista</th>
                                <th>Período</th>
                                <th class="text-end">Comisión</th>
                                <th class="text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($promociones['comisiones'] as $com)
                            <tr>
                                <td><strong>{{ $com->estilista?->nombre_completo ?? 'N/A' }}</strong></td>
                                <td>
                                    <small>{{ $com->periodo_inicio?->format('d/m/Y') }}<br>{{ $com->periodo_fin?->format('d/m/Y') }}</small>
                                </td>
                                <td class="text-end fw-bold" style="color:var(--mb-primary);">
                                    ₡{{ number_format($com->total_comision, 2, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <span class="badge-estado badge-{{ $com->estado }}">{{ ucfirst($com->estado) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">Sin comisiones en este período</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</div>{{-- end tab-content --}}

{{-- ─── CHART.JS ────────────────────────────────────────────────── --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Paleta de colores MUJER BONITA
    const colores = ['#662a5b','#cc5cb8','#f4a946','#28a745','#17a2b8','#dc3545','#6c757d'];

    // ── Gráfica ingresos por mes ──────────────────────────────
    const ctxIngresos = document.getElementById('graficaIngresos');
    if (ctxIngresos) {
        const datosIngresos = @json($graficaIngresosMes);
        new Chart(ctxIngresos, {
            type: 'bar',
            data: {
                labels: datosIngresos.labels,
                datasets: [{
                    label: 'Ingresos (₡)',
                    data: datosIngresos.data,
                    backgroundColor: 'rgba(102,42,91,0.2)',
                    borderColor: '#662a5b',
                    borderWidth: 2,
                    borderRadius: 8,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => '₡' + v.toLocaleString() }
                    }
                }
            }
        });
    }

    // ── Gráfica distribución citas ────────────────────────────
    const ctxCitas = document.getElementById('graficaCitas');
    if (ctxCitas) {
        const datosCitas = @json($graficaCitasEstado);
        new Chart(ctxCitas, {
            type: 'doughnut',
            data: {
                labels: datosCitas.labels.map(l => l.charAt(0).toUpperCase() + l.slice(1)),
                datasets: [{
                    data: datosCitas.data,
                    backgroundColor: colores,
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { size: 12 } } }
                }
            }
        });
    }

    // ── Gráfica Métodos de Pago ───────────────────────────────
    const ctxMetodosPago = document.getElementById('graficaMetodosPago');
    if (ctxMetodosPago) {
        const datosMetodos = @json($pagos['porMetodo']);
        new Chart(ctxMetodosPago, {
            type: 'pie',
            data: {
                labels: Object.keys(datosMetodos).map(k => k.toUpperCase()),
                datasets: [{
                    data: Object.values(datosMetodos),
                    backgroundColor: ['#662a5b', '#198754', '#0d6efd', '#ffc107'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { size: 12 } } }
                }
            }
        });
    }

    // ── Gráfica top servicios ─────────────────────────────────
    const ctxServicios = document.getElementById('graficaServicios');
    if (ctxServicios) {
        const datosServicios = @json($graficaTopServicios);
        new Chart(ctxServicios, {
            type: 'bar',
            data: {
                labels: datosServicios.labels,
                datasets: [{
                    label: 'Ingresos (₡)',
                    data: datosServicios.data,
                    backgroundColor: colores,
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { callback: v => '₡' + v.toLocaleString() } }
                }
            }
        });
    }
});
</script>
@endsection
