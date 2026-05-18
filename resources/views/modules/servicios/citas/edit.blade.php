@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:white; border-radius:12px 12px 0 0; border-bottom:1px solid #dee2e6;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 style="margin:0; font-weight:600;"><svg class="icon me-2" style="color:#CC5CB8;"><use xlink:href="{{ asset('icons/coreui.svg#cil-pencil') }}"></use></svg>Editar Cita #{{ $cita->id }}</h5>
                <a href="{{ route('citas.index') }}" class="btn" style="background-color:#6c757d; color:white; border:none; border-radius:8px;">Volver</a>
            </div>
        </div>
        <div class="card-body" style="padding:2rem;">
            @if($errors->any())
                <div class="alert alert-danger" style="border:none; border-radius:8px; border-left:4px solid #dc3545;"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            <form method="POST" action="{{ route('citas.update', $cita) }}">
                @csrf @method('PUT')
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Cliente</label>
                        <select name="cliente_id" class="form-select" style="border-radius:8px; padding:0.75rem;" required>
                            @foreach($clientes as $cli)<option value="{{ $cli->id }}" {{ $cita->cliente_id == $cli->id ? 'selected' : '' }}>{{ $cli->nombre_completo }}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Servicio</label>
                        <select name="servicio_id" class="form-select" style="border-radius:8px; padding:0.75rem;" required>
                            @foreach($servicios as $srv)<option value="{{ $srv->id }}" {{ $cita->servicio_id == $srv->id ? 'selected' : '' }}>{{ $srv->nombre }} ({{ $srv->duracion_formateada }}) — Bs. {{ number_format($srv->precio, 2) }}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Estilista</label>
                        <select name="estilista_id" class="form-select" style="border-radius:8px; padding:0.75rem;" required>
                            @foreach($estilistas as $est)<option value="{{ $est->id }}" {{ $cita->estilista_id == $est->id ? 'selected' : '' }}>{{ $est->nombre_completo }} — {{ $est->especialidad }}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Fecha</label>
                        <input type="date" name="fecha" class="form-control" value="{{ $cita->fecha->format('Y-m-d') }}" style="border-radius:8px; padding:0.75rem;" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Hora Inicio</label>
                        <select name="hora_inicio" class="form-select" style="border-radius:8px; padding:0.75rem;" required>
                            @for($h = 8; $h <= 19; $h++)
                                @foreach(['00', '30'] as $m)
                                    @php $hora = sprintf('%02d:%s', $h, $m); @endphp
                                    <option value="{{ $hora }}" {{ \Carbon\Carbon::parse($cita->hora_inicio)->format('H:i') == $hora ? 'selected' : '' }}>{{ $hora }}</option>
                                @endforeach
                            @endfor
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Notas</label>
                        <textarea name="notas" class="form-control" rows="3" style="border-radius:8px; padding:0.75rem;">{{ $cita->notas }}</textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:10px 30px; font-weight:600;">Actualizar Cita</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
