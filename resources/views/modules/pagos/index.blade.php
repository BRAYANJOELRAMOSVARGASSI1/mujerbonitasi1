@extends('layouts.app')

@section('title', 'Gestión de Pagos')

@section('content')
<div class="container-fluid content-inner pb-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Gestión de Pagos</h4>
                </div>
                <div class="card-body">
                    <!-- KPIs -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="text-white">Total Cobrado</h5>
                                    <h3>${{ number_format($kpis['total_completado'], 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="text-white">Total Pendiente</h5>
                                    <h3>${{ number_format($kpis['total_pendiente'], 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="text-white">Pagos Efectivo</h5>
                                    <h3>${{ number_format($kpis['pagos_efectivo'], 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <form method="GET" action="{{ route('pagos.index') }}" class="mb-4 d-flex gap-3">
                        <select name="estado" class="form-select w-25">
                            <option value="">Todos los Estados</option>
                            <option value="completado" {{ request('estado') == 'completado' ? 'selected' : '' }}>Completados</option>
                            <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendientes</option>
                        </select>
                        <select name="metodo" class="form-select w-25">
                            <option value="">Todos los Métodos</option>
                            <option value="efectivo" {{ request('metodo') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                            <option value="tarjeta_presencial" {{ request('metodo') == 'tarjeta_presencial' ? 'selected' : '' }}>Tarjeta Presencial</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                        <a href="{{ route('pagos.index') }}" class="btn btn-secondary">Limpiar</a>
                    </form>

                    <!-- Tabla de pagos -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Cita / Servicio</th>
                                    <th>Monto</th>
                                    <th>Método</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pagos as $pago)
                                    <tr>
                                        <td>{{ $pago->updated_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $pago->cita->cliente->nombre ?? 'N/A' }}</td>
                                        <td>#{{ $pago->cita->id }} - {{ $pago->cita->servicio->nombre ?? 'N/A' }}</td>
                                        <td>${{ number_format($pago->monto, 2) }}</td>
                                        <td><span class="badge bg-secondary">{{ $pago->metodo === 'stripe' ? 'ONLINE' : strtoupper($pago->metodo) }}</span></td>
                                        <td>
                                            @if($pago->estado_pago === 'completado')
                                                <span class="badge bg-success">Completado</span>
                                            @else
                                                <span class="badge bg-warning">Pendiente</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($pago->estado_pago === 'completado')
                                                <a href="{{ route('pagos.factura', $pago->id) }}" class="btn btn-sm btn-info" title="Ver Factura">
                                                    <i class="fa fa-file-invoice"></i> Ver Factura
                                                </a>
                                            @else
                                                <button class="btn btn-sm btn-primary" data-coreui-toggle="modal" data-coreui-target="#modalManual-{{ $pago->cita->id }}">
                                                    <i class="fa fa-money-bill"></i> Cobrar
                                                </button>
                                                <!-- Modal Cobro Manual -->
                                                <div class="modal fade" id="modalManual-{{ $pago->cita->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Cobrar Cita #{{ $pago->cita->id }}</h5>
                                                                <button type="button" class="btn-close" data-coreui-dismiss="modal"></button>
                                                            </div>
                                                            <form action="{{ route('pagos.manual', $pago->cita->id) }}" method="POST">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label>Monto a cobrar</label>
                                                                        <input type="number" step="0.01" name="monto" class="form-control" value="{{ $pago->monto }}" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label>Método de Pago</label>
                                                                        <select name="metodo" class="form-select" required>
                                                                            <option value="efectivo">Efectivo</option>
                                                                            <option value="tarjeta_presencial">Tarjeta Presencial (POS)</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Cancelar</button>
                                                                    <button type="submit" class="btn btn-primary">Registrar Pago</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No hay pagos registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-3">
                        {{ $pagos->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
