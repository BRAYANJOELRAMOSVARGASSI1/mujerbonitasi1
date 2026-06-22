@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:white; border-radius:12px 12px 0 0; border-bottom:1px solid #dee2e6;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 style="margin:0; font-weight:600;"><svg class="icon me-2" style="color:#CC5CB8;"><use xlink:href="{{ asset('icons/coreui.svg#cil-gift') }}"></use></svg>Gestión de Promociones</h5>
                    <small style="opacity:0.7;">CU24 — Gestionar Promociones</small>
                </div>
                @can('crear promociones')
                <a href="{{ route('promociones.create') }}" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:8px 16px; font-weight:500;">
                    <svg class="icon me-2"><use xlink:href="{{ asset('icons/coreui.svg#cil-plus') }}"></use></svg>Nueva Promoción
                </a>
                @endcan
            </div>
        </div>
        <div class="card-body" style="background-color:#f8f9fa; padding:1.5rem;">
            @if(session('status'))<div class="alert alert-success" style="border:none; border-radius:8px; border-left:4px solid #28a745;">{{ session('status') }}</div>@endif

            <form method="GET" action="{{ route('promociones.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-5"><input type="text" name="buscar" class="form-control" placeholder="Buscar promoción..." value="{{ request('buscar') }}" style="border-radius:8px; padding:0.75rem;"></div>
                    <div class="col-md-3"><select name="estado" class="form-select" style="border-radius:8px;"><option value="">Todos los estados</option><option value="activa" {{ request('estado')=='activa'?'selected':'' }}>Activas</option><option value="inactiva" {{ request('estado')=='inactiva'?'selected':'' }}>Inactivas</option><option value="expirada" {{ request('estado')=='expirada'?'selected':'' }}>Expiradas</option></select></div>
                    <div class="col-md-4 d-flex gap-2"><button type="submit" class="btn flex-fill" style="background:#CC5CB8; color:white; border:none; border-radius:8px;">Buscar</button><a href="{{ route('promociones.index') }}" class="btn" style="background:#6c757d; color:white; border:none; border-radius:8px;"><svg class="icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-reload') }}"></use></svg></a></div>
                </div>
            </form>

            @if($promociones->count() > 0)
            <div class="row">
                @foreach($promociones as $promo)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(204,92,184,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 10px rgba(0,0,0,0.08)'">
                        <div class="card-body" style="padding:1.5rem;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 style="margin:0; font-weight:600;">{{ $promo->nombre }}</h6>
                                @php
                                    $estadoColor = ['activa'=>'#28a745','inactiva'=>'#6c757d','expirada'=>'#dc3545'][$promo->estado] ?? '#6c757d';
                                @endphp
                                <span class="badge" style="background:{{ $estadoColor }}; font-size:0.7rem;">{{ ucfirst($promo->estado) }}</span>
                            </div>
                            @if($promo->descripcion)<p style="color:#6c757d; font-size:0.85rem; margin-bottom:0.75rem;">{{ Str::limit($promo->descripcion, 80) }}</p>@endif
                            <div class="text-center mb-3">
                                <span style="font-size:2rem; font-weight:700; color:#CC5CB8;">{{ number_format($promo->porcentaje_descuento, 0) }}%</span>
                                <br><small class="text-muted">de descuento</small>
                            </div>
                            <div style="background:#f8f9fa; border-radius:8px; padding:0.75rem; font-size:0.85rem;">
                                <div class="d-flex justify-content-between mb-1"><small><strong>Inicio:</strong></small><span>{{ $promo->fecha_inicio->format('d/m/Y') }}</span></div>
                                <div class="d-flex justify-content-between mb-1"><small><strong>Fin:</strong></small><span>{{ $promo->fecha_fin->format('d/m/Y') }}</span></div>
                                <div class="d-flex justify-content-between"><small><strong>Servicios:</strong></small><span class="badge" style="background:#CC5CB8;">{{ $promo->servicios->count() }}</span></div>
                            </div>
                            @if($promo->is_vigente)<div class="mt-2 text-center"><span class="badge" style="background:#d4edda; color:#155724; padding:6px 12px; border-radius:12px;">✅ Vigente</span></div>@endif
                        </div>
                        <div class="card-footer" style="background:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:0.75rem 1.5rem;">
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('promociones.show', $promo) }}" class="btn btn-sm" style="background:#17a2b8; color:white; border:none; border-radius:6px;">Ver</a>
                                @can('editar promociones')<a href="{{ route('promociones.edit', $promo) }}" class="btn btn-sm" style="background:#ffc107; color:#212529; border:none; border-radius:6px;">Editar</a>@endcan
                                @can('eliminar promociones')<form method="POST" action="{{ route('promociones.destroy', $promo) }}" onsubmit="return confirm('¿Eliminar esta promoción?')">@csrf @method('DELETE')<button class="btn btn-sm" style="background:#dc3545; color:white; border:none; border-radius:6px;">Eliminar</button></form>@endcan
                                
                                @can('crear promociones')
                                @if($promo->is_vigente)
                                    <form method="POST" action="{{ route('promociones.enviar', $promo) }}" onsubmit="return confirm('¿Enviar esta promoción masivamente por correo a todos los clientes?')">
                                        @csrf
                                        <button class="btn btn-sm" style="background:#28a745; color:white; border:none; border-radius:6px;">
                                            <svg class="icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-envelope-closed') }}"></use></svg> Enviar Email
                                        </button>
                                    </form>
                                @endif
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-5"><h5 style="color:#495057;">No hay promociones registradas</h5>@can('crear promociones')<a href="{{ route('promociones.create') }}" class="btn" style="background:#CC5CB8; color:white; border:none; border-radius:8px;">Crear primera promoción</a>@endcan</div>
            @endif
        </div>
        @if($promociones->count() > 0)<div class="card-footer" style="background:#f8f9fa; border:none; padding:1rem 2rem;">{{ $promociones->links() }}</div>@endif
    </div>
</div>
@endsection
