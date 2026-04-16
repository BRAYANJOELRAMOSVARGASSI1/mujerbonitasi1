@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 style="margin:0; font-weight:600;">{{ __('Lista de Usuarios') }}</h5>
                            <small style="opacity:0.9;">Administra los usuarios del sistema</small>
                        </div>
                        <button type="button" class="btn"
                                style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:8px 16px; font-weight:500;"
                                data-coreui-toggle="modal" data-coreui-target="#createUserModal">
                            <svg class="icon me-2">
                                <use xlink:href="{{ asset('icons/coreui.svg#cil-plus') }}"></use>
                            </svg>
                            Crear Usuario
                        </button>
                    </div>
                </div>

                <div class="card-body" style="background-color:#f8f9fa; padding:2rem;">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert" style="border:none; border-radius:8px; background-color:#d4edda; color:#155724; border-left:4px solid #28a745;">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if($users->count() > 0)
                        <div class="row">
                            @foreach($users as $user)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: all 0.3s ease;" 
                                         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(204,92,184,0.15)'"
                                         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 10px rgba(0,0,0,0.08)'">
                                        <div class="card-body" style="padding:1.5rem;">
                                            <div class="d-flex align-items-center mb-3">
                                                <div style="width:45px; height:45px; background-color:#CC5CB8; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:1rem;">
                                                    <svg class="icon" style="color:white; width:24px; height:24px;">
                                                        <use xlink:href="{{ asset('icons/coreui.svg#cil-user') }}"></use>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h6 style="margin:0; color:#212529; font-weight:600;">{{ ucfirst($user->name) }}
                                                        @if($user->status === 'bloqueado')
                                                            <span class="badge" style="background-color:#dc3545; font-size:0.65rem; margin-left:0.5rem; vertical-align: middle;">BLOQUEADO</span>
                                                        @endif
                                                    </h6>
                                                    <small style="color:#6c757d;">{{ $user->email }}</small>
                                                </div>
                                            </div>
                                            
                                            <div style="background-color:#f8f9fa; border-radius:8px; padding:1rem; margin-bottom:1rem;">
                                                <small style="color:#495057; font-weight:500;">Roles asignados:</small>
                                                <div class="mt-2">
                                                    @if($user->roles->count() > 0)
                                                        <span class="badge rounded-pill" style="background-color:#CC5CB8; color:white; font-size:0.75rem;">{{ $user->roles->count() }} roles</span>
                                                    @else
                                                        <span class="badge rounded-pill" style="background-color:#6c757d; color:white; font-size:0.75rem;">Sin roles</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1rem 1.5rem;">
                                            <div class="d-flex gap-2">

                                                @can('ver usuarios')
                                                <a href="{{ route('users.show', $user) }}" class="btn btn-sm" 
                                                   style="background-color:#17a2b8; color:white; border:none; border-radius:6px; padding:6px 12px; font-size:0.875rem;">
                                                    <svg class="icon" style="width:14px; height:14px;">
                                                        <use xlink:href="{{ asset('icons/coreui.svg#cil-eye') }}"></use>
                                                    </svg>
                                                    Ver
                                                </a>
                                                @endcan
                                                
                                                @can('editar usuarios')
                                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm" 
                                                   style="background-color:#ffc107; color:#212529; border:none; border-radius:6px; padding:6px 12px; font-size:0.875rem;">
                                                    <svg class="icon" style="width:14px; height:14px;">
                                                        <use xlink:href="{{ asset('icons/coreui.svg#cil-pencil') }}"></use>
                                                    </svg>
                                                    Editar
                                                </a>

                                                @if($user->status === 'bloqueado')
                                                <form method="POST" action="{{ route('users.unblock', $user) }}" style="display:inline-block;"
                                                      onsubmit="return confirm('¿Confirmas desbloquear temporalmente a este usuario para que vuelva a intentar iniciar sesión?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm" 
                                                            style="background-color:#d896cd; color:white; border:none; border-radius:6px; padding:6px 12px; font-size:0.875rem; font-weight: 600;">
                                                        <svg class="icon" style="width:14px; height:14px;">
                                                            <use xlink:href="{{ asset('icons/coreui.svg#cil-lock-unlocked') }}"></use>
                                                        </svg>
                                                        Desbloquear
                                                    </button>
                                                </form>
                                                @endif
                                                @endcan

                                                @can('eliminar usuarios')
                                                <form method="POST" action="{{ route('users.destroy', $user) }}" 
                                                      style="display:inline-block;" 
                                                      onsubmit="return confirm('¿Estás seguro de que quieres eliminar este usuario?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm" 
                                                            style="background-color:#dc3545; color:white; border:none; border-radius:6px; padding:6px 12px; font-size:0.875rem;">
                                                        <svg class="icon" style="width:14px; height:14px;">
                                                            <use xlink:href="{{ asset('icons/coreui.svg#cil-trash') }}"></use>
                                                        </svg>
                                                    </button>
                                                </form>
                                                @endcan
                                           
                                                @can('roles')
                                                <button type="button" class="btn btn-sm" 
                                                        style="background-color:#CC5CB8; color:white; border:none; border-radius:6px; padding:6px 12px; font-size:0.875rem;"
                                                        data-coreui-toggle="modal" data-coreui-target="#rolesModal"
                                                        data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                                                    <svg class="icon" style="width:14px; height:14px;">
                                                        <use xlink:href="{{ asset('icons/coreui.svg#cil-settings') }}"></use>
                                                    </svg>
                                                    Roles
                                                </button>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div style="width:80px; height:80px; background-color:#e9ecef; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem;">
                                <svg class="icon" style="color:#6c757d; width:40px; height:40px;">
                                    <use xlink:href="{{ asset('icons/coreui.svg#cil-user') }}"></use>
                                </svg>
                            </div>
                            <h5 style="color:#495057; margin-bottom:1rem;">No hay usuarios registrados</h5>
                            <p style="color:#6c757d; margin-bottom:1.5rem;">Comienza creando un nuevo usuario para tu sistema</p>
                            <a href="{{ route('users.create') }}" class="btn" 
                               style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:10px 20px; font-weight:500;">
                                <svg class="icon me-2">
                                    <use xlink:href="{{ asset('icons/coreui.svg#cil-plus') }}"></use>
                                </svg>
                                Crear primer usuario
                            </a>
                        </div>
                    @endif
                </div>

                @if($users->count() > 0)
                    <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1.5rem 2rem;">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear usuario -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border:none; border-radius:12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header" style="background-color:#CC5CB8; color:white; border-radius:12px 12px 0 0; border:none;">
                <h5 class="modal-title" id="createUserModalLabel" style="font-weight:600;">Crear Nuevo Usuario</h5>
                <button type="button" class="btn-close btn-close-white" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>

            @can('crear usuarios')
            <form id="createUserForm" method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="modal-body" style="background-color:#f8f9fa; padding:2rem;">
                    <div class="mb-3">
                        <label for="name" class="form-label" style="color:#495057; font-weight:500;">Nombre</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required
                               style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label" style="color:#495057; font-weight:500;">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required
                               style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label" style="color:#495057; font-weight:500;">Contraseña</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required
                               style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label" style="color:#495057; font-weight:500;">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required
                               style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                    </div>
                </div>
                <div class="modal-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1.5rem 2rem;">
                    <button type="button" class="btn" data-coreui-dismiss="modal" style="background-color:#6c757d; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem; font-weight:500;">
                        Cancelar
                    </button>
                    <button type="submit" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem; font-weight:500;">
                        Crear Usuario
                    </button>
                </div>
            </form>
            @endcan
        </div>
    </div>
</div>

<!-- Modal para roles y permisos -->
<div class="modal fade" id="rolesModal" tabindex="-1" aria-labelledby="rolesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border:none; border-radius:12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header" style="background-color:#CC5CB8; color:white; border-radius:12px 12px 0 0; border:none;">
                <h5 class="modal-title" id="rolesModalLabel" style="font-weight:600;">Asignar Roles y Permisos</h5>
                <button type="button" class="btn-close btn-close-white" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rolesForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body" style="background-color:#f8f9fa; padding:2rem;">
                    <div id="userInfo" class="mb-4" style="background-color:white; padding:1rem; border-radius:8px; border-left:4px solid #CC5CB8;">
                        <p style="margin:0; color:#495057;"><strong style="color:#CC5CB8;">Usuario:</strong> <span id="userName" style="color:#212529;"></span></p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div style="background-color:white; padding:1.5rem; border-radius:8px; margin-bottom:1rem;">
                                <h6 style="color:#CC5CB8; font-weight:600; margin-bottom:1rem; border-bottom:2px solid #CC5CB8; padding-bottom:0.5rem;">Roles</h6>
                                <div id="rolesContainer">
                                    <!-- Los roles se cargarán dinámicamente -->
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div style="background-color:white; padding:1.5rem; border-radius:8px; margin-bottom:1rem;">
                                <h6 style="color:#CC5CB8; font-weight:600; margin-bottom:1rem; border-bottom:2px solid #CC5CB8; padding-bottom:0.5rem;">Permisos</h6>
                                <div id="permissionsContainer">
                                    <!-- Los permisos se cargarán dinámicamente -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1.5rem 2rem;">
                    <button type="button" class="btn" data-coreui-dismiss="modal" style="background-color:#6c757d; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem; font-weight:500;">
                        Cancelar
                    </button>
                    <button type="submit" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem; font-weight:500;">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Manejar el modal de roles y permisos
    document.addEventListener('DOMContentLoaded', function() {
        const rolesModal = document.getElementById('rolesModal');
        const rolesForm = document.getElementById('rolesForm');
        const userNameSpan = document.getElementById('userName');
        const rolesContainer = document.getElementById('rolesContainer');
        const permissionsContainer = document.getElementById('permissionsContainer');

        rolesModal.addEventListener('show.coreui.modal', function(event) {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');

            userNameSpan.textContent = userName;
            rolesForm.action = `/users/${userId}/roles-permissions`;

            // Cargar roles y permisos del usuario
            loadUserRolesAndPermissions(userId);
        });

        function loadUserRolesAndPermissions(userId) {
            fetch(`/users/${userId}/roles/data`)
                .then(response => response.json())
                .then(data => {
                    // Cargar roles
                    rolesContainer.innerHTML = '';
                    data.roles.forEach(role => {
                        const div = document.createElement('div');
                        div.className = 'form-check mb-2';
                        div.style.cssText = 'padding:0.5rem; border-radius:6px;';
                        div.innerHTML = `
                            <input class="form-check-input" type="checkbox" name="roles[]" value="${role.id}" id="role_${role.id}" ${role.assigned ? 'checked' : ''}>
                            <label class="form-check-label" for="role_${role.id}" style="color:#495057; font-weight:500;">${role.name}</label>
                        `;
                        rolesContainer.appendChild(div);
                    });

                    // Cargar permisos
                    permissionsContainer.innerHTML = '';
                    data.permissions.forEach(permission => {
                        const div = document.createElement('div');
                        div.className = 'form-check mb-2';
                        div.style.cssText = 'padding:0.5rem; border-radius:6px;';
                        div.innerHTML = `
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="${permission.id}" id="perm_${permission.id}" ${permission.assigned ? 'checked' : ''}>
                            <label class="form-check-label" for="perm_${permission.id}" style="color:#495057; font-weight:500;">${permission.name}</label>
                        `;
                        permissionsContainer.appendChild(div);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cargar los datos del usuario');
                });
        }
    });
</script>

@endsection
