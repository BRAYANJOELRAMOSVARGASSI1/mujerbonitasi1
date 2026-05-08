@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:#CC5CB8; color:white; border-radius:12px 12px 0 0; border:none;">
            <h5 style="margin:0; font-weight:600;">Editar Estilista — {{ $estilista->nombre_completo }}</h5>
        </div>
        <form method="POST" action="{{ route('estilistas.update', $estilista) }}">
            @csrf @method('PUT')
            <div class="card-body" style="background-color:#f8f9fa; padding:2rem;">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Nombre *</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $estilista->nombre) }}" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Apellido *</label>
                        <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror" value="{{ old('apellido', $estilista->apellido) }}" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                        @error('apellido')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Teléfono *</label>
                        <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono', $estilista->telefono) }}" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                        @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $estilista->email) }}" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Especialidad *</label>
                        <select name="especialidad" class="form-select" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                            @foreach($especialidades as $esp)
                                <option value="{{ $esp }}" {{ old('especialidad', $estilista->especialidad) == $esp ? 'selected' : '' }}>{{ $esp }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Comisión (%) *</label>
                        <select name="porcentaje_comision" class="form-select" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                            <option value="40" {{ old('porcentaje_comision', $estilista->porcentaje_comision) == '40.00' || old('porcentaje_comision', $estilista->porcentaje_comision) == '40' ? 'selected' : '' }}>40%</option>
                            <option value="50" {{ old('porcentaje_comision', $estilista->porcentaje_comision) == '50.00' || old('porcentaje_comision', $estilista->porcentaje_comision) == '50' ? 'selected' : '' }}>50%</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Fecha Contratación *</label>
                        <input type="date" name="fecha_contratacion" class="form-control" value="{{ old('fecha_contratacion', $estilista->fecha_contratacion->format('Y-m-d')) }}" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Estado</label>
                        <select name="estado" class="form-select" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                            <option value="activo" {{ old('estado', $estilista->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="inactivo" {{ old('estado', $estilista->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1.5rem 2rem;">
                <a href="{{ route('estilistas.index') }}" class="btn" style="background-color:#6c757d; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem;">Cancelar</a>
                <button type="submit" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem;">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
@endsection
