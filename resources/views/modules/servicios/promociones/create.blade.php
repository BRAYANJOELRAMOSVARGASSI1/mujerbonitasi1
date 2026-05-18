@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background:white; border-radius:12px 12px 0 0; border-bottom:1px solid #dee2e6;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 style="margin:0; font-weight:600;"><svg class="icon me-2" style="color:#CC5CB8;"><use xlink:href="{{ asset('icons/coreui.svg#cil-gift') }}"></use></svg>Nueva Promoción</h5>
                <a href="{{ route('promociones.index') }}" class="btn" style="background:#6c757d; color:white; border:none; border-radius:8px;">Volver</a>
            </div>
        </div>
        <div class="card-body" style="padding:2rem;">
            @if($errors->any())<div class="alert alert-danger" style="border-radius:8px; border-left:4px solid #dc3545;"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
            <form method="POST" action="{{ route('promociones.store') }}">
                @csrf
                <div class="row g-4">
                    <div class="col-md-8"><label class="form-label fw-bold">Nombre de la Promoción</label><input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required style="border-radius:8px; padding:0.75rem;" placeholder="Ej: Descuento de Verano"></div>
                    <div class="col-md-4"><label class="form-label fw-bold">Porcentaje de Descuento (%)</label><input type="number" name="porcentaje_descuento" class="form-control" value="{{ old('porcentaje_descuento') }}" min="1" max="100" step="0.01" required style="border-radius:8px; padding:0.75rem;"></div>
                    <div class="col-12"><label class="form-label fw-bold">Descripción</label><textarea name="descripcion" class="form-control" rows="3" style="border-radius:8px; padding:0.75rem;" placeholder="Detalles de la promoción...">{{ old('descripcion') }}</textarea></div>
                    <div class="col-md-6"><label class="form-label fw-bold">Fecha de Inicio</label><input type="date" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio', date('Y-m-d')) }}" required style="border-radius:8px; padding:0.75rem;"></div>
                    <div class="col-md-6"><label class="form-label fw-bold">Fecha de Fin</label><input type="date" name="fecha_fin" class="form-control" value="{{ old('fecha_fin') }}" required style="border-radius:8px; padding:0.75rem;"></div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Servicios Incluidos</label>
                        <div class="row g-2" style="max-height:300px; overflow-y:auto; border:1px solid #dee2e6; border-radius:8px; padding:1rem;">
                            @foreach($servicios as $srv)
                            <div class="col-md-6">
                                <div class="form-check" style="padding:8px 12px; background:#f8f9fa; border-radius:8px;">
                                    <input class="form-check-input" type="checkbox" name="servicios[]" value="{{ $srv->id }}" id="srv_{{ $srv->id }}" {{ in_array($srv->id, old('servicios', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="srv_{{ $srv->id }}">
                                        <strong>{{ $srv->nombre }}</strong> <span class="badge" style="background:#CC5CB8; font-size:0.65rem;">{{ $srv->categoria }}</span>
                                        <br><small class="text-muted">Bs. {{ number_format($srv->precio, 2) }} — {{ $srv->duracion_formateada }}</small>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <small class="text-muted mt-1 d-block">Seleccione al menos un servicio al que aplica esta promoción.</small>
                    </div>
                    <div class="col-12"><button type="submit" class="btn btn-lg" style="background:#CC5CB8; color:white; border:none; border-radius:8px; padding:12px 32px; font-weight:600;"><svg class="icon me-2"><use xlink:href="{{ asset('icons/coreui.svg#cil-save') }}"></use></svg>Crear Promoción</button></div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
