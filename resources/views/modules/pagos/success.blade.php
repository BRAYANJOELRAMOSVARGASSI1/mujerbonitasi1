@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    
                    <div class="mb-4 text-success">
                        <i class="fas fa-check-circle" style="font-size: 5rem;"></i>
                    </div>

                    <h2 class="mb-3" style="color:#CC5CB8;">¡Pago Exitoso!</h2>
                    
                    @if(session('info'))
                        <div class="alert alert-info">{{ session('info') }}</div>
                    @endif

                    <p class="text-muted mb-4">
                        Hemos recibido tu pago para la cita <strong>#{{ $cita->id }}</strong> por un monto de 
                        <strong>Bs. {{ number_format($cita->precio_total, 2) }}</strong>.
                    </p>
                    
                    <p>Recibirás un comprobante en tu correo electrónico en breve.</p>

                    <a href="{{ url('/') }}" class="btn btn-primary mt-3">
                        Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
