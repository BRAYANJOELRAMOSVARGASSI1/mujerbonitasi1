@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:white; border-radius:12px 12px 0 0; border-bottom:1px solid #dee2e6;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 style="margin:0; font-weight:600;"><svg class="icon me-2" style="color:#28a745;"><use xlink:href="{{ asset('icons/coreui.svg#cil-check-circle') }}"></use></svg>Detalle — Servicio Realizado #{{ $servicios_realizado->id }}</h5>
                <a href="{{ route('servicios-realizados.index') }}" class="btn" style="background:#6c757d; color:white; border:none; border-radius:8px;">Volver</a>
            </div>
        </div>
        <div class="card-body" style="padding:2rem;">
            <div class="row g-4">
                <div class="col-md-6"><div class="card border" style="border-radius:10px;"><div class="card-header bg-light fw-bold">Cliente</div><div class="card-body"><p><strong>Nombre:</strong> {{ $servicios_realizado->cliente->nombre_completo }}</p><p class="mb-0"><strong>Teléfono:</strong> {{ $servicios_realizado->cliente->telefono }}</p></div></div></div>
                <div class="col-md-6"><div class="card border" style="border-radius:10px;"><div class="card-header bg-light fw-bold">Estilista</div><div class="card-body"><p><strong>Nombre:</strong> {{ $servicios_realizado->estilista->nombre_completo }}</p><p class="mb-0"><strong>Especialidad:</strong> {{ $servicios_realizado->estilista->especialidad }}</p></div></div></div>
                <div class="col-md-6"><div class="card border" style="border-radius:10px;"><div class="card-header bg-light fw-bold">Servicio</div><div class="card-body"><p><strong>Servicio:</strong> {{ $servicios_realizado->servicio->nombre }}</p><p><strong>Categoría:</strong> <span class="badge" style="background:#CC5CB8;">{{ $servicios_realizado->servicio->categoria }}</span></p><p class="mb-0"><strong>Duración Real:</strong> {{ $servicios_realizado->duracion_formateada }}</p></div></div></div>
                <div class="col-md-6"><div class="card border" style="border-radius:10px;"><div class="card-header bg-light fw-bold">Financiero</div><div class="card-body"><p><strong>Precio Cobrado:</strong> <span style="color:#28a745; font-weight:700; font-size:1.2rem;">Bs. {{ number_format($servicios_realizado->precio_cobrado, 2) }}</span></p><p><strong>Comisión ({{ $servicios_realizado->comision_porcentaje }}%):</strong> <span style="color:#17a2b8; font-weight:700; font-size:1.2rem;">Bs. {{ number_format($servicios_realizado->comision_monto, 2) }}</span></p><p class="mb-0"><strong>Fecha:</strong> {{ $servicios_realizado->fecha_realizacion->format('d/m/Y H:i') }}</p></div></div></div>
                @if($servicios_realizado->observaciones)
                <div class="col-12"><div class="card border" style="border-radius:10px;"><div class="card-header bg-light fw-bold">Observaciones</div><div class="card-body">{{ $servicios_realizado->observaciones }}</div></div></div>
                @endif
                @if($servicios_realizado->productos_utilizados)
                <div class="col-12"><div class="card border" style="border-radius:10px;"><div class="card-header bg-light fw-bold">Productos Utilizados</div><div class="card-body">{{ $servicios_realizado->productos_utilizados }}</div></div></div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
