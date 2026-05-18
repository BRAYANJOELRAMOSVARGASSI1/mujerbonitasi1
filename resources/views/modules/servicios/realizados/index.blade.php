@extends('layouts.app')
@section('content')
<div class="container-fluid">
    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius:12px; border-left:4px solid #CC5CB8 !important;">
                <div class="card-body d-flex align-items-center"><div class="me-3" style="width:48px; height:48px; background:#CC5CB8; border-radius:12px; display:flex; align-items:center; justify-content:center;"><svg class="icon" style="color:white; width:24px; height:24px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-check-circle') }}"></use></svg></div><div><small class="text-muted">Total Registrados</small><h4 class="mb-0 fw-bold">{{ $stats['total_registros'] }}</h4></div></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius:12px; border-left:4px solid #28a745 !important;">
                <div class="card-body d-flex align-items-center"><div class="me-3" style="width:48px; height:48px; background:#28a745; border-radius:12px; display:flex; align-items:center; justify-content:center;"><svg class="icon" style="color:white; width:24px; height:24px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-dollar') }}"></use></svg></div><div><small class="text-muted">Total Ingresos</small><h4 class="mb-0 fw-bold">Bs. {{ number_format($stats['total_ingresos'], 2) }}</h4></div></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius:12px; border-left:4px solid #17a2b8 !important;">
                <div class="card-body d-flex align-items-center"><div class="me-3" style="width:48px; height:48px; background:#17a2b8; border-radius:12px; display:flex; align-items:center; justify-content:center;"><svg class="icon" style="color:white; width:24px; height:24px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-wallet') }}"></use></svg></div><div><small class="text-muted">Total Comisiones</small><h4 class="mb-0 fw-bold">Bs. {{ number_format($stats['total_comisiones'], 2) }}</h4></div></div>
            </div>
        </div>
    </div>

    <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:white; border-radius:12px 12px 0 0; border-bottom:1px solid #dee2e6;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 style="margin:0; font-weight:600;"><svg class="icon me-2" style="color:#CC5CB8;"><use xlink:href="{{ asset('icons/coreui.svg#cil-check-circle') }}"></use></svg>Servicios Realizados</h5>
                    <small style="opacity:0.7;">CU14 — Registrar Servicio Realizado</small>
                </div>
                @can('registrar servicio realizado')
                <a href="{{ route('servicios-realizados.create') }}" class="btn" style="background-color:#28a745; color:white; border:none; border-radius:8px; padding:8px 16px; font-weight:500;">
                    <svg class="icon me-2"><use xlink:href="{{ asset('icons/coreui.svg#cil-plus') }}"></use></svg>Registrar Servicio
                </a>
                @endcan
            </div>
        </div>
        <div class="card-body" style="background-color:#f8f9fa; padding:1.5rem;">
            @if(session('status'))<div class="alert alert-success" style="border:none; border-radius:8px; border-left:4px solid #28a745;">{{ session('status') }}</div>@endif

            <form method="GET" action="{{ route('servicios-realizados.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3"><input type="text" name="buscar" class="form-control" placeholder="Buscar cliente..." value="{{ request('buscar') }}" style="border-radius:8px; padding:0.75rem;"></div>
                    <div class="col-md-2"><input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}" style="border-radius:8px;" placeholder="Desde"></div>
                    <div class="col-md-2"><input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}" style="border-radius:8px;" placeholder="Hasta"></div>
                    <div class="col-md-3"><select name="estilista_id" class="form-select" style="border-radius:8px;"><option value="">Todas las estilistas</option>@foreach($estilistas as $est)<option value="{{ $est->id }}" {{ request('estilista_id') == $est->id ? 'selected' : '' }}>{{ $est->nombre_completo }}</option>@endforeach</select></div>
                    <div class="col-md-2 d-flex gap-2"><button type="submit" class="btn flex-fill" style="background:#CC5CB8; color:white; border:none; border-radius:8px;">Buscar</button><a href="{{ route('servicios-realizados.index') }}" class="btn" style="background:#6c757d; color:white; border:none; border-radius:8px;"><svg class="icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-reload') }}"></use></svg></a></div>
                </div>
            </form>

            @if($registros->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover" style="border-radius:8px; overflow:hidden;">
                    <thead style="background-color:#381432; color:white;"><tr><th style="padding:12px;">#</th><th style="padding:12px;">Cliente</th><th style="padding:12px;">Servicio</th><th style="padding:12px;">Estilista</th><th style="padding:12px;">Fecha</th><th style="padding:12px;">Precio</th><th style="padding:12px;">Comisión</th><th style="padding:12px;">Acciones</th></tr></thead>
                    <tbody>
                        @foreach($registros as $reg)
                        <tr style="background:white;">
                            <td style="padding:12px; vertical-align:middle;">{{ $reg->id }}</td>
                            <td style="padding:12px; vertical-align:middle; font-weight:500;">{{ $reg->cliente->nombre_completo }}</td>
                            <td style="padding:12px; vertical-align:middle;"><span class="badge" style="background:#CC5CB8;">{{ $reg->servicio->nombre }}</span></td>
                            <td style="padding:12px; vertical-align:middle;">{{ $reg->estilista->nombre_completo }}</td>
                            <td style="padding:12px; vertical-align:middle;">{{ $reg->fecha_realizacion->format('d/m/Y H:i') }}</td>
                            <td style="padding:12px; vertical-align:middle; color:#28a745; font-weight:600;">Bs. {{ number_format($reg->precio_cobrado, 2) }}</td>
                            <td style="padding:12px; vertical-align:middle; color:#17a2b8; font-weight:600;">Bs. {{ number_format($reg->comision_monto, 2) }}</td>
                            <td style="padding:12px; vertical-align:middle;"><a href="{{ route('servicios-realizados.show', $reg) }}" class="btn btn-sm" style="background:#17a2b8; color:white; border:none; border-radius:6px;"><svg class="icon" style="width:14px; height:14px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-eye') }}"></use></svg> Ver</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5"><h5 style="color:#495057;">No hay servicios realizados registrados</h5></div>
            @endif
        </div>
        @if($registros->count() > 0)<div class="card-footer" style="background:#f8f9fa; border:none; padding:1rem 2rem;">{{ $registros->links() }}</div>@endif
    </div>
</div>
@endsection
