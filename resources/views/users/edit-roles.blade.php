@extends('layouts.app')

@section('content')
<div class="card mb-4">
  <div class="card-header">
    Asignar roles y permisos a: <strong>{{ $user->name }}</strong>
  </div>

  <div class="card-body">
    @if (session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('users.roles.update', $user) }}">
      @csrf
      @method('PUT')

      <div class="row">
        <div class="col-md-6">
          <h5>Roles</h5>
          @foreach ($roles as $role)
            <div class="form-check">
              <input class="form-check-input"
                     type="checkbox"
                     name="roles[]"
                     value="{{ $role->id }}"
                     id="role_{{ $role->id }}"
                     {{ in_array($role->id, $userRoleIds) ? 'checked' : '' }}>
              <label class="form-check-label" for="role_{{ $role->id }}">
                {{ $role->name }}
              </label>
            </div>
          @endforeach
        </div>

        <div class="col-md-6">
          <h5>Permisos</h5>
          @foreach ($permissions as $permission)
            <div class="form-check">
              <input class="form-check-input"
                     type="checkbox"
                     name="permissions[]"
                     value="{{ $permission->id }}"
                     id="perm_{{ $permission->id }}"
                     {{ in_array($permission->id, $userPermissionIds) ? 'checked' : '' }}>
              <label class="form-check-label" for="perm_{{ $permission->id }}">
                {{ $permission->name }}
              </label>
            </div>
          @endforeach
        </div>
      </div>

      <hr>
      <button type="submit" class="btn btn-primary">
        Guardar cambios
      </button>
      <a href="{{ route('users.index') }}" class="btn btn-secondary">
        Volver
      </a>
    </form>
  </div>
</div>
@endsection
