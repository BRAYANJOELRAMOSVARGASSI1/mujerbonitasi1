@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <div class="card-header" style="background-color:#CC5CB8; color:white; border-radius:12px 12px 0 0; border:none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 style="margin:0; font-weight:600;">{{ __('Crear Nuevo Rol') }}</h5>
                            <small style="opacity:0.9;">Defina un nuevo rol y sus permisos</small>
                        </div>
                        <a href="{{ route('roles.index') }}" class="btn" 
                           style="background-color:rgba(255,255,255,0.2); color:white; border:none; border-radius:8px; padding:6px 12px;">
                            <svg class="icon me-1">
                                <use xlink:href="{{ asset('icons/coreui.svg#cil-arrow-left') }}"></use>
                            </svg>
                            Volver
                        </a>
                    </div>
                </div>

                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    <div class="card-body" style="background-color:#f8f9fa; padding:2rem;">
                        
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert" style="border:none; border-radius:8px; background-color:#f8d7da; color:#721c24; border-left:4px solid #dc3545;">
                                <strong>Por favor corrija los errores:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Nombre del Rol --}}
                        <div class="mb-4">
                            <label for="name" class="form-label" style="color:#212529; font-weight:600;">Nombre del Rol</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background-color:#CC5CB8; color:white; border:1px solid #CC5CB8;">
                                    <svg class="icon">
                                        <use xlink:href="{{ asset('icons/coreui.svg#cil-badge') }}"></use>
                                    </svg>
                                </span>
                                <input class="form-control @error('name') is-invalid @enderror" 
                                       type="text" name="name" id="name" 
                                       placeholder="Ej: Administrador, Editor, Usuario" 
                                       value="{{ old('name') }}" required
                                       style="border:1px solid #dee2e6; border-radius:0 8px 8px 0; padding:0.75rem;">
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $error }}</div>
                            @enderror
                            <small class="text-muted">El nombre del rol debe ser único y descriptivo.</small>
                        </div>

                        {{-- Permisos --}}
                        <div class="mb-4">
                            <label class="form-label" style="color:#212529; font-weight:600;">Permisos</label>
                            <div style="background-color:white; border-radius:8px; border:1px solid #dee2e6; padding:1.5rem; max-height:400px; overflow-y:auto;">
                                
                                @if($permissions->count() > 0)
                                    <div class="row">
                                        @php $currentGroup = ''; @endphp
                                        @foreach($permissions as $permission)
                                            @php 
                                                $group = explode('.', $permission->name)[0] ?? 'General';
                                                if($group !== $currentGroup) {
                                                    $currentGroup = $group;
                                                    echo '<div class="col-12"><hr style="border-color:#fdeaea;"><h6 style="color:#CC5CB8; font-weight:600; margin:1rem 0 0.5rem 0;">' . ucfirst($group) . '</h6></div>';
                                                }
                                            @endphp
                                            
                                            <div class="col-md-6 mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="permissions[]" 
                                                           value="{{ $permission->id }}" 
                                                           id="permission_{{ $permission->id }}"
                                                           {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}
                                                           style="border-color:#CC5CB8;">
                                                    <label class="form-check-label" for="permission_{{ $permission->id }}" 
                                                           style="color:#495057; font-size:0.9rem;">
                                                        {{ ucfirst(str_replace('.', ' ', $permission->name)) }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <div style="width:60px; height:60px; background-color:#e9ecef; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                                            <svg class="icon" style="color:#6c757d; width:30px; height:30px;">
                                                <use xlink:href="{{ asset('icons/coreui.svg#cil-vpn') }}"></use>
                                            </svg>
                                        </div>
                                        <p style="color:#6c757d;">No hay permisos disponibles en el sistema.</p>
                                        <a href="{{ route('permissions.create') }}" class="btn btn-sm" 
                                           style="background-color:#CC5CB8; color:white; border:none; border-radius:6px;">
                                            Crear Permisos
                                        </a>
                                    </div>
                                @endif
                            </div>
                            @error('permissions')
                                <div class="text-danger small mt-2">{{ $error }}</div>
                            @enderror
                            <small class="text-muted">Seleccione los permisos que tendrá este rol.</small>
                        </div>
                    </div>

                    <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1.5rem 2rem;">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn" 
                                    style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem; font-weight:500;">
                                <svg class="icon me-2">
                                    <use xlink:href="{{ asset('icons/coreui.svg#cil-check') }}"></use>
                                </svg>
                                Crear Rol
                            </button>
                            
                            <a href="{{ route('roles.index') }}" class="btn" 
                               style="background-color:#6c757d; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem;">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Seleccionar/deseleccionar todos los permisos de un grupo
    function toggleGroupPermissions(groupName) {
        const checkboxes = document.querySelectorAll(`input[name="permissions[]"]`);
        const groupCheckboxes = [];
        
        checkboxes.forEach(checkbox => {
            const label = checkbox.nextElementSibling;
            if (label.textContent.toLowerCase().includes(groupName.toLowerCase())) {
                groupCheckboxes.push(checkbox);
            }
        });
        
        const allChecked = groupCheckboxes.every(cb => cb.checked);
        groupCheckboxes.forEach(checkbox => {
            checkbox.checked = !allChecked;
        });
    }
</script>
@endpush
@endsection