@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:#CC5CB8; color:white; border-radius:12px 12px 0 0; border:none;">
            <h5 style="margin:0; font-weight:600;">Editar Cliente — {{ $cliente->nombre_completo }}</h5>
        </div>
        <form method="POST" action="{{ route('clientes.update', $cliente) }}">
            @csrf @method('PUT')
            <div class="card-body" style="background-color:#f8f9fa; padding:2rem;">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Nombre *</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $cliente->nombre) }}" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Apellido *</label>
                        <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror" value="{{ old('apellido', $cliente->apellido) }}" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                        @error('apellido')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Teléfono *</label>
                        <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono', $cliente->telefono) }}" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                        @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $cliente->email) }}" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento', $cliente->fecha_nacimiento?->format('Y-m-d')) }}" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Dirección</label>
                        <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $cliente->direccion) }}" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Estado</label>
                        <select name="estado" class="form-select" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                            <option value="activo" {{ old('estado', $cliente->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="inactivo" {{ old('estado', $cliente->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Notas</label>
                        <textarea name="notas" class="form-control" rows="3" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">{{ old('notas', $cliente->notas) }}</textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1.5rem 2rem;">
                <a href="{{ route('clientes.index') }}" class="btn" style="background-color:#6c757d; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem;">Cancelar</a>
                <button type="submit" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem;">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
@endsection
