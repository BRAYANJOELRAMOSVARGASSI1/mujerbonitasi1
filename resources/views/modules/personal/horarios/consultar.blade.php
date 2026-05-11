@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Encabezado con Filtros y Navegación -->
    <div class="card mb-4" style="border:none; border-radius:15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h4 style="margin:0; font-weight:700; color:#2c3e50;">
                        <svg class="icon me-2" style="color:#CC5CB8; width:24px; height:24px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-calendar') }}"></use></svg>
                        Calendario de Disponibilidad
                    </h4>
                    <p class="text-muted mb-0">Semana del {{ $startOfWeek->format('d/m/Y') }} al {{ $endOfWeek->format('d/m/Y') }}</p>
                </div>
                
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-outline-secondary" style="border-radius:10px;">
                        <svg class="icon me-1"><use xlink:href="{{ asset('icons/coreui.svg#cil-print') }}"></use></svg> Exportar PDF
                    </button>
                    <a href="{{ route('horarios.index') }}" class="btn btn-light" style="border-radius:10px; border:1px solid #ddd;">
                        <svg class="icon me-1"><use xlink:href="{{ asset('icons/coreui.svg#cil-settings') }}"></use></svg> Gestionar Turnos
                    </a>
                </div>
            </div>

            <hr class="my-4" style="opacity:0.1;">

            <form method="GET" action="{{ route('horarios.consultar') }}" class="row g-3 align-items-end">
                <input type="hidden" name="date" value="{{ $currentDate->toDateString() }}">
                
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-uppercase" style="color:#666;">Estilista</label>
                    <select name="estilista_id" class="form-select" style="border-radius:10px; padding:10px;" onchange="this.form.submit()" {{ Auth::user()->hasRole('estilista') ? 'disabled' : '' }}>
                        <option value="">-- Todos los Estilistas --</option>
                        @foreach($estilistas as $est)
                            <option value="{{ $est->id }}" {{ $selectedEstilistaId == $est->id ? 'selected' : '' }}>
                                {{ $est->nombre_completo }} ({{ $est->especialidad }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <div class="btn-group w-100" style="border-radius:10px; overflow:hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                        <a href="{{ route('horarios.consultar', ['date' => $startOfWeek->copy()->subWeek()->toDateString(), 'estilista_id' => $selectedEstilistaId]) }}" class="btn btn-white border">
                            <svg class="icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-chevron-left') }}"></use></svg> Anterior
                        </a>
                        <a href="{{ route('horarios.consultar', ['date' => now()->toDateString(), 'estilista_id' => $selectedEstilistaId]) }}" class="btn btn-white border">Hoy</a>
                        <a href="{{ route('horarios.consultar', ['date' => $startOfWeek->copy()->addWeek()->toDateString(), 'estilista_id' => $selectedEstilistaId]) }}" class="btn btn-white border">
                            <svg class="icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-chevron-right') }}"></use></svg> Siguiente
                        </a>
                    </div>
                </div>

                <div class="col-md-4 text-end">
                    <div class="d-inline-flex gap-3 small fw-bold">
                        <div class="d-flex align-items-center"><span style="width:12px; height:12px; background:#d4edda; border:1px solid #c3e6cb; border-radius:3px; display:inline-block; margin-right:5px;"></span> Disponible</div>
                        <div class="d-flex align-items-center"><span style="width:12px; height:12px; background:#f8d7da; border:1px solid #f5c6cb; border-radius:3px; display:inline-block; margin-right:5px;"></span> Ocupado</div>
                        <div class="d-flex align-items-center"><span style="width:12px; height:12px; background:#e2e3e5; border:1px solid #d6d8db; border-radius:3px; display:inline-block; margin-right:5px;"></span> No labora</div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Calendario Grid -->
    <div class="card" style="border:none; border-radius:15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); overflow:hidden;">
        <div class="table-responsive">
            <table class="table table-bordered mb-0" style="table-layout: fixed; border-collapse: collapse;">
                <thead style="background:#f8f9fa;">
                    <tr>
                        <th style="width:80px; text-align:center; vertical-align:middle; background:#f1f3f5; font-size:12px; color:#495057;">HORA</th>
                        @php
                            $dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
                            $fechasSemana = [];
                            for($i=0; $i<7; $i++) {
                                $fechasSemana[$dias[$i]] = $startOfWeek->copy()->addDays($i);
                            }
                        @endphp
                        @foreach($dias as $dia)
                            <th class="text-center p-3" style="border-bottom:3px solid #CC5CB8;">
                                <div style="text-transform: uppercase; font-size:11px; letter-spacing:1px; color:#666;">{{ $dia }}</div>
                                <div style="font-size:20px; font-weight:700; color:#2c3e50;">{{ $fechasSemana[$dia]->format('d') }}</div>
                                <div style="font-size:11px; color:#999;">{{ $fechasSemana[$dia]->translatedFormat('M') }}</div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $horas = range(8, 20); // De 8:00 AM a 8:00 PM
                    @endphp
                    @foreach($horas as $hora)
                        <tr>
                            <td class="text-center fw-bold" style="background:#f8f9fa; font-size:12px; color:#495057; border-right:2px solid #dee2e6;">
                                {{ sprintf('%02d:00', $hora) }}
                            </td>
                            @foreach($dias as $dia)
                                @php
                                    $time = sprintf('%02d:00:00', $hora);
                                    
                                    // Buscar si el estilista (o alguno) tiene horario activo este día a esta hora
                                    $estilistaHorario = $horarios->filter(function($h) use ($dia, $time) {
                                        return $h->dia_semana == $dia && $time >= $h->hora_inicio && $time < $h->hora_fin;
                                    })->first();

                                    $statusClass = 'status-off'; // Por defecto no labora (Gris)
                                    $label = 'Fuera de Horario';
                                    $content = '';

                                    if($estilistaHorario) {
                                        // Aquí integrarás con CITAS (P4) más adelante
                                        // Por ahora simulamos que si es Martes a las 10am está ocupado para la demo
                                        if($dia == 'martes' && $hora == 10 && $selectedEstilistaId) {
                                            $statusClass = 'status-occupied';
                                            $label = 'OCUPADO';
                                            
                                            // Privacidad: El cliente NO ve quién está en la cita
                                            if(Auth::user()->hasRole('cliente')) {
                                                $content = 'No disponible';
                                            } else {
                                                $content = 'Cita: Corte de Dama<br><small>Cliente: Ana G.</small>';
                                            }
                                        } else {
                                            $statusClass = 'status-available';
                                            $label = 'DISPONIBLE';
                                            $content = $estilistaHorario->estilista->nombre;
                                        }
                                    }
                                @endphp
                                <td class="{{ $statusClass }} p-2 cell-hover" style="height:80px; position:relative; vertical-align:top; border:1px solid #f1f3f5; transition: all 0.2s;">
                                    <div class="status-badge">{{ $label }}</div>
                                    <div class="cell-content">{!! $content !!}</div>
                                    @if($statusClass == 'status-available' && !Auth::user()->hasRole('estilista'))
                                        <div class="cell-actions">
                                            <button class="btn btn-xs btn-light shadow-sm" title="Agendar Cita">
                                                <svg class="icon" style="width:12px; height:12px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-plus') }}"></use></svg>
                                            </button>
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .status-available { background-color: #f0fff4 !important; }
    .status-available:hover { background-color: #dcfce7 !important; cursor: pointer; }
    
    .status-occupied { background-color: #fff5f5 !important; border-left: 4px solid #f87171 !important; }
    
    .status-off { background-color: #f8f9fa !important; opacity: 0.6; }

    .status-badge {
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }
    
    .status-available .status-badge { color: #2f855a; }
    .status-occupied .status-badge { color: #c53030; }
    .status-off .status-badge { color: #718096; }

    .cell-content {
        font-size: 11px;
        font-weight: 600;
        color: #4a5568;
        line-height: 1.2;
    }

    .cell-actions {
        position: absolute;
        bottom: 5px;
        right: 5px;
        display: none;
    }
    .cell-hover:hover .cell-actions {
        display: block;
    }

    .btn-xs {
        padding: 2px 5px;
        font-size: 10px;
    }

    @media print {
        .btn-group, .btn, .form-select, .form-label, hr { display: none !important; }
        .card { box-shadow: none !important; border: 1px solid #eee !important; }
        body { background: white !important; }
    }
</style>
@endsection
