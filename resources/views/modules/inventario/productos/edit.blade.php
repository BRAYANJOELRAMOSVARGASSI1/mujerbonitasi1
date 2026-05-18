@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:#CC5CB8; color:white; border-radius:12px 12px 0 0; border:none;"><h5 style="margin:0; font-weight:600;">Editar Producto — {{ $producto->nombre }}</h5></div>
        <form method="POST" action="{{ route('productos.update', $producto) }}">
            @csrf @method('PUT')
            <div class="card-body" style="background-color:#f8f9fa; padding:2rem;">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Nombre *</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $producto->nombre) }}" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Categoría *</label>
                        <select name="categoria" class="form-select" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                            @foreach($categorias as $cat)<option value="{{ $cat }}" {{ old('categoria', $producto->categoria) == $cat ? 'selected' : '' }}>{{ $cat }}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Marca</label>
                        <input type="text" name="marca" class="form-control" value="{{ old('marca', $producto->marca) }}" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Ubicación</label>
                        <input type="text" name="ubicacion" class="form-control" value="{{ old('ubicacion', $producto->ubicacion) }}" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Unidad de Medida</label>
                        <select name="unidad_medida" class="form-select" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                            <option value="">Seleccionar...</option>
                            @foreach($unidades as $key => $label)<option value="{{ $key }}" {{ old('unidad_medida', $producto->unidad_medida) == $key ? 'selected' : '' }}>{{ $label }}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Precio Compra (Bs.) *</label>
                        <input type="number" step="0.01" name="precio_compra" class="form-control" value="{{ old('precio_compra', $producto->precio_compra) }}" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Precio Venta (Bs.)</label>
                        <input type="number" step="0.01" name="precio_venta" class="form-control" value="{{ old('precio_venta', $producto->precio_venta) }}" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Stock Actual *</label>
                        <input type="number" name="stock_actual" class="form-control" value="{{ old('stock_actual', $producto->stock_actual) }}" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Stock Mínimo *</label>
                        <input type="number" name="stock_minimo" class="form-control" value="{{ old('stock_minimo', $producto->stock_minimo) }}" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                    </div>
                    <div class="col-md-3 mb-3">
                        <!-- adicion de atributo stock maximo -->
                        <label class="form-label" style="color:#495057; font-weight:500;">Stock Máximo *</label>
                        <input type="number" name="stock_maximo" class="form-control @error('stock_maximo') is-invalid @enderror" value="{{ old('stock_maximo', $producto->stock_maximo) }}" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                        @error('stock_maximo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Estado</label>
                        <select name="estado" class="form-select" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                            <option value="activo" {{ old('estado', $producto->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="inactivo" {{ old('estado', $producto->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="2" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">{{ old('descripcion', $producto->descripcion) }}</textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1.5rem 2rem;">
                <a href="{{ route('productos.index') }}" class="btn" style="background-color:#6c757d; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem;">Cancelar</a>
                <button type="submit" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem;">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
@endsection
