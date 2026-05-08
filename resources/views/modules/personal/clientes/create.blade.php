@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:#CC5CB8; color:white; border-radius:12px 12px 0 0; border:none;">
            <h5 style="margin:0; font-weight:600;">Registrar Nuevo Cliente</h5>
        </div>
        <form method="POST" action="{{ route('clientes.store') }}">
            @csrf
            <div class="card-body" style="background-color:#f8f9fa; padding:2rem;">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Nombre *</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Apellido *</label>
                        <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror" value="{{ old('apellido') }}" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                        @error('apellido')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Teléfono *</label>
                        <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                        @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror" value="{{ old('fecha_nacimiento') }}" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                        @error('fecha_nacimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Dirección</label>
                        <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion') }}" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                        @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Notas (alergias, preferencias, observaciones)</label>
                        <textarea name="notas" class="form-control @error('notas') is-invalid @enderror" rows="3" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">{{ old('notas') }}</textarea>
                        @error('notas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1.5rem 2rem;">
                <a href="{{ route('clientes.index') }}" class="btn" style="background-color:#6c757d; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem;">Cancelar</a>
                <button type="submit" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem;">Registrar Cliente</button>
            </div>
        </form>
    </div>
</div>
@endsection
