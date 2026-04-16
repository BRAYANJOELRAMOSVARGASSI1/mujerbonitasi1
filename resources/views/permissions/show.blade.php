@extends('layouts.app')

@section('content')
    <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <div class="card-header" style="background-color:#CC5CB8; color:white; border-radius:12px 12px 0 0; border:none;">
            <div class="d-flex align-items-center">
                <div style="width:50px; height:50px; background-color:#6c757d; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:1rem;">
                    <svg class="icon" style="color:white; width:24px; height:24px;">
                        <use xlink:href="{{ asset('icons/coreui.svg#cil-vpn') }}"></use>
                    </svg>
                </div>
                <h5 style="margin:0; font-weight:600;">{{ $permission->name }}</h5>
            </div>
        </div>
        
        <div class="card-body" style="background-color:#f8f9fa; padding:2rem;">
            <div class="row">
                <div class="col-md-6">
                    <div style="background-color:white; padding:1.5rem; border-radius:12px; margin-bottom:1rem;">
                        <h6 style="color:#6c757d; font-weight:600; margin-bottom:1rem; border-bottom:2px solid #6c757d; padding-bottom:0.5rem;">Información del Permiso</h6>
                        
                        <div class="mb-3">
                            <label style="color:#495057; font-weight:500;">Nombre:</label>
                            <p style="margin:0.25rem 0; color:#212529; font-size:1.1rem;">{{ $permission->name }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label style="color:#495057; font-weight:500;">Fecha de Creación:</label>
                            <p style="margin:0.25rem 0; color:#212529; font-size:1.1rem;">{{ $permission->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label style="color:#495057; font-weight:500;">Roles Asignados:</label>
                            <p style="margin:0.25rem 0; color:#212529; font-size:1.1rem;">{{ $permission->roles->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div style="background-color:white; padding:1.5rem; border-radius:12px; margin-bottom:1rem;">
                        <h6 style="color:#6c757d; font-weight:600; margin-bottom:1rem; border-bottom:2px solid #6c757d; padding-bottom:0.5rem;">Roles con este Permiso</h6>
                        
                        @if($permission->roles->count() > 0)
                            @foreach($permission->roles as $role)
                                <div class="d-flex align-items-center mb-2">
                                    <div style="width:35px; height:35px; background-color:#CC5CB8; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:0.75rem;">
                                        <svg class="icon" style="color:white; width:16px; height:16px;">
                                            <use xlink:href="{{ asset('icons/coreui.svg#cil-group') }}"></use>
                                        </svg>
                                    </div>
                                    <span style="color:#212529; font-weight:500;">{{ $role->name }}</span>
                                </div>
                            @endforeach
                        @else
                            <p style="color:#6c757d; margin:0; font-style:italic;">No hay roles asignados</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1.5rem 2rem;">
            <div class="d-flex justify-content-between">
                <a href="{{ route('permissions.index') }}" class="btn" 
                   style="background-color:#6c757d; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem; font-weight:500;">
                    <svg class="icon me-2" style="width:16px; height:16px;">
                        <use xlink:href="{{ asset('icons/coreui.svg#cil-arrow-left') }}"></use>
                    </svg>
                    Volver
                </a>
                
                <div>
                    <a href="{{ route('permissions.edit', $permission) }}" class="btn me-2" 
                       style="background-color:#ffc107; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem; font-weight:500;">
                        <svg class="icon me-2" style="width:16px; height:16px;">
                            <use xlink:href="{{ asset('icons/coreui.svg#cil-pencil') }}"></use>
                        </svg>
                        Editar
                    </a>
                    
                    <form method="POST" action="{{ route('permissions.destroy', $permission) }}" style="display:inline;" 
                          onsubmit="return confirm('¿Estás seguro de que quieres eliminar este permiso?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn" 
                                style="background-color:#dc3545; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem; font-weight:500;">
                            <svg class="icon me-2" style="width:16px; height:16px;">
                                <use xlink:href="{{ asset('icons/coreui.svg#cil-trash') }}"></use>
                            </svg>
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
