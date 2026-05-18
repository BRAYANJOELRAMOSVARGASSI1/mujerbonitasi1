@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:#CC5CB8; color:white; border-radius:12px 12px 0 0; border:none;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 style="margin:0; font-weight:600;">{{ $producto->nombre }}</h5>
                <a href="{{ route('productos.index') }}" class="btn btn-sm" style="background-color:rgba(255,255,255,0.2); color:white; border:none; border-radius:6px;">← Volver</a>
            </div>
        </div>
        <div class="card-body" style="background-color:#f8f9fa; padding:2rem;">
            <div class="row">
                <div class="col-md-6 mb-3"><div style="background-color:white; padding:1rem; border-radius:8px; border-left:4px solid #CC5CB8;"><small style="color:#6c757d;">Categoría</small><p style="margin:0; font-weight:600;">{{ $producto->categoria }}</p></div></div>
                <div class="col-md-6 mb-3"><div style="background-color:white; padding:1rem; border-radius:8px; border-left:4px solid #CC5CB8;"><small style="color:#6c757d;">Marca</small><p style="margin:0; font-weight:600;">{{ $producto->marca ?? 'N/A' }}</p></div></div>
                <div class="col-md-3 mb-3"><div style="background-color:white; padding:1rem; border-radius:8px; border-left:4px solid {{ $producto->nivel_alerta === 'critico' ? '#dc3545' : ($producto->nivel_alerta === 'bajo' ? '#ffc107' : '#28a745') }};"><small style="color:#6c757d;">Stock Actual</small><p style="margin:0; font-weight:600; font-size:1.2rem;">{{ $producto->stock_actual }}</p></div></div>
                <div class="col-md-3 mb-3"><div style="background-color:white; padding:1rem; border-radius:8px; border-left:4px solid #17a2b8;"><small style="color:#6c757d;">Stock Mínimo</small><p style="margin:0; font-weight:600;">{{ $producto->stock_minimo }}</p></div></div>
                <div class="col-md-3 mb-3"><div style="background-color:white; padding:1rem; border-radius:8px; border-left:4px solid #17a2b8;"><!-- adicion de atributo stock maximo --><small style="color:#6c757d;">Stock Máximo</small><p style="margin:0; font-weight:600;">{{ $producto->stock_maximo }}</p></div></div>
                <div class="col-md-3 mb-3"><div style="background-color:white; padding:1rem; border-radius:8px; border-left:4px solid #17a2b8;"><small style="color:#6c757d;">Precio Compra</small><p style="margin:0; font-weight:600;">Bs. {{ number_format($producto->precio_compra, 2) }}</p></div></div>
                <div class="col-md-3 mb-3"><div style="background-color:white; padding:1rem; border-radius:8px; border-left:4px solid #28a745;"><small style="color:#6c757d;">Precio Venta</small><p style="margin:0; font-weight:600; color:#28a745;">{{ $producto->precio_venta ? 'Bs. '.number_format($producto->precio_venta, 2) : 'N/A' }}</p></div></div>
            </div>
        </div>
        <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1.5rem 2rem;">
            <a href="{{ route('productos.edit', $producto) }}" class="btn" style="background-color:#ffc107; color:#212529; border:none; border-radius:8px; padding:0.5rem 1.5rem;">Editar</a>
        </div>
    </div>
</div>
@endsection
