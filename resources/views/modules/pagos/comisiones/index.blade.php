@extends('layouts.app')
@section('content')
<div class="container-fluid">
    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius:12px; border-left:4px solid #CC5CB8 !important;">
                <div class="card-body d-flex align-items-center"><div class="me-3" style="width:48px; height:48px; background:#CC5CB8; border-radius:12px; display:flex; align-items:center; justify-content:center;"><svg class="icon" style="color:white; width:24px; height:24px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-check-circle') }}"></use></svg></div><div><small class="text-muted">Servicios en Período</small><h4 class="mb-0 fw-bold">{{ $resumen['total_servicios'] }}</h4></div></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius:12px; border-left:4px solid #28a745 !important;">
                <div class="card-body d-flex align-items-center"><div class="me-3" style="width:48px; height:48px; background:#28a745; border-radius:12px; display:flex; align-items:center; justify-content:center;"><svg class="icon" style="color:white; width:24px; height:24px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-dollar') }}"></use></svg></div><div><small class="text-muted">Total Ingresos</small><h4 class="mb-0 fw-bold">Bs. {{ number_format($resumen['total_ingresos'], 2) }}</h4></div></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius:12px; border-left:4px solid #17a2b8 !important;">
                <div class="card-body d-flex align-items-center"><div class="me-3" style="width:48px; height:48px; background:#17a2b8; border-radius:12px; display:flex; align-items:center; justify-content:center;"><svg class="icon" style="color:white; width:24px; height:24px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-wallet') }}"></use></svg></div><div><small class="text-muted">Total Comisiones</small><h4 class="mb-0 fw-bold">Bs. {{ number_format($resumen['total_comisiones'], 2) }}</h4></div></div>
            </div>
        </div>
    </div>

    <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background:white; border-radius:12px 12px 0 0; border-bottom:1px solid #dee2e6;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 style="margin:0; font-weight:600;"><svg class="icon me-2" style="color:#CC5CB8;"><use xlink:href="{{ asset('icons/coreui.svg#cil-wallet') }}"></use></svg>Comisiones de Estilistas</h5>
                    <small style="opacity:0.7;">CU25 — Calcular Comisión Estilista</small>
                </div>
            </div>
        </div>
        <div class="card-body" style="background:#f8f9fa; padding:1.5rem;">
            @if(session('status'))<div class="alert alert-success" style="border:none; border-radius:8px; border-left:4px solid #28a745;">{{ session('status') }}</div>@endif
            @if(session('error'))<div class="alert alert-danger" style="border:none; border-radius:8px; border-left:4px solid #dc3545;">{{ session('error') }}</div>@endif

            {{-- Calcular Comisiones (Solo Admin) --}}
            @can('calcular comisiones')
            <div class="card mb-4" style="border:2px solid #CC5CB8; border-radius:10px;">
                <div class="card-header" style="background:#CC5CB8; color:white; font-weight:600;">Calcular Comisiones por Período</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('comisiones.calcular') }}" class="row g-3 align-items-end">
                        @csrf
                        <div class="col-md-4"><label class="form-label fw-bold">Período Inicio</label><input type="date" name="periodo_inicio" class="form-control" value="{{ $periodoInicio }}" required style="border-radius:8px; padding:0.75rem;"></div>
                        <div class="col-md-4"><label class="form-label fw-bold">Período Fin</label><input type="date" name="periodo_fin" class="form-control" value="{{ $periodoFin }}" required style="border-radius:8px; padding:0.75rem;"></div>
                        <div class="col-md-4"><button type="submit" class="btn w-100" style="background:#CC5CB8; color:white; border:none; border-radius:8px; padding:0.75rem; font-weight:600;">Calcular Comisiones</button></div>
                    </form>
                </div>
            </div>
            @endcan

            {{-- Filtros --}}
            <form method="GET" action="{{ route('comisiones.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3"><input type="date" name="periodo_inicio" class="form-control" value="{{ request('periodo_inicio', $periodoInicio) }}" style="border-radius:8px;"></div>
                    <div class="col-md-3"><input type="date" name="periodo_fin" class="form-control" value="{{ request('periodo_fin', $periodoFin) }}" style="border-radius:8px;"></div>
                    <div class="col-md-2"><select name="estado" class="form-select" style="border-radius:8px;"><option value="">Todos</option><option value="pendiente" {{ request('estado')=='pendiente'?'selected':'' }}>Pendientes</option><option value="aprobada" {{ request('estado')=='aprobada'?'selected':'' }}>Aprobadas</option><option value="pagada" {{ request('estado')=='pagada'?'selected':'' }}>Pagadas</option></select></div>
                    <div class="col-md-2"><select name="estilista_id" class="form-select" style="border-radius:8px;"><option value="">Todas</option>@foreach($estilistas as $est)<option value="{{ $est->id }}" {{ request('estilista_id')==$est->id?'selected':'' }}>{{ $est->nombre_completo }}</option>@endforeach</select></div>
                    <div class="col-md-2"><button type="submit" class="btn w-100" style="background:#CC5CB8; color:white; border:none; border-radius:8px;">Filtrar</button></div>
                </div>
            </form>

            @if($comisiones->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover" style="border-radius:8px; overflow:hidden;">
                    <thead style="background:#381432; color:white;"><tr><th style="padding:12px;">#</th><th style="padding:12px;">Estilista</th><th style="padding:12px;">Período</th><th style="padding:12px;">Servicios</th><th style="padding:12px;">Total Ingresos</th><th style="padding:12px;">Comisión</th><th style="padding:12px;">Estado</th><th style="padding:12px;">Acciones</th></tr></thead>
                    <tbody>
                        @foreach($comisiones as $com)
                        <tr style="background:white;">
                            <td style="padding:12px; vertical-align:middle;">{{ $com->id }}</td>
                            <td style="padding:12px; vertical-align:middle; font-weight:500;">{{ $com->estilista->nombre_completo }}</td>
                            <td style="padding:12px; vertical-align:middle;">{{ $com->periodo_inicio->format('d/m') }} — {{ $com->periodo_fin->format('d/m/Y') }}</td>
                            <td style="padding:12px; vertical-align:middle; text-align:center;"><span class="badge" style="background:#CC5CB8;">{{ $com->cantidad_servicios }}</span></td>
                            <td style="padding:12px; vertical-align:middle; color:#28a745; font-weight:600;">Bs. {{ number_format($com->total_servicios, 2) }}</td>
                            <td style="padding:12px; vertical-align:middle; color:#17a2b8; font-weight:700; font-size:1.05rem;">Bs. {{ number_format($com->total_comision, 2) }}</td>
                            <td style="padding:12px; vertical-align:middle;">
                                @php $sc = ['pendiente'=>['#fff3cd','#856404'],'aprobada'=>['#d4edda','#155724'],'pagada'=>['#cce5ff','#004085']][$com->estado] ?? ['#e2e3e5','#383d41']; @endphp
                                <span class="badge" style="background:{{ $sc[0] }}; color:{{ $sc[1] }}; padding:5px 10px; border-radius:12px;">{{ ucfirst($com->estado) }}</span>
                            </td>
                            <td style="padding:12px; vertical-align:middle;">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('comisiones.show', $com) }}" class="btn btn-sm" style="background:#17a2b8; color:white; border:none; border-radius:6px;">Ver</a>
                                    @if($com->estado == 'pendiente')@can('aprobar comisiones')
                                    <form method="POST" action="{{ route('comisiones.aprobar', $com) }}" onsubmit="return confirm('¿Aprobar esta comisión?')">@csrf<button class="btn btn-sm" style="background:#28a745; color:white; border:none; border-radius:6px;">Aprobar</button></form>
                                    @endcan @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5"><h5 style="color:#495057;">No hay comisiones calculadas</h5><p class="text-muted">Use el formulario de arriba para calcular las comisiones del período.</p></div>
            @endif
        </div>
        @if($comisiones->count() > 0)<div class="card-footer" style="background:#f8f9fa; border:none; padding:1rem 2rem;">{{ $comisiones->links() }}</div>@endif
    </div>
</div>
@endsection
