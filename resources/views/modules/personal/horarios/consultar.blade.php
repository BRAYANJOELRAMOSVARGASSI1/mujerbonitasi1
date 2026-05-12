@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Encabezado con Filtros y Navegación -->
    <div class="card mb-4 border-0 shadow-sm" style="border-radius:15px;">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h4 class="fw-bold mb-1" style="color:#2c3e50;">
                        <svg class="icon me-2" style="color:#CC5CB8; width:24px; height:24px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-calendar') }}"></use></svg>
                        Calendario de Disponibilidad
                    </h4>
                    <p class="text-muted mb-0">Semana del {{ $startOfWeek->format('d/m/Y') }} al {{ $endOfWeek->format('d/m/Y') }}</p>
                </div>
                
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-outline-secondary rounded-pill px-3">
                        <svg class="icon me-1"><use xlink:href="{{ asset('icons/coreui.svg#cil-print') }}"></use></svg> Exportar PDF
                    </button>
                    @unless(Auth::user()->hasRole('estilista'))
                    <a href="{{ route('horarios.index') }}" class="btn btn-primary rounded-pill px-3" style="background:#CC5CB8; border:none;">
                        <svg class="icon me-1"><use xlink:href="{{ asset('icons/coreui.svg#cil-settings') }}"></use></svg> Gestionar Turnos
                    </a>
                    @endunless
                </div>
            </div>

            <hr class="my-4 opacity-10">

            <form method="GET" action="{{ route('horarios.consultar') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-uppercase text-secondary">Ir a Fecha</label>
                    <input type="date" name="date" class="form-control rounded-3" value="{{ $currentDate->toDateString() }}" onchange="this.form.submit()">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-uppercase text-secondary">Estilista</label>
                    <select name="estilista_id" class="form-select rounded-3" onchange="this.form.submit()" {{ Auth::user()->hasRole('estilista') ? 'disabled' : '' }}>
                        <option value="">-- Todos los Estilistas --</option>
                        @foreach($estilistas as $est)
                            <option value="{{ $est->id }}" {{ $selectedEstilistaId == $est->id ? 'selected' : '' }}>
                                {{ $est->nombre_completo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="btn-group w-100 shadow-sm rounded-3 overflow-hidden">
                        <a href="{{ route('horarios.consultar', ['date' => $startOfWeek->copy()->subWeek()->toDateString(), 'estilista_id' => $selectedEstilistaId]) }}" class="btn btn-white border">
                            <svg class="icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-chevron-left') }}"></use></svg>
                        </a>
                        <a href="{{ route('horarios.consultar', ['date' => now()->toDateString(), 'estilista_id' => $selectedEstilistaId]) }}" class="btn btn-white border fw-bold">Hoy</a>
                        <a href="{{ route('horarios.consultar', ['date' => $startOfWeek->copy()->addWeek()->toDateString(), 'estilista_id' => $selectedEstilistaId]) }}" class="btn btn-white border">
                            <svg class="icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-chevron-right') }}"></use></svg>
                        </a>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="d-flex flex-wrap gap-2 justify-content-end mb-1">
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Disponible</span>
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Ocupado</span>
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">Permiso</span>
                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Libre</span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Calendario Grid -->
    <div class="card border-0 shadow-sm" style="border-radius:15px; overflow:hidden;">
        <div class="table-responsive">
            <table class="table table-bordered mb-0 calendar-table">
                <thead class="bg-light">
                    <tr>
                        <th style="width:100px;" class="text-center align-middle bg-light text-secondary small fw-bold">HORA</th>
                        @php
                            $dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
                            $fechasSemana = [];
                            for($i=0; $i<7; $i++) {
                                $fechasSemana[$dias[$i]] = $startOfWeek->copy()->addDays($i);
                            }
                        @endphp
                        @foreach($dias as $dia)
                            <th class="text-center p-3 {{ $fechasSemana[$dia]->isToday() ? 'bg-primary-subtle' : '' }}" style="border-bottom:3px solid #CC5CB8;">
                                <div class="text-uppercase small text-secondary fw-bold" style="letter-spacing:1px;">{{ $dia }}</div>
                                <div class="fs-4 fw-bold {{ $fechasSemana[$dia]->isToday() ? 'text-primary' : 'text-dark' }}">{{ $fechasSemana[$dia]->format('d') }}</div>
                                <div class="small text-muted">{{ $fechasSemana[$dia]->translatedFormat('M') }}</div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php $horas = range(8, 20); @endphp
                    @foreach($horas as $hora)
                        <tr>
                            <td class="text-center align-middle fw-bold bg-light text-secondary border-end" style="font-size:13px;">
                                {{ sprintf('%02d:00', $hora) }}
                            </td>
                            @foreach($dias as $dia)
                                @php
                                    $time = sprintf('%02d:00:00', $hora);
                                    $fechaActual = $fechasSemana[$dia]->toDateString();
                                    
                                    // 1. Verificar Horario Base
                                    $hBase = $horarios->filter(fn($h) => $h->dia_semana == $dia && $time >= $h->hora_inicio && $time < $h->hora_fin)->first();

                                    // 2. Verificar Excepciones (Vacaciones/Permisos)
                                    $excepcion = $excepciones->filter(fn($e) => $e->fecha->toDateString() == $fechaActual && $time >= $e->hora_inicio && $time < $e->hora_fin)->first();

                                    // 3. Verificar Citas Reales (P4)
                                    $cita = $citas->filter(fn($c) => $c->fecha->toDateString() == $fechaActual && $time >= $c->hora_inicio && $time < $c->hora_fin)->first();

                                    // 4. Lógica de Colores y Contenido
                                    $statusClass = 'status-off';
                                    $label = 'LIBRE';
                                    $content = '';
                                    $detail = [];

                                    if($hBase) {
                                        $statusClass = 'status-available';
                                        $label = 'DISPONIBLE';
                                        $content = $hBase->estilista->nombre;
                                        $detail = ['tipo' => 'Horario Laboral', 'estilista' => $hBase->estilista->nombre_completo];

                                        if($excepcion) {
                                            $statusClass = 'status-exception';
                                            $label = strtoupper($excepcion->tipo == 'vacaciones' ? 'VACACIONES' : 'PERMISO');
                                            $content = $excepcion->motivo ?? 'No disponible';
                                            $detail = ['tipo' => 'Excepción', 'motivo' => $content, 'estilista' => $excepcion->estilista->nombre_completo];
                                        }
                                        
                                        if($cita) {
                                            $statusClass = 'status-occupied';
                                            $label = strtoupper($cita->estado);
                                            
                                            if(Auth::user()->hasRole('cliente')) {
                                                $content = 'No disponible';
                                            } else {
                                                $content = "{$cita->servicio->nombre}<br><small>Clie: {$cita->cliente->nombre}</small>";
                                            }

                                            $detail = [
                                                'id' => $cita->id,
                                                'tipo' => 'Cita Programada',
                                                'servicio' => $cita->servicio->nombre,
                                                'cliente' => $cita->cliente->nombre_completo,
                                                'estado' => $cita->estado,
                                                'pago' => $cita->precio_total . ' Bs.'
                                            ];
                                        }
                                    }
                                @endphp
                                <td class="{{ $statusClass }} p-2 position-relative align-top cell-hover" 
                                    style="height:95px; border:1px solid #f1f3f5;"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#detailModal" 
                                    data-detail='@json($detail)'
                                    data-hora="{{ sprintf('%02d:00', $hora) }}"
                                    data-fecha="{{ $fechaActual }}"
                                    data-status="{{ $statusClass }}">
                                    
                                    <div class="status-badge fw-bold" style="font-size:9px;">{{ $label }}</div>
                                    <div class="cell-content small fw-bold mt-1" style="line-height:1.1;">{!! $content !!}</div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de Detalle -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Detalle de Turno</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modalBody">
                    <!-- Contenido dinámico JS -->
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
                <div id="modalActions"></div>
            </div>
        </div>
    </div>
</div>

<style>
    .calendar-table { table-layout: fixed; border-collapse: collapse; }
    .status-available { background-color: #f0fff4 !important; }
    .status-available:hover { background-color: #dcfce7 !important; cursor: pointer; }
    .status-occupied { background-color: #fff5f5 !important; border-left: 4px solid #f87171 !important; cursor: pointer; }
    .status-exception { background-color: #fffbeb !important; border-left: 4px solid #fbbf24 !important; cursor: pointer; }
    .status-off { background-color: #f8f9fa !important; opacity: 0.5; }

    .status-available .status-badge { color: #2f855a; }
    .status-occupied .status-badge { color: #c53030; }
    .status-exception .status-badge { color: #b45309; }
    .status-off .status-badge { color: #718096; }

    .cell-content { color: #4a5568; }
    .cell-hover:hover { transform: scale(1.02); z-index: 10; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    
    .cell-actions { position: absolute; bottom: 5px; right: 5px; display: none; }
    .cell-hover:hover .cell-actions { display: block; }
    .btn-xs { padding: 3px 6px; font-size: 10px; }

    @media print {
        .btn, .form-control, .form-select, .form-label, .nav, .sidebar { display: none !important; }
        .container-fluid { padding: 0 !important; }
        .card { box-shadow: none !important; border: 1px solid #ddd !important; }
        .status-off { display: none; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const detailModal = document.getElementById('detailModal');
    detailModal.addEventListener('show.bs.modal', function(event) {
        const cell = event.relatedTarget;
        const detail = JSON.parse(cell.getAttribute('data-detail'));
        const hora = cell.getAttribute('data-hora');
        const fecha = cell.getAttribute('data-fecha');
        const status = cell.getAttribute('data-status');

        let html = `
            <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle p-3 bg-light me-3">
                    <svg class="icon text-primary" style="width:24px; height:24px;"><use xlink:href="{{ asset('icons/coreui.svg#cil-clock') }}"></use></svg>
                </div>
                <div>
                    <div class="text-secondary small fw-bold">FECHA Y HORA</div>
                    <div class="fw-bold fs-5">${fecha} - ${hora}</div>
                </div>
            </div>
        `;

        if (status === 'status-off') {
            html += '<div class="alert alert-secondary">El salón no atiende en este horario o el estilista no tiene turno asignado.</div>';
        } else {
            html += '<ul class="list-group list-group-flush border-top pt-2">';
            for (const [key, value] of Object.entries(detail)) {
                html += `
                    <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                        <span class="text-secondary text-uppercase small fw-bold">${key}</span>
                        <span class="fw-bold">${value}</span>
                    </li>
                `;
            }
            html += '</ul>';
        }

        document.getElementById('modalBody').innerHTML = html;
        
        let actionsHtml = '';
        
        // Botón Agendar Cita: Visible para Recepcionista y Admin (y Cliente si aplica)
        if (status === 'status-available' && !{{ Auth::user()->hasRole('estilista') ? 'true' : 'false' }}) {
            actionsHtml += '<button class="btn btn-primary rounded-pill px-4 me-2">Agendar Cita</button>';
        }

        // Botón Gestionar Turno: SOLO para Admin
        if ({{ Auth::user()->hasAnyRole(['admin', 'super-admin']) ? 'true' : 'false' }}) {
            actionsHtml += `<a href="{{ route('horarios.index') }}" class="btn btn-outline-dark rounded-pill px-4">Gestionar Turnos</a>`;
        }

        // Botón Finalizar Trabajo: Solo para Estilista en citas activas
        if (status === 'status-occupied' && {{ Auth::user()->hasRole('estilista') ? 'true' : 'false' }} && detail.estado !== 'completada') {
            actionsHtml = `
                <form action="{{ url('horarios/citas') }}/${detail.id}/finalizar" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success text-white rounded-pill px-4">Finalizar Trabajo</button>
                </form>
            `;
        }
        document.getElementById('modalActions').innerHTML = actionsHtml;
    });
});
</script>
@endsection
