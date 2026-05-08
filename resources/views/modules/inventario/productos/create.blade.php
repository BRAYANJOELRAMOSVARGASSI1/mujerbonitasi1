@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:#CC5CB8; color:white; border-radius:12px 12px 0 0; border:none;"><h5 style="margin:0; font-weight:600;">Registrar Nuevo Producto</h5></div>
        <form method="POST" action="{{ route('productos.store') }}">
            @csrf
            <div class="card-body" style="background-color:#f8f9fa; padding:2rem;">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Nombre *</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Categoría *</label>
                        <select name="categoria" class="form-select @error('categoria') is-invalid @enderror" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                            <option value="">Seleccionar...</option>
                            @foreach($categorias as $cat)<option value="{{ $cat }}" {{ old('categoria') == $cat ? 'selected' : '' }}>{{ $cat }}</option>@endforeach
                        </select>
                        @error('categoria')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Marca</label>
                        <input type="text" name="marca" class="form-control" value="{{ old('marca') }}" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Unidad de Medida</label>
                        <select name="unidad_medida" class="form-select" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                            <option value="">Seleccionar...</option>
                            @foreach($unidades as $key => $label)<option value="{{ $key }}" {{ old('unidad_medida') == $key ? 'selected' : '' }}>{{ $label }}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Precio Compra (Bs.) *</label>
                        <input type="number" step="0.01" name="precio_compra" class="form-control @error('precio_compra') is-invalid @enderror" value="{{ old('precio_compra') }}" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                        @error('precio_compra')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Precio Venta (Bs.)</label>
                        <input type="number" step="0.01" name="precio_venta" class="form-control" value="{{ old('precio_venta') }}" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Stock Actual *</label>
                        <input type="number" name="stock_actual" class="form-control @error('stock_actual') is-invalid @enderror" value="{{ old('stock_actual', 0) }}" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                        @error('stock_actual')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Stock Mínimo *</label>
                        <input type="number" name="stock_minimo" class="form-control @error('stock_minimo') is-invalid @enderror" value="{{ old('stock_minimo', 5) }}" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                        @error('stock_minimo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="2" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">{{ old('descripcion') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1.5rem 2rem;">
                <a href="{{ route('productos.index') }}" class="btn" style="background-color:#6c757d; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem;">Cancelar</a>
                <button type="submit" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem;">Registrar Producto</button>
            </div>
        </form>
    </div>
</div>
@endsection
