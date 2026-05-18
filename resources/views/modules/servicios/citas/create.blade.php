@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background-color:white; border-radius:12px 12px 0 0; border-bottom:1px solid #dee2e6;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 style="margin:0; font-weight:600; color:#212529;">
                        <svg class="icon me-2" style="color:#CC5CB8;"><use xlink:href="{{ asset('icons/coreui.svg#cil-calendar') }}"></use></svg>Agendar Nueva Cita
                    </h5>
                    <small style="opacity:0.7;">CU8 — Agendar Cita | CU9 — Asignación de Estilista</small>
                </div>
                <a href="{{ route('citas.index') }}" class="btn" style="background-color:#6c757d; color:white; border:none; border-radius:8px; padding:8px 16px;">
                    <svg class="icon me-2"><use xlink:href="{{ asset('icons/coreui.svg#cil-arrow-left') }}"></use></svg>Volver
                </a>
            </div>
        </div>
        <div class="card-body" style="background-color:white; padding:2rem;">
            @if($errors->any())
                <div class="alert alert-danger" style="border:none; border-radius:8px; border-left:4px solid #dc3545;">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('citas.store') }}" id="formCita">
                @csrf
                <div class="row g-4">
                    {{-- Paso 1: Seleccionar Cliente --}}
                    <div class="col-12">
                        <div class="card" style="border:1px solid #CC5CB8; border-radius:10px;">
                            <div class="card-header" style="background-color:#CC5CB8; color:white; border-radius:10px 10px 0 0; padding:10px 15px;">
                                <strong>Paso 1:</strong> Seleccionar Cliente
                            </div>
                            <div class="card-body" style="padding:1.5rem;">
                                <select name="cliente_id" id="cliente_id" class="form-select" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;" required>
                                    <option value="">-- Seleccione un cliente --</option>
                                    @foreach($clientes as $cli)
                                        <option value="{{ $cli->id }}" {{ old('cliente_id') == $cli->id ? 'selected' : '' }}>
                                            {{ $cli->nombre_completo }} — {{ $cli->telefono }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 2: Seleccionar Servicio --}}
                    <div class="col-12">
                        <div class="card" style="border:1px solid #CC5CB8; border-radius:10px;">
                            <div class="card-header" style="background-color:#CC5CB8; color:white; border-radius:10px 10px 0 0; padding:10px 15px;">
                                <strong>Paso 2:</strong> Seleccionar Servicio
                            </div>
                            <div class="card-body" style="padding:1.5rem;">
                                <select name="servicio_id" id="servicio_id" class="form-select" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;" required>
                                    <option value="">-- Seleccione un servicio --</option>
                                    @foreach($servicios as $srv)
                                        <option value="{{ $srv->id }}" data-duracion="{{ $srv->duracion_minutos }}" data-precio="{{ $srv->precio }}" {{ old('servicio_id') == $srv->id ? 'selected' : '' }}>
                                            {{ $srv->nombre }} — {{ $srv->categoria }} ({{ $srv->duracion_formateada }}) — Bs. {{ number_format($srv->precio, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="servicioInfo" class="mt-2" style="display:none;">
                                    <div class="d-flex gap-3">
                                        <span class="badge" style="background-color:#d4edda; color:#155724; padding:8px 12px; border-radius:8px;">
                                            Duración: <strong id="infoDuracion">-</strong>
                                        </span>
                                        <span class="badge" style="background-color:#cce5ff; color:#004085; padding:8px 12px; border-radius:8px;">
                                            Precio: <strong id="infoPrecio">-</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 3: Fecha y Hora --}}
                    <div class="col-md-6">
                        <div class="card" style="border:1px solid #CC5CB8; border-radius:10px;">
                            <div class="card-header" style="background-color:#CC5CB8; color:white; border-radius:10px 10px 0 0; padding:10px 15px;">
                                <strong>Paso 3:</strong> Fecha y Hora
                            </div>
                            <div class="card-body" style="padding:1.5rem;">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label fw-bold">Fecha</label>
                                        <input type="date" name="fecha" id="fecha" class="form-control" value="{{ old('fecha', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-bold">Hora de Inicio</label>
                                        <select name="hora_inicio" id="hora_inicio" class="form-select" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;" required>
                                            <option value="">-- Hora --</option>
                                            @for($h = 8; $h <= 19; $h++)
                                                @foreach(['00', '30'] as $m)
                                                    @php $hora = sprintf('%02d:%s', $h, $m); @endphp
                                                    <option value="{{ $hora }}" {{ old('hora_inicio') == $hora ? 'selected' : '' }}>{{ $hora }}</option>
                                                @endforeach
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div id="horaFinInfo" class="mt-2" style="display:none;">
                                    <span class="badge" style="background-color:#e2e3e5; color:#383d41; padding:8px 12px; border-radius:8px;">
                                        Hora de finalización estimada: <strong id="infoHoraFin">-</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 4: Estilista Disponible (CU9 - AJAX) --}}
                    <div class="col-md-6">
                        <div class="card" style="border:1px solid #CC5CB8; border-radius:10px;">
                            <div class="card-header" style="background-color:#CC5CB8; color:white; border-radius:10px 10px 0 0; padding:10px 15px;">
                                <strong>Paso 4:</strong> Estilista Disponible
                            </div>
                            <div class="card-body" style="padding:1.5rem;">
                                <div id="estilistasLoading" style="display:none;" class="text-center py-3">
                                    <div class="spinner-border text-primary" role="status" style="color:#CC5CB8 !important;"></div>
                                    <p class="mt-2 text-muted">Buscando estilistas disponibles...</p>
                                </div>
                                <div id="estilistasContainer">
                                    <select name="estilista_id" id="estilista_id" class="form-select" style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;" required>
                                        <option value="">-- Seleccione servicio, fecha y hora primero --</option>
                                    </select>
                                    <small id="estilistasMsg" class="text-muted mt-1 d-block">Complete los pasos anteriores para ver estilistas disponibles.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Notas --}}
                    <div class="col-12">
                        <label class="form-label fw-bold">Notas (opcional)</label>
                        <textarea name="notas" class="form-control" rows="3" placeholder="Preferencias del cliente, alergias, observaciones..." style="border:1px solid #dee2e6; border-radius:8px; padding:0.75rem;">{{ old('notas') }}</textarea>
                    </div>

                    {{-- Botón --}}
                    <div class="col-12">
                        <button type="submit" class="btn btn-lg" style="background-color:#CC5CB8; color:white; border:none; border-radius:8px; padding:12px 32px; font-weight:600;">
                            <svg class="icon me-2"><use xlink:href="{{ asset('icons/coreui.svg#cil-calendar-check') }}"></use></svg>Agendar Cita
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const servicioSel = document.getElementById('servicio_id');
    const fechaInput  = document.getElementById('fecha');
    const horaSelect  = document.getElementById('hora_inicio');
    const estSelect   = document.getElementById('estilista_id');

    // Mostrar info del servicio seleccionado
    servicioSel.addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        const info = document.getElementById('servicioInfo');
        if (this.value) {
            info.style.display = 'block';
            document.getElementById('infoDuracion').textContent = opt.dataset.duracion + ' min';
            document.getElementById('infoPrecio').textContent = 'Bs. ' + parseFloat(opt.dataset.precio).toFixed(2);
        } else {
            info.style.display = 'none';
        }
        buscarEstilistas();
    });

    fechaInput.addEventListener('change', buscarEstilistas);
    horaSelect.addEventListener('change', buscarEstilistas);

    function buscarEstilistas() {
        const servicioId = servicioSel.value;
        const fecha      = fechaInput.value;
        const horaInicio = horaSelect.value;

        if (!servicioId || !fecha || !horaInicio) return;

        const loading = document.getElementById('estilistasLoading');
        const container = document.getElementById('estilistasContainer');
        const msg = document.getElementById('estilistasMsg');

        loading.style.display = 'block';
        container.style.display = 'none';

        fetch(`{{ route('citas.estilistas-disponibles') }}?servicio_id=${servicioId}&fecha=${fecha}&hora_inicio=${horaInicio}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            loading.style.display = 'none';
            container.style.display = 'block';

            // Mostrar hora fin estimada
            const horaFinInfo = document.getElementById('horaFinInfo');
            horaFinInfo.style.display = 'block';
            document.getElementById('infoHoraFin').textContent = data.hora_fin;

            // Llenar estilistas
            estSelect.innerHTML = '';
            if (data.estilistas.length === 0) {
                estSelect.innerHTML = '<option value="">No hay estilistas disponibles</option>';
                msg.textContent = 'No hay estilistas disponibles para el horario seleccionado. Intenta con otra fecha u hora.';
                msg.className = 'text-danger mt-1 d-block';
            } else {
                estSelect.innerHTML = '<option value="">-- Seleccione estilista --</option>';
                data.estilistas.forEach(est => {
                    const opt = document.createElement('option');
                    opt.value = est.id;
                    opt.textContent = `${est.nombre} ${est.apellido} — ${est.especialidad}`;
                    estSelect.appendChild(opt);
                });
                msg.textContent = `${data.estilistas.length} estilista(s) disponible(s) para este horario.`;
                msg.className = 'text-success mt-1 d-block';
            }
        })
        .catch(() => {
            loading.style.display = 'none';
            container.style.display = 'block';
            msg.textContent = 'Error al consultar disponibilidad. Intente nuevamente.';
            msg.className = 'text-danger mt-1 d-block';
        });
    }
});
</script>
@endsection
