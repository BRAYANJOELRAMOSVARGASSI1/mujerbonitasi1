@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:white; border-radius:12px 12px 0 0; border-bottom:1px solid #dee2e6;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 style="margin:0; font-weight:600;"><svg class="icon me-2" style="color:#28a745;"><use xlink:href="{{ asset('icons/coreui.svg#cil-check-circle') }}"></use></svg>Registrar Servicio Realizado</h5>
                <a href="{{ route('servicios-realizados.index') }}" class="btn" style="background:#6c757d; color:white; border:none; border-radius:8px;">Volver</a>
            </div>
        </div>
        <div class="card-body" style="padding:2rem;">
            @if($errors->any())<div class="alert alert-danger" style="border:none; border-radius:8px; border-left:4px solid #dc3545;"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
            @if(session('error'))<div class="alert alert-danger" style="border-radius:8px;">{{ session('error') }}</div>@endif

            <form method="POST" action="{{ route('servicios-realizados.store') }}">
                @csrf
                <div class="row g-4">
                    {{-- Selección de Cita --}}
                    <div class="col-12">
                        <label class="form-label fw-bold">Seleccionar Cita Pendiente</label>
                        <select name="cita_id" id="cita_id" class="form-select" style="border-radius:8px; padding:0.75rem;" required>
                            <option value="">-- Seleccione una cita --</option>
                            @foreach($citasPendientes as $cp)
                                <option value="{{ $cp->id }}" {{ ($citaSeleccionada && $citaSeleccionada->id == $cp->id) || old('cita_id') == $cp->id ? 'selected' : '' }}>
                                    #{{ $cp->id }} — {{ $cp->cliente->nombre_completo }} | {{ $cp->servicio->nombre }} | {{ $cp->fecha->format('d/m/Y') }} {{ \Carbon\Carbon::parse($cp->hora_inicio)->format('H:i') }} | {{ $cp->estilista->nombre_completo }}
                                </option>
                            @endforeach
                        </select>
                        @if($citasPendientes->isEmpty())<small class="text-warning mt-1 d-block">No hay citas pendientes de realización.</small>@endif
                    </div>

                    {{-- Info de la cita seleccionada --}}
                    @if($citaSeleccionada)
                    <div class="col-12">
                        <div class="card" style="border:2px solid #CC5CB8; border-radius:10px;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3"><strong>Cliente:</strong><br>{{ $citaSeleccionada->cliente->nombre_completo }}</div>
                                    <div class="col-md-3"><strong>Servicio:</strong><br>{{ $citaSeleccionada->servicio->nombre }}</div>
                                    <div class="col-md-3"><strong>Estilista:</strong><br>{{ $citaSeleccionada->estilista->nombre_completo }}</div>
                                    <div class="col-md-3"><strong>Precio:</strong><br><span style="color:#28a745; font-weight:700;">Bs. {{ number_format($citaSeleccionada->precio_total, 2) }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Duración Real (minutos)</label>
                        <input type="number" name="duracion_real_minutos" class="form-control" value="{{ old('duracion_real_minutos', $citaSeleccionada ? $citaSeleccionada->servicio->duracion_minutos : '') }}" min="1" max="600" style="border-radius:8px; padding:0.75rem;" placeholder="Ej: 45">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Productos Utilizados</label>
                        <input type="text" name="productos_utilizados" class="form-control" value="{{ old('productos_utilizados') }}" style="border-radius:8px; padding:0.75rem;" placeholder="Ej: Shampoo L'Oréal, Tinte Wella...">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="4" style="border-radius:8px; padding:0.75rem;" placeholder="Detalles del trabajo realizado, estado del cabello, recomendaciones...">{{ old('observaciones') }}</textarea>
                    </div>

                    <div class="col-12">
                        <div class="alert alert-info" style="border-radius:8px; border:none; border-left:4px solid #17a2b8;">
                            <strong>Nota:</strong> Al registrar el servicio como realizado, la comisión se calculará automáticamente según el porcentaje configurado para la estilista.
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-lg" style="background:#28a745; color:white; border:none; border-radius:8px; padding:12px 32px; font-weight:600;">
                            <svg class="icon me-2"><use xlink:href="{{ asset('icons/coreui.svg#cil-check-circle') }}"></use></svg>Registrar Servicio Realizado
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
