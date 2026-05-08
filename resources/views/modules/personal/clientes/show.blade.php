@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:#CC5CB8; color:white; border-radius:12px 12px 0 0; border:none;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 style="margin:0; font-weight:600;">Detalle del Cliente</h5>
                <a href="{{ route('clientes.index') }}" class="btn btn-sm" style="background-color:rgba(255,255,255,0.2); color:white; border:none; border-radius:6px;">← Volver</a>
            </div>
        </div>
        <div class="card-body" style="background-color:#f8f9fa; padding:2rem;">
            <div class="row">
                <div class="col-md-3 text-center mb-4">
                    <div style="width:100px; height:100px; background-color:#CC5CB8; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                        <svg class="icon" style="color:white; width:50px; height:50px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-user') }}"></use></svg>
                    </div>
                    <h5 style="color:#212529; font-weight:600;">{{ $cliente->nombre_completo }}</h5>
                    <span class="badge" style="background-color:{{ $cliente->estado === 'activo' ? '#28a745' : '#dc3545' }};">{{ ucfirst($cliente->estado) }}</span>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div style="background-color:white; padding:1rem; border-radius:8px; border-left:4px solid #CC5CB8;">
                                <small style="color:#6c757d; font-weight:500;">Teléfono</small>
                                <p style="margin:0; color:#212529; font-weight:600;">{{ $cliente->telefono }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div style="background-color:white; padding:1rem; border-radius:8px; border-left:4px solid #CC5CB8;">
                                <small style="color:#6c757d; font-weight:500;">Email</small>
                                <p style="margin:0; color:#212529; font-weight:600;">{{ $cliente->email ?? 'No registrado' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div style="background-color:white; padding:1rem; border-radius:8px; border-left:4px solid #CC5CB8;">
                                <small style="color:#6c757d; font-weight:500;">Fecha de Nacimiento</small>
                                <p style="margin:0; color:#212529; font-weight:600;">{{ $cliente->fecha_nacimiento ? $cliente->fecha_nacimiento->format('d/m/Y') : 'No registrada' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div style="background-color:white; padding:1rem; border-radius:8px; border-left:4px solid #CC5CB8;">
                                <small style="color:#6c757d; font-weight:500;">Dirección</small>
                                <p style="margin:0; color:#212529; font-weight:600;">{{ $cliente->direccion ?? 'No registrada' }}</p>
                            </div>
                        </div>
                        @if($cliente->notas)
                        <div class="col-12 mb-3">
                            <div style="background-color:white; padding:1rem; border-radius:8px; border-left:4px solid #ffc107;">
                                <small style="color:#6c757d; font-weight:500;">Notas / Observaciones</small>
                                <p style="margin:0; color:#212529;">{{ $cliente->notas }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer" style="background-color:#f8f9fa; border:none; border-radius:0 0 12px 12px; padding:1.5rem 2rem;">
            <a href="{{ route('clientes.edit', $cliente) }}" class="btn" style="background-color:#ffc107; color:#212529; border:none; border-radius:8px; padding:0.5rem 1.5rem;">Editar</a>
        </div>
    </div>
</div>
@endsection
