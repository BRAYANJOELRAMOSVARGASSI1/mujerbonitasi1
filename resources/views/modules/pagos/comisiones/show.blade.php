@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card mb-4" style="border:none; border-radius:12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background:white; border-radius:12px 12px 0 0; border-bottom:1px solid #dee2e6;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 style="margin:0; font-weight:600;"><svg class="icon me-2" style="color:#CC5CB8;"><use xlink:href="{{ asset('icons/coreui.svg#cil-wallet') }}"></use></svg>Detalle Comisión — {{ $comision->estilista->nombre_completo }}</h5>
                <a href="{{ route('comisiones.index') }}" class="btn" style="background:#6c757d; color:white; border:none; border-radius:8px;">Volver</a>
            </div>
        </div>
        <div class="card-body" style="padding:2rem;">
            @php $sc = ['pendiente'=>'warning','aprobada'=>'success','pagada'=>'info'][$comision->estado] ?? 'secondary'; @endphp
            <div class="alert alert-{{ $sc }} text-center mb-4"><strong>Estado: {{ ucfirst($comision->estado) }}</strong></div>

            <div class="row g-4 mb-4">
                <div class="col-md-3 text-center"><div class="card border-0 shadow-sm" style="border-radius:12px; padding:1.5rem;"><small class="text-muted">Período</small><h5 class="fw-bold">{{ $comision->periodo_inicio->format('d/m') }} — {{ $comision->periodo_fin->format('d/m/Y') }}</h5></div></div>
                <div class="col-md-3 text-center"><div class="card border-0 shadow-sm" style="border-radius:12px; padding:1.5rem;"><small class="text-muted">Servicios Realizados</small><h3 class="fw-bold" style="color:#CC5CB8;">{{ $comision->cantidad_servicios }}</h3></div></div>
                <div class="col-md-3 text-center"><div class="card border-0 shadow-sm" style="border-radius:12px; padding:1.5rem;"><small class="text-muted">Total Ingresos</small><h3 class="fw-bold" style="color:#28a745;">Bs. {{ number_format($comision->total_servicios, 2) }}</h3></div></div>
                <div class="col-md-3 text-center"><div class="card border-0 shadow-sm" style="border-radius:12px; padding:1.5rem; border:2px solid #17a2b8 !important;"><small class="text-muted">Total Comisión</small><h3 class="fw-bold" style="color:#17a2b8;">Bs. {{ number_format($comision->total_comision, 2) }}</h3></div></div>
            </div>

            <h6 class="fw-bold mb-3">Detalle de Servicios Realizados</h6>
            @if($serviciosDetalle->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover" style="border-radius:8px; overflow:hidden;">
                    <thead style="background:#381432; color:white;"><tr><th style="padding:10px;">Fecha</th><th style="padding:10px;">Servicio</th><th style="padding:10px;">Cliente</th><th style="padding:10px;">Precio</th><th style="padding:10px;">Comisión %</th><th style="padding:10px;">Comisión Bs.</th></tr></thead>
                    <tbody>
                        @foreach($serviciosDetalle as $det)
                        <tr style="background:white;">
                            <td style="padding:10px;">{{ $det->fecha_realizacion->format('d/m/Y H:i') }}</td>
                            <td style="padding:10px;"><span class="badge" style="background:#CC5CB8;">{{ $det->servicio->nombre }}</span></td>
                            <td style="padding:10px;">{{ $det->cliente->nombre_completo }}</td>
                            <td style="padding:10px; color:#28a745; font-weight:600;">Bs. {{ number_format($det->precio_cobrado, 2) }}</td>
                            <td style="padding:10px;">{{ $det->comision_porcentaje }}%</td>
                            <td style="padding:10px; color:#17a2b8; font-weight:700;">Bs. {{ number_format($det->comision_monto, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            @if($comision->estado == 'pendiente')
            @can('aprobar comisiones')
            <div class="mt-3">
                <form method="POST" action="{{ route('comisiones.aprobar', $comision) }}" onsubmit="return confirm('¿Aprobar esta comisión?')">
                    @csrf
                    <button type="submit" class="btn btn-lg" style="background:#28a745; color:white; border:none; border-radius:8px; padding:12px 32px; font-weight:600;">✅ Aprobar Comisión</button>
                </form>
            </div>
            @endcan
            @endif
        </div>
    </div>
</div>
@endsection
