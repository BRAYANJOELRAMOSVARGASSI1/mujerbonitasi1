@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:#CC5CB8; color:white; border-radius:12px 12px 0 0; border:none;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 style="margin:0; font-weight:600;">{{ $servicio->nombre }}</h5>
                <a href="{{ route('servicios.index') }}" class="btn btn-sm" style="background-color:rgba(255,255,255,0.2); color:white; border:none; border-radius:6px;">← Volver</a>
            </div>
        </div>
        <div class="card-body" style="background-color:#f8f9fa; padding:2rem;">
            <div class="row">
                <div class="col-md-6 mb-3"><div style="background-color:white; padding:1rem; border-radius:8px; border-left:4px solid #CC5CB8;"><small style="color:#6c757d;">Categoría</small><p style="margin:0; font-weight:600;">{{ $servicio->categoria }}</p></div></div>
                <div class="col-md-6 mb-3"><div style="background-color:white; padding:1rem; border-radius:8px; border-left:4px solid #28a745;"><small style="color:#6c757d;">Precio</small><p style="margin:0; font-weight:700; color:#28a745; font-size:1.3rem;">Bs. {{ number_format($servicio->precio, 2) }}</p></div></div>
                <div class="col-md-6 mb-3"><div style="background-color:white; padding:1rem; border-radius:8px; border-left:4px solid #17a2b8;"><small style="color:#6c757d;">Duración</small><p style="margin:0; font-weight:600;">{{ $servicio->duracion_formateada }}</p></div></div>
                <div class="col-md-6 mb-3"><div style="background-color:white; padding:1rem; border-radius:8px; border-left:4px solid {{ $servicio->estado === 'activo' ? '#28a745' : '#dc3545' }};"><small style="color:#6c757d;">Estado</small><p style="margin:0;"><span class="badge" style="background-color:{{ $servicio->estado === 'activo' ? '#28a745' : '#dc3545' }};">{{ ucfirst($servicio->estado) }}</span></p></div></div>
                @if($servicio->descripcion)<div class="col-12 mb-3"><div style="background-color:white; padding:1rem; border-radius:8px; border-left:4px solid #ffc107;"><small style="color:#6c757d;">Descripción</small><p style="margin:0;">{{ $servicio->descripcion }}</p></div></div>@endif
            </div>
        </div>
        <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1.5rem 2rem;">
            <a href="{{ route('servicios.edit', $servicio) }}" class="btn" style="background-color:#ffc107; color:#212529; border:none; border-radius:8px; padding:0.5rem 1.5rem;">Editar</a>
        </div>
    </div>
</div>
@endsection
