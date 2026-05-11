@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:white; border-radius:12px 12px 0 0; border-bottom:1px solid #dee2e6;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 style="margin:0; font-weight:600; color:#212529;">
                        <svg class="icon me-2" style="color:#CC5CB8;"><use xlink:href="{{ asset('icons/coreui.svg#cil-user') }}"></use></svg>
                        {{ __('Gestión de Recepcionistas') }}
                    </h5>
                    <small style="opacity:0.7;">p2-gestion de personal y clientes | Tabla Independiente</small>
                </div>
                <a href="{{ route('recepcionistas.create') }}" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:8px 16px; font-weight:500;">
                    <svg class="icon me-2"><use xlink:href="{{ asset('icons/coreui.svg#cil-plus') }}"></use></svg>Nueva Recepcionista
                </a>
            </div>
        </div>
        <div class="card-body" style="background-color:#f8f9fa; padding:1.5rem;">
            @if (session('status'))
                <div class="alert alert-success" style="border:none; border-radius:8px; background-color:#d4edda; color:#155724; border-left:4px solid #28a745;">{{ session('status') }}</div>
            @endif
            <form method="GET" action="{{ route('recepcionistas.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre o teléfono..." value="{{ request('buscar') }}" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                    </div>
                    <div class="col-md-3">
                        <select name="estado" class="form-select" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                            <option value="">Todos los estados</option>
                            <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activos</option>
                            <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn flex-fill" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px;">Buscar</button>
                        <a href="{{ route('recepcionistas.index') }}" class="btn" style="background-color:#6c757d; color:white; border:none; border-radius:8px;"><svg class="icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-reload') }}"></use></svg></a>
                    </div>
                </div>
            </form>
            @if($recepcionistas->count() > 0)
                <div class="row">
                    @foreach($recepcionistas as $rec)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(204,92,184,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 10px rgba(0,0,0,0.08)'">
                                <div class="card-body" style="padding:1.5rem;">
                                    <div class="d-flex align-items-center mb-3">
                                        <div style="width:45px; height:45px; background:linear-gradient(135deg, #CC5CB8, #9b59b6); border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:1rem;">
                                            <svg class="icon" style="color:white; width:24px; height:24px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-user') }}"></use></svg>
                                        </div>
                                        <div>
                                            <h6 style="margin:0; color:#212529; font-weight:600;">{{ $rec->nombre_completo }}
                                                @if($rec->estado === 'inactivo')<span class="badge bg-danger" style="font-size:0.65rem; margin-left:0.5rem;">INACTIVO</span>@endif
                                            </h6>
                                            <small style="color:#6c757d;">{{ $rec->telefono }}</small>
                                        </div>
                                    </div>
                                    <div style="background-color:#f8f9fa; border-radius:8px; padding:0.75rem;">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small style="color:#495057;"><strong>Email:</strong></small>
                                            <small style="color:#212529;">{{ $rec->email ?? 'N/A' }}</small>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <small style="color:#495057;"><strong>Contratación:</strong></small>
                                            <small style="color:#212529;">{{ $rec->fecha_contratacion->format('d/m/Y') }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1rem 1.5rem;">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('recepcionistas.edit', $rec) }}" class="btn btn-sm" style="background-color:#ffc107; color:#212529; border:none; border-radius:6px; padding:6px 12px;">
                                            <svg class="icon" style="width:14px; height:14px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-pencil') }}"></use></svg> Editar
                                        </a>
                                        <form method="POST" action="{{ route('recepcionistas.destroy', $rec) }}" style="display:inline-block;" onsubmit="return confirm('¿Eliminar esta recepcionista?')">
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
                        <svg class="icon" style="color:#6c757d; width:40px; height:40px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-user') }}"></use></svg>
                    </div>
                    <h5 style="color:#495057;">No hay recepcionistas registradas</h5>
                    <a href="{{ route('recepcionistas.create') }}" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:10px 20px;">
                        <svg class="icon me-2"><use xlink:href="{{ asset('icons/coreui.svg#cil-plus') }}"></use></svg>Registrar primera recepcionista
                    </a>
                </div>
            @endif
        </div>
        @if($recepcionistas->count() > 0)
            <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1.5rem 2rem;">{{ $recepcionistas->links() }}</div>
        @endif
    </div>
</div>
@endsection
