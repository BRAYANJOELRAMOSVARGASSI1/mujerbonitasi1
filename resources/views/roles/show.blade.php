@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <div class="card-header" style="background-color:#CC5CB8; color:white; border-radius:12px 12px 0 0; border:none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 style="margin:0; font-weight:600;">{{ __('Detalles del Rol') }}: {{ ucfirst($role->name) }}</h5>
                            <small style="opacity:0.9;">Información completa del rol y usuarios asignados</small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('roles.edit', $role) }}" class="btn" 
                               style="background-color:rgba(255,255,255,0.2); color:white; border:none; border-radius:8px; padding:6px 12px;">
                                <svg class="icon me-1">
                                    <use xlink:href="{{ asset('icons/coreui.svg#cil-pencil') }}"></use>
                                </svg>
                                Editar
                            </a>
                            <a href="{{ route('roles.index') }}" class="btn" 
                               style="background-color:rgba(255,255,255,0.2); color:white; border:none; border-radius:8px; padding:6px 12px;">
                                <svg class="icon me-1">
                                    <use xlink:href="{{ asset('icons/coreui.svg#cil-arrow-left') }}"></use>
                                </svg>
                                Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body" style="background-color:#f8f9fa; padding:2rem;">
                    <div class="row">
                        {{-- Información General --}}
                        <div class="col-md-6 mb-4">
                            <div class="card" style="border:none; border-radius:12px; background-color:white; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                                <div class="card-body" style="padding:1.5rem;">
                                    <div class="d-flex align-items-center mb-3">
                                        <div style="width:60px; height:60px; background:linear-gradient(135deg, #CC5CB8, #381432); border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:1rem;">
                                            <svg class="icon" style="color:white; width:30px; height:30px;">
                                                <use xlink:href="{{ asset('icons/coreui.svg#cil-badge') }}"></use>
                                            </svg>
                                        </div>
                                        <div>
                                            <h6 style="margin:0; color:#212529; font-weight:600; font-size:1.1rem;">{{ ucfirst($role->name) }}</h6>
                                            <small style="color:#6c757d;">Rol del Sistema</small>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-6">
                                            <div style="text-align:center; padding:1rem; background-color:#f8f9fa; border-radius:8px;">
                                                <div style="font-size:2rem; font-weight:700; color:#CC5CB8;">{{ $role->permissions_count }}</div>
                                                <small style="color:#495057;">Permisos</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div style="text-align:center; padding:1rem; background-color:#f8f9fa; border-radius:8px;">
                                                <div style="font-size:2rem; font-weight:700; color:#17a2b8;">{{ $role->users_count }}</div>
                                                <small style="color:#495057;">Usuarios</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Permisos Asignados --}}
                        <div class="col-md-6 mb-4">
                            <div class="card" style="border:none; border-radius:12px; background-color:white; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                                <div class="card-header" style="background-color:#381432; color:white; border-radius:12px 12px 0 0; border:none;">
                                    <h6 style="margin:0; font-weight:600;">Permisos Asignados</h6>
                                </div>
                                <div class="card-body" style="padding:1.5rem; max-height:250px; overflow-y:auto;">
                                    @if($role->permissions->count() > 0)
                                        <div class="row">
                                            @php $currentGroup = ''; @endphp
                                            @foreach($role->permissions as $permission)
                                                @php 
                                                    $group = explode('.', $permission->name)[0] ?? 'General';
                                                    if($group !== $currentGroup) {
                                                        $currentGroup = $group;
                                                        echo '<div class="col-12"><hr style="border-color:#fdeaea; margin:0.5rem 0;"><h6 style="color:#CC5CB8; font-weight:600; margin:0 0 0.5rem 0; font-size:0.85rem;">' . ucfirst($group) . '</h6></div>';
                                                    }
                                                @endphp
                                                
                                                <div class="col-12 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <div style="width:8px; height:8px; background-color:#28a745; border-radius:50%; margin-right:0.5rem;"></div>
                                                        <small style="color:#495057;">{{ ucfirst(str_replace('.', ' ', $permission->name)) }}</small>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-3">
                                            <svg class="icon mb-2" style="color:#6c757d; width:30px; height:30px;">
                                                <use xlink:href="{{ asset('icons/coreui.svg#cil-warning') }}"></use>
                                            </svg>
                                            <p style="color:#6c757d; margin:0;">Este rol no tiene permisos asignados</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Usuarios con este Rol --}}
                    @if($role->users->count() > 0)
                        <div class="card mb-4" style="border:none; border-radius:12px; background-color:white; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                            <div class="card-header" style="background-color:#17a2b8; color:white; border-radius:12px 12px 0 0; border:none;">
                                <h6 style="margin:0; font-weight:600;">Usuarios con este Rol ({{ $role->users->count() }})</h6>
                            </div>
                            <div class="card-body" style="padding:1.5rem;">
                                <div class="row">
                                    @foreach($role->users as $user)
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <div style="background-color:#f8f9fa; border-radius:8px; padding:1rem; border-left:4px solid #17a2b8;">
                                                <div class="d-flex align-items-center">
                                                    <div style="width:40px; height:40px; background-color:#17a2b8; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:0.75rem;">
                                                        <svg class="icon" style="color:white; width:20px; height:20px;">
                                                            <use xlink:href="{{ asset('icons/coreui.svg#cil-user') }}"></use>
                                                        </svg>
                                                    </div>
                                                    <div style="flex:1;">
                                                        <div style="font-weight:600; color:#212529;">{{ $user->name }}</div>
                                                        <small style="color:#6c757d;">{{ $user->email }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1.5rem 2rem;">
                    <div class="d-flex justify-content-between align-items-center">
                        <small style="color:#6c757d;">Creado el {{ $role->created_at->format('d/m/Y H:i') }}</small>
                        
                        <div class="d-flex gap-2">
                            <a href="{{ route('roles.edit', $role) }}" class="btn" 
                               style="background-color:#ffc107; color:#212529; border:none; border-radius:8px; padding:0.5rem 1rem; font-weight:500;">
                                <svg class="icon me-1">
                                    <use xlink:href="{{ asset('icons/coreui.svg#cil-pencil') }}"></use>
                                </svg>
                                Editar Rol
                            </a>
                            
                            <form method="POST" action="{{ route('roles.destroy', $role) }}" 
                                  style="display:inline-block;" 
                                  onsubmit="return confirm('¿Estás seguro de que quieres eliminar este rol?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn" 
                                        style="background-color:#dc3545; color:white; border:none; border-radius:8px; padding:0.5rem 1rem; font-weight:500;">
                                    <svg class="icon me-1">
                                        <use xlink:href="{{ asset('icons/coreui.svg#cil-trash') }}"></use>
                                    </plus>
                                    Eliminar Rol
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection