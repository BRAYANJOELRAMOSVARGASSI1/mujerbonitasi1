@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:#CC5CB8; color:white; border-radius:12px 12px 0 0; border:none;"><h5 style="margin:0; font-weight:600;">Registrar Nueva Herramienta</h5></div>
        <form method="POST" action="{{ route('herramientas.store') }}">
            @csrf
            <div class="card-body" style="background-color:#f8f9fa; padding:2rem;">
                <div class="row">
                    <div class="col-md-6 mb-3"><label class="form-label" style="color:#495057; font-weight:500;">Nombre *</label><input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">@error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6 mb-3"><label class="form-label" style="color:#495057; font-weight:500;">Categoría *</label><select name="categoria" class="form-select @error('categoria') is-invalid @enderror" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;"><option value="">Seleccionar...</option>@foreach($categorias as $cat)<option value="{{ $cat }}" {{ old('categoria') == $cat ? 'selected' : '' }}>{{ $cat }}</option>@endforeach</select>@error('categoria')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6 mb-3"><label class="form-label" style="color:#495057; font-weight:500;">Área Asignada</label><select name="area_asignada" class="form-select" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;"><option value="">Seleccionar...</option>@foreach($areas as $a)<option value="{{ $a }}" {{ old('area_asignada') == $a ? 'selected' : '' }}>{{ $a }}</option>@endforeach</select></div>
                    <div class="col-md-6 mb-3"><label class="form-label" style="color:#495057; font-weight:500;">Número de Serie</label><input type="text" name="numero_serie" class="form-control @error('numero_serie') is-invalid @enderror" value="{{ old('numero_serie') }}" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">@error('numero_serie')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6 mb-3"><label class="form-label" style="color:#495057; font-weight:500;">Fecha Adquisición</label><input type="date" name="fecha_adquisicion" class="form-control" value="{{ old('fecha_adquisicion') }}" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;"></div>
                    <div class="col-md-6 mb-3"><label class="form-label" style="color:#495057; font-weight:500;">Costo (Bs.)</label><input type="number" step="0.01" name="costo" class="form-control" value="{{ old('costo') }}" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;"></div>
                    <div class="col-12 mb-3"><label class="form-label" style="color:#495057; font-weight:500;">Descripción</label><textarea name="descripcion" class="form-control" rows="2" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">{{ old('descripcion') }}</textarea></div>
                </div>
            </div>
            <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1.5rem 2rem;">
                <a href="{{ route('herramientas.index') }}" class="btn" style="background-color:#6c757d; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem;">Cancelar</a>
                <button type="submit" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem;">Registrar Herramienta</button>
            </div>
        </form>
    </div>
</div>
@endsection
