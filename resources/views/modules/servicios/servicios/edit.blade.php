@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:#CC5CB8; color:white; border-radius:12px 12px 0 0; border:none;"><h5 style="margin:0; font-weight:600;">Editar Servicio — {{ $servicio->nombre }}</h5></div>
        <form method="POST" action="{{ route('servicios.update', $servicio) }}">
            @csrf @method('PUT')
            <div class="card-body" style="background-color:#f8f9fa; padding:2rem;">
                <div class="row">
                    <div class="col-md-6 mb-3"><label class="form-label" style="color:#495057; font-weight:500;">Nombre *</label><input type="text" name="nombre" class="form-control" value="{{ old('nombre', $servicio->nombre) }}" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;"></div>
                    <div class="col-md-6 mb-3"><label class="form-label" style="color:#495057; font-weight:500;">Categoría *</label><select name="categoria" class="form-select" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">@foreach($categorias as $cat)<option value="{{ $cat }}" {{ old('categoria', $servicio->categoria) == $cat ? 'selected' : '' }}>{{ $cat }}</option>@endforeach</select></div>
                    <div class="col-md-6 mb-3"><label class="form-label" style="color:#495057; font-weight:500;">Precio (Bs.) *</label><input type="number" step="0.01" name="precio" class="form-control" value="{{ old('precio', $servicio->precio) }}" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;"></div>
                    <div class="col-md-6 mb-3"><label class="form-label" style="color:#495057; font-weight:500;">Duración (minutos) *</label><input type="number" name="duracion_minutos" class="form-control" value="{{ old('duracion_minutos', $servicio->duracion_minutos) }}" required min="5" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;"></div>
                    <div class="col-md-6 mb-3"><label class="form-label" style="color:#495057; font-weight:500;">Estado</label><select name="estado" class="form-select" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;"><option value="activo" {{ old('estado', $servicio->estado) == 'activo' ? 'selected' : '' }}>Activo</option><option value="inactivo" {{ old('estado', $servicio->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option></select></div>
                    <div class="col-12 mb-3"><label class="form-label" style="color:#495057; font-weight:500;">Descripción</label><textarea name="descripcion" class="form-control" rows="3" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">{{ old('descripcion', $servicio->descripcion) }}</textarea></div>
                </div>
            </div>
            <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1.5rem 2rem;">
                <a href="{{ route('servicios.index') }}" class="btn" style="background-color:#6c757d; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem;">Cancelar</a>
                <button type="submit" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem;">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
@endsection
