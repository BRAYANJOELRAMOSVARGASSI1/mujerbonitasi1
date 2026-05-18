@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:white; border-radius:12px 12px 0 0; border-bottom:1px solid #dee2e6;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 style="margin:0; font-weight:600;"><svg class="icon me-2" style="color:#CC5CB8;"><use xlink:href="{{ asset('icons/coreui.svg#cil-calendar') }}"></use></svg>Detalle Cita #{{ $cita->id }}</h5>
                <a href="{{ route('citas.index') }}" class="btn" style="background-color:#6c757d; color:white; border:none; border-radius:8px;">Volver</a>
            </div>
        </div>
        <div class="card-body" style="padding:2rem;">
            @php $ec = ['pendiente'=>'warning','en_curso'=>'info','completada'=>'success','cancelada'=>'danger'][$cita->estado] ?? 'secondary'; @endphp
            <div class="alert alert-{{ $ec }} text-center"><strong>Estado: {{ ucfirst(str_replace('_',' ',$cita->estado)) }}</strong></div>
            <div class="row g-4">
                <div class="col-md-6"><div class="card border" style="border-radius:10px;"><div class="card-header bg-light fw-bold">Cliente</div><div class="card-body"><p><strong>Nombre:</strong> {{ $cita->cliente->nombre_completo }}</p><p><strong>Teléfono:</strong> {{ $cita->cliente->telefono }}</p><p class="mb-0"><strong>Email:</strong> {{ $cita->cliente->email ?? 'N/A' }}</p></div></div></div>
                <div class="col-md-6"><div class="card border" style="border-radius:10px;"><div class="card-header bg-light fw-bold">Servicio</div><div class="card-body"><p><strong>Servicio:</strong> {{ $cita->servicio->nombre }}</p><p><strong>Categoría:</strong> <span class="badge" style="background:#CC5CB8;">{{ $cita->servicio->categoria }}</span></p><p class="mb-0"><strong>Duración:</strong> {{ $cita->servicio->duracion_formateada }}</p></div></div></div>
                <div class="col-md-6"><div class="card border" style="border-radius:10px;"><div class="card-header bg-light fw-bold">Fecha y Hora</div><div class="card-body"><p><strong>Fecha:</strong> {{ $cita->fecha->format('d/m/Y') }}</p><p><strong>Inicio:</strong> {{ \Carbon\Carbon::parse($cita->hora_inicio)->format('H:i') }}</p><p class="mb-0"><strong>Fin:</strong> {{ \Carbon\Carbon::parse($cita->hora_fin)->format('H:i') }}</p></div></div></div>
                <div class="col-md-6"><div class="card border" style="border-radius:10px;"><div class="card-header bg-light fw-bold">Estilista</div><div class="card-body"><p><strong>Nombre:</strong> {{ $cita->estilista->nombre_completo }}</p><p class="mb-0"><strong>Especialidad:</strong> {{ $cita->estilista->especialidad }}</p></div></div></div>
                <div class="col-12"><div class="card border" style="border-radius:10px;"><div class="card-body"><div class="row"><div class="col-md-4 text-center"><span class="text-muted">Precio Total</span><h3 style="color:#28a745; font-weight:700;">Bs. {{ number_format($cita->precio_total, 2) }}</h3></div><div class="col-md-8"><strong>Notas:</strong><p>{{ $cita->notas ?? 'Sin observaciones' }}</p></div></div></div></div></div>
                @if($cita->servicioRealizado)
                <div class="col-12"><div class="card" style="border:2px solid #28a745; border-radius:10px;"><div class="card-header" style="background:#d4edda; color:#155724; font-weight:600;">✅ Servicio Realizado</div><div class="card-body"><p><strong>Fecha:</strong> {{ $cita->servicioRealizado->fecha_realizacion->format('d/m/Y H:i') }}</p><p><strong>Duración Real:</strong> {{ $cita->servicioRealizado->duracion_formateada }}</p><p><strong>Observaciones:</strong> {{ $cita->servicioRealizado->observaciones ?? 'N/A' }}</p><p class="mb-0"><strong>Comisión:</strong> Bs. {{ number_format($cita->servicioRealizado->comision_monto, 2) }} ({{ $cita->servicioRealizado->comision_porcentaje }}%)</p></div></div></div>
                @endif
                <div class="col-12 d-flex gap-2">
                    @if(in_array($cita->estado, ['pendiente', 'en_curso']))
                        @can('editar citas')<a href="{{ route('citas.edit', $cita) }}" class="btn" style="background:#ffc107; color:#212529; border:none; border-radius:8px;">Editar</a>@endcan
                        @if(!$cita->is_realizada)@can('registrar servicio realizado')<a href="{{ route('servicios-realizados.create', ['cita_id' => $cita->id]) }}" class="btn" style="background:#28a745; color:white; border:none; border-radius:8px;">Registrar como Realizado</a>@endcan @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
