@extends('layouts.app')

@section('title', 'Factura de Pago')

@section('content')
<div class="container-fluid content-inner pb-0">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Factura #{{ $pago->transaccion_id }}</h4>
                    <div>
                        <a href="{{ route('pagos.index') }}" class="btn btn-secondary btn-sm">Volver</a>
                        <a href="{{ route('pagos.factura.pdf', $pago->id) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-download"></i> Descargar PDF
                        </a>
                    </div>
                </div>
                <div class="card-body p-5" id="factura-content">
                    <div class="text-center mb-5">
                        <h2 class="fw-bold">MUJER BONITA</h2>
                        <p class="text-muted">Salón de Belleza Integral</p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <h5 class="fw-bold">Datos del Cliente:</h5>
                            <p>
                                <strong>Nombre:</strong> {{ $pago->cita->cliente->nombre ?? 'N/A' }}<br>
                                <strong>Teléfono:</strong> {{ $pago->cita->cliente->telefono ?? 'N/A' }}<br>
                                <strong>Email:</strong> {{ $pago->cita->cliente->usuario->email ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <h5 class="fw-bold">Datos de la Factura:</h5>
                            <p>
                                <strong>Transacción:</strong> {{ $pago->transaccion_id }}<br>
                                <strong>Fecha:</strong> {{ $pago->updated_at->format('d/m/Y H:i') }}<br>
                                <strong>Método de Pago:</strong> <span class="badge bg-secondary">{{ strtoupper($pago->metodo) }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Descripción / Servicio</th>
                                    <th>Estilista Asignado</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $pago->cita->servicio->nombre ?? 'Servicio Estándar' }} (Cita #{{ $pago->cita->id }})</td>
                                    <td>{{ $pago->cita->estilista->nombre ?? 'N/A' }}</td>
                                    <td>${{ number_format($pago->monto, 2) }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-end fw-bold">TOTAL PAGADO:</th>
                                    <th class="fw-bold text-success">${{ number_format($pago->monto, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="text-center mt-5 text-muted">
                        <p>¡Gracias por tu preferencia!<br>Mujer Bonita Salón de Belleza</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
