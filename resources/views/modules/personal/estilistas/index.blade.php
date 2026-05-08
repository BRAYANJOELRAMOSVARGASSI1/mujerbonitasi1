@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:white; border-radius:12px 12px 0 0; border-bottom:1px solid #dee2e6;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 style="margin:0; font-weight:600; color:#212529;">
                        <svg class="icon me-2" style="color:#CC5CB8;"><use xlink:href="{{ asset('icons/coreui.svg#cil-star') }}"></use></svg>
                        {{ __('Gestión de Estilistas') }}
                    </h5>
                    <small style="opacity:0.7;">p2-gestion de personal y clientes | CU5 — Registrar Estilista</small>
                </div>
                <a href="{{ route('estilistas.create') }}" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:8px 16px; font-weight:500;">
                    <svg class="icon me-2"><use xlink:href="{{ asset('icons/coreui.svg#cil-plus') }}"></use></svg>Nueva Estilista
                </a>
            </div>
        </div>
        <div class="card-body" style="background-color:#f8f9fa; padding:1.5rem;">
            @if (session('status'))
                <div class="alert alert-success" style="border:none; border-radius:8px; background-color:#d4edda; color:#155724; border-left:4px solid #28a745;">{{ session('status') }}</div>
            @endif
            <form method="GET" action="{{ route('estilistas.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-5">
                        <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre o teléfono..." value="{{ request('buscar') }}" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                    </div>
                    <div class="col-md-3">
                        <select name="especialidad" class="form-select" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                            <option value="">Todas las especialidades</option>
                            @foreach(\App\Modules\P2_GestionPersonalClientes\Models\Estilista::ESPECIALIDADES as $esp)
                                <option value="{{ $esp }}" {{ request('especialidad') == $esp ? 'selected' : '' }}>{{ $esp }}</option>
                            @endforeach
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
                        <a href="{{ route('estilistas.index') }}" class="btn" style="background-color:#6c757d; color:white; border:none; border-radius:8px;"><svg class="icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-reload') }}"></use></svg></a>
                    </div>
                </div>
            </form>
            @if($estilistas->count() > 0)
                <div class="row">
                    @foreach($estilistas as $est)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(204,92,184,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 10px rgba(0,0,0,0.08)'">
                                <div class="card-body" style="padding:1.5rem;">
                                    <div class="d-flex align-items-center mb-3">
                                        <div style="width:45px; height:45px; background:linear-gradient(135deg, #CC5CB8, #9b59b6); border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:1rem;">
                                            <svg class="icon" style="color:white; width:24px; height:24px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-star') }}"></use></svg>
                                        </div>
                                        <div>
                                            <h6 style="margin:0; color:#212529; font-weight:600;">{{ $est->nombre_completo }}
                                                @if($est->estado === 'inactivo')<span class="badge" style="background-color:#dc3545; font-size:0.65rem; margin-left:0.5rem;">INACTIVO</span>@endif
                                            </h6>
                                            <small style="color:#6c757d;">{{ $est->telefono }}</small>
                                        </div>
                                    </div>
                                    <div style="background-color:#f8f9fa; border-radius:8px; padding:0.75rem;">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small style="color:#495057;"><strong>Especialidad:</strong></small>
                                            <span class="badge" style="background-color:#CC5CB8;">{{ $est->especialidad }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <small style="color:#495057;"><strong>Comisión:</strong></small>
                                            <span style="color:#28a745; font-weight:600;">{{ $est->porcentaje_comision }}%</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <small style="color:#495057;"><strong>Contratación:</strong></small>
                                            <small style="color:#212529;">{{ $est->fecha_contratacion->format('d/m/Y') }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1rem 1.5rem;">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('estilistas.show', $est) }}" class="btn btn-sm" style="background-color:#17a2b8; color:white; border:none; border-radius:6px; padding:6px 12px;">
                                            <svg class="icon" style="width:14px; height:14px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-eye') }}"></use></svg> Ver
                                        </a>
                                        <a href="{{ route('estilistas.edit', $est) }}" class="btn btn-sm" style="background-color:#ffc107; color:#212529; border:none; border-radius:6px; padding:6px 12px;">
                                            <svg class="icon" style="width:14px; height:14px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-pencil') }}"></use></svg> Editar
                                        </a>
                                        <form method="POST" action="{{ route('estilistas.destroy', $est) }}" style="display:inline-block;" onsubmit="return confirm('¿Eliminar esta estilista?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm" style="background-color:#dc3545; color:white; border:none; border-radius:6px; padding:6px 12px;">
                                                <svg class="icon" style="width:14px; height:14px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-trash') }}"></use></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <div style="width:80px; height:80px; background-color:#e9ecef; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem;">
                        <svg class="icon" style="color:#6c757d; width:40px; height:40px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-star') }}"></use></svg>
                    </div>
                    <h5 style="color:#495057;">No hay estilistas registradas</h5>
                    <a href="{{ route('estilistas.create') }}" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:10px 20px;">
                        <svg class="icon me-2"><use xlink:href="{{ asset('icons/coreui.svg#cil-plus') }}"></use></svg>Registrar primera estilista
                    </a>
                </div>
            @endif
        </div>
        @if($estilistas->count() > 0)
            <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1.5rem 2rem;">{{ $estilistas->links() }}</div>
        @endif
    </div>
</div>
@endsection
