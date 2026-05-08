@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:white; border-radius:12px 12px 0 0; border-bottom:1px solid #dee2e6;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 style="margin:0; font-weight:600; color:#212529;">
                        <svg class="icon me-2" style="color:#CC5CB8;"><use xlink:href="{{ asset('icons/coreui.svg#cil-calendar') }}"></use></svg>Gestión de Horarios
                    </h5>
                    <small style="opacity:0.7;">p2-gestion de personal y clientes | CU22 — Gestionar Horarios | CU23 — Consultar Horarios</small>
                </div>
                <a href="{{ route('horarios.create') }}" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:8px 16px; font-weight:500;">
                    <svg class="icon me-2"><use xlink:href="{{ asset('icons/coreui.svg#cil-plus') }}"></use></svg>Nuevo Horario
                </a>
            </div>
        </div>
        <div class="card-body" style="background-color:#f8f9fa; padding:1.5rem;">
            @if (session('status'))
                <div class="alert alert-success" style="border:none; border-radius:8px; background-color:#d4edda; color:#155724; border-left:4px solid #28a745;">{{ session('status') }}</div>
            @endif
            <form method="GET" action="{{ route('horarios.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-5">
                        <select name="estilista_id" class="form-select" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                            <option value="">Todas las estilistas</option>
                            @foreach($estilistas as $est)
                                <option value="{{ $est->id }}" {{ request('estilista_id') == $est->id ? 'selected' : '' }}>{{ $est->nombre }} {{ $est->apellido }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="dia_semana" class="form-select" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                            <option value="">Todos los días</option>
                            @foreach($diasSemana as $key => $label)
                                <option value="{{ $key }}" {{ request('dia_semana') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn flex-fill" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px;">Filtrar</button>
                        <a href="{{ route('horarios.index') }}" class="btn" style="background-color:#6c757d; color:white; border:none; border-radius:8px;"><svg class="icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-reload') }}"></use></svg></a>
                    </div>
                </div>
            </form>
            @if($horarios->count() > 0)
            <div class="table-responsive">
                <table class="table" style="background:white; border-radius:8px; overflow:hidden;">
                    <thead style="background-color:#CC5CB8; color:white;">
                        <tr><th>Estilista</th><th>Especialidad</th><th>Día</th><th>Entrada</th><th>Salida</th><th>Acciones</th></tr>
                    </thead>
                    <tbody>
                        @foreach($horarios as $h)
                        <tr>
                            <td style="font-weight:500;">{{ $h->estilista->nombre_completo }}</td>
                            <td><span class="badge" style="background-color:#CC5CB8;">{{ $h->estilista->especialidad }}</span></td>
                            <td>{{ ucfirst($h->dia_semana) }}</td>
                            <td>{{ $h->hora_inicio }}</td>
                            <td>{{ $h->hora_fin }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('horarios.edit', $h) }}" class="btn btn-sm" style="background-color:#ffc107; color:#212529; border:none; border-radius:6px; padding:4px 10px;">
                                        <svg class="icon" style="width:14px; height:14px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-pencil') }}"></use></svg>
                                    </a>
                                    <form method="POST" action="{{ route('horarios.destroy', $h) }}" onsubmit="return confirm('¿Eliminar este horario?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="background-color:#dc3545; color:white; border:none; border-radius:6px; padding:4px 10px;">
                                            <svg class="icon" style="width:14px; height:14px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-trash') }}"></use></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <div class="text-center py-5">
                    <h5 style="color:#495057;">No hay horarios registrados</h5>
                    <a href="{{ route('horarios.create') }}" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:10px 20px;">Crear primer horario</a>
                </div>
            @endif
        </div>
        @if($horarios->count() > 0)
            <div class="card-footer" style="background-color:#f8f9fa; border:none; padding:1.5rem 2rem;">{{ $horarios->links() }}</div>
        @endif
    </div>
</div>
@endsection
