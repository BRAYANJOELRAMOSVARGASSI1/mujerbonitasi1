@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:white; border-radius:12px 12px 0 0; border-bottom:1px solid #dee2e6;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 style="margin:0; font-weight:600; color:#212529;">
                        <svg class="icon me-2" style="color:#CC5CB8;"><use xlink:href="{{ asset('icons/coreui.svg#cil-calendar') }}"></use></svg>Agenda de Citas
                    </h5>
                    <small style="opacity:0.7;">p4-gestión de servicios y citas | CU8 — Agendar Cita</small>
                </div>
                @can('crear citas')
                <a href="{{ route('citas.create') }}" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:8px 16px; font-weight:500;">
                    <svg class="icon me-2"><use xlink:href="{{ asset('icons/coreui.svg#cil-plus') }}"></use></svg>Nueva Cita
                </a>
                @endcan
            </div>
        </div>
        <div class="card-body" style="background-color:#f8f9fa; padding:1.5rem;">
            @if (session('status'))
                <div class="alert alert-success" style="border:none; border-radius:8px; background-color:#d4edda; color:#155724; border-left:4px solid #28a745;">{{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" style="border:none; border-radius:8px; background-color:#f8d7da; color:#721c24; border-left:4px solid #dc3545;">{{ session('error') }}</div>
            @endif

            {{-- Filtros --}}
            <form method="GET" action="{{ route('citas.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="buscar" class="form-control" placeholder="Buscar cliente..." value="{{ request('buscar') }}" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="fecha" class="form-control" value="{{ request('fecha') }}" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                    </div>
                    <div class="col-md-2">
                        <select name="estado" class="form-select" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                            <option value="">Todos los estados</option>
                            <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="en_curso" {{ request('estado') == 'en_curso' ? 'selected' : '' }}>En Curso</option>
                            <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
                            <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="estilista_id" class="form-select" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">
                            <option value="">Todas las estilistas</option>
                            @foreach($estilistas as $est)
                                <option value="{{ $est->id }}" {{ request('estilista_id') == $est->id ? 'selected' : '' }}>{{ $est->nombre_completo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn flex-fill" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px;">Buscar</button>
                        <a href="{{ route('citas.index') }}" class="btn" style="background-color:#6c757d; color:white; border:none; border-radius:8px;"><svg class="icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-reload') }}"></use></svg></a>
                    </div>
                </div>
            </form>

            @if($citas->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover" style="border-radius:8px; overflow:hidden;">
                        <thead style="background-color:#381432; color:white;">
                            <tr>
                                <th style="padding:12px;">#</th>
                                <th style="padding:12px;">Cliente</th>
                                <th style="padding:12px;">Servicio</th>
                                <th style="padding:12px;">Estilista</th>
                                <th style="padding:12px;">Fecha</th>
                                <th style="padding:12px;">Horario</th>
                                <th style="padding:12px;">Precio</th>
                                <th style="padding:12px;">Estado</th>
                                <th style="padding:12px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($citas as $cita)
                                <tr style="background-color:white;">
                                    <td style="padding:12px; vertical-align:middle;">{{ $cita->id }}</td>
                                    <td style="padding:12px; vertical-align:middle; font-weight:500;">{{ $cita->cliente->nombre_completo }}</td>
                                    <td style="padding:12px; vertical-align:middle;">
                                        <span class="badge" style="background-color:#CC5CB8; font-size:0.75rem;">{{ $cita->servicio->nombre }}</span>
                                    </td>
                                    <td style="padding:12px; vertical-align:middle;">{{ $cita->estilista->nombre_completo }}</td>
                                    <td style="padding:12px; vertical-align:middle;">{{ $cita->fecha->format('d/m/Y') }}</td>
                                    <td style="padding:12px; vertical-align:middle;">
                                        {{ \Carbon\Carbon::parse($cita->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($cita->hora_fin)->format('H:i') }}
                                    </td>
                                    <td style="padding:12px; vertical-align:middle; font-weight:600; color:#28a745;">Bs. {{ number_format($cita->precio_total, 2) }}</td>
                                    <td style="padding:12px; vertical-align:middle;">
                                        @php
                                            $estadoColors = [
                                                'pendiente' => ['bg' => '#fff3cd', 'text' => '#856404', 'border' => '#ffc107'],
                                                'en_curso'  => ['bg' => '#cce5ff', 'text' => '#004085', 'border' => '#007bff'],
                                                'completada'=> ['bg' => '#d4edda', 'text' => '#155724', 'border' => '#28a745'],
                                                'cancelada' => ['bg' => '#f8d7da', 'text' => '#721c24', 'border' => '#dc3545'],
                                            ];
                                            $ec = $estadoColors[$cita->estado] ?? $estadoColors['pendiente'];
                                        @endphp
                                        <span class="badge" style="background-color:{{ $ec['bg'] }}; color:{{ $ec['text'] }}; border:1px solid {{ $ec['border'] }}; font-size:0.75rem; padding:5px 10px; border-radius:12px;">
                                            {{ ucfirst(str_replace('_', ' ', $cita->estado)) }}
                                        </span>
                                    </td>
                                    <td style="padding:12px; vertical-align:middle;">
                                        <div class="d-flex gap-1">
                                            @php
                                                $pagoCompletado = \App\Modules\P5_PagosFacturacion\Models\Pago::where('cita_id', $cita->id)->where('estado_pago', 'completado')->exists();
                                            @endphp
                                            @if(!$pagoCompletado && $cita->estado != 'cancelada')
                                                <a href="{{ route('pagos.checkout', $cita->id) }}" class="btn btn-sm" style="background-color:#6f42c1; color:white; border:none; border-radius:6px; padding:4px 8px;" title="Pagar Cita">
                                                    <svg class="icon" style="width:14px; height:14px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-credit-card') }}"></use></svg>
                                                </a>
                                            @endif
                                            <a href="{{ route('citas.show', $cita) }}" class="btn btn-sm" style="background-color:#17a2b8; color:white; border:none; border-radius:6px; padding:4px 8px;" title="Ver Detalle">
                                                <svg class="icon" style="width:14px; height:14px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-eye') }}"></use></svg>
                                            </a>
                                            @if(in_array($cita->estado, ['pendiente', 'en_curso']))
                                                @can('editar citas')
                                                <a href="{{ route('citas.edit', $cita) }}" class="btn btn-sm" style="background-color:#ffc107; color:#212529; border:none; border-radius:6px; padding:4px 8px;">
                                                    <svg class="icon" style="width:14px; height:14px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-pencil') }}"></use></svg>
                                                </a>
                                                @endcan
                                                @can('cancelar citas')
                                                <form method="POST" action="{{ route('citas.destroy', $cita) }}" onsubmit="return confirm('¿Cancelar esta cita?')">@csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm" style="background-color:#dc3545; color:white; border:none; border-radius:6px; padding:4px 8px;">
                                                        <svg class="icon" style="width:14px; height:14px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-x-circle') }}"></use></svg>
                                                    </button>
                                                </form>
                                                @endcan
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <svg class="icon mb-3" style="width:48px; height:48px; color:#CC5CB8;"><use xlink:href="{{ asset('icons/coreui.svg#cil-calendar') }}"></use></svg>
                    <h5 style="color:#495057;">No hay citas registradas</h5>
                    @can('crear citas')
                    <a href="{{ route('citas.create') }}" class="btn" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px;">Agendar primera cita</a>
                    @endcan
                </div>
            @endif
        </div>
        @if($citas->count() > 0)
        <div class="card-footer" style="background-color:#f8f9fa; border:none; padding:1rem 2rem;">{{ $citas->links() }}</div>
        @endif
    </div>
</div>
@endsection
