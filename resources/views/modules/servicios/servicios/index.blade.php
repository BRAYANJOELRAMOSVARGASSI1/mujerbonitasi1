@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:white; border-radius:12px 12px 0 0; border-bottom:1px solid #dee2e6;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 style="margin:0; font-weight:600; color:#212529;">
                        <svg class="icon me-2" style="color:#CC5CB8;"><use xlink:href="{{ asset('icons/coreui.svg#cil-cut') }}"></use></svg>Catálogo de Servicios
                    </h5>
                    <small style="opacity:0.7;">p4-gestion de servicios y citas | CU11 — Gestionar Servicios</small>
                </div>
                @can('crear servicios')
                <a href="{{ route('servicios.create') }}" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:8px 16px; font-weight:500;">
                    <svg class="icon me-2"><use xlink:href="{{ asset('icons/coreui.svg#cil-plus') }}"></use></svg>Nuevo Servicio
                </a>
                @endcan
            </div>
        </div>
        <div class="card-body" style="background-color:#f8f9fa; padding:1.5rem;">
            @if (session('status'))
                <div class="alert alert-success" style="border:none; border-radius:8px; background-color:#d4edda; color:#155724; border-left:4px solid #28a745;">{{ session('status') }}</div>
            @endif
            <form method="GET" action="{{ route('servicios.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-5"><input type="text" name="buscar" class="form-control" placeholder="Buscar servicio..." value="{{ request('buscar') }}" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;"></div>
                    <div class="col-md-3">
                        <select name="categoria" class="form-select" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $cat)<option value="{{ $cat }}" {{ request('categoria') == $cat ? 'selected' : '' }}>{{ $cat }}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="estado" class="form-select" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                            <option value="">Todos</option>
                            <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activos</option>
                            <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn flex-fill" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px;">Buscar</button>
                        <a href="{{ route('servicios.index') }}" class="btn" style="background-color:#6c757d; color:white; border:none; border-radius:8px;"><svg class="icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-reload') }}"></use></svg></a>
                    </div>
                </div>
            </form>
            @if($servicios->count() > 0)
                <div class="row">
                    @foreach($servicios as $srv)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: all 0.3s ease;"
                                 onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(204,92,184,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 10px rgba(0,0,0,0.08)'">
                                <div class="card-body" style="padding:1.5rem;">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 style="margin:0; color:#212529; font-weight:600;">{{ $srv->nombre }}
                                            @if($srv->estado === 'inactivo')<span class="badge" style="background-color:#dc3545; font-size:0.6rem; margin-left:0.3rem;">INACTIVO</span>@endif
                                        </h6>
                                    </div>
                                    <span class="badge mb-2" style="background-color:#CC5CB8; font-size:0.7rem;">{{ $srv->categoria }}</span>
                                    @if($srv->descripcion)<p style="color:#6c757d; font-size:0.85rem; margin-bottom:0.75rem;">{{ Str::limit($srv->descripcion, 80) }}</p>@endif
                                    <div style="background-color:#f8f9fa; border-radius:8px; padding:0.75rem;">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small><strong>Precio:</strong></small>
                                            <span style="color:#28a745; font-weight:700; font-size:1.1rem;">Bs. {{ number_format($srv->precio, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <small><strong>Duración:</strong></small>
                                            <span style="color:#495057;">{{ $srv->duracion_formateada }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:0.75rem 1.5rem;">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('servicios.show', $srv) }}" class="btn btn-sm" style="background-color:#17a2b8; color:white; border:none; border-radius:6px; padding:4px 10px;"><svg class="icon" style="width:14px; height:14px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-eye') }}"></use></svg> Ver</a>
                                        
                                        @can('editar servicios')
                                        <a href="{{ route('servicios.edit', $srv) }}" class="btn btn-sm" style="background-color:#ffc107; color:#212529; border:none; border-radius:6px; padding:4px 10px;"><svg class="icon" style="width:14px; height:14px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-pencil') }}"></use></svg> Editar</a>
                                        @endcan

                                        @can('eliminar servicios')
                                        <form method="POST" action="{{ route('servicios.destroy', $srv) }}" onsubmit="return confirm('¿Eliminar?')">@csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm" style="background-color:#dc3545; color:white; border:none; border-radius:6px; padding:4px 10px;"><svg class="icon" style="width:14px; height:14px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-trash') }}"></use></svg></button>
                                        </form>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5"><h5 style="color:#495057;">No hay servicios registrados</h5><a href="{{ route('servicios.create') }}" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px;">Registrar primer servicio</a></div>
            @endif
        </div>
        @if($servicios->count() > 0)<div class="card-footer" style="background-color:#f8f9fa; border:none; padding:1.5rem 2rem;">{{ $servicios->links() }}</div>@endif
    </div>
</div>
@endsection
