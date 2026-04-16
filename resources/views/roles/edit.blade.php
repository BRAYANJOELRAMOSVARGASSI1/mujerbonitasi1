@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <div class="card-header" style="background-color:#CC5CB8; color:white; border-radius:12px 12px 0 0; border:none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 style="margin:0; font-weight:600;">{{ __('Editar Rol') }}</h5>
                            <small style="opacity:0.9;">Edita los permisos del rol</small>
                        </div>
                        <a href="{{ route('roles.index') }}" class="btn" 
                           style="background-color:rgba(255,255,255,0.2); color:white; border:none; border-radius:8px; padding:8px 16px; font-weight:500;">
                            <svg class="icon me-2">
                                <use xlink:href="{{ asset('icons/coreui.svg#cil-arrow-left') }}"></use>
                            </svg>
                            Volver a la gestión de roles
                        </a>
                    </div>
                </div>

                <div class="card-body" style="background-color:#f8f9fa; padding:2rem;">
                    <form method="POST" action="{{ route('roles.update', $role) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="role_name" class="form-label">Nombre del Rol</label>
                            <input type="text" class="form-control" id="role_name" name="name" value="{{ $role->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Permisos</label>
                            <div class="d-flex flex-wrap">
                                @foreach($permissions as $permission)
                                    <div class="form-check form-check-inline" style="margin-right: 15px;">
                                        <input class="form-check-input" type="checkbox" 
                                               name="permissions[]" 
                                               value="{{ $permission->id }}" 
                                               id="permission_{{ $permission->id }}"
                                               {{ in_array($permission->id, $rolePermissionIds->toArray()) ? 'checked' : '' }}
                                               style="border-color:#CC5CB8;">
                                        <label class="form-check-label" for="permission_{{ $permission->id }}" 
                                               style="color:#495057; font-size:0.9rem;">
                                            {{ ucfirst(str_replace('.', ' ', $permission->name)) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:10px 20px; font-weight:500;">
                            Actualizar Rol
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
