@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background:white; border-radius:12px 12px 0 0; border-bottom:1px solid #dee2e6;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 style="margin:0; font-weight:600;"><svg class="icon me-2" style="color:#CC5CB8;"><use xlink:href="{{ asset('icons/coreui.svg#cil-gift') }}"></use></svg>{{ $promocion->nombre }}</h5>
                <a href="{{ route('promociones.index') }}" class="btn" style="background:#6c757d; color:white; border:none; border-radius:8px;">Volver</a>
            </div>
        </div>
        <div class="card-body" style="padding:2rem;">
            @php $estadoColor = ['activa'=>'#28a745','inactiva'=>'#6c757d','expirada'=>'#dc3545'][$promocion->estado] ?? '#6c757d'; @endphp
            <div class="row g-4">
                <div class="col-12 text-center">
                    <span style="font-size:3rem; font-weight:700; color:#CC5CB8;">{{ number_format($promocion->porcentaje_descuento, 0) }}%</span><br>
                    <span class="badge" style="background:{{ $estadoColor }}; padding:8px 16px; font-size:0.85rem; border-radius:12px;">{{ ucfirst($promocion->estado) }}</span>
                    @if($promocion->is_vigente)<span class="badge" style="background:#d4edda; color:#155724; padding:8px 16px; font-size:0.85rem; border-radius:12px;">✅ Vigente</span>@endif
                </div>
                <div class="col-md-6"><div class="card border" style="border-radius:10px;"><div class="card-header bg-light fw-bold">Detalles</div><div class="card-body"><p><strong>Descripción:</strong> {{ $promocion->descripcion ?? 'Sin descripción' }}</p><p><strong>Fecha Inicio:</strong> {{ $promocion->fecha_inicio->format('d/m/Y') }}</p><p class="mb-0"><strong>Fecha Fin:</strong> {{ $promocion->fecha_fin->format('d/m/Y') }}</p></div></div></div>
                <div class="col-md-6"><div class="card border" style="border-radius:10px;"><div class="card-header bg-light fw-bold">Servicios Incluidos ({{ $promocion->servicios->count() }})</div><div class="card-body">
                    @foreach($promocion->servicios as $srv)
                    <div class="d-flex justify-content-between align-items-center mb-2" style="background:#f8f9fa; padding:8px 12px; border-radius:8px;">
                        <div><strong>{{ $srv->nombre }}</strong><br><small class="text-muted">{{ $srv->categoria }}</small></div>
                        <div class="text-end"><span style="color:#28a745; font-weight:600;">Bs. {{ number_format($srv->precio, 2) }}</span><br><small style="color:#dc3545; font-weight:600;">-{{ number_format($promocion->porcentaje_descuento, 0) }}% = Bs. {{ number_format($srv->precio * (1 - $promocion->porcentaje_descuento/100), 2) }}</small></div>
                    </div>
                    @endforeach
                </div></div></div>
            </div>
        </div>
    </div>
</div>
@endsection
