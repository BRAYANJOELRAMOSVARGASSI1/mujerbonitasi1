@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:#CC5CB8; color:white; border-radius:12px 12px 0 0; border:none;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 style="margin:0; font-weight:600;">{{ $estilista->nombre_completo }}</h5>
                <a href="{{ route('estilistas.index') }}" class="btn btn-sm" style="background-color:rgba(255,255,255,0.2); color:white; border:none; border-radius:6px;">← Volver</a>
            </div>
        </div>
        <div class="card-body" style="background-color:#f8f9fa; padding:2rem;">
            <div class="row">
                <div class="col-md-3 text-center mb-4">
                    <div style="width:100px; height:100px; background:linear-gradient(135deg, #CC5CB8, #9b59b6); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                        <svg class="icon" style="color:white; width:50px; height:50px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-star') }}"></use></svg>
                    </div>
                    <h5 style="color:#212529; font-weight:600;">{{ $estilista->nombre_completo }}</h5>
                    <span class="badge" style="background-color:{{ $estilista->estado === 'activo' ? '#28a745' : '#dc3545' }};">{{ ucfirst($estilista->estado) }}</span>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div style="background-color:white; padding:1rem; border-radius:8px; border-left:4px solid #CC5CB8;">
                                <small style="color:#6c757d;">Especialidad</small>
                                <p style="margin:0; font-weight:600;">{{ $estilista->especialidad }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div style="background-color:white; padding:1rem; border-radius:8px; border-left:4px solid #28a745;">
                                <small style="color:#6c757d;">Comisión</small>
                                <p style="margin:0; font-weight:600; color:#28a745;">{{ $estilista->porcentaje_comision }}%</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div style="background-color:white; padding:1rem; border-radius:8px; border-left:4px solid #17a2b8;">
                                <small style="color:#6c757d;">Teléfono</small>
                                <p style="margin:0; font-weight:600;">{{ $estilista->telefono }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div style="background-color:white; padding:1rem; border-radius:8px; border-left:4px solid #17a2b8;">
                                <small style="color:#6c757d;">Fecha Contratación</small>
                                <p style="margin:0; font-weight:600;">{{ $estilista->fecha_contratacion->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                    @if($estilista->horarios->count() > 0)
                    <h6 style="color:#CC5CB8; font-weight:600; margin-top:1rem;">Horarios Asignados</h6>
                    <div class="table-responsive">
                        <table class="table table-sm" style="background:white; border-radius:8px;">
                            <thead><tr><th>Día</th><th>Entrada</th><th>Salida</th></tr></thead>
                            <tbody>
                                @foreach($estilista->horarios as $h)
                                <tr><td>{{ ucfirst($h->dia_semana) }}</td><td>{{ $h->hora_inicio }}</td><td>{{ $h->hora_fin }}</td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1.5rem 2rem;">
            <a href="{{ route('estilistas.edit', $estilista) }}" class="btn" style="background-color:#ffc107; color:#212529; border:none; border-radius:8px; padding:0.5rem 1.5rem;">Editar</a>
        </div>
    </div>
</div>
@endsection
