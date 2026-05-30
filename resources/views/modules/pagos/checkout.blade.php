@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white text-center border-0 pt-4">
                    <h3 class="mb-0" style="color:#CC5CB8;">Resumen de Pago</h3>
                </div>
                <div class="card-body p-4">
                    
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <h5 class="mb-3">Detalle de la Cita #{{ $cita->id }}</h5>
                    
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Servicio: 
                            <span class="fw-bold">{{ $cita->servicio->nombre ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Fecha: 
                            <span class="fw-bold">{{ $cita->fecha->format('d/m/Y') }} {{ $cita->hora_inicio }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Total a Pagar:
                            <h4 class="text-success mb-0">Bs. {{ number_format($cita->precio_total, 2) }}</h4>
                        </li>
                    </ul>

                    <form action="{{ route('pagos.stripe.iniciar', $cita->id) }}" method="POST" class="d-grid gap-2">
                        @csrf
                        <button type="submit" class="btn btn-lg text-white" style="background-color: #635bff;">
                            <i class="fas fa-lock"></i> Pagar con Stripe
                        </button>
                    </form>
                    
                    <div class="text-center mt-3 text-muted" style="font-size: 0.85rem;">
                        <i class="fas fa-shield-alt"></i> Pagos seguros encriptados por Stripe
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
