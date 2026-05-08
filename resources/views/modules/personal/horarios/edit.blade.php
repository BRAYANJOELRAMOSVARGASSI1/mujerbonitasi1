@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:#CC5CB8; color:white; border-radius:12px 12px 0 0; border:none;">
            <h5 style="margin:0; font-weight:600;">Editar Horario</h5>
        </div>
        <form method="POST" action="{{ route('horarios.update', $horario) }}">
            @csrf @method('PUT')
            <div class="card-body" style="background-color:#f8f9fa; padding:2rem;">
                @if($errors->any())
                    <div class="alert alert-danger" style="border:none; border-radius:8px; border-left:4px solid #dc3545;">
                        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Estilista *</label>
                        <select name="estilista_id" class="form-select" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                            @foreach($estilistas as $est)
                                <option value="{{ $est->id }}" {{ old('estilista_id', $horario->estilista_id) == $est->id ? 'selected' : '' }}>{{ $est->nombre }} {{ $est->apellido }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Día *</label>
                        <select name="dia_semana" class="form-select" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                            @foreach($diasSemana as $key => $label)
                                <option value="{{ $key }}" {{ old('dia_semana', $horario->dia_semana) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Hora Inicio *</label>
                        <input type="time" name="hora_inicio" class="form-control" value="{{ old('hora_inicio', substr($horario->hora_inicio, 0, 5)) }}" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" style="color:#495057; font-weight:500;">Hora Fin *</label>
                        <input type="time" name="hora_fin" class="form-control" value="{{ old('hora_fin', substr($horario->hora_fin, 0, 5)) }}" required style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                    </div>
                </div>
            </div>
            <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1.5rem 2rem;">
                <a href="{{ route('horarios.index') }}" class="btn" style="background-color:#6c757d; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem;">Cancelar</a>
                <button type="submit" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:0.5rem 1.5rem;">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
@endsection
